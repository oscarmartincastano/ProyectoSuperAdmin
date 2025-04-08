<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Reserva;
use App\Models\Instalacion;
use App\Models\Desactivacion_reserva;
use App\Models\Campos_personalizados;
use App\Models\Desactivaciones_periodicas;
use App\Models\Excepciones_desactivaciones_periodicas;
use Carbon\Carbon;

class Pista extends Model
{
    use HasFactory;

    protected $table = 'pistas';

    protected $fillable = [
        'id_instalacion',
        'nombre',
        'nombre_corto',
        'id_deporte',
        'tipo',
        'horario',
        'allow_cancel',
        'atenlacion_reserva',
        'allow_more_res',
        'reservas_por_tramo',
        'max_dias_antelacion',
        'active',
        'precio',
        'bloqueo'
    ];

    public function instalacion()
    {
        return $this->belongsTo(Instalacion::class, 'id_instalacion', 'id');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id', 'id_pista');
    }

    public function desactivaciones_periodicas()
    {
        return $this->hasMany(Desactivaciones_periodicas::class, 'id_pista', 'id');
    }

    public function campos_personalizados()
    {
        return $this->belongsToMany(Campos_personalizados::class, 'pistas_campos', 'id_pista', 'id_campo');
    }

    public function getAllCamposPersonalizadosAttribute()
    {
        return $this->allCamposPersonalizados();
    }

    public function allCamposPersonalizados() {

        $campos_personalizados = $this->campos_personalizados;
        foreach (Campos_personalizados::where('id_instalacion', $this->id_instalacion)->where('all_pistas', 1)->get() as $key => $value) {
            $campos_personalizados->push($value);
        }

        return $campos_personalizados;
    }

    public function getHorarioDeserializedAttribute() {
        return $this->horarioDeserializado();
    }

    public function horarioDeserializado() {
        $horario = unserialize($this->horario);
        return $horario;
    }

    public function reservas_por_dia($fecha)
    {
        return Reserva::where('id_pista', $this->id)->where('fecha', $fecha)->get();
    }

    public function reservas_activas_por_dia($fecha)
    {
        return Reserva::where('id_pista', $this->id)->where('estado', 'active')->where('fecha', $fecha)->get();
    }

    public function reservas_pendientes_por_dia($fecha)
    {
        return Reserva::where('id_pista', $this->id)->where('estado', 'pendiente')->where('fecha', $fecha)->get();
    }

    public function reservas_canceladas_por_dia($fecha)
    {
        return Reserva::where('id_pista', $this->id)->where('estado', 'canceled')->where('fecha', $fecha)->get();
    }

    public function get_reservas_fecha_hora($timestamp)
    {
        $reservas = Reserva::where([['id_pista', $this->id], ['fecha', date('Y-m-d', $timestamp)]])->orderByRaw("FIELD ('id', 'estado', 'active', 'pasado', 'canceled') ASC")->get()->filter(function($reserva) use ($timestamp) {
            return in_array($timestamp, $reserva->horarios_deserialized);
        });
        $jump = 0;

        $ret_reservas = [];
        foreach ($reservas as $key => $reserva) {
            /* if (in_array($timestamp, $reserva->horarios_deserialized)) { */
                if ($jump) {
                    $jump=$jump-1;
                    continue;
                }
                $reserva->usuario = $reserva->user;
                if ($reserva->reserva_multiple) {
                    $reserva->numero_reservas = Reserva::where([['id_pista', $reserva->id_pista], ['reserva_multiple', $reserva->reserva_multiple], ['timestamp', $reserva->timestamp], ['estado', $reserva->estado], ['id_usuario', $reserva->id_usuario]])->count();
                    $jump = Reserva::where([['id_pista', $reserva->id_pista], ['reserva_multiple', $reserva->reserva_multiple], ['timestamp', $reserva->timestamp], ['estado', $reserva->estado], ['id_usuario', $reserva->id_usuario]])->count()-1;
                }
                $reserva->string_intervalo = date('H:i', $timestamp) . ' - ' . date('H:i', strtotime("+{$reserva->minutos_totales} minutes", strtotime(date('H:i', $timestamp))));
                array_push($ret_reservas, $reserva);
            /* } */
        }

        return $ret_reservas;
    }

    public function get_reserva_activa_fecha_hora($timestamp)
    {
        $reservas = Reserva::where([['id_pista', $this->id], ['fecha', date('Y-m-d', $timestamp)]])->orderByRaw('estado ASC')->get()->filter(function($reserva) use ($timestamp) {
            return (in_array($timestamp, $reserva->horarios_deserialized) && $reserva->estado != 'canceled' && $reserva->estado != 'pendiente') ||
                   (in_array($timestamp, $reserva->horarios_deserialized) && $reserva->estado == 'pendiente' && strtotime($reserva->created_at . "+15 minutes")>strtotime(date('Y-m-d H:i:s'))) ||
                   (in_array($timestamp, $reserva->horarios_deserialized) && $reserva->estado == 'pendiente' && $reserva->creado_por == 'admin');
        });
        return $reservas;

        /* $ret_reservas = [];
        foreach ($reservas as $key => $reserva) {
            if ((in_array($timestamp, $reserva->horarios_deserialized) && $reserva->estado != 'canceled' && $reserva->estado != 'pendiente') ||
                (in_array($timestamp, $reserva->horarios_deserialized) && $reserva->estado == 'pendiente' && strtotime($reserva->created_at . "+15 minutes")>strtotime(date('Y-m-d H:i:s'))) ||
                (in_array($timestamp, $reserva->horarios_deserialized) && $reserva->estado == 'pendiente' && $reserva->creado_por == 'admin')) {
                $reserva->usuario = User::find($reserva->id_usuario);
                array_push($ret_reservas, $reserva);
            }
        }

        return $ret_reservas; */
    }

    public function check_desactivacion_periodica($fecha)
    {
        $desactivaciones_dia =[];


        foreach ($this->desactivaciones_periodicas as $desactivacion) {
            if (in_array(date('w', strtotime($fecha)), unserialize($desactivacion->dias)) &&
                $fecha > $desactivacion->fecha_inicio &&
                $fecha < $desactivacion->fecha_fin
               ) {
                array_push($desactivaciones_dia, $desactivacion);
            }
        }
        return count($desactivaciones_dia) != 0 ? $desactivaciones_dia : false;
    }

    public function check_desactivado($timestamp)
    {
        if (Desactivacion_reserva::where([['id_pista', $this->id], ['timestamp', $timestamp]])->first()) {
            return true;
        }
        $desactivaciones_periodicas_dia = $this->check_desactivacion_periodica(date('Y-m-d', $timestamp));

        if ($desactivaciones_periodicas_dia) {
            foreach ($desactivaciones_periodicas_dia as $desactivacion) {

                /* if ((strtotime(date('Y-m-d', $timestamp)) >= strtotime('2022-03-27 00:00') && strtotime(date('Y-m-d', $timestamp)) <= strtotime('2022-10-30 00:00'))) {
                    if (
                        strtotime(date('H:i', $timestamp)) -3600 >= strtotime($desactivacion->hora_inicio) &&
                        strtotime(date('H:i', $timestamp)) -3600 < strtotime($desactivacion->hora_fin)
                    ) {
                        return true;
                    }
                }else { */

                    if (
                        strtotime(date('H:i', $timestamp)) >= strtotime($desactivacion->hora_inicio) &&
                        strtotime(date('H:i', $timestamp)) < strtotime($desactivacion->hora_fin)
                    ) {

                        if (Excepciones_desactivaciones_periodicas::where([['id_pista', $this->id], ['timestamp', $timestamp]])->first()) {
                            return false;
                        }
                        return 2;
                    }
                /* } */
            }
        }
        return false;
    }

    public function check_reserva_valida($timestamp)
    {

        if (
            strtotime(date('Y-m-d', $timestamp)) < strtotime(date('Y-m-d') . " +{$this->max_dias_antelacion} days") &&
            !$this->check_desactivado($timestamp) &&
            $this->reservas_por_tramo > $this->get_reserva_activa_fecha_hora($timestamp)->count() &&
            new \DateTime(date('d-m-Y H:i', strtotime("+{$this->atenlacion_reserva} hours"))) < new \DateTime(date('d-m-Y H:i', strtotime(date('d-m-Y H:i', $timestamp) . " +{$this->get_minutos_given_timestamp($timestamp)} minutes" )))
            ) {
            return true;
        }
        return false;
    }

    public function reservas_permitidas_restantes($timestamp)
    {
        return $this->reservas_por_tramo - $this->get_reserva_activa_fecha_hora($timestamp)->count();
    }

    public function horario_tramos($fecha)
    {
        $fecha = new \DateTime($fecha);
        $horario=[];
        foreach ($this->horario_deserialized as $key => $item) {
            if (in_array($fecha->format('w'), $item['dias']) || ($fecha->format('w') == 0 && in_array(7, $item['dias']))) {
                foreach ($item['intervalo'] as $index => $intervalo) {
                    $a = new \DateTime($intervalo['hfin']);
                    if($a->format("H:i:s")=="00:00:00"){
                        $a->modify('+1 day');
                    }
                    $b = new \DateTime($intervalo['hinicio']);
                    $interval = $a->diff($b);
                    $dif = $interval->format('%h') * 60;
                    $dif += $interval->format('%i');
                    $dif = $dif / $intervalo['secuencia'];

                    $hora = new \DateTime($fecha->format('d-m-Y') . ' ' . $intervalo['hinicio']);

                    for ($i = 0; $i < $dif+1; $i++) {

                        $string_hora = $hora->format('H:i') . ' - ' . $hora->modify("+{$intervalo['secuencia']} minutes")->format('H:i');
                        $timestamp = \Carbon\Carbon::parse($hora->format('d-m-Y H:i:s'))->subMinutes($intervalo['secuencia'])->timestamp;

                        $horario[$index][$i] = $timestamp;

                        if ($hora->format('H:i') == $intervalo['hfin']) {
                            break;
                        }
                    }
                }
            }
        }
        return $horario;
    }

    public function horario_con_reservas_por_dia($fecha)
{
    $fecha = new \DateTime($fecha);
    $horario = [];

    foreach ($this->horario_deserialized as $key => $item) {
        // Verifica si las claves 'dias' e 'intervalo' existen
        $dias = $item['dias'] ?? [];
        $intervalos = $item['intervalo'] ?? [];

        if (in_array($fecha->format('w'), $dias) || ($fecha->format('w') == 0 && in_array(7, $dias))) {
            foreach ($intervalos as $index => $intervalo) {
                $a = new \DateTime($intervalo['hfin']);

                if ($a->format("H:i:s") == "00:00:00") {
                    $a->modify('+1 day');
                }

                $b = new \DateTime($intervalo['hinicio']);
                $interval = $a->getTimestamp() > $b->getTimestamp() ? $a->diff($b) : new \DateTime(date('H:i', $a->getTimestamp() - $b->getTimestamp()));
                $dif = !is_a($interval, 'DateTime') ? $interval->format("%h") * 60 : $interval->format('H') * 60;
                $dif += !is_a($interval, 'DateTime') ? $interval->format("%i") : $interval->format('i');
                $dif = $dif / $intervalo['secuencia'];

                $hora = new \DateTime($fecha->format('d-m-Y') . ' ' . $intervalo['hinicio']);

                for ($i = 0; $i < $dif + 1; $i++) {
                    $string_hora = $hora->format('H:i') . ' - ' . $hora->modify("+{$intervalo['secuencia']} minutes")->format('H:i');
                    $timestamp = \Carbon\Carbon::parse($hora->format('d-m-Y H:i:s'))->subMinutes($intervalo['secuencia'])->timestamp;

                    $fechas_activas = $this->get_reserva_activa_fecha_hora($timestamp);

                    $horario[$index][$i]['reservado'] = $fechas_activas ? true : false;
                    $horario[$index][$i]['string'] = $string_hora;
                    $horario[$index][$i]['height'] = str_replace(',', '.', $intervalo['secuencia'] / 10);
                    $horario[$index][$i]['width'] = str_replace(',', '.', ($intervalo['secuencia'] * 40) / 60);
                    $suma_hora = 0;
                    for ($y = 7; $y < intval(explode(":", date('H:i', $timestamp))[0]); $y++) {
                        $suma_hora += 40;
                    }
                    $hora_coord = $suma_hora + (explode(":", date('H:i', $timestamp))[1] * 2 / 3);
                    $horario[$index][$i]['hora'] = str_replace(',', '.', $hora_coord);
                    $horario[$index][$i]['tramos'] = 1;
                    $horario[$index][$i]['timestamp'] = $timestamp;
                    $horario[$index][$i]['num_res'] = $fechas_activas->count();
                    $horario[$index][$i]['valida'] = $this->check_reserva_valida($timestamp);

                    $horario[$index][$i]['estado'] = 'ok';

                    if ($this->check_reserva_valida($timestamp) == false) {
                        $horario[$index][$i]['estado'] = 'reservado';

                        if ($this->check_desactivado($timestamp)) {
                            $horario[$index][$i]['estado'] = 'desactivado';
                        }

                        if ($this->checkantelacion($timestamp)) {
                            $horario[$index][$i]['estado'] = 'desactivado';
                        }

                        if ($this->checkpasada($timestamp)) {
                            $horario[$index][$i]['estado'] = 'desactivado';
                        }
                    }

                    if ($hora->format('H:i') == $intervalo['hfin']) {
                        break;
                    }
                }
            }
        }
    }

    return $horario;
}

    // Obtienes los dias del mes que tiene el horario de la pista
    // public function obtener_dias_horario_mes($mesElegido){
    //     foreach ($this->horario_deserialized as $key => $item) {
    //         $fechas = []; //Array que guarda el timestamp de los días del mes del horario

    //         for ($dia = 1; $dia <= 31; $dia++) {
    //             $fecha = \Carbon\Carbon::createFromDate(null, $mesElegido, $dia);
    //             if ($fecha->month != $mesElegido) {
    //                 // Si el día está fuera del mes actual, terminar el bucle
    //                 break;
    //             }
    //             if (in_array($fecha->dayOfWeek, $item["dias"])) {
    //                 $fechas[] = $fecha->timestamp;
    //             }
    //         }

    //         // Ordenar el array de fechas por orden ascendente
    //         sort($fechas);
    //     }
    //     return $fechas;
    // }

    public function horario_con_reservas_por_mes($mesYearElegido)
    {
        // $fecha = new \DateTime($fecha);
        $horario=[];
        // dd($this->horario_deserialized);
        $fechas = obtener_dias_horario_mes($mesYearElegido);
        foreach ($this->horario_deserialized as $key => $item) {
            foreach ($item['intervalo'] as $index => $intervalo) {

                $a = new \DateTime($intervalo['hfin']);

                if($a->format("H:i:s")=="00:00:00"){
                    $a->modify('+1 day');
                }
                // $b = new \DateTime($intervalo['hinicio']);
                // $interval = $a->getTimestamp() > $b->getTimestamp() ? $a->diff($b) : new \DateTime(date('H:i', $a->getTimestamp() - $b->getTimestamp()));
                // $dif = !is_a($interval, 'DateTime') ? $interval->format("%h") * 60 : $interval->format('H') * 60;
                // $dif += !is_a($interval, 'DateTime') ? $interval->format("%i") : $interval->format('i');
                // $dif = $dif / $intervalo['secuencia'];

                foreach ($fechas as $i => $fecha) {
                    $fecha = new \DateTime(date('Y-m-d', $fecha));
                    $hora = new \DateTime($fecha->format('d-m-Y') . ' ' . $intervalo['hinicio']);
                    $timestamp = \Carbon\Carbon::parse($hora->format('d-m-Y H:i:s'))->timestamp;
                    $fechas_activas = $this->get_reserva_activa_fecha_hora($timestamp);
                    // dd($fechas_activas);
                    $horario[$index][$i]['reservado'] = $fechas_activas ? true : false;
                    $width = 100/count($fechas);
                    $horario[$index][$i]['width'] = $width;
                    $horario[$index][$i]['hora'] = $width*$i;
                    $horario[$index][$i]['tramos'] = 1;
                    $horario[$index][$i]['timestamp'] = $timestamp;
                    $horario[$index][$i]['num_res'] = $fechas_activas->count();
                    $horario[$index][$i]['valida'] = $this->check_reserva_valida($timestamp);
                    $horario[$index][$i]['estado'] = 'ok';
                    // dd(\Carbon\Carbon::parse($hora->format('d-m-Y H:i:s'))->subMinutes($intervalo['secuencia']));
                    if($this->check_reserva_valida($timestamp)==false){



                        $horario[$index][$i]['estado'] = 'reservado';

                        if($this->check_desactivado($timestamp)){

                            $horario[$index][$i]['estado'] = 'desactivado';
                        }

                        if($this->checkantelacion($timestamp)){

                            $horario[$index][$i]['estado'] = 'desactivado';
                        }

                        if($this->checkpasada($timestamp)){

                            $horario[$index][$i]['estado'] = 'desactivado';

                        }
                    }

                    if ($hora->format('H:i') == $intervalo['hfin']) {
                        break;
                    }
                }

                // for ($i = 0; $i < $dif+1; $i++) {

                //     // $string_hora = $hora->format('H:i') . ' - ' . $hora->modify("+{$intervalo['secuencia']} minutes")->format('H:i');
                //     $timestamp = \Carbon\Carbon::parse($hora->format('d-m-Y H:i:s'))->subMinutes($intervalo['secuencia'])->timestamp;

                //     $fechas_activas = $this->get_reserva_activa_fecha_hora($timestamp);

                //     $horario[$index][$i]['reservado'] = $fechas_activas ? true : false;
                //     // $horario[$index][$i]['string'] = $string_hora;
                //     $horario[$index][$i]['height'] = str_replace(',', '.', $intervalo['secuencia'] / 10);
                //     $horario[$index][$i]['width'] = str_replace(',', '.', ($intervalo['secuencia']*40) / 60);
                //     $suma_hora = 0;
                //     for ($y=7; $y < intval(explode(":", date('H:i', $timestamp))[0]); $y++) {
                //         $suma_hora += 40;
                //     }
                //     $hora_coord = $suma_hora + (explode(":", date('H:i', $timestamp))[1]*2/3);
                //     $horario[$index][$i]['hora'] = str_replace(',', '.', $hora_coord);
                //     $horario[$index][$i]['tramos'] = 1;
                //     $horario[$index][$i]['timestamp'] = $timestamp;
                //     $horario[$index][$i]['num_res'] = $fechas_activas->count();
                //     $horario[$index][$i]['valida'] = $this->check_reserva_valida($timestamp);

                //     $horario[$index][$i]['estado'] = 'ok';

                //     if($this->check_reserva_valida($timestamp)==false){



                //         $horario[$index][$i]['estado'] = 'reservado';

                //         if($this->check_desactivado($timestamp)){

                //             $horario[$index][$i]['estado'] = 'desactivado';
                //         }

                //         if($this->checkantelacion($timestamp)){

                //             $horario[$index][$i]['estado'] = 'desactivado';
                //         }

                //         if($this->checkpasada($timestamp)){

                //             $horario[$index][$i]['estado'] = 'desactivado';

                //         }


                //     }

                //     if ($hora->format('H:i') == $intervalo['hfin']) {
                //         break;
                //     }
                // }
            }

        }
        return $horario;
    }

    public function horarios_final($period)
    {
        $final = [];

        $reservasActivasFinal = Reserva::where(
            [
                ['id_pista', $this->id],
                ['estado', '!=', 'canceled'],
                ['fecha', '>=', $period->start->format('Y-m-d')],
                ['fecha', '<=', $period->end->format('Y-m-d')]
            ]
        )->get();
        // foreach ($reservasActivasFinal as $key => $value) {
        //     if($value->id == 84878){
        //     }
        // }
        $desactivacionesReservas = Desactivacion_reserva::where([['id_pista', $this->id], ['timestamp', '>=', $period->start->getTimestamp()], ['timestamp', '<=', $period->end->getTimestamp()]])->get();
        $excepcionesDesactivacionesPeriodicas = Excepciones_desactivaciones_periodicas::where([['id_pista', $this->id], ['timestamp', '>=', $period->start->getTimestamp()], ['timestamp', '<=', $period->end->getTimestamp()]])->get();
        foreach ($period as $fecha) {
            $carbon_fecha = \Carbon\Carbon::parse($fecha);
            $horario = [];

            foreach ($this->horario_deserialized as $key => $item) {
                if (
                    in_array($carbon_fecha->dayOfWeek, $item['dias'])
                    || ($carbon_fecha->format('w') == 0 && in_array(7, $item['dias']))
                ) {
                    foreach ($item['intervalo'] as $index => $intervalo) {
                        $hInicio = Carbon::parse($carbon_fecha->format('Y-m-d') . ' ' . $intervalo['hinicio']);
                        $hFin = Carbon::parse($carbon_fecha->format('Y-m-d') . ' ' . $intervalo['hfin']);

                        $secuencia = $intervalo['secuencia'];

                        $horas = [];
                        while ($hInicio->lt($hFin)) {
                            $horas[] = $hInicio->copy();
                            $hInicio->addMinutes($secuencia);
                        }

                        foreach ($horas as $i => $hora) {

                            $timestamp = $hora->getTimestamp();
       
                            $reservasActivas =
                                $reservasActivasFinal->where('estado', '!=', 'canceled')->filter(function ($reserva) use ($timestamp) {
                                    return in_array($timestamp, $reserva->horarios_deserialized);
                                });


                            // if($fecha->format('d-m-Y') == "05-06-2024"){
                            //     if($timestamp == 1717592400){
                            //         dd($reservasActivas, $horas);
                            //     }
                
                            // }
                            $fecha1 = Carbon::createFromTimestamp($timestamp)
                                ->startOfDay();


             
                            // checkReservaActiva

                            $horario[$index][$i]['valida'] = false;
                            $fecha2 = Carbon::now()->addDays($this->max_dias_antelacion)->startOfDay();


                            $checkDesactivado = false;
                            $desactivaciones = count($desactivacionesReservas->where('timestamp', $timestamp));

                            if (
                                $desactivaciones > 0
                            ) {
                                $checkDesactivado = true;
                            }
                            $desactivaciones_periodicas_dia = $this->check_desactivacion_periodica(date('Y-m-d', $timestamp));
                            if ($desactivaciones_periodicas_dia) {
                                foreach ($desactivaciones_periodicas_dia as $desactivacion) {

                                    if (
                                        strtotime(date('H:i', $timestamp)) >= strtotime($desactivacion->hora_inicio) &&
                                        strtotime(date('H:i', $timestamp)) < strtotime($desactivacion->hora_fin)
                                    ) {

                                        $excepciones = count($excepcionesDesactivacionesPeriodicas->where('timestamp', $timestamp));

                                        if (
                                            $excepciones > 0
                                        ) {
                                            $checkDesactivado = false;
                                        }
                                        $checkDesactivado = true;
                                    }
                                }
                            }

                            $fecha5 = Carbon::now()->addHours($this->atenlacion_reserva)->startOfMinute();
                            $fecha6 = Carbon::createFromTimestamp($timestamp)->addMinutes($this->get_minutos_given_timestamp($timestamp))->startOfMinute();

                            if (
                                $fecha1 < $fecha2 &&
                                !$checkDesactivado &&
                                $this->reservas_por_tramo > count($reservasActivas) &&
                                $fecha5 < $fecha6

                            ) {
                                $horario[$index][$i]['valida'] = true;
                            }
                 
                            $fecha7 = Carbon::now()->startOfDay();
                            $fecha8 = now()->addDays($this->max_dias_antelacion)->startOfDay();
                            $fecha9 = Carbon::now()->addHours($this->atenlacion_reserva)->startOfMinute();

                            $fecha10 = Carbon::createFromTimestamp($timestamp)->addMinutes($this->get_minutos_given_timestamp($timestamp))->startOfMinute();

                            $n_reservas_en_espera =
                                $reservasActivas->filter(function ($reserva) use ($timestamp) {
                                    return in_array($timestamp, $reserva->horarios_deserialized) && $reserva->estado == 'espera';
                                })->count();


                                $horario[$index][$i]['siguiente_reserva_lista_espera'] =
                                $fecha7 < $fecha8 &&
                                !$checkDesactivado &&
                                $fecha9 < $fecha10 &&
                                $this->reservas_por_tramo - count($reservasActivas) <= 0 &&
                                $this->instalacion && $this->instalacion->configuracion && // Verifica que la relación esté cargada
                                $this->instalacion->configuracion->reservas_lista_espera > 0 &&
                                $n_reservas_en_espera < $this->instalacion->configuracion->reservas_lista_espera;

                            $horario[$index][$i]['reservado'] = $reservasActivas ? true : false;
                            $horario[$index][$i]['string'] = $hora->format('H:i') . ' - ' . $hora->copy()->addMinutes($secuencia)->format('H:i');
                            $horario[$index][$i]['height'] = str_replace(',', '.', $intervalo['secuencia'] / 10);
                            $horario[$index][$i]['tramos'] = 1;
                            $horario[$index][$i]['timestamp'] = $timestamp;
                            $horario[$index][$i]['num_res'] = count($reservasActivas);
                            $horario[$index][$i]['reunion'] = $this->id_instalacion == 2 ? ($reservasActivas[0] ?? null)  : null;
              
                            // dd($this->instalacion);
                            if ($hora->format('H:i') == $intervalo['hfin']) {
                                break;
                            }
                        }
                    }
                }
            }
            $final[] = $horario;
        }
        return $final;
    }


    public function checkantelacion($timestamp){

        if(strtotime(date('Y-m-d', $timestamp)) < strtotime(date('Y-m-d') . " +{$this->max_dias_antelacion} days")){

            return false;
        }
        return true;
    }
    public function checkpasada($timestamp){
        if(
        new \DateTime(date('d-m-Y H:i', strtotime("+{$this->atenlacion_reserva} hours"))) < new \DateTime(date('d-m-Y H:i', strtotime(date('d-m-Y H:i', $timestamp) . " +{$this->get_minutos_given_timestamp($timestamp)} minutes" )))
        ){
            return false;
        }

        return true;

    }

    public function horario_con_reservas_por_dia_admin($fecha)
    {
        $fecha = new \DateTime($fecha);
        $horario=[];
        foreach ($this->horario_deserialized as $key => $item) {
            if (in_array($fecha->format('w'), $item['dias']) || ($fecha->format('w') == 0 && in_array(7, $item['dias']))) {
                foreach ($item['intervalo'] as $index => $intervalo) {
                    $a = new \DateTime($intervalo['hfin']);
                    if($a->format("H:i:s")=="00:00:00"){
                        $a->modify('+1 day');
                    }
                    $b = new \DateTime($intervalo['hinicio']);
                    $interval = $a->diff($b);
                    $dif = $interval->format('%h') * 60;
                    $dif += $interval->format('%i');
                    $dif = $dif / $intervalo['secuencia'];

                    $hora = new \DateTime($fecha->format('d-m-Y') . ' ' . $intervalo['hinicio']);

                    for ($i = 0; $i < $dif+1; $i++) {

                        $string_hora = $hora->format('H:i') . ' - ' . $hora->modify("+{$intervalo['secuencia']} minutes")->format('H:i');
                        $timestamp = \Carbon\Carbon::parse($hora->format('d-m-Y H:i:s'))->subMinutes($intervalo['secuencia'])->timestamp;

                        $fechas_activas = $this->get_reserva_activa_fecha_hora($timestamp);

                        $horario[$index][$i]['reservado'] = $fechas_activas ? true : false;
                        $horario[$index][$i]['string'] = $string_hora;
                        $horario[$index][$i]['tramos'] = 1;
                        $horario[$index][$i]['reservas'] = $this->get_reservas_fecha_hora($timestamp);
                        $horario[$index][$i]['num_res'] = $fechas_activas->count();
                        $horario[$index][$i]['timestamp'] = $timestamp;
                        $horario[$index][$i]['desactivado'] = $this->check_desactivado($timestamp);

                        if ($hora->format('H:i') == $intervalo['hfin']) {
                            break;
                        }
                    }
                }
            }
        }
        return $horario;
    }


/*
    public function get_minutos_given_timestamp($timestamp)
    {
        $fecha = new \DateTime(date('Y-m-d H:i', $timestamp));

        foreach ($this->horario_deserialized as $key => $item) {
            if (in_array($fecha->format('w'), $item['dias']) || ($fecha->format('w') == 0 && in_array(7, $item['dias']))) {
                foreach ($item['intervalo'] as $index => $intervalo) {
                    if (strtotime(date('H:i', $timestamp)) >= strtotime($intervalo['hinicio']) && strtotime(date('H:i', $timestamp)) < strtotime($intervalo['hfin'])) {
                        return $intervalo['secuencia'];
                    }
                }
            }
        }
    }
*/

    public function get_minutos_given_timestamp($timestamp)
    {
        $fecha = new \DateTime(date('Y-m-d H:i', $timestamp));

        foreach ($this->horario_deserialized as $key => $item) {
            if (in_array($fecha->format('w'), $item['dias']) || ($fecha->format('w') == 0 && in_array(7, $item['dias']))) {
                foreach ($item['intervalo'] as $index => $intervalo) {


                    if($intervalo['hfin']=="00:00"){

                        $a = new \DateTime($intervalo['hfin']);

                        $a->modify('+1 day');

                        $valor= $a->getTimestamp();



                    }else{
                        $valor= strtotime($intervalo['hfin']);
                    }
                    if (strtotime(date('H:i', $timestamp)) >= strtotime($intervalo['hinicio']) && strtotime(date('H:i', $timestamp)) < $valor) {
                        return $intervalo['secuencia'];
                    }
                }
            }
        }
    }




    public function get_intervalo_given_timestamp($timestamp)
{
    $fecha = new \DateTime(date('Y-m-d H:i', $timestamp));

    foreach ($this->horario_deserialized as $key => $item) {
        if (in_array($fecha->format('w'), $item['dias']) || ($fecha->format('w') == 0 && in_array(7, $item['dias']))) {
            foreach ($item['intervalo'] as $index => $intervalo) {
                $valor = ($intervalo['hfin'] == "00:00") ?
                    (new \DateTime($intervalo['hfin']))->modify('+1 day')->getTimestamp() :
                    strtotime($intervalo['hfin']);

                if (strtotime(date('H:i', $timestamp)) >= strtotime($intervalo['hinicio']) && strtotime(date('H:i', $timestamp)) < $valor) {
                    $interval = $intervalo;
                    $interval['tipopextra'] = $timestamp > strtotime('2022-08-22') ? ($intervalo['tipopextra'] ?? 'fijo') : 'fijo';
                    $interval['pextra'] = $timestamp > strtotime('2022-08-22') ? ($intervalo['pextra'] ?? 0) : 0;
                    return $interval;
                }
            }
        }
    }

    return null; // Devuelve null si no se encuentra un intervalo
}

    public function get_precio_total_given_timestamp($timestamp, $extra_opciones = 0)
    {
        $intervalo = $this->get_intervalo_given_timestamp($timestamp);
        $extra = $intervalo['pextra'];

        return !isset($intervalo['tipopextra']) || $intervalo['tipopextra'] == 'fijo' ?
                $this->precio + $extra + $extra_opciones :
                ($this->precio + $extra_opciones) + (($this->precio + $extra_opciones) * $extra)/100;
    }

    public function get_precio_extra_given_timestamp($timestamp)
    {
        $fecha = new \DateTime(date('Y-m-d H:i', $timestamp));

        foreach ($this->horario_deserialized as $key => $item) {
            if (in_array($fecha->format('w'), $item['dias']) || ($fecha->format('w') == 0 && in_array(7, $item['dias']))) {
                foreach ($item['intervalo'] as $index => $intervalo) {
                    if (strtotime(date('H:i', $timestamp)) >= strtotime($intervalo['hinicio']) && strtotime(date('H:i', $timestamp)) < strtotime($intervalo['hfin'])) {
                        return $intervalo['pextra'];
                    }
                }
            }
        }
    }

    public function get_timestamp_tramo_given_timestamp($timestamp)
    {
        $intervalo = $this->get_intervalo_given_timestamp($timestamp);

        if (!$intervalo) {
            return false;
        }

        $a = new \DateTime($intervalo['hfin']);
        if($a->format("H:i:s")=="00:00:00"){
            $a->modify('+1 day');
        }
        $b = new \DateTime($intervalo['hinicio']);
        $interval = $a->diff($b);
        $diff_minutes = $interval->format("%h") * 60;
        $diff_minutes += $interval->format("%i");

        $numero_veces = $diff_minutes/$intervalo['secuencia'];

        /* if (!is_int($diff_minutes/$intervalo['secuencia'])) { */
        $hora = new \DateTime($intervalo['hinicio']);
        for ($i=0; $i < floor($numero_veces); $i++) {
            $siguiente_hora = date('H:i', strtotime($hora->format('H:i') . " +{$intervalo['secuencia']} minutes"));
            if (strtotime($hora->format('H:i')) <= strtotime(date('H:i', $timestamp)) && strtotime($siguiente_hora) >  strtotime(date('H:i', $timestamp))) {
                return date('Y-m-d H:i', strtotime(date('Y-m-d', $timestamp) . ' ' . $hora->format('H:i')));
            }
            $hora->modify("+{$intervalo['secuencia']} minutes");

        }
        return false;
    }

    public function maximo_reservas_para_usuario(User $user)
    {
        return $this->reservas_por_tramo < $user->numero_reservas_perimitidas($this->tipo) ? ($this->reservas_permitidas_restantes(request()->timestamp) < $this->reservas_por_tramo ? $this->reservas_permitidas_restantes(request()->timestamp) : $this->reservas_por_tramo) : ($this->reservas_permitidas_restantes(request()->timestamp) < $user->numero_reservas_perimitidas($this->tipo) ?  $this->reservas_permitidas_restantes(request()->timestamp) : $user->numero_reservas_perimitidas($this->tipo));
    }
}
