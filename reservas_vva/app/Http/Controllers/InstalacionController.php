<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewReserva;
use App\Mail\CancelarReserva;
use App\Mail\ReservaAdmin;
use App\Models\Pista;
use App\Models\Instalacion;
use App\Models\Permiso;
use App\Models\User;
use App\Models\Cobro;
use App\Models\Configuracion;
use App\Models\Reserva;
use App\Models\Pedido;
use App\Models\Desactivacion_reserva;
use App\Models\Campos_personalizados;
use App\Models\Deporte;
use App\Models\Pistas_campos_relation;
use App\Models\Valor_campo_personalizado;
use App\Models\Desactivaciones_periodicas;
use App\Models\Reservas_periodicas;
use App\Models\Excepciones_desactivaciones_periodicas;
use App\Models\Mensajes_difusion;
use App\Models\Tipos_participante;
use App\Models\Evento;
use App\Models\Participante;
use App\Models\Participante_eventos_mes;
use App\Models\Dias_festivos;
use App\Models\Servicio_Usuario;
use App\Models\Servicio;
use App\Models\Recibo;
use App\Models\Acceso;
use Intervention\Image\ImageManagerStatic as Image;
use DateTime;
use Carbon\Carbon;
use App\Models\Registro;
use App\Mail\NewInscripcion;
use App\Models\BonoParticipante;
use App\Models\Pedido_participante_log;
use App\Mail\NotificacionEntradas;
use App\Models\Log;
use App\Models\LogRecibosDiario;
/* use Illuminate\Support\Facades\Auth; */

class InstalacionController extends Controller
{
    public function rangeWeek ($date) {
        $dt = strtotime ($date);
        return array (
          "start" => date ('N', $dt) == 1 ? date ('Y-m-d', $dt) : date ('Y-m-d', strtotime ('last monday', $dt)),
          "end" => date ('Y-m-d', strtotime ('next monday', $dt))
        );
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function ver_accesos_usuario(Request $request){
        $user = User::find($request->id);
        return $user->accesos;
    }
    public function index(Request $request) {
        $reservas_caducadas = Reserva::where([['estado', 'pendiente'], ['creado_por', 'user'], ['created_at', '<', \Carbon\Carbon::now()->subMinutes(15)->toDateTimeString()]]);

        Pedido::whereIn('id_reserva', $reservas_caducadas->pluck('id'))->update(['estado' => 'cancelado']);
        Pedido::whereIn('id_reserva', $reservas_caducadas->pluck('id'))->delete();
        $reservas_caducadas->update(['estado' => 'canceled']);
        Reserva::where([['estado', 'canceled'], ['creado_por', 'user'], ['created_at', '<', \Carbon\Carbon::now()->subMinutes(15)->toDateTimeString()]])->delete();

        $instalacion = auth()->user()->instalacion;
        if (isset($request->semana)) {
            if (isset($request->week)) {
                $semana = $request->week;
                $d = (int)substr($semana, 6) * 7;
                $date = DateTime::createFromFormat('z Y', $d . ' ' . substr($semana, 0, 4));

                $semana = $this->rangeWeek(date("Y-m-d", strtotime($date->format('Y-m-d')."+{$request->semana} weeks")));
            }else {
                $semana = $this->rangeWeek(date("Y-m-d", strtotime(date('Y-m-d')."+{$request->semana} weeks")));
            }
        }
        else{
            $semana = $this->rangeWeek(date('Y-m-d'));
        }


        if (isset($request->week) && !isset($request->semana)) {
            $semana = $request->week;
            $d = (int)substr($semana, 6) * 7;
            $date = DateTime::createFromFormat('z Y', $d . ' ' . substr($semana, 0, 4));

            $semana = $this->rangeWeek($date->format('Y-m-d'));
        }


        // solo ocurre si el tipo_reservas_id es 2, es decir con los mercadillos, ya que funciona de mes en mes y no por semanas
        $newMonthYear = date('Y-m');
        if(isset($request->mes)){
            if(isset($request->month)){
                $monthYear = $request->month;
                // Convertir $monthYear a un objeto Carbon
                $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
                $date->setDay(1);
                // Sumar los meses deseados
                $date->addMonths($request->mes);

                // Obtener la nueva fecha en el formato deseado
                $newMonthYear = $date->format('Y-m');
            }
            else{
                $date = \Carbon\Carbon::createFromFormat('Y-m', date('Y-m'));
                $date->setDay(1);

                // Sumar los meses deseados (si $request->mes es negativo, restará meses en lugar de sumar)
                $date->addMonths($request->mes);

                // Obtener la nueva fecha en formato Y-m
                $newMonthYear = $date->format('Y-m');

                // setlocale(LC_ALL,"es_ES");
                //  \Carbon\Carbon::setLocale('es');
                 $fecha = \Carbon\Carbon::createFromFormat('Y-m', $newMonthYear);
                //  dd($fecha);
                //  $mes = $fecha->format("F");
                // dd($mes, $fecha);
            }
        }

        if (isset($request->month) && !isset($request->mes)) {
            $newMonthYear = $request->month;
        }

        $fechasMes = "";
        if ($instalacion->tipo_reservas_id == 2) {
            $timestamp = \Carbon\Carbon::createFromFormat('Y-m', $newMonthYear)->timestamp;
            $fechasMes = obtener_dias_horario_mes(strtotime($newMonthYear));
        }

        $period = new \DatePeriod(new DateTime($semana['start']), new \DateInterval('P1D'), new DateTime($semana['end']));
        if (auth()->user()->subrol == 'piscina') {
            $pistas = Pista::where([['id_instalacion', auth()->user()->instalacion->id], ['tipo', 'Piscina']])->orderByDesc('id')->get();
        } elseif (auth()->user()->subrol == 'deportes') {
            $pistas = Pista::where([['id_instalacion', auth()->user()->instalacion->id]])->orderBy('id')->get();
        } else {
            $pistas = Pista::where('id_instalacion', auth()->user()->instalacion->id)->orderBy('id')->get();
        }
        

        if(request()->slug_instalacion == "la-guijarrosa" || request()->slug_instalacion == "santaella" ){
            $user = auth()->user();
            $ultimoRegistro = $user->registros()->latest()->first();
            return view('instalacion.home', compact('instalacion', 'period', 'pistas', 'newMonthYear', 'fechasMes','ultimoRegistro'));
        }else{
            return view('instalacion.home', compact('instalacion', 'period', 'pistas', 'newMonthYear', 'fechasMes'));
        }

    }

    public function reservas_dia(Request $request)
    {
        $pistas = Pista::where('id_instalacion', auth()->user()->instalacion->id)->get();
        $ret_pistas = [];
        foreach ($pistas as $i => $pista){
            $ret_pistas[$i] = $pista;
            $ret_pistas[$i]['num_reservas_dia'] = count($pista->reservas_activas_por_dia($request->fecha));
            $ret_pistas[$i]['res_dia'] = $pista->horario_con_reservas_por_dia_admin($request->fecha);
        }

        return $ret_pistas;
    }

    public function reservas_dia_por_pista(Request $request)
    {
        $pista = Pista::find($request->id_pista);
        $ret_pistas = $pista;
        $ret_pistas['num_reservas_dia'] = count($pista->reservas_activas_por_dia($request->fecha));
        $ret_pistas['res_dia'] = $pista->horario_con_reservas_por_dia_admin($request->fecha);

        return $ret_pistas;
    }

    public function numero_reservas_dia_por_pista(Request $request)
    {
        $reservas = Reserva::where([['estado', 'active'], ['fecha', $request->fecha]])->orWhere([['estado', 'pendiente'], ['fecha', $request->fecha]])->get();

        $retorno = [];
        foreach ($reservas as $key => $value) {
            if (isset($retorno[$value['id_pista']])) {
                $retorno[$value['id_pista']] += 1;
            } else {
                $retorno[$value['id_pista']] = 1;
            }
        }

        return $retorno;
    }

    public function validar_reserva(Request $request)
    {

        $reserva = Reserva::find($request->id);

        if ($reserva->estado == 'active' || $reserva->estado == 'pendiente') {
            $estado_pedido = $request->accion == 'active' ? 'pagado' : ($request->accion == 'canceled' ? 'Devolucion pendiente' : 'En proceso');
            if ($reserva->reserva_multiple) {
                Reserva::where('reserva_multiple', $reserva->reserva_multiple)->update((['estado' =>$request->accion, 'observaciones_admin' => $request->observaciones]));
                Pedido::where('id', Reserva::where('reserva_multiple', $reserva->reserva_multiple)->first()->id_pedido)->update(['estado' => $estado_pedido]);

                return redirect()->back()->with('dia_reserva_hecha', date('Y-m-d', $reserva->timestamp));
            }

            $reserva->update((['estado' =>$request->accion, 'observaciones_admin' => $request->observaciones, 'observaciones' => $request->observaciones]));
            if($request->accion == 'canceled'){

                $bloqueos = Desactivacion_reserva::where('reserva_id',$request->id)->delete();

            }
                Pedido::where('id', $reserva->id_pedido)->update(['estado' => $estado_pedido]);

            if($request->accion == 'canceled') {
                Mail::to($reserva->user->email)->send(new CancelarReserva($reserva->user, $reserva));
                $pedido = Pedido::find($reserva->id_pedido);

                \DB::purge('mysql');

                $dynamic_db_name = 'manager_reservas';
                $config = \Config::get('database.connections.mysql');

                $config['database'] = $dynamic_db_name;
                $config['password'] = "#3p720hqK";
                config()->set('database.connections.mysql', $config);

                $pedido_manager = New Pedido();
                $pedido_manager->id = $pedido->id;
                $pedido_manager->id_usuario = $pedido->id_usuario;
                $pedido_manager->amount = $pedido->amount;
                $pedido_manager->id_reserva = $pedido->id_reserva;
                $pedido_manager->estado = $pedido->estado;
                $pedido_manager->save();





                \DB::purge('mysql');

            }
            return redirect()->back()->with('dia_reserva_hecha', date('Y-m-d', $reserva->timestamp));
        }

        return redirect()->back()->with('error', 'true');
    }

    public function cancelar_reserva(Request $request)
    {
        $reserva = Reserva::find($request->id);
        /* if ($reserva->estado == 'canceled') { */
            if ($reserva->reserva_multiple) {
                Reserva::where('reserva_multiple', $reserva->reserva_multiple)->update((['estado' => 'canceled', 'creado_por' => 'admin', 'observaciones_admin' => 'Cancelado por admin']));
                $estado_pedido = 'cancelado';
                Pedido::where('id', Reserva::where('reserva_multiple', $reserva->reserva_multiple)->first()->id_pedido)->update(['estado' => 'cancelado']);
                return redirect()->back()->with('dia_reserva_hecha', date('Y-m-d', $reserva->timestamp));
            }
            $reserva->update((['estado' => 'canceled', 'observaciones_admin' => 'Cancelado por admin']));
            Pedido::where('id', $reserva->id_pedido)->update(['estado' => 'cancelado']);
            return redirect()->back()->with('dia_reserva_hecha', date('Y-m-d', $reserva->timestamp));
        /* } */

        return redirect()->back()->with('error', 'true');
    }

    public function reschedule_reserva(Request $request)
    {
        $reserva = Reserva::find($request->id);
        $pista = Pista::find($request->pista); // Obtener la pista seleccionada

        if ($reserva->estado == 'active' || $reserva->estado == 'pendiente') {
            $timestamp = date('Y-m-d H:i', strtotime($request->date . ' ' . date('H:i', strtotime($request->hora))));
            $hora_reserva = $pista->get_timestamp_tramo_given_timestamp(strtotime($timestamp));

            if (!$hora_reserva) {
                return redirect()->back()->with('error', 'No se ha cambiado la fecha porque no existen tramos horarios con esa fecha y hora. Comprueba bien la hora.');
            }

             // Comprobar si la pista ya tiene una reserva en la fecha y hora seleccionadas
            $existeReserva = Reserva::where('fecha', $request->date)
            ->where('timestamp', strtotime($hora_reserva))
            ->where('id_pista', $pista->id)
            ->exists();

            if ($existeReserva) {
                return redirect()->back()->with('error', 'La pista seleccionada ya está reservada en esa fecha y hora.');
            }

            if ($reserva->reserva_multiple) {
                Reserva::where('reserva_multiple', $reserva->reserva_multiple)
                        ->update((['fecha' => $request->date, 'timestamp' => strtotime($hora_reserva), 'horarios' => serialize([strtotime($hora_reserva)])]));

                return redirect()->back()->with('dia_reserva_hecha', date('Y-m-d', $reserva->timestamp));
            }
            $reserva->update((['fecha' => $request->date, 'timestamp' => strtotime($hora_reserva), 'horarios' => serialize([strtotime($hora_reserva)]),'id_pista' => $pista->id]));
            return redirect()->back()->with('dia_reserva_hecha', date('Y-m-d', $reserva->timestamp));
        }

        return redirect()->back()->with('error', 'true');
    }

    public function actualizar_asistencia(Request $request)
    {
        Reserva::find($request->id)->update(['estado_asistencia' => $request->estado]);

        return redirect()->back()->with('success', 'Se ha actualizado la asistencia de esta reserva');
    }

    public function hacer_reserva_view(Request $request)
    {
        $reserva = Reserva::where([['id_pista', $request->id_pista], ['timestamp', $request->timestamp], ['estado', 'active']])->first();

        $pista = Pista::find($request->id_pista);
        $fecha = $request->timestamp;

        foreach ($pista->horario_deserialized as $item){
            if (in_array(date('w', $fecha), $item['dias']) || ( date('w', $fecha) == 0 && in_array(7, $item['dias']) )){
                foreach ($item['intervalo'] as $index => $intervalo){
                    $a = new \DateTime($intervalo['hfin']);
                    if($a->format("H:i:s")=="00:00:00"){
                        $a->modify('+1 day');
                    }
                    $b = new \DateTime($intervalo['hinicio']);
                    $interval = $a->diff($b);
                    $diff_minutes = $interval->format("%h") * 60;
                    $diff_minutes += $interval->format("%i");
                    $numero_veces = $diff_minutes/$intervalo['secuencia'];

                    $hora = new \DateTime($intervalo['hinicio']);
                    for ($i=0; $i < floor($numero_veces); $i++) {
                        if ($hora->format('h:i') == date(date('h:i', $fecha))) {
                            $secuencia = $intervalo['secuencia'];
                            $number = $numero_veces - $i;
                            /* $hfin = date('h:i',strtotime (date('h:i', $fecha) . " +{$intervalo['secuencia']} minutes")); */
                        }
                        $hora->modify("+{$intervalo['secuencia']} minutes");
                    }
                }
            }
        }

        $users = User::where('id_instalacion', auth()->user()->id_instalacion)->whereNotNull('aprobado')->where('rol', 'user')->orderBy('varios', 'desc')->orderBy('name', 'asc')->get();

        return view('instalacion.reservas.add', compact('pista', 'fecha', 'secuencia', 'number', 'users'));
    }

    public function hacer_reserva(Request $request)
    {
        $pista = Pista::find($request->id_pista);
        /* if (!$pista->check_reserva_valida($request->timestamp)) {
            return redirect()->back();
        } */

        if ($request->user_id == 'new_user') {
            $check_user = User::where('email', $request->email)->first();
            if ($check_user) {
                $request->user_id = $check_user->id;
            } else {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email ?? $this->generateRandomString(6) . '-newuser@gestioninstalacion.es',
                    'id_instalacion' => auth()->user()->instalacion->id,
                    'tlfno' => $request->tlfno,
                    'aprobado' => date('Y-m-d H:i:s'),
                    'rol' => 'user',
                    'password' => \Hash::make($this->generateRandomString(6)),
                ]);
                $request->user_id = $user->id;
            }
        }

        $minutos_totales = $request->secuencia * $request->tarifa;

        $timestamps[0] = (int)$request->timestamp;

        if ($request->tarifa > 1) {
            for ($i=1; $i < $request->tarifa; $i++) {
                $timestamps[$i] = \Carbon\Carbon::parse(date('d-m-Y H:i:s', $request->timestamp))->addMinutes($request->secuencia*$i)->timestamp;
            }
        }
        $reserva_multiple_id = null;

        $reservas_ids = [];
        if (isset($request->numero_reservas) && $request->numero_reservas>1) {
            for ($i=0; $i < $request->numero_reservas; $i++) {
                $reserva = Reserva::create([
                    'id_pista' => $request->id_pista,
                    'id_usuario' => $request->user_id,
                    'timestamp' => $request->timestamp,
                    'horarios' => serialize($timestamps),
                    'fecha' => date('Y/m/d', $request->timestamp),
                    'hora' => \Carbon\Carbon::createFromTimestamp($request->timestamp)->format('Hi'),
                    'tarifa' => $request->tarifa,
                    'minutos_totales' => $minutos_totales,
                    'creado_por' => 'admin'
                ]);
                array_push($reservas_ids, $reserva->id);
                $reserva_multiple_id = $i==0 ? $reserva->id : $reserva_multiple_id;
                $reserva->update(['reserva_multiple' => $reserva_multiple_id]);



                if (isset($request->observaciones)) {
                    $reserva->update(['observaciones' => $request->observaciones]);
                }

                if (isset($request->campo_adicional)) {
                    foreach ($request->campo_adicional as $id_campo => $valor) {
                        Valor_campo_personalizado::create([
                            'id_reserva' => $reserva->id,
                            'id_campo' => $id_campo,
                            'valor' => $valor
                        ]);
                    }
                }
            }
        } else {
            if($request->slug_instalacion =='vvadecordoba') {
                if ($pista->id != 10) {
                    $reserva = Reserva::create([
                        'id_pista' => $request->id_pista,
                        'id_usuario' => $request->user_id,
                        'timestamp' => $request->timestamp,
                        'horarios' => serialize($timestamps),
                        'fecha' => date('Y/m/d', $request->timestamp),
                        'hora' => \Carbon\Carbon::createFromTimestamp($request->timestamp)->format('Hi'),
                        'tarifa' => $request->tarifa,
                        'minutos_totales' => $minutos_totales,
                        'creado_por' => 'admin',
                        'estado' => $request->estado_pedido
                    ]);
                    array_push($reservas_ids, $reserva->id);

                    if (isset($request->observaciones)) {
                        $reserva->update(['observaciones' => $request->observaciones]);
                    }
                }
            }else{
                $reserva = Reserva::create([
                    'id_pista' => $request->id_pista,
                    'id_usuario' => $request->user_id,
                    'timestamp' => $request->timestamp,
                    'horarios' => serialize($timestamps),
                    'fecha' => date('Y/m/d', $request->timestamp),
                    'hora' => \Carbon\Carbon::createFromTimestamp($request->timestamp)->format('Hi'),
                    'tarifa' => $request->tarifa,
                    'minutos_totales' => $minutos_totales,
                    'creado_por' => 'admin',
                    'estado' => $request->estado_pedido
                ]);
                array_push($reservas_ids, $reserva->id);

                if (isset($request->observaciones)) {
                    $reserva->update(['observaciones' => $request->observaciones]);
                }
            }


            $precio_mas = 0;
            if (isset($request->campo_adicional)) {
                foreach ($request->campo_adicional as $id_campo => $valor) {
                    if($request->slug_instalacion =='vvadecordoba') {
                        if ($pista->id == 10 && $id_campo == 4) {
                            $valor_campo = Valor_campo_personalizado::create([
                                'id_campo' => $id_campo
                            ]);

                            $campo = $valor_campo->campo;
                            $opciones = $campo->tipo == 'select' ? unserialize($campo->opciones) : 0;

                            $valor_texto = '<ul>';
                            foreach ($valor as $key => $numero) {
                                if ($numero) {
                                    for ($i = 0; $i < $numero; $i++) {
                                        $reserva = Reserva::create([
                                            'id_pista' => $request->id_pista,
                                            'id_usuario' => $request->user_id,
                                            'timestamp' => $request->timestamp,
                                            'horarios' => serialize($timestamps),
                                            'fecha' => date('Y/m/d', $request->timestamp),
                                            'hora' => date('Hi', $request->timestamp),
                                            'tarifa' => $request->tarifa,
                                            'tipo' => $key,
                                            'minutos_totales' => $minutos_totales,
                                            'creado_por' => 'admin',
                                            'estado' => $request->estado_pedido
                                        ]);
                                        array_push($reservas_ids, $reserva->id);

                                        if (!$reserva_multiple_id) {
                                            $reserva_multiple_id = $i == 0 ? $reserva->id : $reserva_multiple_id;
                                            $valor_campo->update(['id_reserva' => $reserva->id]);
                                        }

                                        $reserva->update(['reserva_multiple' => $reserva_multiple_id]);
                                        /* if ($reserva_multiple_id) {
                                            if (isset($request->observaciones)) {
                                                $reserva->update(['observaciones' => $request->observaciones]);
                                            }
                                        } */
                                    }
                                    $valor_texto .= "<li>{$key}: {$numero} personas</li>";
                                    $precio_mas += $opciones ? $opciones[array_search($key, array_column($opciones, 'texto'))]['pextra'] * $numero : 0;
                                }
                            }
                            $valor_texto .= ' </ul>';

                            if (!isset($reserva)) {
                                $valor_campo->delete();
                                return redirect()->back()->with('error', 'No se han seleccionado entradas. No se ha completado la reserva.');
                            }
                            if (Reserva::where('reserva_multiple', $reserva_multiple_id)->get()->count() == 1) {
                                Reserva::where('reserva_multiple', $reserva_multiple_id)->update(['reserva_multiple' => null]);
                            }

                            $valor_campo->update(['valor' => $valor_texto]);
                        } else {
                            $valor_campo = Valor_campo_personalizado::create([
                                'id_reserva' => $reserva->id,
                                'id_campo' => $id_campo,
                                'valor' => $valor
                            ]);

                            $campo = $valor_campo->campo;
                            $opciones = unserialize($campo->opciones);

                            $precio_mas += $campo->tipo == 'select' ? ($opciones[array_search($valor, array_column($opciones, 'texto'))]['pextra'] ?? 0) : 0;
                        }
                    }
                    else{
                        $valor_campo = Valor_campo_personalizado::create([
                            'id_reserva' => $reserva->id,
                            'id_campo' => $id_campo,
                            'valor' => $valor
                        ]);

                        $campo = $valor_campo->campo;
                        $opciones = unserialize($campo->opciones);

                        $precio_mas += $campo->tipo == 'select' ? ($opciones[array_search($valor, array_column($opciones, 'texto'))]['pextra'] ?? 0) : 0;

                    }
                }
            }
        }



        $precio_intervalo = $pista->get_precio_total_given_timestamp($request->timestamp, $precio_mas);
        $amount = $request->tarifa * $precio_intervalo;

        $pedido = new Pedido();

        $pedido_id=generateOrderID();
        $pedido->id = $pedido_id;
        $pedido->amount = $amount;
        $pedido->id_usuario = $request->user_id;
        $pedido->id_reserva = $reserva->id;
        $pedido->estado = $request->estado_pedido == 'active' ? 'pagado' : 'En proceso';
        $pedido->tipo_pago = $request->tipo_pago == 'tarjeta' ? 'tarjeta' : 'efectivo';
        $pedido->save();

        if ($reserva_multiple_id) {
            Reserva::where('reserva_multiple', $reserva_multiple_id)->update(['id_pedido' => $pedido_id]);
        }

        Reserva::whereIn('id', $reservas_ids)->update(['id_pedido' => $pedido_id]);

        if (isset($request->sendmail)) {
            //Mail::to(auth()->user()->instalacion->user_admin->email)->send(new ReservaAdmin($reserva->user, $reserva));
            Mail::to($reserva->user->email)->send(new NewReserva($reserva->user, $reserva));
        }


        $date = new DateTime(date('Y-m-d', $request->timestamp));
        $week = $date->format("Y") . '-' . 'W' . $date->format("W");

        return redirect($request->slug_instalacion . '/admin/reservas?week='. $week)->with('dia_reserva_hecha', date('Y-m-d', $request->timestamp));
    }

    public function desactivar_tramo(Request $request) {
        $instalacion = auth()->user()->instalacion;

        Desactivacion_reserva::create([
            'id_pista' => $request->id_pista,
            'timestamp' => $request->timestamp
        ]);
        Excepciones_desactivaciones_periodicas::where([
            ['id_pista', $request->id_pista],
            ['timestamp', $request->timestamp]
        ])->delete();

        return redirect()->back()->with('dia_reserva_hecha', date('Y-m-d', $request->timestamp));
    }

    public function activar_tramo(Request $request) {
        $instalacion = auth()->user()->instalacion;

        if (isset($request->periodic)) {
            Excepciones_desactivaciones_periodicas::create([
                'id_pista' => $request->id_pista,
                'timestamp' => $request->timestamp
            ]);
        } else {
            Desactivacion_reserva::where([['id_pista', $request->id_pista],['timestamp', $request->timestamp]])->delete();
        }

        return redirect()->back()->with('dia_reserva_hecha', date('Y-m-d', $request->timestamp));
    }

    public function desactivar_dia(Request $request) {
        $pista = Pista::find($request->id_pista);

        foreach ($pista->horario_con_reservas_por_dia_admin($request->dia) as $item) {
            foreach ($item as $valor) {
                Desactivacion_reserva::create([
                    'id_pista' => $request->id_pista,
                    'timestamp' => $valor['timestamp']
                ]);
            }
        }

        return redirect()->back();
    }

    public function activar_dia(Request $request) {
        $pista = Pista::find($request->id_pista);

        foreach ($pista->horario_con_reservas_por_dia_admin($request->dia) as $item) {
            foreach ($item as $valor) {
                Desactivacion_reserva::where([['id_pista', $request->id_pista],['timestamp', $valor['timestamp']]])->delete();
            }
        }

        return redirect()->back();
    }

    public function listado_todas_reservas(Request $request)
    {
        $reservas = Reserva::where('reserva_periodica', null)->whereIn('id_pista', Pista::where('id_instalacion', auth()->user()->instalacion->id)->pluck('id'))->orderByDesc('id')->paginate(10);
        return view('instalacion.reservas.list', compact('reservas'));
    }

    public function listado_reservas_piscina(Request $request)
    {
        $reservas = Reserva::where([['reserva_periodica', null], ['id_pista', 10]])->orderByDesc('id')->paginate(10);
        return view('instalacion.reservas.list', compact('reservas'));
    }

    public function listado_asistentes_piscina(Request $request)
    {
        $period = !$request->mes ? new \DatePeriod(\Carbon\Carbon::now()->startOfMonth(), new \DateInterval('P1D'), \Carbon\Carbon::now()->endOfMonth()) :
                                   new \DatePeriod(\Carbon\Carbon::parse("2022-0{$request->mes}-01")->startOfMonth(), new \DateInterval('P1D'), \Carbon\Carbon::parse("2022-0{$request->mes}-01")->endOfMonth());
        $piscina = Pista::find(10);
        return view('instalacion.reservas.list_asistentes_piscina', compact('period', 'piscina'));
    }

    public function reservas_list_datatable(Request $request)
    {
        if(isset($request->page)){
            $data = Reserva::select('users.id as user_id','users.name as user_name', 'users.apellidos', 'pistas.nombre', 'pistas.tipo', 'reservas.id', 'reservas.timestamp', 'reservas.fecha', 'reservas.minutos_totales', 'reservas.estado', 'reservas.hora', 'reservas.id_pedido', 'reservas.tipo')
            ->join('pistas', 'reservas.id_pista', '=', 'pistas.id')->join('users', 'reservas.id_usuario', '=', 'users.id')->orderByDesc('reservas.id')->paginate(15, ['*'], 'page', $request->page);
        } else {
            $data = Reserva::select('users.id as user_id','users.name as user_name', 'users.apellidos', 'pistas.nombre', 'pistas.tipo', 'reservas.id', 'reservas.timestamp', 'reservas.fecha', 'reservas.minutos_totales', 'reservas.estado', 'reservas.hora', 'reservas.id_pedido', 'reservas.tipo')
            ->join('pistas', 'reservas.id_pista', '=', 'pistas.id')->join('users', 'reservas.id_usuario', '=', 'users.id')->where('users.name', 'like', '%'.$request->text.'%')->orderByDesc('reservas.id')->paginate(35);
        }

        return $data;
    }


    public function listado_pedidos_eventos_search(Request $request)
    {
        $instalacion = auth()->user()->instalacion;
        $search = $request->search;

        $usuarios = User::where('name', 'like', "%{$search}%")->orWhere('apellidos', 'like', "%{$search}%")->get();


        if (isset($request->tipo_pedido)) {
            if ($request->tipo_pedido == 'Piscina') {
                if($search){
                    if ($usuarios) {
                        $pedidos = Pedido::whereIn('id_reserva', Reserva::where('id_pista', 10)->pluck('id'))->where('id', 'like', "%{$search}%")->orWhereIn('id_usuario', $usuarios->pluck('id'))->orderByDesc('created_at')->paginate(100);
                    } else {
                        $pedidos = Pedido::whereIn('id_reserva', Reserva::where('id_pista', 10)->pluck('id'))->where('id', 'like', "%{$search}%")->orderByDesc('created_at')->paginate(100);
                    }
                }else{
                    $pedidos = Pedido::whereIn('id_reserva', Reserva::where('id_pista', 10)->pluck('id'))->orderByDesc('created_at')->paginate(100);
                }

            } else if ($request->tipo_pedido == 'reservas') {

                if($search){
                    if ($usuarios) {
                        $pedidos = Pedido::whereIn('id_reserva', Reserva::whereIn('id_pista', $instalacion->pistas->pluck('id'))->pluck('id'))->where('id', 'like', "%{$search}%")->orWhereIn('id_usuario', $usuarios->pluck('id'))->orderByDesc('created_at')->paginate(100);
                    } else {
                        $pedidos = Pedido::whereIn('id_reserva', Reserva::whereIn('id_pista', $instalacion->pistas->pluck('id'))->pluck('id'))->where('id', 'like', "%{$search}%")->orderByDesc('created_at')->paginate(100);
                    }
                }else{
                    $pedidos = Pedido::whereIn('id_reserva', Reserva::whereIn('id_pista', $instalacion->pistas->pluck('id'))->pluck('id'))->orderByDesc('created_at')->paginate(100);
                }

            } else if ($request->tipo_pedido == 'eventos') {

                if($search){
                    if ($usuarios) {
                        $pedidos = Pedido::whereIn('id_evento', $instalacion->eventos->pluck('id'))->where('id', 'like', "%{$search}%")->orWhereIn('id_usuario', $usuarios->pluck('id'))->orderByDesc('created_at')->paginate(100);
                    } else {
                        $pedidos = Pedido::whereIn('id_evento', $instalacion->eventos->pluck('id'))->where('id', 'like', "%{$search}%")->orderByDesc('created_at')->paginate(100);
                    }
                }else{
                    $pedidos = Pedido::whereIn('id_evento', $instalacion->eventos->pluck('id'))->orderByDesc('created_at')->paginate(100);
                }

            } else {
                abort(404);
            }
        } else {
            abort(404);
        }


        return view('instalacion.pedidos.list', compact('pedidos'));
    }

    public function listado_pedidos(Request $request)
    {
        $instalacion = auth()->user()->instalacion;
        if (isset($request->tipo_pedido)) {
            if ($request->tipo_pedido == 'Piscina') {
                $pedidos = Pedido::whereIn('id_reserva', Reserva::where('id_pista', 10)->pluck('id'))->orderByDesc('created_at')->paginate(10);
            } else if ($request->tipo_pedido == 'reservas') {
                $pedidos = Pedido::whereIn('id_reserva', Reserva::whereIn('id_pista', $instalacion->pistas->pluck('id'))->pluck('id'))->orderByDesc('created_at')->paginate(10);
            } else if ($request->tipo_pedido == 'eventos') {
                $pedidos = Pedido::whereIn('id_evento', $instalacion->eventos->pluck('id'))->orderByDesc('created_at')->paginate(10);
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }

                        /* $pedidos = $request->tipo == 'Piscina' ? Pedido::whereIn('id_reserva', Reserva::where('id_pista', 10)->pluck('id'))->orderByDesc('created_at')->paginate() :
                                                Pedido::whereIn('id_reserva', Reserva::whereIn('id_pista', $instalacion->pistas->pluck('id'))->pluck('id'))->orWhereIn('id_evento', $instalacion->eventos->pluck('id'))->orderByDesc('created_at')->paginate(10); */
        // remove paginate
        return view('instalacion.pedidos.list', compact('pedidos'));
    }

    public function informes_pedidos(Request $request)
    {
        if (isset($request->fecha_inicio) && isset($request->fecha_fin)) {
            $pedidos = Pedido::where('created_at', '>=', date('Y-m-d H:i:s', strtotime($request->fecha_inicio)))->where('created_at', '<=', date('Y-m-d H:i:s', strtotime($request->fecha_fin . ' 23:59:59')))->where('estado', '!=', 'cancelado');
            /* return $pedidos->get(); */
            if ($request->tipo != '---') {
                if ($request->tipo == 'tarjeta') {
                    $pedidos->where([['estado', 'pagado'], ['amount', '>', 0]])->whereNotIn('id_reserva', Reserva::where('creado_por', 'admin')->pluck('id'));
                } elseif($request->tipo == 'efectivo') {
                    $pedidos->where([['estado', 'pagado'], ['amount', '>', 0]])->whereIn('id_reserva', Reserva::where('creado_por', 'admin')->pluck('id'));
                } elseif($request->tipo == 'bono') {
                    $pedidos->where([['estado', 'pagado'], ['amount', 0]]);
                } else {
                    $pedidos->where([['estado', 'En proceso']]);
                }
            }
            if ($request->tipo_espacio != '---') {
                $pedidos->whereIn('id_reserva', Reserva::whereIn('id_pista', Pista::where('tipo', $request->tipo_espacio)->pluck('id'))->pluck('id'));
            }
            $total = $pedidos->sum('amount');
            $pedidos = $pedidos->get();
            return view('instalacion.pedidos.lista_informe', compact('pedidos', 'total'));
        }
        return view('instalacion.pedidos.generar_informe');
    }

    public function print_order(Request $request)
    {
        $pedido = Pedido::find($request->id);

        return view('instalacion.pedidos.print', compact('pedido'));
    }

    public function send_order(Request $request)
    {

        $pedido = Pedido::where('id', $request->id)->first();
        $participantes = Participante::where('id_pedido', $pedido->id)->get();

        Mail::to($pedido->user->email)->send(new NewInscripcion($pedido->user, $participantes[0]));

        return back();
    }

    public function send_pedido(Request $request){
        // Mail::to($pedido->user->email)->send(new NewInscripcion($pedido->user, $participantes[0]));

        $participantes = Participante::where('id_pedido', $request->id)->get();
        $pedido = Pedido::find($request->id);

        foreach ($participantes as $participante) {
            Mail::to($pedido->user->email)->send(new NewInscripcion($pedido->user, $participante));
        }
        // Mail::to(Pedido::find($request->id)->user->email)->send(new NewInscripcion(Pedido::find($request->id)->user, Participante::where('id_pedido', $request->id)->first()));
        return back();
    }

    public function delete_evento(Request $request)
    {
        Evento::find($request->id)->delete();

        return redirect()->back();
    }

    public function remove_tipo_participante(Request $request)
    {
        Tipos_participante::find($request->id)->delete();

        return redirect()->back();
    }

    public function ver_pdf_entradas (Request $request){

        $participantes = Participante::where('id_pedido', request()->id)->get();
        $participante = Participante::where('id_pedido', request()->id)->get()->first();
        $evento = Evento::find($participante->id_evento);
        $usuario = User::find($participante->id_usuario);
        return view('instalacion.pedidos.descargar_entradas')->with(compact('participante', 'evento', 'usuario', 'participantes'));

    }

    public function devolver_pedido(Request $request)
    {
        $pedido = Pedido::find($request->id);
        $pedido->update(['estado' => 'Devolucion pendiente']);
        if ($request->cancel_reserva) {
            Reserva::where('id_pedido', $request->id)->update(['estado' => 'canceled']);
        }

        \DB::purge('mysql');

        $dynamic_db_name = 'manager_reservas';
        $config = \Config::get('database.connections.mysql');

        $config['database'] = $dynamic_db_name;
        $config['password'] = "#3p720hqK";
        config()->set('database.connections.mysql', $config);

        $pedido_manager = New Pedido();
        $pedido_manager->id = $pedido->id;
        $pedido_manager->id_usuario = $pedido->id_usuario;
        $pedido_manager->amount = $pedido->amount;
        $pedido_manager->id_reserva = $pedido->id_reserva;
        $pedido_manager->estado = $pedido->estado;
        $pedido_manager->save();

        \DB::purge('mysql');

        return redirect()->back();
    }

    public function reservas_periodicas(Request $request)
    {
        $reservas_periodicas = Reservas_periodicas::whereIn('id_pista', Pista::where('id_instalacion', auth()->user()->instalacion->id)->pluck('id'))->get();

        return view('instalacion.reservas.reservas_periodicas', compact('reservas_periodicas'));
    }

    public function add_reservas_periodicas_view(Request $request)
    {
        $reservas_periodicas = Reservas_periodicas::whereIn('id_pista', Pista::where('id_instalacion', auth()->user()->instalacion->id)->pluck('id'))->get();

        return view('instalacion.reservas.add_reserva_periodica', compact('reservas_periodicas'));
    }

    public function add_reservas_periodicas(Request $request)
    {
        $pista = Pista::find($request->espacio);

        $reserva_periodica = Reservas_periodicas::create([
            'id_pista' => $request->espacio,
            'id_user' => $request->user_id,
            'dias' => serialize($request->dias),
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);

        $period = new \DatePeriod(new DateTime($request->fecha_inicio), new \DateInterval('P1D'), new DateTime($request->fecha_fin));

        foreach ($period as $fecha) {
            if (in_array($fecha->format('w'), $request->dias)) {
                foreach ($pista->horario_tramos($fecha->format('Y-m-d')) as $horas) {
                    foreach ($horas as $hora) {
                        if (
                            strtotime(date('H:i', $hora)) >= strtotime($request->hora_inicio) &&
                            strtotime(date('H:i', $hora)) < strtotime($request->hora_fin)
                           ) {
                               /* if (strtotime(date('Y-m-d', $hora)) >= strtotime('2022-03-27 00:00') && strtotime(date('Y-m-d', $hora)) <= strtotime('2022-10-30 00:00')) {
                                    $hora = $hora + 3600;
                               } */
                            $reserva = Reserva::create([
                                'id_pista' => $pista->id,
                                'id_usuario' => $request->user_id,
                                'timestamp' => $hora,
                                'estado' => $request->estado_pedido,
                                'horarios' => serialize([$hora]),
                                'fecha' => date('Y/m/d', $hora),
                                'hora' => date('Hi', $hora),
                                'tarifa' => 1,
                                'minutos_totales' => $pista->get_minutos_given_timestamp($hora),
                                'reserva_periodica' => $reserva_periodica->id,
                                'creado_por' => 'admin'
                            ]);

                            $precio_intervalo = $pista->get_precio_total_given_timestamp($hora);

                            $pedido = new Pedido();
                            if ($request->estado_pedido == 'pendiente' && $request->tipo_pago == 'tarjeta') {
                                $pedido_id=$pista->instalacion->prefijo_pedido.generateOrderID();
                            } else {
                                $pedido_id=generateOrderID();
                            }
                            $pedido->id = $pedido_id;
                            $pedido->amount = $precio_intervalo;
                            $pedido->id_usuario = $request->user_id;
                            $pedido->id_reserva = $reserva->id;
                            $pedido->estado = $request->estado_pedido == 'active' ? 'pagado' : 'En proceso';
                            $pedido->tipo_pago = $request->tipo_pago == 'tarjeta' ? 'tarjeta' : 'efectivo';
                            $pedido->save();

                            Reserva::where('id', $reserva->id)->update(['id_pedido' => $pedido_id]);
                        }
                    }
                }
            }
        }

        return redirect('/'.request()->slug_instalacion.'/admin/reservas/periodicas');
    }

    public function comprobar_reservas_en_reserva_periodica(Request $request)
    {
        $pista = Pista::find($request->espacio);
        $period = new \DatePeriod(new DateTime($request->fecha_inicio), new \DateInterval('P1D'), new DateTime($request->fecha_fin));
        foreach ($period as $fecha) {
            if (in_array($fecha->format('w'), $request->dias)) {
                foreach ($pista->horario_tramos($fecha->format('Y-m-d')) as $horas) {
                    foreach ($horas as $hora) {
                        if (
                            strtotime(date('H:i', $hora)) >= strtotime($request->hora_inicio) &&
                            strtotime(date('H:i', $hora)) < strtotime($request->hora_fin)
                           ) {
                               /* if (strtotime(date('Y-m-d', $hora)) >= strtotime('2022-03-27 00:00') && strtotime(date('Y-m-d', $hora)) <= strtotime('2022-10-30 00:00')) {
                                    $hora = $hora + 3600;
                               } */
                            //comprobar si ya existe una reserva en ese tramo
                            $reserva = Reserva::where([['id_pista', $pista->id], ['timestamp', $hora]])->first();

                            if ($reserva) {
                                return response()->json($reserva);
                            }

                        }
                    }
                }
            }
        }

        return response()->json(false);
    }

    /* public function arreglos_reservas()
    {
        $reservas = Reserva::where([['timestamp', '>=', strtotime('2022-03-27 00:00')], ['timestamp', '<=', strtotime('2022-10-30 00:00')]], ['creado_por', 'admin'], ['reserva_periodica', '!=', null])->get();

        foreach ($reservas as $reserva) {
            $res = Reserva::find($reserva->id);

            $hora = $res->timestamp - 3600;

            $res->timestamp = $hora;
            $res->horarios = serialize([$hora]);
            $res->hora = date('Hi', $hora);
            $res->minutos_totales = 60;

            $res->save();
        }

        return Reserva::where([['timestamp', '>=', strtotime('2022-03-27 00:00')], ['timestamp', '<=', strtotime('2022-10-30 00:00')]], ['creado_por', 'admin'], ['reserva_periodica', '!=', null])->get();
    } */

    public function borrar_reservas_periodicas(Request $request)
    {

        // $reservas = Reserva::where([['reserva_periodica', $request->id], ['fecha', '>=', date('Y-m-d')]])->delete();
        $reservas = Reserva::where([['reserva_periodica', $request->id], ['fecha', '>=', date('Y-m-d')]])->get();
        if ($reservas->count() > 0) {
            foreach ($reservas as $reserva) {
                //obtener el pedido cuando el campo id_reserva sea igual al id de la reserva
                $pedido = Pedido::where('id_reserva', $reserva->id)->first();
                if ($pedido->estado == "En proceso") {
                    $pedido->estado = "cancelado";
                    $pedido->save();
                    $pedido->delete();
                    $reserva->delete();
                }
            }
        }

        //como saber el count de las reservas que quedan
        $reservas2 = Reserva::where([['reserva_periodica', $request->id], ['fecha', '>=', date('Y-m-d')]])->get();

        if ($reservas2->count() == 0) {
            Reservas_periodicas::find($request->id)->delete();
        }



        return redirect('/'.request()->slug_instalacion.'/admin/reservas/periodicas');
    }

    public function desactivaciones_periodicas(Request $request)
    {
        $desactivaciones = Desactivaciones_periodicas::whereIn('id_pista', Pista::where('id_instalacion', auth()->user()->instalacion->id)->pluck('id'))->get();

        return view('instalacion.reservas.desactivaciones', compact('desactivaciones'));
    }

    public function add_desactivaciones_periodicas_view(Request $request)
    {
        $desactivaciones = Desactivaciones_periodicas::whereIn('id_pista', Pista::where('id_instalacion', auth()->user()->instalacion->id)->pluck('id'))->get();

        return view('instalacion.reservas.add_desactivacion', compact('desactivaciones'));
    }

    public function add_desactivaciones_periodicas(Request $request)
    {
        Desactivaciones_periodicas::create([
            'id_pista' => $request->espacio,
            'dias' => serialize($request->dias),
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);

        return redirect('/'.request()->slug_instalacion.'/admin/reservas/desactivaciones');
    }

    public function borrar_desactivaciones_periodicas(Request $request)
    {
        Desactivaciones_periodicas::find($request->id)->delete();

        return back();
    }

    public function edit_info(Request $request) {
        // Obtener la instalación actual del usuario autenticado
        $instalacion = auth()->user()->instalacion;
    
        // Conectar a la base de datos 'superadmin' y buscar el registro que coincida con el slug de la instalación
        $registro = DB::connection('superadmin')
            ->table('superadmin')
            ->where('url', 'like', "https://gestioninstalacion.es/{$instalacion->slug}")
            ->first();
    
        // Verificar si se encontró el registro
        if (!$registro) {
            return redirect()->back()->with('error', 'No se encontró el registro en la base de datos superadmin.');
        }
    
        // Obtener el campo tipo_calendario
        $tipoCalendario = $registro->tipo_calendario;
    
        // Obtener los permisos relacionados con la instalación
        $permisos = DB::table('permisos')->where('id_instalacion', $instalacion->id)->get();
    
        // Convertir la instalación a un array para la vista
        $instalacion = $instalacion->toArray();
        // Pasar los datos a la vista
        return view('instalacion.editdata.edit', compact('instalacion', 'tipoCalendario', 'permisos'));
    }

    public function editar_info(Request $request) {
        $instalacion = auth()->user()->instalacion;
        $data = $request->all();
        array_shift($data);

        if (isset($data['servicios'])) {
            $data['servicios'] = serialize($data['servicios']);
        }
        if (isset($data['horario'])) {
            $data['horario'] = serialize($data['horario']);
        }
        if ($request->logo) {
            $image = $request->file('logo');
            $img = Image::make($image->getRealPath());
            $img->orientate();
            $path = public_path() . '/img';

            $name = $instalacion->slug . '.png';

            if (getimagesize($image)[0] > 1000) {
                $img->resize(900, 900, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path .'/'. $name, 85, 'png');
            }else{
                $img->save($path .'/'. $name, 85, 'png');
            }

        } else if ($request->cover) {
            $image = $request->file('cover');
            $img = Image::make($image->getRealPath());
            $img->orientate();
            $path = public_path() . '/img/portadas-inst';

            $name = $instalacion->slug . '.jpg';

            if (getimagesize($image)[0] > 1000) {
                $img->resize(900, 900, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path .'/'. $name, 85, 'jpg');
            }else{
                $img->save($path .'/'. $name, 85, 'jpg');
            }

        } else if ($request->galeria) {
            $image = $request->file('galeria');
            $img = Image::make($image->getRealPath());
            $img->orientate();
            $path = public_path() . '/img/galerias/'.$instalacion->slug;

            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $name = md5(rand()) . '.jpg';

            if (getimagesize($image)[0] > 1000) {
                $img->resize(900, 900, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path .'/'. $name, 85, 'jpg');
            }else{
                $img->save($path .'/'. $name, 85, 'jpg');
            }

        } elseif(($request->tipo_calendario == 1 || $request->tipo_calendario == 0) && $request->tipo_calendario != null) {
            $registro = DB::connection('superadmin')
            ->table('superadmin')
            ->where('url', 'like', "https://gestioninstalacion.es/{$instalacion->slug}")
            ->first();

            // Actualizar el campo tipo_calendario de $registro
            DB::connection('superadmin')
                ->table('superadmin')
                ->where('id', $registro->id)
                ->update(['tipo_calendario' => $request->tipo_calendario]);
        }else if($request->permisos){
           DB::table('permisos')->where('id_instalacion', $instalacion->id)->update($data['permisos']);
        } 
        else {

            Instalacion::find($instalacion->id)->update($data);
        }

        return redirect()->route('edit_config_inst', ['slug_instalacion'=> $instalacion->slug]);
    }

    public function eliminar_imagen_galeria(Request $request){
     $instalacion = auth()->user()->instalacion;

        if (file_exists(public_path() . '/img/galerias/'.$instalacion->slug . '/' . $request->nombre_archivo . '.jpg')) {
            unlink(public_path() . '/img/galerias/'. $instalacion->slug . '/' . $request->nombre_archivo . '.jpg');
        }
        return back();
    }

    public function pistas() {
        $instalacion = auth()->user()->instalacion;
        $pistas = Pista::where('id_instalacion', $instalacion->id)->get();
        return view('instalacion.pistas.list', compact('instalacion', 'pistas'));
    }

    public function add_pista_view() {
        $instalacion = auth()->user()->instalacion;
        $pistas= Pista::all();
        return view('instalacion.pistas.add', compact('instalacion','pistas'));
    }

    public function add_pista(Request $request) {
        $instalacion = auth()->user()->instalacion;

        $data = $request->all();
        $data['id_instalacion'] = $instalacion->id;

        $horario = $request->horario;
        foreach ($horario as $indexhor => $item) {
            foreach ($item['intervalo'] as $indexinterval => $intervalo) {
                $a = new \DateTime($intervalo['hfin']);
                if($a->format("H:i:s")=="00:00:00"){
                    $a->modify('+1 day');
                }
                $b = new \DateTime($intervalo['hinicio']);
                $interval = $a->diff($b);
                $diff_minutes = $interval->format("%h") * 60;
                $diff_minutes += $interval->format("%i");

                $intervalo['secuencia'] = $intervalo['secuencia'] == 'completo' ? $diff_minutes : $intervalo['secuencia'];

                $numero_veces = $diff_minutes/$intervalo['secuencia'];

                if (!is_int($diff_minutes/$intervalo['secuencia'])) {
                    $hora = new \DateTime($intervalo['hinicio']);
                    for ($i=0; $i < floor($numero_veces); $i++) {
                        $hora->modify("+{$intervalo['secuencia']} minutes");
                    }

                    $horario[$indexhor]['intervalo'][$indexinterval]['hfin'] = $hora->format('H:i');
                }

                //si es intervalo completo
                $horario[$indexhor]['intervalo'][$indexinterval]['secuencia'] = $intervalo['secuencia'] == 'completo' ? $diff_minutes : $intervalo['secuencia'];
            }
        }

        $data['horario'] = serialize($horario);
        if(isset($request->bloqueo)){
            $data ['bloqueo']= serialize($request->bloqueo);}
        else{
            $data ['bloqueo']=null;
        }

        $pista = Pista::create($data);



        return redirect("/" . auth()->user()->instalacion->slug . "/admin/pistas");
    }

    public function edit_pista_view(Request $request) {
        $instalacion = auth()->user()->instalacion;
        $pista = Pista::find($request->id);

        $pistas= Pista::all();



        return view('instalacion.pistas.edit', compact('instalacion', 'pista','pistas'));
    }

    public function edit_pista(Request $request) {

        $data = $request->all();


        array_shift($data);

        $horario = $request->horario;
        foreach ($horario as $indexhor => $item) {
            foreach ($item['intervalo'] as $indexinterval => $intervalo) {
                $a = new \DateTime($intervalo['hfin']);
                if($a->format("H:i:s")=="00:00:00"){
                    $a->modify('+1 day');
                }
                $b = new \DateTime($intervalo['hinicio']);
                $interval = $b->getTimestamp() < $a->getTimestamp() ? $a->diff($b) : new \DateTime(date('H:i', $a->getTimestamp() - $b->getTimestamp()));

                $diff_minutes = !is_a($interval, 'DateTime') ? $interval->format("%h") * 60 : ($interval->format('H')-1) * 60;
                $diff_minutes += !is_a($interval, 'DateTime') ? $interval->format("%i") : $interval->format('i');
                $intervalo['secuencia'] = $intervalo['secuencia'] == 'completo' ? $diff_minutes : $intervalo['secuencia'];

                $numero_veces = $diff_minutes/$intervalo['secuencia'];

                if (!is_int($diff_minutes/$intervalo['secuencia'])) {
                    $hora = new \DateTime($intervalo['hinicio']);
                    for ($i=0; $i < floor($numero_veces); $i++) {
                        $hora->modify("+{$intervalo['secuencia']} minutes");
                    }

                    $horario[$indexhor]['intervalo'][$indexinterval]['hfin'] = $hora->format('H:i');
                }

                //si es intervalo completo
                $horario[$indexhor]['intervalo'][$indexinterval]['secuencia'] = $intervalo['secuencia'] == 'completo' ? $diff_minutes : $intervalo['secuencia'];
            }
        }

        $data['horario'] = serialize($horario);
        if(isset($request->bloqueo)){
        $data ['bloqueo']= serialize($request->bloqueo);}
        else{
            $data ['bloqueo']=null;
        }
        /* return $data; */
        Pista::where('id', $request->id)->update($data);



        return redirect("/" . auth()->user()->instalacion->slug . '/admin/pistas');
    }

    public function desactivar_pista(Request $request)
    {
        $pista = Pista::find($request->id);
        $pista->update(['active' => !$pista->active]);

        return redirect()->back();
    }

    public function configuracion_pistas_reservas(Request $request) {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.configuraciones.pistas_reservas', compact('instalacion'));
    }

    public function configuracion_instalacion(Request $request) {
        // Obtener la instalación actual del usuario autenticado
        $instalacion = auth()->user()->instalacion;
    
        // Conectar a la base de datos 'superadmin' y buscar el registro que coincida con el slug de la instalación
        $registro = DB::connection('superadmin')
            ->table('superadmin')
            ->Where('url', 'like', "https://gestioninstalacion.es/{$instalacion->slug}")
            ->first();
    
        // Verificar si se encontró el registro
        if (!$registro) {
            return redirect()->back()->with('error', 'No se encontró el registro en la base de datos superadmin.');
        }
    
        // Obtener el campo tipo_calendario
        $tipoCalendario = $registro->tipo_calendario;
    
        // Pasar el tipo_calendario a la vista junto con la instalación
        return view('instalacion.configuraciones.instalacion', compact('instalacion', 'tipoCalendario'));
    }

    public function edit_configuracion(Request $request) {
        $instalacion = auth()->user()->instalacion;
        $data = $request->all();
        array_shift($data);

        if (!isset($request->allow_cancel)) {
            $data['allow_cancel'] = 0;
        }
        if (!isset($request->block_today)) {
            $data['block_today'] = 0;
        }
        if (!isset($request->observaciones)) {
            $data['observaciones'] = 0;
        }
        if (isset($request->max_reservas_tipo_espacio)) {
            $data['max_reservas_tipo_espacio'] = serialize($request->max_reservas_tipo_espacio);
        }

        Configuracion::find($instalacion->configuracion->id)->update($data);
        return redirect()->back();
    }

    public function campos_adicionales(Request $request) {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.configuraciones.campos_adicionales', compact('instalacion'));
    }

    public function view_campos_personalizados(Request $request) {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.configuraciones.campos_personalizados', compact('instalacion'));
    }

    public function add_campos_personalizados(Request $request) {
        if (!isset($request->required_field)) {
            $request->required_field = 0;
        }
        if (!isset($request->opcion)) {
            $opciones = null;
        }else{
            $opciones = serialize($request->opcion);
        }

        $campo = Campos_personalizados::create([
            'id_instalacion' => auth()->user()->instalacion->id,
            'tipo' => $request->tipo,
            'label' => $request->label,
            'opciones' => $opciones,
            'required' => $request->required_field
        ]);

        if (is_array($request->pistas)) {
            foreach ($request->pistas as $id_pista) {
                DB::table('pistas_campos')->insert([
                    'id_pista' => $id_pista,
                    'id_campo' => $campo->id
                ]);
            }
        }

        return redirect('/' . auth()->user()->instalacion->slug . '/admin/campos-adicionales');
    }

    public function view_edit_campos_personalizados(Request $request) {
        $instalacion = auth()->user()->instalacion;
        $campo = Campos_personalizados::find($request->id);
        return view('instalacion.configuraciones.edit_campos_personalizados', compact('instalacion','campo'));
    }

    public function edit_campos_personalizados(Request $request) {
        if (!isset($request->required_field)) {
            $request->required_field = 0;
        }
        if (!isset($request->opcion)) {
            $opciones = null;
        }else{
            $opciones = serialize($request->opcion);
        }

        $campo = Campos_personalizados::find($request->id);

        $campo->update([
            'id_instalacion' => auth()->user()->instalacion->id,
            'tipo' => $request->tipo,
            'label' => $request->label,
            'opciones' => $opciones,
            'required' => $request->required_field
        ]);

        DB::table('pistas_campos')->where('id_campo', $campo->id)->delete();

        if (is_array($request->pistas)) {
            foreach ($request->pistas as $id_pista) {
                DB::table('pistas_campos')->insert([
                    'id_pista' => $id_pista,
                    'id_campo' => $campo->id
                ]);
            }
        }else{
            $campo->update(['all_pistas' => 0]);
        }

        return redirect('/' . auth()->user()->instalacion->slug . '/admin/campos-adicionales');
    }

    public function delete_campos_personalizados(Request $request) {
        Campos_personalizados::find($request->id)->delete();
        Pistas_campos_relation::where('id_campo', $request->id)->delete();

        return redirect('/' . auth()->user()->instalacion->slug . '/admin/campos-adicionales');
    }

    public function users() {
        $instalacion = auth()->user()->instalacion;
        $users = User::where('id_instalacion', $instalacion->id)->orderBy('rol')->paginate(10);
        return view('instalacion.users.list', compact('instalacion', 'users'));
    }

    public function users_list_datatable(Request $request)
    {
        $data = isset($request->page) ? User::select('id', 'email', 'name', 'apellidos', 'tlfno', 'rol')
                                                ->orderBy('rol')
                                                ->paginate(10, ['*'], 'page', $request->page) :
                                        User::select('id', 'email', 'name', 'apellidos', 'tlfno', 'rol')->where('name', 'like', '%'.$request->text.'%')->orWhere('email',  'like', '%'.$request->text.'%')->orderBy('rol')->paginate(35);

        return $data;
    }

    public function users_no_valid() {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.users.list_no_valid', compact('instalacion'));
    }

    public function validar_user(Request $request)
    {
        $user = User::find($request->id);
        $user->update(['aprobado' => date('Y-m-d H:i:s')]);

        return redirect()->back();
    }

    public function borrar_permanente_user(Request $request)
    {
        DB::table('users')->where('id', $request->id)->delete();

        return redirect()->back();
    }

    public function add_user_view(Request $request)
    {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.users.add', compact('instalacion'));
    }

    public function add_user(Request $request)
    {
        $data = $request->all();

        if (User::where('email', $request->email)->first()) {
            return redirect()->back()->with('error', 'Ya existe un usuario con ese email. Prueba otro email.');
        }

        if(request()->slug_instalacion == 'la-guijarrosa' || request()->slug_instalacion == 'santaella'){
            $data['codigo_tarjeta'] = $request->codigotarjeta;
        }
        $data['password'] = \Hash::make($request->password);
        $data['aprobado'] = date('Y-m-d H:i:s');
        $data['rol'] = 'user';

        User::where('id', $request->id)->create($data);

        return redirect("/". auth()->user()->instalacion->slug . "/admin/users");
    }

    public function desactivar_user(Request $request)
    {
        $user = User::withTrashed()->find($request->id);

        if (!$user->deleted_at) {
            $user->delete();
        } else{
            $user->restore();
        }
        return redirect()->back();
    }

    public function edit_user_view(Request $request)
    {
        $instalacion = auth()->user()->instalacion;
        $user = User::find($request->id);

        return view('instalacion.users.edit', compact('instalacion', 'user'));
    }

    public function editar_user(Request $request)
    {
        $data = $request->all();
        $user = User::find($request->id);

        array_shift($data);

        if (isset($request->max_reservas_tipo_espacio)) {
            $max_reservas_tipo_espacio = $request->max_reservas_tipo_espacio;
        
            foreach ($max_reservas_tipo_espacio as $tipo => $value) {
                $configuracionMaxReservas = unserialize($user->instalacion?->configuracion?->max_reservas_tipo_espacio ?? '');
        
                if (isset($configuracionMaxReservas[$tipo]) && $configuracionMaxReservas[$tipo] == $value) {
                    unset($max_reservas_tipo_espacio[$tipo]);
                }
            }
        
            $data['max_reservas_tipo_espacio'] = $max_reservas_tipo_espacio ? serialize($max_reservas_tipo_espacio) : null;
        }

        if(request()->slug_instalacion == 'la-guijarrosa' || request()->slug_instalacion == 'santaella'){
            $data['codigo_tarjeta'] = $request->codigotarjeta;
        }

        if (!isset($request->password)) {
            unset($data['password']);
            $user->update($data);
        }else {
            $data['password'] = \Hash::make($request->password);
            $user->update($data);
        }
        return redirect("/". auth()->user()->instalacion->slug . "/admin/users");
    }

    public function cambiar_foto_user(Request $request)
    {
        $user = User::find($request->id);

        return view('instalacion.users.change_photo', compact('user'));
    }

    public function ver_user(Request $request)
    {
        $user = User::withTrashed()->find($request->id);

        return view('instalacion.users.ver', compact('user'));
    }

    public function update_max_reservas_user(Request $request)
    {
        $user = User::find($request->id);

        $max_reservas_tipo_espacio = $request->max_reservas_tipo_espacio;

        foreach ($max_reservas_tipo_espacio as $tipo => $value) {
            if (
                isset(unserialize($user->instalacion->configuracion->max_reservas_tipo_espacio)[$tipo]) &&
                unserialize($user->instalacion->configuracion->max_reservas_tipo_espacio)[$tipo] == $value
                ) {
                unset($max_reservas_tipo_espacio[$tipo]);
            }
        }

        $max_reservas_tipo_espacio = $max_reservas_tipo_espacio ? serialize($max_reservas_tipo_espacio) : null;

        $user->update(['max_reservas_tipo_espacio' => $max_reservas_tipo_espacio]);

        return back();
    }

    public function user_add_cobro_view(Request $request)
    {
        $user = User::find($request->id);

        return view('instalacion.users.add_cobro', compact('user'));
    }

    public function user_add_cobro(Request $request)
    {
        $user = User::find($request->id);
        $data = $request->all();
        $data['id_user'] = $request->id;

        Cobro::create($data);

        return redirect("/{$request->slug_instalacion}/admin/users/{$request->id}/ver");
    }

    public function list_cobros(Request $request)
    {
        $instalacion = auth()->user()->instalacion;

        return view('instalacion.cobros.list', compact('instalacion'));
    }

    public function add_cobro_view(Request $request)
    {
        $instalacion = auth()->user()->instalacion;

        return view('instalacion.cobros.add');
    }

    public function add_cobro(Request $request)
    {
        $data = $request->all();

        Cobro::create($data);

        return redirect("/{$request->slug_instalacion}/admin/cobro");
    }

    public function edit_cobro_view(Request $request)
    {
        $cobro = Cobro::find($request->id);

        return view('instalacion.cobros.edit', compact('cobro'));
    }

    public function edit_cobro(Request $request)
    {
        $cobro = Cobro::find($request->id);

        $data = $request->all();
        array_shift($data);

        $cobro->update($data);

        return redirect("/{$request->slug_instalacion}/admin/users/{$cobro->user->id}/ver");
    }

    public function delete_cobro(Request $request)
    {
        Cobro::find($request->id)->delete();

        return redirect()->back();
    }

    public function list_msg(Request $request){
        $mensajes = Mensajes_difusion::all();
        return view('instalacion.mensajes.list', compact('mensajes'));
    }

    public function create_msg_view(Request $request){
        return view('instalacion.mensajes.create');
    }

    public function create_msg(Request $request){
        /* $mensaje = Mensajes_difusion::create($request->except('_token'));

        return $mensaje; */

        $mensaje = New Mensajes_difusion();
        $mensaje->id_instalacion = $request->id_instalacion;
        $mensaje->fecha_inicio = $request->fecha_inicio;
        $mensaje->fecha_fin = $request->fecha_fin;
        $mensaje->titulo = $request->titulo;
        $mensaje->tipo_mensaje = $request->tipo_mensaje == 'publico' ? 'publico' : 'privado';
        $mensaje->contenido = $request->contenido;
        $mensaje->save();

        return redirect("/{$request->slug_instalacion}/admin/mensajes");
    }

    public function edit_msg_view(Request $request){
        $mensaje = Mensajes_difusion::find($request->id);

        return view('instalacion.mensajes.create', compact('mensaje'));
    }

    public function edit_msg(Request $request){
        $mensaje = Mensajes_difusion::find($request->id);

        $mensaje->update($request->except('_token'));

        return redirect("/{$request->slug_instalacion}/admin/mensajes");
    }

    public function delete_msg(Request $request){
        $mensaje = Mensajes_difusion::find($request->id);

        $mensaje->delete();

        return redirect("/{$request->slug_instalacion}/admin/mensajes");
    }

    public function list_eventos(Request $request)
    {
        if($request->slug_instalacion != 'feria-jamon-villanuevadecordoba'){
        $pedidos_caducados = Pedido::withTrashed()->where([['estado', 'En proceso'],  ['created_at', '<', \Carbon\Carbon::now()->subMinutes(10)->toDateTimeString()]]);

       Participante_eventos_mes::whereIn('id_pedido', $pedidos_caducados->pluck('id'))->delete();
        }else{
            $pedidos_caducados = Pedido::withTrashed()->where([['estado', 'En proceso']]);

            Participante::whereIn('id_pedido', $pedidos_caducados->pluck('id'))->delete();
        }


        $eventos = Evento::where('id_instalacion', auth()->user()->id_instalacion)->get();
        return view('instalacion.eventos.list', compact('eventos'));
    }

    public function create_evento_view(Request $request)
    {
        $deportes = Deporte::all();
        $evento = Evento::find($request->id);
        return view('instalacion.eventos.add', compact('deportes', 'evento'));
    }

    public function create_evento(Request $request)
    {
        if (isset($request->id)) {
            $evento = Evento::find($request->id);
            $evento->update($request->except('_token', 'cartel'));
        } else {
            $evento = Evento::create($request->except('_token', 'cartel'));
        }

        if ($request->cartel) {
            $image = $request->file('cartel');
            $img = Image::make($image->getRealPath());
            $img->orientate();
            $path = public_path() . '/img/eventos/'.$request->slug_instalacion;

            $name = $evento->id . '.jpg';

            if (getimagesize($image)[0] > 1000) {
                $img->resize(900, 900, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path .'/'. $name, 85, 'jpg');
            }else{
                $img->save($path .'/'. $name, 85, 'jpg');
            }
        }
        $evento->entradas_agotadas = $request->entradas_agotadas;
        $evento->save();

        return redirect("/{$request->slug_instalacion}/admin/eventos");
    }

    public function tipos_participante(Request $request)
    {
        $instalacion = auth()->user()->instalacion;
        $tipos_participantes = Tipos_participante::where('id_instalacion', $instalacion->id)->get();

        return view('instalacion.eventos.tipos_participantes', compact('tipos_participantes'));
    }

    public function add_tipos_participante_view(Request $request)
    {
        $instalacion = auth()->user()->instalacion;
        $tipos_participantes = Tipos_participante::where('id_instalacion', $instalacion->id)->get();
        $campos = Campos_personalizados::where('id_instalacion', $instalacion->id)->get();

        return view('instalacion.eventos.add_tipo_participante', compact('tipos_participantes', 'campos'));
    }

    public function add_tipos_participante(Request $request)
    {
        $instalacion = auth()->user()->instalacion;

        $tipo_participante = Tipos_participante::create($request->except('_token', 'campos'));

        foreach ($request->campos as $value) {
            DB::table('tipos_participante_campos')->insert(['id_tipo_participante' => $tipo_participante->id, 'id_campo' => $value]);
        }

        return redirect("/{$request->slug_instalacion}/admin/eventos/tipos-clientes");
    }

    public function ver_evento(Request $request)
    {

        $instalacion = auth()->user()->instalacion;
        $evento = Evento::find($request->id);
        if($evento->renovacion_mes==0){
            $cabeceras_campos= $evento->tipo_participante->campos_personalizados;

            if ($instalacion->slug == "feria-jamon-villanuevadecordoba") {
                // Filtrar participantes con estado "pagado"
                $participantes_eventos = $evento->participantes->filter(function ($participante) {
                    return $participante->pedido->estado == 'pagado';
                });

                // Crear una colección única por pedido_id para evitar duplicados
                $participantes_evento = $participantes_eventos->unique(function ($participante) {
                    return $participante->pedido->id;
                });
                $pedidos = Pedido::where('id_evento',$evento->id)->where('estado', 'pagado')->get();

                return view('instalacion.eventos.ver_evento', compact('instalacion', 'evento', 'participantes_evento','cabeceras_campos','pedidos', 'participantes_eventos'));

            } else {
                // En caso contrario, devolver todos los participantes con estado "pagado"
                $participantes_evento = $evento->participantes->filter(function ($participante) {
                    return $participante->pedido->estado == 'pagado';
                });
                $pedidos = Pedido::where('id_evento',$evento->id)->where('estado', 'pagado')->get();

                return view('instalacion.eventos.ver_evento', compact('instalacion', 'evento', 'participantes_evento','cabeceras_campos','pedidos'));
            }


        }
        else{
            $participantes_evento = Participante_eventos_mes::whereIn('id_participante', $evento->participantes->pluck('id'))->get();
            $years_query = Participante_eventos_mes::whereIn('id_participante', $evento->participantes->pluck('id'))->groupBy('num_year')->pluck('num_year');
            $meses_query = Participante_eventos_mes::whereIn('id_participante', $evento->participantes->pluck('id'))->groupBy('num_mes')->pluck('num_mes');
            $meses = Participante_eventos_mes::select(DB::raw("CONCAT(num_year, '-', num_mes) AS string"), 'num_year', 'num_mes')->whereIn('id_participante', $evento->participantes->pluck('id'))->whereIn('num_year', $years_query)->whereIn('num_mes', $meses_query)->orderByDesc('num_year')->orderByDesc('num_mes')->groupBy('string')->get();
            return view('instalacion.eventos.ver', compact('instalacion', 'evento', 'meses', 'participantes_evento'));
        }




    }

    public function edit_participante_view(Request $request)
    {
        $instalacion = auth()->user()->instalacion;
        $participante = Participante::find($request->id_participante);

        return view('instalacion.eventos.edit_participante', compact('instalacion', 'participante'));
    }

    public function edit_participante(Request $request)
    {
        $instalacion = auth()->user()->instalacion;
        $participante = Participante::find($request->id_participante);

        foreach ($request->campo_adicional as $id_valor => $valor) {
            Valor_campo_personalizado::find($id_valor)->update([
                'valor' => $valor
            ]);
        }

        return redirect("/{$request->slug_instalacion}/admin/eventos/{$request->id}");
    }

    public function delete_participante(Request $request)
    {
        $participante = Participante::find($request->id_participante);

        $participante->delete();

        return redirect("/{$request->slug_instalacion}/admin/eventos/{$request->id}");
    }

    public function list_dias_festivos(Request $request){
        $dias = Dias_festivos::all();

        return view('instalacion.configuraciones.list_dias_festivos',compact('dias'));
    }

    public function configuracion_dias_festivos(Request $request){
        return view('instalacion.configuraciones.add_dias_festivos');
    }

    public function almacenar_festivo(Request $request){

        $dia = New Dias_festivos();

        $dia->dia_festivo = $request->dia_festivo;
        $dia->save();
        return redirect("/{$request->slug_instalacion}/admin/configuracion/dias-festivos");
    }

    public function edit_view_dia_festivo(Request $request){
        $dia = Dias_festivos::find($request->id);
        return view('instalacion.configuraciones.edit_dia_festivo',compact('dia'));
    }

    public function edit_dia_festivo(Request $request){
        $dia = Dias_festivos::find($request->id);

        $dia->dia_festivo = $request->dia_festivo;
        $dia->save();
        return redirect("/{$request->slug_instalacion}/admin/configuracion/dias-festivos");
    }

    public function delete_dia_festivo(Request $request){
        Dias_festivos::find($request->id)->delete();

        return redirect("/{$request->slug_instalacion}/admin/configuracion/dias-festivos");
    }


    public function list_participantes(){

        $prefijo_pedido = auth()->user()->instalacion->prefijo_pedido;
        $participantes = Participante::whereNotNull('id_evento')->where('id_pedido', 'like', $prefijo_pedido.'%')->orderBy('created_at', 'desc')->paginate(10);
        $participantespago = Participante::whereNotNull('id_evento')->where('id_pedido', 'like', $prefijo_pedido.'%')->get();
        $novalidos = [];
        foreach ($participantespago as $participantepago) {
            if($participantepago->pedido->estado != 'pagado'){
                $novalidos[] = $participantepago->id;
            }
        }

        $participantespago = $participantespago->except($novalidos);



        $pedido = Pedido::where('estado','pagado')->where('id', 'like', $prefijo_pedido.'%')->get();
        $pedidos_validos = collect();

        foreach ($participantes as $participante) {
            $pedidos_validos->push($participante->pedido);
        }

        return view('instalacion.eventos.list_participantes',compact('participantes','participantespago','pedido'));
    }

    public function list_participantes_servicios(){
        if(request()->slug_instalacion == "la-guijarrosa"){
            // nombre	email	servicio	ultimo recibo pagado activo
            $data = [];
            $users = User::all();
            foreach ($users as $user) {
                $servicios = Servicio_Usuario::where('id_usuario', $user->id)->get();
                foreach ($servicios as $servicio) {

                    $ultimo_recibo = $servicio->recibos->filter(function($recibo) {
                        return $recibo->estado == 'pagado' || $recibo->pedido_id != null;
                    })->last();

                    $fecha_ultimo_recibo = $ultimo_recibo ? \Carbon\Carbon::parse($ultimo_recibo->created_at)->format('d-m-Y') : 'No hay recibos';
                    $data[] = [
                        'nombre' => $user->name,
                        'email' => $user->email,
                        'servicio' => $servicio->servicio->nombre,
                        'ultimo_recibo_pagado' => $fecha_ultimo_recibo,
                        'activo' => $servicio->activo
                    ];
                }
            }
            return view('instalacion.servicios.list_participantes_guijarrosa',compact('data'));
        }else{

            $participantes = Participante::whereNotNull('id_servicio')->orderBy('created_at', 'desc')->get();
            $participantespago = Participante::whereNotNull('id_servicio')->orderBy('created_at', 'desc')->get();
            $participantesexportar = Collect();
            foreach ($participantespago as $participante) {
                if($participante->pedido->estado == 'pagado'){
                    $participantesexportar->push($participante);
                }
            }

            return view('instalacion.servicios.list_participantes_servicios',compact('participantes','participantespago', 'participantesexportar'));
        }

    }

    public function view_informes_participantes(Request $request){
        if (isset($request->fecha_inicio) && isset($request->fecha_fin)) {
            $total = 0;

            $participantes = Participante::where('created_at', '>=', date('Y-m-d H:i:s', strtotime($request->fecha_inicio)))->where('created_at', '<=', date('Y-m-d H:i:s', strtotime($request->fecha_fin . ' 23:59:59')));
            $evento = Evento::where('nombre', $request->tipo_evento)->first();
            $pedidos = Pedido::where('created_at', '>=', date('Y-m-d H:i:s', strtotime($request->fecha_inicio)))->where('created_at', '<=', date('Y-m-d H:i:s', strtotime($request->fecha_fin . ' 23:59:59')))->where('estado','pagado');
            /* $participantes_mes = Participante_eventos_mes::where('num_mes',$request->meses)->get(); */

            if ($request->tipo_evento != '---') {
                $participantes->where('id_evento', $evento->id);
                $pedidos = Pedido::where('created_at', '>=', date('Y-m-d H:i:s', strtotime($request->fecha_inicio)))->where('created_at', '<=', date('Y-m-d H:i:s', strtotime($request->fecha_fin . ' 23:59:59')))->where('id_evento',$evento->id)->where('estado','pagado');
            }
            /* return $participantes_mes; */
           /*  if($request->meses != '---'){
                foreach($participantes_mes as $pm){
                 $participantes =  $participantes->where('id',$pm->id_participante);
                }
            } */

            $participantes = $participantes->get();
            $pedidos = $pedidos->get();

            $total = $pedidos->sum('amount');
            $cabeceras_campos= $evento->tipo_participante->campos_personalizados;

            return view('instalacion.eventos.lista_informe', compact('participantes','total','cabeceras_campos'));
        }

        $participantes = Participante::all();
        $years_query = Participante_eventos_mes::whereIn('id_participante', $participantes->pluck('id'))->groupBy('num_year')->pluck('num_year');
        $meses_query = Participante_eventos_mes::whereIn('id_participante', $participantes->pluck('id'))->groupBy('num_mes')->pluck('num_mes');
        $meses = Participante_eventos_mes::select(DB::raw("CONCAT(num_year, '-', num_mes) AS string"), 'num_year', 'num_mes')->whereIn('id_participante', $participantes->pluck('id'))->whereIn('num_year', $years_query)->whereIn('num_mes', $meses_query)->orderByDesc('num_year')->orderByDesc('num_mes')->groupBy('string')->get();

        return view('instalacion.eventos.generar_informe',compact('meses'));
    }

    public function listar_servicios_clientes(Request $request){
        /* $servicio = Servicio_Usuario::all(); */

       /*  $fechaActual = Carbon::now();
        $primerDiaMesSiguiente = $fechaActual->copy()->addMonth()->startOfMonth();
        $ultimoDiaMesSiguiente = $fechaActual->copy()->addMonth()->endOfMonth();

        $servicio = Servicio_usuario::where('activo', 'si')
        ->whereBetween('fecha_expiracion', [
            $primerDiaMesSiguiente,
            $ultimoDiaMesSiguiente
        ])
        ->get(); */
        $fechaActual = Carbon::now();

        // Obtener el primer y último día del mes anterior
        $primerDiaMesAnterior = $fechaActual->copy()->subMonth()->startOfMonth();
        $ultimoDiaMesAnterior = $fechaActual->copy()->subMonth()->endOfMonth();

        // Obtener el primer y último día del mes actual
        $primerDiaMesActual = $fechaActual->copy()->startOfMonth();
        $ultimoDiaMesActual = $fechaActual->copy()->endOfMonth();

        // Realizar la consulta para obtener los servicios entre las fechas deseadas
        $servicio = Servicio_usuario::where('activo', 'si')
            ->where(function($query) use ($primerDiaMesAnterior, $ultimoDiaMesActual) {
                $query->whereBetween('fecha_expiracion', [$primerDiaMesAnterior, $ultimoDiaMesActual]);
            })
            ->get();


        return view('instalacion.servicios.listado',compact('servicio'));
    }

    public function user_add_recibo_view(Request $request)
    {
        $user = User::find($request->id);
        $servicios = Servicio::all();
        $meses = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        $anios = range(date('Y')+1, date('Y') - 1);
        return view('instalacion.users.add_recibo', compact('user', 'servicios', 'meses','anios'));
    }

    public function user_add_recibo(Request $request)
    {
        $user = User::find($request->id);
        $servicio = Servicio::find($request->servicio);
        $servicio_usuario = Servicio_usuario::where('id_usuario', $user->id)
                                        ->where('id_servicio', $servicio->id)
                                        ->first();

        $acceso = Acceso::where('user_id',$user->id)->first();


        if ($servicio_usuario) {
            $recibo = new Recibo();
            $recibo->amount = $servicio->precio;
            $recibo->id_servicio = $request->servicio;
            $recibo->id_usuario = $user->id;
            $recibo->pedido_id = null;
            $recibo->id_servicio_usuario = $servicio_usuario->id;
            $recibo->tipo = "servicio";
            $recibo->estado = $request->estado;
            $recibo->created_at = \Carbon\Carbon::createFromDate($request->anio,$request->mes,02);
            /* $recibo->creador_recibo = Auth::user()->name; */
            $recibo->save();

            $mes = $request->input('mes');
            $anio = $request->input('anio');
            $dia = now()->day;
            $fecha_expiracion = \Carbon\Carbon::createFromDate($anio, $mes, $dia)->addMonth();
            $servicio_usuario->fecha_expiracion = $fecha_expiracion;
            $servicio_usuario->save();

            $acceso->activo = 'on';
            $acceso->inicio = \Carbon\Carbon::createFromDate($anio, $mes, $dia);
            $acceso->fin = \Carbon\Carbon::createFromDate($anio, $mes, $dia)->addMonth();
            if($request->slug_instalacion == "santaella"){
                $acceso->apertura = "08:30:00";
                $acceso->cierre = "22:00:00";
            }else{
                $acceso->apertura = "09:00:00";
                $acceso->cierre = "21:00:00";
            }
            $acceso->save();


            return redirect($request->slug_instalacion . '/admin/users/'.$user->id.'/ver');
        }else{
            return redirect()->back()->with('error', 'El usuario no ha contratado ese servicio.');
        }


    }

   /*  public function generar_recibo_servicios(){

        $dia = 8;
        $fecha = \Carbon\Carbon::now()->startOfMonth()->addDays($dia - 1);

        $servicio_usuarios = Servicio_Usuario::where('activo', 'si')->get();



        foreach($servicio_usuarios as $item){
            $recibo = new Recibo();

            $recibo->amount = $item->servicio->precio;
            $recibo->id_servicio = $item->id_servicio;
            $recibo->id_usuario =  $item->id_usuario;
            $recibo->pedido_id = null;
            $recibo->id_servicio_usuario = $item->id;
            $recibo->tipo = "servicio";
            $recibo->save();
        }

        return true;

    } */

    public function generar_recibo_servicios_anual() {

        $servicio_usuarios = Servicio_Usuario::where('activo', 'si')->get();
        foreach($servicio_usuarios as $item) {
            // Verificar si ya existe un recibo para este usuario y servicio en el mes actual
            $reciboExistente = Recibo::where('id_servicio_usuario', $item->id)
                ->where('id_usuario', $item->id_usuario)
                ->whereYear('created_at', '=', \Carbon\Carbon::now()->year)
                ->first();
            if ($reciboExistente) {
                continue; // Saltar al siguiente servicio_usuario si ya existe un recibo para este mes
            }

           /*  $acceso = Acceso::where('user_id',$item->id_usuario)->first();


            if($acceso){
                $acceso->activo = 'off';
                $acceso->save();
            }else{
                continue;
            } */


            if(\Carbon\Carbon::now()->day == \Carbon\Carbon::parse($item->created_at)->day and \Carbon\Carbon::now()->month == \Carbon\Carbon::parse($item->created_at)->month){

                $recibo = new Recibo();
                $recibo->amount = $item->servicio->precio;
                $recibo->id_servicio = $item->id_servicio;
                $recibo->id_usuario = $item->id_usuario;
                $recibo->pedido_id = null;
                $recibo->id_servicio_usuario = $item->id;
                $recibo->tipo = "servicio";
                $recibo->estado = "pendiente";
                $recibo->save();
            }

        }

        return true;
    }

    public function generar_pedido($recibo,$instalacion)
    {
        $pedido = new Pedido();
        $pedido_id=$instalacion->prefijo_pedido.generateOrderID();
        $pedido->id = $pedido_id;
        $pedido->id_usuario = $recibo->id_usuario;
        switch ($recibo->tipo){
            case('servicio'):
                $pedido->id_servicio = $recibo->id_servicio;
                $precio_total = $recibo->amount;
                break;
            case('escuela'):
                $pedido->id_evento = $recibo->id_evento;
                $precio_total = $recibo->amount;
                break;
        }

        $pedido->estado = "En proceso";
        /*  $pedido->expiration = $expiration; */




        $pedido->amount = $precio_total;
        $pedido->save();
        return $pedido_id;
    }

    public function generar_recibo_trim_sem()
{
    // Obtener todos los servicios activos que sean trimestrales o semestrales
    $servicio_usuarios = Servicio_Usuario::where('activo', 'si')
        ->whereHas('servicio', function ($query) {
            // Filtrar los servicios que tengan duración trimestral o semestral
            $query->whereIn('duracion', ['trimestral', 'semestral']);
        })
        ->get();

    // Recorrer cada servicio de usuario
    foreach ($servicio_usuarios as $item) {
        // Convertir la fecha de expiración a un objeto Carbon para poder hacer la comparación
        $fecha_expiracion = \Carbon\Carbon::parse($item->fecha_expiracion);
        // Verificar si la fecha de expiración ha pasado
        if ($fecha_expiracion->isPast()) {
            // Si la fecha ha pasado, desactivar el servicio cambiando el campo 'activo' a 'no'
            $item->activo = 'no';
            $item->save();
        }
    }

    // Retornar true o cualquier valor que necesites
    return true;
}

    public function generar_recibo_servicios() {
        /* $servicio_usuarios = Servicio_Usuario::where('activo', 'si')->get(); */
        $servicio_usuarios = Servicio_Usuario::where('activo', 'si')
        ->whereHas('servicio', function ($query) {
            $query->whereNotIn('duracion', ['trimestral', 'semestral']);
        })
        ->get();
        // dd($servicio_usuarios->where('id_usuario', 3033)->first());
        // test
        // $servicio_usuarios = Servicio_Usuario::where('id_usuario', 2916)->orWhere('id_usuario', 2903)->get();
        foreach($servicio_usuarios as $item) {
            $user = User::find($item->id_usuario);

            $fecha_expiracion = \Carbon\Carbon::parse($item->fecha_expiracion);

            $reciboExistente = Recibo::where('id_servicio_usuario', $item->id)
                ->where('id_usuario', $item->id_usuario)
                ->whereMonth('created_at', '=', \Carbon\Carbon::now()->month)
                ->whereYear('created_at', '=', \Carbon\Carbon::now()->year)
                ->first();

            if($fecha_expiracion->gt(\Carbon\Carbon::now())){
                $reciboExistente = true;
            }
            if ($reciboExistente) {
                LogRecibosDiario::create([
                    'id_servicio_usuario' => $item->id,
                    'id_usuario' => $item->id_usuario,
                    'mensaje' => 'Recibo ya existente',
                    'fecha_expiracion' => $fecha_expiracion,
                    'pago_recurrente' => $user->pago_recurrente,
                    'created_at' => \Carbon\Carbon::now(),
                ]);
                continue; // Saltar al siguiente servicio_usuario si ya existe un recibo para este mes
            }
            $recibo = new Recibo();
            $recibo->amount = $item->servicio->precio;
            $recibo->id_servicio = $item->id_servicio;
            $recibo->id_usuario = $item->id_usuario;
            $recibo->pedido_id = null;
            $recibo->id_servicio_usuario = $item->id;
            $recibo->tipo = "servicio";
            $recibo->estado = "pendiente";
            $recibo->save();
            $acceso = Acceso::where('user_id',$item->id_usuario)->first();

            if($acceso){
                $acceso->activo = 'off';
                $acceso->save();
            } else {
                $acceso = new Acceso();
                $acceso->inicio = \Carbon\Carbon::now()->format('Y-m-d');
                $acceso->user_id = $item->id_usuario;
                $acceso->activo = 'off';
                if(request()->slug_instalacion == "santaella"){
                    $acceso->apertura = "08:30:00";
                    $acceso->cierre = "22:00:00";
                }else{
                    $acceso->apertura = "09:00:00";
                    $acceso->cierre = "21:00:00";
                }
                $acceso->save();
            }

            if((request()->slug_instalacion == "la-guijarrosa" || request()->slug_instalacion == "santaella") and $user->token_redsys and $user->pago_recurrente == "on"){
                $user = User::find($item->id_usuario);
                $instalacion = $user->instalacion->slug;
                $fecha=\Carbon\Carbon::now();
                $ano=$fecha->year;
                $fecha->format("F"); // Inglés.
                $mes = $fecha->formatLocalized('%B');
                $description = $recibo->servicio->nombre  . '-' .$mes.' '.$ano ;
                $amount = $recibo->amount;
                $pedidoid = $this->generar_pedido($recibo,$user->instalacion);
                $recibo->pedido_id = $pedidoid;
                $recibo->save();

                $customerId = $user->token_redsys;
                if($customerId == null){
                    return false;
                }

                try
                {

                    $key = config('redsys.key_'.$instalacion);

                    $redsys = new \Sermepa\Tpv\Tpv();
                    $redsys->setIdentifier($customerId);
                    $redsys->setMerchantcode(config('redsys.merchantcode_'.$instalacion));
                    $redsys->setCurrency(config('redsys.currency'));
                    $redsys->setTransactiontype('0');
                    $redsys->setTerminal(config('redsys.terminal'));
                    $redsys->setMethod('T');
                    $redsys->setEnvironment(config('redsys.recu_'.$instalacion));
                    $redsys->setVersion(config('redsys.version'));
                    $redsys->setProductDescription($description);
                    $redsys->setAmount($amount);
                    $redsys->setOrder($pedidoid);
                    $redsys->setMerchantDirectPayment(true);
                    $redsys->setMerchantCofIni('N');

                    $parameters = ['DS_MERCHANT_EXCEP_SCA' => 'MIT', 'DS_MERCHANT_DIRECTPAYMENT' => 'true', 'DS_MERCHANT_COF_TYPE' => 'N', 'Ds_Merchant_Cof_Txnid' => 'N'];
                    $redsys->setParameters($parameters);
                    $signature = $redsys->generateMerchantSignature($key);
                    $redsys->setMerchantSignature($signature);

                    $response = json_decode($redsys->send(), true);
                    $parameters = $redsys->getMerchantParameters($response['Ds_MerchantParameters']);
                    $DsResponse = $parameters["Ds_Response"];
                    // $DsResponse = 0;

                    // "Ds_Order": "1674816823",
                    // "Ds_MerchantCode": "097204762",
                    // "Ds_Terminal": "1",
                    // "Ds_TransactionType": "0",
                    // "Ds_Card_PSD2": "Y"

                    if ($redsys->check($key, $response) && $DsResponse <= 99) {
                        //Si es todo correcto ya podemos hacer lo que necesitamos, para este ejemplo solo mostramos los datos.
                        $recibo->estado = "pagado";
                        $recibo->save();
                        $item->fecha_expiracion = \Carbon\Carbon::now()->addMonth();
                        $item->save();
                        $pedido = Pedido::find($pedidoid);
                        $pedido->estado = "pagado";
                        $pedido->save();
                        $acceso->activo = 'on';
                        $acceso->fin = \Carbon\Carbon::now()->addMonth();
                        $acceso->save();
                        LogRecibosDiario::create([
                            'id_servicio_usuario' => $item->id,
                            'id_usuario' => $item->id_usuario,
                            'mensaje' => 'Recibo creado y cobrado automaticamente',
                            'pago_recurrente' => $user->pago_recurrente,
                            'fecha_expiracion' => $fecha_expiracion,
                            'created_at' => \Carbon\Carbon::now(),
                        ]);
                        return "true";
                        // $redsys->executeRedirection();
                    } else {
                        LogRecibosDiario::create([
                            'id_servicio_usuario' => $item->id,
                            'id_usuario' => $item->id_usuario,
                            'mensaje' => 'Recibo creado pero fallo redsys',
                            'pago_recurrente' => $user->pago_recurrente,
                            'fecha_expiracion' => $fecha_expiracion,
                            'created_at' => \Carbon\Carbon::now(),
                        ]);
                        DB::table('fallos_recurrentes')->insert([
                            'error' => $response['Ds_Response'],
                            'DsResponse' => $DsResponse,
                            'user_id' => $item->id_usuario,
                            'created_at' => \Carbon\Carbon::now()
                        ]);

                        return "false";
                    }


                    // $parameters = ['DS_MERCHANT_COF_TYPE' => 'R'];
                    // $redsys->setParameters($parameters);

                    //  $form = $redsys->createForm();


                } catch (\Sermepa\Tpv\TpvException $e) {
                    return $e->getMessage();
                    // echo $e->getMessage();
                }
            }else{
                LogRecibosDiario::create([
                    'id_servicio_usuario' => $item->id,
                    'id_usuario' => $item->id_usuario,
                    'mensaje' => 'Recibo creado pero no cobrado automaticamente',
                    'pago_recurrente' => $user->pago_recurrente,
                    'fecha_expiracion' => $fecha_expiracion,
                    'created_at' => \Carbon\Carbon::now(),
                ]);
            }

        }

        return true;
    }

    public function user_delete_recibo(){


            $recibo= Recibo::find(request()->id_recibo);


            if($recibo->pedido_id ==""){
                $recibo->delete();

                return redirect()->back()->with('success','Recibo borrado con exito');
            }

                return redirect()->back()->with('error','El recibo no se puede eliminar');

    }

    public function listado_accesos(){

        $registros = Registro::orderBy("id", "desc")->paginate(10);

        return view('instalacion.accesospuertas.listado', compact('registros'));

    }

    public function listado_usuarios(){

        $accesos = Acceso::with('usuarios')->get();

        return view('instalacion.accesospuertas.listado_usuario', compact('accesos'));

    }

    public function edit_usuario_acceso(Request $request){


        $acceso=Acceso::find($request->id);
        return view('instalacion.accesospuertas.edit_usuario')->with(compact('acceso'));
    }

    public function edit_usuario_acceso_form(Request $request){

        $acceso=Acceso::find($request->id);
        $usuario = User::find($acceso->user_id);
        $accesos = Acceso::where('user_id', $usuario->id)->where('id', '!=', $acceso->id)->get();
        foreach ($accesos as $accesodelete) {
            $accesodelete->delete();
        }
        if($request->has('activo') == 1) {
            // Si 'activo' está presente en la solicitud, el checkbox está marcado
            $acceso->activo = "on";
        } else {
            // Si 'activo' no está presente, el checkbox no está marcado
            $acceso->activo = "off";
        }


        $acceso->inicio=$request->finicio;
        $acceso->fin=$request->ffin;
        $acceso->apertura=$request->apertura;
        $acceso->cierre=$request->cierre;
        $acceso->user_id= $acceso->user_id;
        $acceso->save();


        return  redirect()->back()->with('message', 'Usuario modificado con éxito');
    }

    public function eliminar_usuario_acceso(Request $request){


        $acceso=Acceso::where('user_id',$request->id);

        $registros=Registro::where('user_id',$request->id);

        $registros->delete();

        $acceso->delete();


        return  redirect()->back()->with('message', 'Usuario Borrado con éxito');
    }

    public function vista_nuevo_acceso(){
        $users = User::all();
        return view('instalacion.accesospuertas.new_acceso')->with(compact('users'));
    }

    public function nuevo_acceso(Request $request){

        $acceso=new Acceso();
        if($request->has('activo') == 1) {
            // Si 'activo' está presente en la solicitud, el checkbox está marcado
            $acceso->activo = "on";
        } else {
            // Si 'activo' no está presente, el checkbox no está marcado
            $acceso->activo = "off";
        }
        $acceso->inicio=$request->finicio;
        $acceso->fin=$request->ffin;
        $acceso->apertura=$request->apertura;
        $acceso->cierre=$request->cierre;
        $acceso->user_id= $request->user_id;
        $acceso->save();

        return  redirect()->back()->with('message', 'Usuario creado con éxito');

    }

    public function checkin_participantes(){

        $fecha = request()->dayFilter;
        $dias = [
            "03-03-2024" => "3 de marzo",
            "02-03-2024" => "2 de marzo",
        ];
        if($fecha){
            if($fecha != '06-01-2024'){
                $participantes = Participante::where('fecha_pedido', $fecha)->orWhere('fecha_pedido', "Bono")->orderBy('created_at', 'desc')->get();
            }else{
                $participantes = Participante::where('fecha_pedido', $fecha)->orderBy('created_at', 'desc')->get();
            }
            $participantespago = $participantes->count();
        }else{
            $participantes = Participante::orderBy('created_at', 'desc')->get();
            $participantespago = $participantes->count();

        }
        $pedido = Collect();
        $sinpedido = [];
        foreach($participantes as $participante){
            if($participante->pedido){
                if($participante->pedido->estado == 'pagado'){
                    $pedido->push($participante->pedido);
                }
            }else{
                $sinpedido[] = $participante;
            }

        }

        $pedido = $pedido->unique('id');


        return view('instalacion.eventos.checkin_participantes',compact('participantes','participantespago','pedido', 'dias', 'fecha'));
    }


    public function crear_entradas(Request $request){

        $pedidoLogNoCorrespondidos = Pedido_participante_log::leftJoin('pedidos', 'pedido_participante_log.pedido_id', '=', 'pedidos.id')
        ->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('participantes')
                ->whereRaw('participantes.id_pedido = pedido_participante_log.pedido_id')
                ->whereRaw('participantes.nombre = pedido_participante_log.nombre_participante');
        })
        ->where('pedidos.estado', 'pagado')
        ->whereNotIn('pedidos.id_evento', [20, 21]) // Excluye los eventos 20 y 21
        ->select('pedido_participante_log.*') // Selecciona solo los campos de Pedido_participante_log
        ->get();

        return view('instalacion.pedidos.crear_entradas',compact('pedidoLogNoCorrespondidos'));
    }

    /* public function crear_entradas_form(Request $request) {
        $participante = new Participante();

        $tipoEntrada = $request->tipo_entrada;

        $participante->fecha_pedido = $request->tipo_entrada;

        if ($tipoEntrada == "30-12-2023" || $tipoEntrada == "31-12-2023" || $tipoEntrada == "Bono") {
            $participante->id_evento = 7;
        } else {
            $participante->id_evento = 8;
        }

        $participante->id_usuario = $request->id_usuario;
        $participante->nombre = $request->nombre_participante;
        $participante->estado = 'active';
        $participante->estado_pedido = 'active';
        $participante->id_pedido = $request->id_predido;
        $participante->showQR = 'true';
        $participante->save();

        $valorcampo = new Valor_campo_personalizado();
        $valorcampo->id_participante = $participante->id;
        $valorcampo->id_campo = 1;
        $valorcampo->valor = $request->nombre_participante;
        $valorcampo->save();

        $valorcampo = new Valor_campo_personalizado();
        $valorcampo->id_participante = $participante->id;
        $valorcampo->id_campo = 5;
        if($request->tipo_entrada == "30-12-2023"){
            $valorcampo->valor = '30 de diciembre (4€)';
        }
        elseif($request->tipo_entrada == "31-12-2023"){
            $valorcampo->valor = '31 de diciembre(3€)';
        }elseif($request->tipo_entrada == "Bono"){
            $valorcampo->valor = 'Bono acceso todos los días (12€)';
        }else{
            $valorcampo->valor = '6 de enero (2€)';
        }
        $valorcampo->save();

        return redirect()->back();
    } */

    public function crear_entradas_form(Request $request) {
        $participante = new Participante();

        $id_evento = Pedido::find($request->id_predido)->id_evento;

        $participante->id_usuario = $request->id_usuario;
        $participante->nombre = $request->nombre_participante;
        $participante->estado = 'active';
        $participante->estado_pedido = 'active';
        $participante->id_pedido = $request->id_predido;
        $participante->showQR = 'true';
        $participante->id_evento = $id_evento;
        $participante->save();

        foreach (unserialize($request->tipo_entrada) as $key => $tipoEntrada) {
            if($key != 'precio'){
                $valorcampo = new Valor_campo_personalizado();
                $valorcampo->id_participante = $participante->id;
                $valorcampo->id_campo = $key;
                $valorcampo->valor = $tipoEntrada;
                $valorcampo->save();
            }
        }


        return redirect()->back();
    }

    public function enviocorreoentradas(Request $request){
        $pedidoLogNoCorrespondidos = Pedido_participante_log::leftJoin('pedidos', 'pedido_participante_log.pedido_id', '=', 'pedidos.id')
        ->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('participantes')
                ->whereRaw('participantes.id_pedido = pedido_participante_log.pedido_id')
                ->whereRaw('participantes.nombre = pedido_participante_log.nombre_participante');
        })
        ->where('pedidos.estado', 'pagado')
        ->select('pedido_participante_log.*') // Selecciona solo los campos de Pedido_participante_log
        ->get();

        if ($pedidoLogNoCorrespondidos->isNotEmpty()) {
            // Enviar correo si hay registros en $pedidoLogNoCorrespondidos
            $destinatarios = ['rafacruz@tallerempresarial.es','javiersanchez@tallerempresarial.es','hugovicente@tallerempresarial.es'];
            Mail::to($destinatarios)->send(new NotificacionEntradas($pedidoLogNoCorrespondidos));
        }

        return true;
    }

    public function update_codigo_tarjeta_user(Request $request){
        $user_exitente = User::where('codigo_tarjeta', $request->codigo_tarjeta)->first();
        if($user_exitente){
            //Quitarle al usuario antiguo la tarjeta
            $user_exitente->codigo_tarjeta = null;
            $user_exitente->save();

            // Asignar al usuario la tarjeta
            $user = User::find($request->id);
            $user->codigo_tarjeta = $request->codigo_tarjeta;
            $user->save();
            return redirect()->back()->with('error', 'Código de tarjeta reasignado a este usuario.');
        }
        $user = User::find($request->id);
        $user->codigo_tarjeta = $request->codigo_tarjeta;
        $user->save();
        return redirect()->back()->with('success', 'Código de tarjeta actualizado con éxito');
    }

    /* public function crearentradascron(Request $request){
        $pedidoLogNoCorrespondidos = Pedido_participante_log::leftJoin('pedidos', 'pedido_participante_log.pedido_id', '=', 'pedidos.id')
        ->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('participantes')
                ->whereRaw('participantes.id_pedido = pedido_participante_log.pedido_id')
                ->whereRaw('participantes.nombre = pedido_participante_log.nombre_participante');
        })
        ->where('pedidos.estado', 'pagado')
        ->select('pedido_participante_log.*') // Selecciona solo los campos de Pedido_participante_log
        ->get();

        if ($pedidoLogNoCorrespondidos->isNotEmpty()) {


            foreach ($pedidoLogNoCorrespondidos as $item) {

               $participante = New Participante();

               $tipoEntrada = $item->tipo_entrada;


                    if ($tipoEntrada == "Bono acceso todos los días (12€)") {
                        $participante->fecha_pedido = 'Bono';
                    } elseif (preg_match('/(\d{1,2}) de (\w+)\s?\(\d+€\)/', $tipoEntrada, $matches)) {
                        $dia = $matches[1];
                        $mesTexto = $matches[2];

                        $meses = [
                            "enero" => "01",
                            "febrero" => "02",
                            "marzo" => "03",
                            "abril" => "04",
                            "mayo" => "05",
                            "junio" => "06",
                            "julio" => "07",
                            "agosto" => "08",
                            "septiembre" => "09",
                            "octubre" => "10",
                            "noviembre" => "11",
                            "diciembre" => "12"
                        ];

                        $mes = $meses[strtolower($mesTexto)] ?? null;

                        if ($mes) {
                            // Asumiendo que el año es 2024 para "enero"
                            $anio = $mesTexto == 'enero' ? '2024' : '2023';
                            $participante->fecha_pedido = sprintf("%02d-%s-%s", $dia, $mes, $anio);
                        }
                    }


                    // if ($tipoEntrada == "30 de diciembre (4€)" || $tipoEntrada == "31 de diciembre(3€)" || $tipoEntrada == "Bono acceso todos los días (12€)") {
                    //     $participante->id_evento = 7;
                    // } else {
                    //     $participante->id_evento = 8;
                    // }

                    $participante->id_usuario = $item->user_id;
                    $participante->nombre = $item->nombre_participante;
                    $participante->estado = 'active';
                    $participante->estado_pedido = 'active';
                    $participante->id_pedido = $item->pedido_id;
                    $participante->showQR = 'true';
                    $participante->save();

                    $valorcampo = new Valor_campo_personalizado();
                    $valorcampo->id_participante = $participante->id;
                    $valorcampo->id_campo = 1;
                    $valorcampo->valor = $item->nombre_participante;
                    $valorcampo->save();

                    $valorcampo = new Valor_campo_personalizado();
                    $valorcampo->id_participante = $participante->id;
                    $valorcampo->id_campo = 5;
                    if($tipoEntrada == "30 de diciembre (4€)"){
                        $valorcampo->valor = '30 de diciembre (4€)';
                    }
                    elseif($tipoEntrada == "31 de diciembre(3€)"){
                        $valorcampo->valor = '31 de diciembre(3€)';
                    }elseif($tipoEntrada == "Bono acceso todos los días (12€)"){
                        $valorcampo->valor = 'Bono acceso todos los días (12€)';
                    }else{
                        $valorcampo->valor = '6 de enero (2€)';
                    }
                    $valorcampo->save();
            }

            return true;
        }
    } */

     public function crearentradascron(Request $request){
        $pedidoLogNoCorrespondidos = Pedido_participante_log::leftJoin('pedidos', 'pedido_participante_log.pedido_id', '=', 'pedidos.id')
        ->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('participantes')
                ->whereRaw('participantes.id_pedido = pedido_participante_log.pedido_id')
                ->whereRaw('participantes.nombre = pedido_participante_log.nombre_participante');
        })
        ->where('pedidos.estado', 'pagado')
        ->select('pedido_participante_log.*') // Selecciona solo los campos de Pedido_participante_log
        ->get();

        if ($pedidoLogNoCorrespondidos->isNotEmpty()) {


            foreach ($pedidoLogNoCorrespondidos as $item) {

                $participante = New Participante();

                $id_evento = Pedido::find($item->pedido_id)->id_evento;

                $pedido = Pedido::find($item->pedido_id);

                $participante->id_usuario = $item->user_id;
                $participante->nombre = $item->nombre_participante;
                $participante->estado = 'active';
                $participante->estado_pedido = 'active';
                $participante->id_pedido = $item->pedido_id;
                $participante->showQR = 'true';
                $participante->id_evento = $id_evento;
                $participante->save();

                foreach (unserialize($item->tipo_entrada) as $key => $tipoEntrada) {
                    if($key != 'precio'){
                        $valorcampo = new Valor_campo_personalizado();
                        $valorcampo->id_participante = $participante->id;
                        $valorcampo->id_campo = $key;
                        $valorcampo->valor = $tipoEntrada;
                        $valorcampo->save();
                    }
                }
                \Mail::to($pedido->user->email)->send(new NewInscripcion($pedido->user, $participante));
            }
            return true;
        }
    }


    public function edit_recibo(Request $request)
    {
        $recibo = Recibo::findOrFail($request->recibo);
        $user = User::find($request->id);
        $servicios = Servicio::all();
        $meses = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        $anios = range(date('Y')+1, date('Y') - 1);

        // Restar un mes a la fecha de expiración del recibo
        $fecha_expiracion = \Carbon\Carbon::parse($recibo->fecha_expiracion);
        $mes_predefinido = $fecha_expiracion->month;
        $anio_predefinido = $fecha_expiracion->year;

        return view('instalacion.users.edit_recibo', compact('user', 'recibo', 'servicios', 'meses', 'anios', 'mes_predefinido', 'anio_predefinido'));
    }


    public function editar_recibo_post(Request $request)
    {

        $recibo = Recibo::find($request->recibo);
        $user = User::find($request->id);
        $servicio = Servicio::find($request->servicio);
        $servicio_usuario = Servicio_usuario::where('id_usuario', $user->id)
        ->where('id_servicio', $servicio->id)
        ->first();

        $acceso = Acceso::where('user_id',$user->id)->first();

        if ($servicio_usuario) {
            $recibo->amount = $servicio->precio;
            $recibo->id_servicio = $request->servicio;
            $recibo->id_usuario = $user->id;
            $recibo->pedido_id = null;
            $recibo->id_servicio_usuario = $servicio_usuario->id;
            $recibo->tipo = "servicio";
            $recibo->estado = $request->estado;
            $recibo->created_at = \Carbon\Carbon::createFromDate($request->anio,$request->mes,02);
            $recibo->save();

            $mes = $request->input('mes');
            $anio = $request->input('anio');
            $dia = now()->day;
            $fecha_expiracion = \Carbon\Carbon::createFromDate($anio, $mes, $dia)->addMonth();;
            $servicio_usuario->fecha_expiracion = $fecha_expiracion;
            $servicio_usuario->save();

            $acceso->activo = 'on';
            $acceso->inicio = \Carbon\Carbon::createFromDate($anio, $mes, $dia);
            $acceso->fin = \Carbon\Carbon::createFromDate($anio, $mes, $dia)->addMonth();
            if($request->slug_instalacion == "santaella"){
                $acceso->apertura = "08:30:00";
                $acceso->cierre = "22:00:00";
            }else{
                $acceso->apertura = "09:00:00";
                $acceso->cierre = "21:00:00";
            }
            $acceso->save();

            return redirect($request->slug_instalacion . '/admin/users/'.$user->id.'/ver');
        }else{
            return redirect()->back()->with('error', 'El usuario no ha contratado ese servicio.');
        }



    }

    public function listado_lectores(Request $request)
    {
        /* $lectores = Log::where('status', 'valida')->get(); */
        $lectores = Log::select('lector_id', DB::raw('count(*) as total'))
        ->where('status', 'valida')
        ->groupBy('lector_id')
        ->having('lector_id', '>=', 1) // Filtrar para lector_id 1 a 9
        ->having('lector_id', '<=', 9)
        ->get();
        return view('instalacion.eventos.checked', compact('lectores'));
    }

    public function desactivar(Request $request)
    {

        // Buscar el servicio del usuario por id
        $servicio = Servicio_Usuario::where('id_usuario', $request->id)->where('id_servicio',$request->servicio_id)->first(); // Ajusta esta consulta si es necesario


        if ($servicio) {
            $servicio->activo = 'no';
            $servicio->save();
        }

        // Redirigir de nuevo a la vista anterior con un mensaje de éxito
        return redirect()->back()->with('success', 'El servicio ha sido desactivado.');
    }

    public function borrarRecibosSinPedido($slug, $id)
    {
        // Buscar el usuario por el id
        $user = User::findOrFail($id);

        // Buscar todos los recibos de este usuario donde no haya pedido_id
        $recibos = Recibo::where('id_usuario', $user->id)
                        ->whereNull('pedido_id')  // Asegurarse de que no haya un pedido_id
                        ->get();

        // Marcar como eliminados (soft delete)
        foreach ($recibos as $recibo) {
            $recibo->delete();
        }

        // Redirigir con mensaje de éxito
        return redirect()->back()->with('success', 'Recibos sin pedido han sido eliminados.');
    }

    public function user_add_servicio_view(Request $request)
    {
        $user = User::find($request->id);
        $servicios = Servicio::all();
        $meses = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        $anios = range(date('Y')+1, date('Y') - 1);
        return view('instalacion.users.add_servicio', compact('user', 'servicios', 'meses','anios'));
    }

    public function user_add_servicio(Request $request){
        $user = User::find($request->id);
        $servicio = Servicio::find($request->servicio);

        $mes = $request->input('mes');
        $anio = $request->input('anio');
        $dia = now()->day;

        $fecha_expiracion = \Carbon\Carbon::createFromDate($anio, $mes, $dia);


        $servicio_usuario = new Servicio_Usuario();

        $servicio_usuario->fecha_expiracion = $fecha_expiracion;
        $servicio_usuario->activo = 'si';
        $servicio_usuario->id_usuario = $user->id;
        $servicio_usuario->id_servicio = $servicio->id;
        $servicio_usuario->save();



        return redirect($request->slug_instalacion . '/admin/users/'.$user->id.'/ver');

    }


}


