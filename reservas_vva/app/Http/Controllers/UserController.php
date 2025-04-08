<?php

namespace App\Http\Controllers;

use App\Mail\CancelarReserva;
use App\Models\Desactivacion_reserva;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewReserva;
use App\Models\Valor_campo_personalizado;
use App\Models\Instalacion;
use App\Models\Pista;
use App\Models\Reserva;
use App\Models\User;
use App\Models\Pedido;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Str;
use App\Mail\MailTemplate;
use App\Models\Evento;
use App\Models\Participante;
use App\Models\Participante_eventos_mes;
use App\Models\Mensajes_difusion;
use App\Models\Dias_festivos;
use App\Models\Servicio_Usuario;
use App\Models\Recibo;
use App\Models\Log;
use App\Models\Bono;
use App\Models\BonoUsuario;
use Illuminate\Support\Facades\Auth;
use App\Models\BonoParticipante;
class UserController extends Controller
{
    public function index(Request $request) {


        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        if (count($instalacion->deportes)>1 || count($instalacion->pistas) == 0) {
            return view('home', compact('instalacion'));
        }
        return redirect("{$instalacion->slug}/{$instalacion->pistas->first()->tipo}");
    }

    public function pistas(Request $request) {

        if($request->slug_instalacion == "vvadecordoba"  && $request->deporte == "piscina") {
            $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();

            $pistas = Pista::where([['tipo', $request->deporte], ['id_instalacion', $instalacion->id]])->get();

            if (isset($request->id_pista)) {
                $pista_selected = Pista::find($request->id_pista);
            } else{
                if (isset($pistas[0])) {
                    $pista_selected = $pistas[0];
                } else {
                    abort(404);
                }
            }

            if (isset($request->semana)) {
                if ($pista_selected->max_dias_antelacion > 10) {
                    $current_date = new DateTime(date("Y-m-d", strtotime(date('Y-m-d')."+{$request->semana} weeks")));
                    $plus_date = new DateTime(date("Y-m-d", strtotime(date('Y-m-d')."+{$request->semana} weeks")));
                    $plus_date->add(new \DateInterval('P8D'));
                } else {
                    $current_date = new DateTime(date("Y-m-d", strtotime(date('Y-m-d')."+".$request->semana*$pista_selected->max_dias_antelacion." days")));
                    $plus_date = new DateTime(date("Y-m-d", strtotime(date('Y-m-d')."+".$request->semana*$pista_selected->max_dias_antelacion." days")));
                    $plus_date->add(new \DateInterval('P'.$pista_selected->max_dias_antelacion.'D'));
                }
            }elseif (isset($request->dia)) {
                if ($pista_selected->max_dias_antelacion > 10) {
                    $fecha = Carbon::createFromFormat('d/m/Y', $request->dia)->format('d-m-Y');
                    $current_date = new DateTime($fecha);
                    $plus_date = new DateTime($fecha);
                    $plus_date->add(new \DateInterval('P7D'));
                } else {
                    $fecha = Carbon::createFromFormat('d/m/Y', $request->dia)->format('d-m-Y');
                    $current_date = new DateTime($fecha);
                    $plus_date = new DateTime($fecha);
                    $plus_date->add(new \DateInterval('P'.$pista_selected->max_dias_antelacion.'D'));
                }
            }else{
                if ($pista_selected->max_dias_antelacion > 10) {
                    $current_date = new DateTime();
                    $plus_date = new DateTime();
                    $plus_date->add(new \DateInterval('P7D'));
                } else {
                    $current_date = new DateTime();
                    $plus_date = new DateTime();
                    $plus_date->add(new \DateInterval('P'.$pista_selected->max_dias_antelacion.'D'));
                }
            }

            $date_for_valid = new DateTime();
            $date_for_valid->add(new \DateInterval('P'.$pista_selected->max_dias_antelacion.'D'));

            $valid_period = new \DatePeriod(new DateTime(), \DateInterval::createFromDateString('1 day'), $date_for_valid);
            $period = new \DatePeriod($current_date, \DateInterval::createFromDateString('1 day'), $plus_date);


            return view('pista.pista', compact('period', 'valid_period', 'pistas', 'pista_selected'));

        }else{
            return redirect("{$request->slug_instalacion}");
        }



        //return redirect("{$request->slug_instalacion}");

    }

    public function reserva(Request $request)
    {
        $reserva = Reserva::where([['id_pista', $request->id_pista], ['timestamp', $request->timestamp], ['estado', 'active']])->first();

        $pista = Pista::find($request->id_pista);
        $user = User::find(auth()->user()->id);
        $fecha = $request->timestamp;
        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        $deporte_id = $pista->id_deporte;
        $bono_usuario = BonoUsuario::where('id_usuario', auth()->user()->id)
                                ->where('estado', 'active')
                                ->where('num_usos', '>', 0)
                                ->whereHas('bono', function($query) use ($deporte_id) {
                                    $query->where('id_deporte', $deporte_id);
                                })
                                ->first();

        $servicios_disponibles = Servicio::where('pista_id',$request->id_pista)->where('reservas','=',"Si")->pluck('id')->toArray();


        if($servicios_disponibles==[]){

            $servicios_disponibles = Servicio::where('tipo_espacio',$pista->id_deporte)->where('reservas','=',"Si")->pluck('id')->toArray();

            }

        $servicios_contratados = Servicio_Usuario::where('id_usuario', $user->id)
        ->whereDate('fecha_expiracion', '>=', \Carbon\Carbon::now()->format('Y-m-d'))
        ->pluck('id_servicio')
        ->toArray();

        $servicios_no_contratados = array_intersect($servicios_disponibles,$servicios_contratados);


        if (!$pista->check_reserva_valida($request->timestamp)) {
            return view('pista.reservanodisponible');
        }
        if (!$user->check_maximo_reservas_espacio($pista->tipo)) {
            $max_reservas = true;
            return view('pista.reservanodisponible', compact('max_reservas'));
        }
        if (!$user->aprobado) {
            $user_no_valid = true;
            return view('pista.reservanodisponible', compact('user_no_valid'));
        }


        if (count($servicios_disponibles)>0 && ($request->slug_instalacion != "santaella" || $request->slug_instalacion != "la-guijarrosa" )) {

            if (count($servicios_no_contratados) == 0) {
                return view('pista.reservaservicionodisponible');
            }


        }

        // dd($pista->horario_deserialized);

        foreach ($pista->horario_deserialized as $item){
            if (in_array(date('w', $fecha), $item['dias']) || ( date('w', $fecha) == 0 && in_array(7, $item['dias']) )){
                foreach ($item['intervalo'] as $index => $intervalo){
                    $hora = new \DateTime(date('Y-m-d H:i', $fecha));
                    $a = new \DateTime(date('Y-m-d', $fecha) . ' ' . $intervalo['hfin']);
                    if($a->format("H:i:s")=="00:00:00"){
                        $a->modify('+1 day');
                    }
                    $b = new \DateTime(date('Y-m-d', $fecha) . ' ' . $intervalo['hinicio']);
                    if ($hora >= $b && $hora <= $a) {
                        $secuencia = $intervalo['secuencia'];
                        $interval = $a->diff($b);
                        $diff_minutes = $interval->format("%h") * 60;
                        $diff_minutes += $interval->format("%i");
                        $numero_veces = $diff_minutes/$secuencia;

                        for ($i=0; $i < floor($numero_veces)+1; $i++) {
                            if (!$pista->check_reserva_valida($hora->getTimestamp())) {
                                $number = $i;
                                break;
                            }
                            if ($hora->format('H:i') == $a->format('H:i')) {
                                $number = $i;
                                break;
                            }
                            $hora->modify("+{$secuencia} minutes");
                        }
                    }
                }
            }
        }
        $intervalo = $pista->get_intervalo_given_timestamp($request->timestamp);
        if($bono_usuario){
            return view('pista.reserva', compact('pista', 'fecha', 'secuencia', 'number', 'intervalo', 'instalacion','bono_usuario'));
        }else{
            return view('pista.reserva', compact('pista', 'fecha', 'secuencia', 'number', 'intervalo', 'instalacion'));
        }
    }

    public function reservar(Request $request)
    {
        $pista = Pista::find($request->id_pista);
        if (!$pista->check_reserva_valida($request->timestamp)) {
            return redirect()->back();
        }

        $minutos_totales = $request->secuencia * $request->tarifa;

        $timestamps[0] = (int)$request->timestamp;

        if ($request->tarifa > 1) {
            for ($i=1; $i < $request->tarifa; $i++) {
                $timestamps[$i] = \Carbon\Carbon::parse(date('d-m-Y H:i:s', $request->timestamp))->addMinutes($request->secuencia*$i)->timestamp;
            }
        }

        $reserva = Reserva::create([
            'id_pista' => $request->id_pista,
            'id_usuario' => auth()->user()->id,
            'timestamp' => $request->timestamp,
            'horarios' => serialize($timestamps),
            'fecha' => date('Y/m/d', $request->timestamp),
            'hora' => date('Hi', $request->timestamp),
            'tarifa' => $request->tarifa,
            'minutos_totales' => $minutos_totales
        ]);

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
        /* $pedido = new Pedido();

        $pedido_id= generateOrderID();

        $pedido->id = $pedido_id;
        $pedido->amount = 3;
        $pedido->id_usuario = auth()->user()->id;
        $pedido->id_reserva = $reserva->id;
        $pedido->estado = "En proceso";
        $pedido->save();     */

        /* Mail::to(auth()->user()->instalacion->user_admin->email)->send(new NewReserva(auth()->user(), $reserva)); */

        return redirect("/{$request->slug_instalacion}/mis-reservas");
    }

    public function mis_reservas(Request $request)
    {
        $reservas_caducadas = Reserva::where([['estado', 'pendiente'], ['creado_por', 'user'], ['created_at', '<', \Carbon\Carbon::now()->subMinutes(15)->toDateTimeString()]]);
        Pedido::whereIn('id_reserva', $reservas_caducadas->pluck('id'))->update(['estado' => 'cancelado']);
        Pedido::whereIn('id_reserva', $reservas_caducadas->pluck('id'))->delete();
        $reservas_caducadas->update(['estado' => 'canceled']);
        Reserva::where([['estado', 'canceled'], ['creado_por', 'user'], ['created_at', '<', \Carbon\Carbon::now()->subMinutes(15)->toDateTimeString()]])->delete();

        $reservas = Reserva::where('id_usuario', auth()->user()->id)->orderBy('created_at', 'desc')->simplePaginate();

        return view('user.misreservas', compact('reservas'));
    }

    public function cancel_reservas(Request $request)
    {
        Reserva::find($request->id)->update(['estado' => 'canceled']);
        $reserva=Reserva::find($request->id);
        $pedido=Pedido::find($reserva->id_pedido);

        $bloqueos = Desactivacion_reserva::where('reserva_id',$request->id)->delete();

        $pedido->estado="Devolucion pendiente";
        $pedido->save();
        $reserva->observaciones="Pista cancelada por el usuario";

        Mail::to($reserva->user->email)->send(new CancelarReserva($reserva->user, $reserva));

        \DB::purge('mysql');

        return redirect()->back();
    }

    public function perfil(Request $request)
    {
        return view('user.perfil');
    }

    public function edit_perfil(Request $request)
    {
        $data = $request->all();
        array_shift($data);
        if(!isset($request->pago_recurrente)){
            $data['pago_recurrente'] = "off";
        }
        if (!isset($request->password)) {
            unset($data['password']);
            unset($data['password_rep']);
            User::where('id', auth()->user()->id)->update($data);
        }else {
            if ($data['password'] == $data['password_rep']) {
                unset($data['password_rep']);
                $data['password'] = Hash::make($request->password);
                User::where('id', auth()->user()->id)->update($data);
            } else {
                return redirect()->back()->with('error', 'true');
            }
        }
        return redirect()->back()->with('success', 'true');
    }

    public function delete_perfil(Request $request)
    {
        $usuario = User::where('id', auth()->user()->id)->delete();
        return redirect()->to('http://gestioninstalacion.es/seleccion-instalacion');
    }

    public function normas_instalacion(Request $request) {

        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        if (!$instalacion->html_normas) {
            return redirect("/{$instalacion->slug}");
        }
        return view("normas", compact('instalacion'));
    }

    public function contacto_instalacion(Request $request) {

        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();

        return view("contacto", compact('instalacion'));
    }

    public function enviar_contacto_instalacion(Request $request) {

        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        $msg = "<div><strong>Datos:</strong></div><ul><li>Asunto: {$request->asunto}</li><li>Nombre persona: {$request->name}</li><li>Email: {$request->email}</li>";
        if ($request->tlfno) {
            $msg .= "<li>Teléfono: {$request->tlfno}</li>";
        }
        $msg .= "</ul><br><div><strong>Mensaje:</strong><br><ul><li>{$request->mensaje}</li></ul></div>";

        if($request->slug_instalacion=="villafranca-de-cordoba"){
            $envia="deporte@villafrancadecordoba.es";
        }else{
            $envia="alfonso@tallerempresarial.es";
        }
        Mail::to($envia)->send(new MailTemplate("Formulario contacto - Reservas {$instalacion->nombre}", $msg));
        if(request()->slug_instalacion == "villafranca-navidad"){
            Mail::to("javiersanchez@tallerempresarial.es")->send(new MailTemplate("Formulario contacto - Reservas {$instalacion->nombre}", $msg));
        }

        return redirect()->back()->with('success', 'Se ha enviado su mensaje. Pronto alguien se pondrá en contacto contigo.');
    }
/*
    public function condiciones_generales() {
        return view("index.condiciones_custom");
    }

    public function privacidad() {
        return view('index.privacidad_custom');
    }

    public function terminos_condiciones() {
        return view('index.terminos_custom');
    }
*/
    public function condiciones_generales() {
        return view("condicionesgenerales");
    }

    public function privacidad() {
        if(request()->slug_instalacion == "feria-jamon-villanuevadecordoba"){
            return redirect ("https://reservas.jarotea.es/jamon/politica");
        }else{
            return view("privacidad");
        }
    }

    public function terminos_condiciones() {
        if(request()->slug_instalacion == "feria-jamon-villanuevadecordoba"){
            return redirect ("https://reservas.jarotea.es/jamon/condiciones");
        }else{
            return view("terminoscondiciones");
        }
    }
    public function buscarInstalacion() {

        $instalaciones = Instalacion::all();
        return view('seleccioninstalacion', compact('instalaciones'));
    }

    public function search(Request $request){
        $instalaciones = Instalacion::where('nombre','Like','%'.$request->search.'%')->orwhere('direccion','Like','%'.$request->search.'%')->get();
        $salida = "";
        foreach($instalaciones as $instalacion){
            $salida.='<div class="col-sm-6">
                    <div class="card mb-5">
                        <a href="/'.$instalacion->slug.'/new" style="text-decoration:none; color: black">
                            <div class="row justify-content-center">
                                <div class="col-3 col-md-4" style="text-align: center; margin: auto">
                                    <img src="/img/'.$instalacion->slug.'.png" style="max-height: 50px" />
                                </div>
                                <div class="col-9 col-md-8">
                                    <div class="card-body">
                                        <p class="card-text">'.$instalacion->nombre.'</p>
                                        <p class="card-text" style="font-size: 12px"><small class="text-muted">('.$instalacion->direccion.')</small></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>';
        }

        return response($salida);

    }


    public function index_nuevo(Request $request)
{
    // Obtener el segmento después de "https://gestioninstalacion.es/" en la URL
    $segmento = str_replace('https://gestioninstalacion.es/', '', $request->slug_instalacion);

    // Conectar a la base de datos 'superadmin' y buscar el registro que coincida con el segmento
    $registro = DB::connection('superadmin')
        ->table('superadmin')
        ->where('url', 'like', "https://gestioninstalacion.es/$segmento")
        ->first();

    // Verificar si se encontró el registro
    if (!$registro) {
        return response()->json(['error' => 'Registro no encontrado en la base de datos superadmin'], 404);
    }

    // Obtener el campo tipo_calendario
    $tipoCalendario = $registro->tipo_calendario;

    // Continuar con la lógica existente de la función index_nuevo
    $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
    $eventos = Evento::where([
        ['id_instalacion', $instalacion->id],
        ['insc_fecha_inicio', '<=', date('Y-m-d H:i:s')],
        ['insc_fecha_fin', '>=', date('Y-m-d H:i:s')],
    ])->orderBy('fecha_inicio')->get();

    if (isset($instalacion->deportes_clases->first()->id)) {
        $pistas = Pista::where([
            ['id_instalacion', $instalacion->id],
        ])->get();
    } else {
        $pistas = null;
    }

    $servicios = Servicio::all();
    $dias_festivos = Dias_festivos::all(['dia_festivo']);
    $user = Auth::user();
    if ($tipoCalendario == 0) {
        // Lógica existente para tipo_calendario = 0
        $suma_hora = 0;
        for ($y = 7; $y < intval(explode(":", date('H:i', strtotime(date('Y-m-d H:i'))))[0]); $y++) {
            $suma_hora += 40;
        }
        $hora_coord = $suma_hora + (explode(":", date('H:i', strtotime(date('Y-m-d H:i'))))[1] * 2 / 3);
        $block_pista = str_replace(',', '.', $hora_coord);

        if ($request->slug_instalacion == "la-guijarrosa" || $request->slug_instalacion == "santaella") {
            $bonos = Bono::all();
        } else {
            $bonos = collect();
        }

        if (($request->slug_instalacion == "la-guijarrosa" || $request->slug_instalacion == "santaella") && $user) {
            $paso = $user->accesos()->first();
            $ultimoRegistro = $user->registros()->latest()->first();
            return view('new.index', compact('instalacion', 'pistas', 'block_pista', 'eventos', 'dias_festivos', 'servicios', 'paso', 'ultimoRegistro', 'bonos', 'tipoCalendario'));
        } else {
            return view('new.index', compact('instalacion', 'pistas', 'block_pista', 'eventos', 'dias_festivos', 'servicios', 'tipoCalendario'));
        }
    } elseif ($tipoCalendario == 1) {
        // Nueva lógica para tipo_calendario = 1
        $pistas = Pista::where([['id_instalacion', $instalacion->id], ['active', 1]])->get();
        // Determinar la pista seleccionada
    if ($request->id) {
        $pista_selected = Pista::find($request->id);
    } else {
        $pista_selected = $pistas->first(); // Seleccionar la primera pista por defecto
    }
        
        if (!$pista_selected) {
            abort(404, 'Pista no encontrada');
        }
    

        if (isset($request->semana)) {
            if ($pista_selected->max_dias_antelacion > 10) {
                $current_date = new DateTime(date("Y-m-d", strtotime(date('Y-m-d') . "+{$request->semana} weeks")));
                $plus_date = new DateTime(date("Y-m-d", strtotime(date('Y-m-d') . "+{$request->semana} weeks")));
                $plus_date->add(new \DateInterval('P8D'));
            } else {
                $current_date = new DateTime(date("Y-m-d", strtotime(date('Y-m-d') . "+" . $request->semana * $pista_selected->max_dias_antelacion . " days")));
                $plus_date = new DateTime(date("Y-m-d", strtotime(date('Y-m-d') . "+" . $request->semana * $pista_selected->max_dias_antelacion . " days")));
                $plus_date->add(new \DateInterval('P' . $pista_selected->max_dias_antelacion . 'D'));
            }
        } elseif (isset($request->dia)) {
            $fecha = Carbon::createFromFormat('d/m/Y', $request->dia)->format('d-m-Y');
            $current_date = new DateTime($fecha);
            $plus_date = new DateTime($fecha);
            $plus_date->add(new \DateInterval('P' . $pista_selected->max_dias_antelacion . 'D'));
        } else {
            $current_date = new DateTime();
            $plus_date = new DateTime();
            $plus_date->add(new \DateInterval('P' . $pista_selected->max_dias_antelacion . 'D'));
        }

        $date_for_valid = new DateTime();
        $date_for_valid->add(new \DateInterval('P' . $pista_selected->max_dias_antelacion . 'D'));

        $valid_period = new \DatePeriod(new DateTime(), \DateInterval::createFromDateString('1 day'), $date_for_valid);
        $period = new \DatePeriod($current_date, \DateInterval::createFromDateString('1 day'), $plus_date);
        $horarios_final = $pista_selected->horarios_final($period);

        return view('new.index', compact('instalacion', 'pistas', 'period', 'horarios_final', 'dias_festivos', 'tipoCalendario', 'pista_selected', 'eventos', 'servicios', 'valid_period'));
    }
}

public function obtenerHorarios(Request $request, $slug_instalacion, $nombre, $tipo, $id)
{
    // Buscar la pista por ID
    $pista = Pista::find($id);

    if (!$pista) {
        return response()->json(['error' => 'Pista no encontrada'], 404);
    }

    // Obtener la fecha seleccionada o usar la fecha actual
    $fecha = $request->query('dia', date('Y-m-d'));

    // Crear un periodo de fechas basado en la configuración de la pista
    $current_date = new \DateTime($fecha);
    $plus_date = (clone $current_date)->add(new \DateInterval('P' . $pista->max_dias_antelacion . 'D'));
    $period = new \DatePeriod($current_date, \DateInterval::createFromDateString('1 day'), $plus_date);

    // Obtener los horarios finales para el periodo
    $horarios_final = $pista->horarios_final($period);

    // Devolver los horarios en formato JSON
    return response()->json($horarios_final);
}

public function pistas_por_deportes_fecha(Request $request) {
    try {
        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        $fecha = $request->fecha;

        $pistas = Pista::where([
            ['id_deporte', $request->deporte],
            ['id_instalacion', $instalacion->id],
            ['active', 1]
        ])->get();

        foreach ($pistas as $key => $value) {
            $value->horario_con_reservas_por_dia = $value->horario_con_reservas_por_dia($fecha);
        }

        return response()->json($pistas);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    public function pistas_por_deportes_mes(Request $request){
        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        $fecha = \Carbon\Carbon::createFromDate($request->year, $request->mes, 1);

        $timestamp = $fecha->timestamp;

        $pistas = Pista::where([['id_deporte', $request->deporte], ['id_instalacion', $instalacion->id],['active',1]])->get();
        foreach ($pistas as $key => $value) {
            $value->horario_con_reservas_por_mes = $value->horario_con_reservas_por_mes($timestamp);
        }

        dd($pistas);
        return $pistas;
    }

    public function mis_reservas_new(Request $request) {
        if(request()->slug_instalacion == "villafranca-navidad" or request()->slug_instalacion == "villafranca-actividades" or request()->slug_instalacion == "ciprea24" or request()->slug_instalacion == "eventos-bodega" or request()->slug_instalacion == "feria-jamon-villanuevadecordoba"){
            return redirect("/{$request->slug_instalacion}/new/mis-eventos");
        }

        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        $reservas = Reserva::where('id_usuario', auth()->user()->id)->orderBy('created_at', 'desc')->simplePaginate();

        return view('new.perfil.mis_reservas', compact('instalacion', 'reservas'));
    }

    public function mi_perfil_new(Request $request) {
        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        $user = Auth::user();
        $ultimoRegistro = $user->registros()->latest()->first();
        $paso = $user->accesos()->first();

        return view('new.perfil.perfil', compact('instalacion','ultimoRegistro','paso'));
    }

    public function mis_eventos(Request $request) {
        if($request->slug_instalacion != 'feria-jamon-villanuevadecordoba'){
            $pedidos_caducados = Pedido::withTrashed()->where([['estado', 'En proceso'],  ['created_at', '<', \Carbon\Carbon::now()->subMinutes(10)->toDateTimeString()]]);
            Participante_eventos_mes::whereIn('id_pedido', $pedidos_caducados->pluck('id'))->delete();
            Participante::whereIn('id_pedido', $pedidos_caducados->pluck('id'))->delete();
        }else{
            $pedidos_caducados = Pedido::withTrashed()->where([['estado', 'En proceso']]);
            Participante_eventos_mes::whereIn('id_pedido', $pedidos_caducados->pluck('id'))->delete();
            Participante::whereIn('id_pedido', $pedidos_caducados->pluck('id'))->delete();
        }

        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        /* $reservas = Evento::where('id_'); */

        return view('new.perfil.mis_eventos', compact('instalacion'));
    }

    public function mis_servicios(Request $request){

        $user = User::find(auth()->user()->id);
        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        $servicio_usuario = $servicios_contratados = Servicio_Usuario::where('id_usuario', $user->id)->get();
        return view('new.perfil.mis_servicios', compact('instalacion','servicio_usuario'));
    }

    public function baja_servicio(Request $request)
        {
            $servicio = Servicio_Usuario::find($request->servicio);
            $servicio->activo = 'no';
            $servicio->save();
            return redirect()->back();
        }

    public function ver_recibos(Request $request){

        $user = User::find(auth()->user()->id);
        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        $recibos = Recibo::where('id_usuario',$user->id)->orderBy('created_at', 'asc')->get();
        if(request()->slug_instalacion == "villafranca-navidad"){
            $recibos_no_pagados = Recibo::where('id_usuario', $user->id)->where(function ($query) {
                $query->whereNull('pedido_id')->where('estado', 'pendiente')->orWhere('pedido_id', '');
            })->count();
        }else{

            $recibos_no_pagados = Recibo::where('id_usuario', $user->id)->where('estado', 'pendiente')->where(function ($query) {
                $query->whereNull('pedido_id')->orWhere('pedido_id', '');
            })->count();
        }

        return view('new.perfil.recibos',compact('recibos','instalacion','recibos_no_pagados'));
    }

    public function contacto_instalacion_new(Request $request) {

        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();

        return view("new.contacto", compact('instalacion'));
    }

    public function inscripcion_evento(Request $request)
    {
        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        $evento = Evento::find($request->id);
        return view('new.evento.reservar', compact('instalacion', 'evento'));
    }
    public function escaner(Request $request) {
        $logs = Log::orderBy('id', 'desc')->whereDate('created_at', \Carbon\Carbon::today())->where('lector_id', 1)->get();
        $check = false;
        return view('instalacion.qrs.escaner')->with(compact('logs', 'check'));
    }
    public function escanear(Request $request) {
        try{
        $result = [];
        $status = 'failed';
        $msg = '';
        $code = unserialize($request->code)[0];
        $participante_id = unserialize($request->code)[1];
        $participante = Participante::find($participante_id);
        $evento_id = $participante->evento->id;

        $entrada = null;
        $now = \Carbon\Carbon::now();
        $check = true;
        // Comprobar qr de pedido
        if($code != null && (substr($code, 0, 2) == 'na' or substr($code, 0, 2) == 'ac') or substr($code, 0, 2) == 'ev' or substr($code, 0, 2) == 'fj') {
            $ref = $code;
            $pedido = Pedido::find($ref);
            if($pedido == null) {
                $msg = 'El pedido no es válido';
                $status = false;

                Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'no-existe', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 1, 'participante_id' => $participante->id ,'created_at' => $now]);
            } else {

                $fecha = $participante->fecha_pedido;
                $checked = 0;
                $show = false;
                if(request()->slug_instalacion == "villafranca-navidad"){
                    if($fecha == "Bono"){
                        if($now->format('d-m-Y') == "23-12-2023" or $now->format('d-m-Y') == "24-12-2023" or $now->format('d-m-Y') == "30-12-2023" or $now->format('d-m-Y') == "01-01-2024"){
                            $show = true;
                        }
                        if($now->format('d-m-Y') == "25-12-2023" or $now->format('d-m-Y') == "31-12-2023"){
                            if($now->format('H') <= 7){
                                $show = true;
                            }
                        }
                    }
                    if($fecha == '31-12-2023'){
                        $fecha = '01-01-2024';
                    }
                    if($participante->id_evento == 8){
                        if($now->format('d-m-Y') == "06-01-2024" and $now->format('H') < 22) {
                            $show = true;
                        }
                    }else{
                        if(($now->format('d-m-Y') == "06-01-2024" and $now->format('H') >= 22) or $now->format('d-m-Y') == "07-01-2024") {
                            $show = true;
                        }
                    }

                }




                if($pedido->checked == null or $show) {
                    $valido = false;
                    $caso_especial = true;
                    $validoActividad = false;
                    if(request()->slug_instalacion == "villafranca-navidad"){
                        if($fecha != "Bono"){
                            $fechaCarbon = \Carbon\Carbon::createFromFormat('d-m-Y', $fecha);
                            $fechaCarbon->addDays(1);
                            if($now->format('d-m-Y') == $fechaCarbon->format('d-m-Y') and $now->format('H') <= 7) {
                                $valido = true;
                            }
                        }

                        $caso_especial = true;

                        if($participante->id_evento == 7){
                            $caso_especial = false;
                            if(($now->format('d-m-Y') == "06-01-2024" and $now->format('H') >= 22) or $now->format('d-m-Y') == "07-01-2024") {
                                $caso_especial = true;
                            }
                        }else{
                            if($participante->id_evento == 8){
                                $caso_especial = false;
                                if($now->format('d-m-Y') == "06-01-2024" and $now->format('H') < 22) {
                                    $caso_especial = true;
                                }
                            }
                        }

                    }else{
                        // $fecha_prueba = "30-04-2024 14:30"; // testing
                        // $now = \Carbon\Carbon::parse($fecha_prueba); // testing
                        $hora_inicio = \Carbon\Carbon::parse($participante->hora_inicio);
                        $hora_fin = \Carbon\Carbon::parse($participante->hora_fin);

                        $fecha_inicio = \Carbon\Carbon::parse($participante->evento->fecha_inicio);
                        $fecha_fin = \Carbon\Carbon::parse($participante->evento->fecha_fin);
                        if($participante->tipo_entrada == "Solo DJ's"){
                            $fecha_inicio = \Carbon\Carbon::parse($participante->fecha_pedido . ' ' . $participante->hora_inicio);
                        }
                        if($now->gte($fecha_inicio) and $now->lte($fecha_fin)){
                            $validoActividad = true;
                        }

                    }

                    if($validoActividad) {
                        if($checked == 1) {
                            $msg = 'Ya hay entradas validadas';
                            $status = false;
                            Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 1, 'participante_id' => $participante->id ,'created_at' => $now]);
                        } else {
                            $msg = '';
                            $status = true;
                            if($participante->estado_pedido == "validado"){
                                $msg = 'Ya ha sido validado';
                                $status = false;
                                Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 1, 'participante_id' => $participante->id ,'created_at' => $now]);
                            }else{
                                if($fecha == "Bono"){
                                    $bono_participante = BonoParticipante::where('id_participante', $participante->id)->where('fecha', $now->format('d-m-Y'))->first();


                                    if(!$bono_participante and $participante->id_evento != 8){

                                        $bono_participante = new BonoParticipante();
                                        $bono_participante->id_participante = $participante->id;
                                        $bono_participante->fecha = $now->format('d-m-Y');
                                        $bono_participante->save();
                                        $status = true;
                                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'valida', 'entrada_id' => $ref, 'pedido_id' => $pedido->id, 'lector_id' => 1, 'participante_id' => $participante->id ,'created_at' => $now]);
                                    }else{
                                        $status = false;
                                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => $pedido->id, 'lector_id' => 1, 'participante_id' => $participante->id ,'created_at' => $now]);
                                    }
                                }else{
                                    $participante->estado_pedido = 'validado';
                                    $participante->save();
                                    $all_checked = true;
                                    foreach(Participante::where('id_pedido', $pedido->id)->get() as $participante) {
                                        if($participante->estado_pedido != 'validado') {
                                            $all_checked = false;
                                            continue;
                                        }
                                    }

                                    if($all_checked) {
                                        $pedido->checked = 1;
                                        $pedido->save();
                                    }
                                    Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'valida', 'entrada_id' => $ref, 'pedido_id' => $pedido->id, 'lector_id' => 1, 'participante_id' => $participante->id ,'created_at' => $now]);

                                }

                            }
                        }
                    }else{
                        $msg = 'Fecha incorrecta';
                        $status = false;
                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'fecha-incorrecta', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 1, 'participante_id' => $participante->id ,'created_at' => $now]);
                    }
                } else {

                    $msg = 'Ya hay entradas validadas';
                    $status = false;
                    Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => $pedido->id, 'lector_id' => 1, 'participante_id' => $participante->id ,'created_at' => $now]);
                }
            }
        } else {

            Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'no-existe', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 1, 'participante_id' => $participante->id ,'created_at' => $now]);
            $msg = 'El código no es válido';
            $status = false;

        }
        return redirect('/'.request()->slug_instalacion.'/api')->with(compact('status'));
    }catch(\Exception $e){
        return redirect()->back()->with('status', false)->with('msg', 'Error, vuelva a leer el código QR');
    }
    }
    public function descargar_entradas(){
        $participantes = Participante::where('id_pedido', request()->id)->get();
        $participante = Participante::where('id_pedido', request()->id)->get()->first();
        $evento = Evento::find($participante->id_evento);
        $usuario = User::find($participante->id_usuario);
         /* return view('new.evento.descargar_entradas', compact('participante', 'evento', 'usuario', 'participantes')); */
        $pdf = \PDF::loadView('new.evento.descargar_entradas', compact('participante', 'evento', 'usuario', 'participantes'));

        return $pdf->download('entradas_'.$participante->id_pedido.'.pdf');
    }

    public function mantener_instalacion(Request $request)
    {
        /* return dd('asdf'); */
        /* if (auth()->check()) {
            auth()->logout();
        } */
        return redirect("/{$request->slug_instalacion}");
    }
    /* public function renovar_mi_evento(Request $request)
    {
        $participante = Participante::find($request->id);
        Participante_eventos_mes::create([
            'id_participante' => $participante->id,
            'id_pedido' => $pedido_id,
            'num_mes' =>  date('d')<=10 ? date('m') : date('m') + 1
        ]);
    } */

    public function anuncios(Request $request)
    {
        $mensajes = Mensajes_difusion::orderByDesc('created_at')->paginate(10);
        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();

        return view('user.anuncios', compact('mensajes','instalacion'));
    }

    public function anuncios_publicos(Request $request)
    {
        $mensajes = Mensajes_difusion::where('tipo_mensaje','publico')->orderByDesc('created_at')->paginate(10);
        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        return view('new.anuncios_publicos', compact('mensajes','instalacion'));
    }

    public function index_listado(Request $request){
        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        $showHiddenDiv = false;
        $fecha = \Carbon\Carbon::now()->format('Y-m-d');
        $reservas = Reserva::where('reserva_periodica', null)->where('fecha',$fecha)->where('estado','active')->orderByDesc('id')->get();
        return view('listadogestion',compact('instalacion','showHiddenDiv','reservas'));
    }

    public function check(Request $request)
    {
        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        $password = $request->input('pw_dev');
        $showHiddenDiv = false;
        $fecha = \Carbon\Carbon::now()->format('Y-m-d');
        $reservas = Reserva::where('reserva_periodica', null)->where('fecha',$fecha)->where('estado','active')->orderByDesc('id')->get();
        if ($password === 'Villa_franca20' && $request->slug_instalacion=="villafranca-de-cordoba") {
            $showHiddenDiv = true;
            return view('listadogestion',compact('showHiddenDiv','instalacion','reservas'));
        }elseif ($password === 'mercadillos_23#' && $request->slug_instalacion=="mercadillos-villafranca-de-cordoba") {
            $showHiddenDiv = true;
            return view('listadogestion',compact('showHiddenDiv','instalacion','reservas'));
        }

        return view('listadogestion',compact('showHiddenDiv','instalacion','reservas'));
    }


    public function escaner2(Request $request) {

        $logs = Log::orderBy('id', 'desc')->whereDate('created_at', \Carbon\Carbon::today())->where('lector_id', 2)->get();
        $check = false;
        return view('instalacion.qrs.escaner2')->with(compact('logs', 'check'));
    }

    public function escanear2(Request $request) {
        try{
        $result = [];
        $status = 'failed';
        $msg = '';
        $code = unserialize($request->code)[0];
        $participante_id = unserialize($request->code)[1];
        $participante = Participante::find($participante_id);
        $evento_id = $participante->evento->id;

        $entrada = null;
        $now = \Carbon\Carbon::now();
        $check = true;
        // Comprobar qr de pedido
        if($code != null && (substr($code, 0, 2) == 'na' or substr($code, 0, 2) == 'ac') or substr($code, 0, 2) == 'ev' or substr($code, 0, 2) == 'fj') {
            $ref = $code;
            $pedido = Pedido::find($ref);
            if($pedido == null) {
                $msg = 'El pedido no es válido';
                $status = false;

                Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'no-existe', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 2, 'participante_id' => $participante->id ,'created_at' => $now]);
            } else {

                $fecha = $participante->fecha_pedido;
                $checked = 0;
                $show = false;
                if(request()->slug_instalacion == "villafranca-navidad"){
                    if($fecha == "Bono"){
                        if($now->format('d-m-Y') == "23-12-2023" or $now->format('d-m-Y') == "24-12-2023" or $now->format('d-m-Y') == "30-12-2023" or $now->format('d-m-Y') == "01-01-2024"){
                            $show = true;
                        }
                        if($now->format('d-m-Y') == "25-12-2023" or $now->format('d-m-Y') == "31-12-2023"){
                            if($now->format('H') <= 7){
                                $show = true;
                            }
                        }
                    }
                    if($fecha == '31-12-2023'){
                        $fecha = '01-01-2024';
                    }
                    if($participante->id_evento == 8){
                        if($now->format('d-m-Y') == "06-01-2024" and $now->format('H') < 22) {
                            $show = true;
                        }
                    }else{
                        if(($now->format('d-m-Y') == "06-01-2024" and $now->format('H') >= 22) or $now->format('d-m-Y') == "07-01-2024") {
                            $show = true;
                        }
                    }

                }




                if($pedido->checked == null or $show) {
                    $valido = false;
                    $caso_especial = true;
                    $validoActividad = false;
                    if(request()->slug_instalacion == "villafranca-navidad"){
                        if($fecha != "Bono"){
                            $fechaCarbon = \Carbon\Carbon::createFromFormat('d-m-Y', $fecha);
                            $fechaCarbon->addDays(1);
                            if($now->format('d-m-Y') == $fechaCarbon->format('d-m-Y') and $now->format('H') <= 7) {
                                $valido = true;
                            }
                        }

                        $caso_especial = true;

                        if($participante->id_evento == 7){
                            $caso_especial = false;
                            if(($now->format('d-m-Y') == "06-01-2024" and $now->format('H') >= 22) or $now->format('d-m-Y') == "07-01-2024") {
                                $caso_especial = true;
                            }
                        }else{
                            if($participante->id_evento == 8){
                                $caso_especial = false;
                                if($now->format('d-m-Y') == "06-01-2024" and $now->format('H') < 22) {
                                    $caso_especial = true;
                                }
                            }
                        }

                    }else{
                        // $fecha_prueba = "30-04-2024 14:30"; // testing
                        // $now = \Carbon\Carbon::parse($fecha_prueba); // testing
                        $hora_inicio = \Carbon\Carbon::parse($participante->hora_inicio);
                        $hora_fin = \Carbon\Carbon::parse($participante->hora_fin);

                        $fecha_inicio = \Carbon\Carbon::parse($participante->evento->fecha_inicio);
                        $fecha_fin = \Carbon\Carbon::parse($participante->evento->fecha_fin);
                        if($participante->tipo_entrada == "Solo DJ's"){
                            $fecha_inicio = \Carbon\Carbon::parse($participante->fecha_pedido . ' ' . $participante->hora_inicio);
                        }
                        if($now->gte($fecha_inicio) and $now->lte($fecha_fin)){
                            $validoActividad = true;
                        }

                    }

                    if($validoActividad) {
                        if($checked == 1) {
                            $msg = 'Ya hay entradas validadas';
                            $status = false;
                            Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 2, 'participante_id' => $participante->id ,'created_at' => $now]);
                        } else {
                            $msg = '';
                            $status = true;
                            if($participante->estado_pedido == "validado"){
                                $msg = 'Ya ha sido validado';
                                $status = false;
                                Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 2, 'participante_id' => $participante->id ,'created_at' => $now]);
                            }else{
                                if($fecha == "Bono"){
                                    $bono_participante = BonoParticipante::where('id_participante', $participante->id)->where('fecha', $now->format('d-m-Y'))->first();


                                    if(!$bono_participante and $participante->id_evento != 8){

                                        $bono_participante = new BonoParticipante();
                                        $bono_participante->id_participante = $participante->id;
                                        $bono_participante->fecha = $now->format('d-m-Y');
                                        $bono_participante->save();
                                        $status = true;
                                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'valida', 'entrada_id' => $ref, 'pedido_id' => $pedido->id, 'lector_id' => 2, 'participante_id' => $participante->id ,'created_at' => $now]);
                                    }else{
                                        $status = false;
                                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => $pedido->id, 'lector_id' => 2, 'participante_id' => $participante->id ,'created_at' => $now]);
                                    }
                                }else{
                                    $participante->estado_pedido = 'validado';
                                    $participante->save();
                                    $all_checked = true;
                                    foreach(Participante::where('id_pedido', $pedido->id)->get() as $participante) {
                                        if($participante->estado_pedido != 'validado') {
                                            $all_checked = false;
                                            continue;
                                        }
                                    }

                                    if($all_checked) {
                                        $pedido->checked = 1;
                                        $pedido->save();
                                    }
                                    Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'valida', 'entrada_id' => $ref, 'pedido_id' => $pedido->id, 'lector_id' => 2, 'participante_id' => $participante->id ,'created_at' => $now]);

                                }

                            }
                        }
                    }else{
                        $msg = 'Fecha incorrecta';
                        $status = false;
                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'fecha-incorrecta', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 2, 'participante_id' => $participante->id ,'created_at' => $now]);
                    }
                } else {

                    $msg = 'Ya hay entradas validadas';
                    $status = false;
                    Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => $pedido->id, 'lector_id' => 2, 'participante_id' => $participante->id ,'created_at' => $now]);
                }
            }
        } else {

            Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'no-existe', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 2, 'participante_id' => $participante->id ,'created_at' => $now]);
            $msg = 'El código no es válido';
            $status = false;

        }
        return redirect('/'.request()->slug_instalacion.'/api/escaner2')->with(compact('status'));
    }catch(\Exception $e){
        return redirect()->back()->with('status', false)->with('msg', 'Error, vuelva a leer el código QR');
    }
    }

    public function escaner3(Request $request) {

        $logs = Log::orderBy('id', 'desc')->whereDate('created_at', \Carbon\Carbon::today())->where('lector_id', 3)->get();
        $check = false;
        return view('instalacion.qrs.escaner3')->with(compact('logs', 'check'));
    }

    public function escanear3(Request $request) {
        try{
        $result = [];
        $status = 'failed';
        $msg = '';
        $code = unserialize($request->code)[0];
        $participante_id = unserialize($request->code)[1];
        $participante = Participante::find($participante_id);
        $evento_id = $participante->evento->id;

        $entrada = null;
        $now = \Carbon\Carbon::now();
        $check = true;
        // Comprobar qr de pedido
        if($code != null && (substr($code, 0, 2) == 'na' or substr($code, 0, 2) == 'ac') or substr($code, 0, 2) == 'ev' or substr($code, 0, 2) == 'fj') {
            $ref = $code;
            $pedido = Pedido::find($ref);
            if($pedido == null) {
                $msg = 'El pedido no es válido';
                $status = false;

                Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'no-existe', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 3, 'participante_id' => $participante->id ,'created_at' => $now]);
            } else {

                $fecha = $participante->fecha_pedido;
                $checked = 0;
                $show = false;
                if(request()->slug_instalacion == "villafranca-navidad"){
                    if($fecha == "Bono"){
                        if($now->format('d-m-Y') == "23-12-2023" or $now->format('d-m-Y') == "24-12-2023" or $now->format('d-m-Y') == "30-12-2023" or $now->format('d-m-Y') == "01-01-2024"){
                            $show = true;
                        }
                        if($now->format('d-m-Y') == "25-12-2023" or $now->format('d-m-Y') == "31-12-2023"){
                            if($now->format('H') <= 7){
                                $show = true;
                            }
                        }
                    }
                    if($fecha == '31-12-2023'){
                        $fecha = '01-01-2024';
                    }
                    if($participante->id_evento == 8){
                        if($now->format('d-m-Y') == "06-01-2024" and $now->format('H') < 22) {
                            $show = true;
                        }
                    }else{
                        if(($now->format('d-m-Y') == "06-01-2024" and $now->format('H') >= 22) or $now->format('d-m-Y') == "07-01-2024") {
                            $show = true;
                        }
                    }

                }




                if($pedido->checked == null or $show) {
                    $valido = false;
                    $caso_especial = true;
                    $validoActividad = false;
                    if(request()->slug_instalacion == "villafranca-navidad"){
                        if($fecha != "Bono"){
                            $fechaCarbon = \Carbon\Carbon::createFromFormat('d-m-Y', $fecha);
                            $fechaCarbon->addDays(1);
                            if($now->format('d-m-Y') == $fechaCarbon->format('d-m-Y') and $now->format('H') <= 7) {
                                $valido = true;
                            }
                        }

                        $caso_especial = true;

                        if($participante->id_evento == 7){
                            $caso_especial = false;
                            if(($now->format('d-m-Y') == "06-01-2024" and $now->format('H') >= 22) or $now->format('d-m-Y') == "07-01-2024") {
                                $caso_especial = true;
                            }
                        }else{
                            if($participante->id_evento == 8){
                                $caso_especial = false;
                                if($now->format('d-m-Y') == "06-01-2024" and $now->format('H') < 22) {
                                    $caso_especial = true;
                                }
                            }
                        }

                    }else{
                        // $fecha_prueba = "30-04-2024 14:30"; // testing
                        // $now = \Carbon\Carbon::parse($fecha_prueba); // testing
                        $hora_inicio = \Carbon\Carbon::parse($participante->hora_inicio);
                        $hora_fin = \Carbon\Carbon::parse($participante->hora_fin);

                        $fecha_inicio = \Carbon\Carbon::parse($participante->evento->fecha_inicio);
                        $fecha_fin = \Carbon\Carbon::parse($participante->evento->fecha_fin);
                        if($participante->tipo_entrada == "Solo DJ's"){
                            $fecha_inicio = \Carbon\Carbon::parse($participante->fecha_pedido . ' ' . $participante->hora_inicio);
                        }
                        if($now->gte($fecha_inicio) and $now->lte($fecha_fin)){
                            $validoActividad = true;
                        }

                    }

                    if($validoActividad) {
                        if($checked == 1) {
                            $msg = 'Ya hay entradas validadas';
                            $status = false;
                            Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 3, 'participante_id' => $participante->id ,'created_at' => $now]);
                        } else {
                            $msg = '';
                            $status = true;
                            if($participante->estado_pedido == "validado"){
                                $msg = 'Ya ha sido validado';
                                $status = false;
                                Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 3, 'participante_id' => $participante->id ,'created_at' => $now]);
                            }else{
                                if($fecha == "Bono"){
                                    $bono_participante = BonoParticipante::where('id_participante', $participante->id)->where('fecha', $now->format('d-m-Y'))->first();


                                    if(!$bono_participante and $participante->id_evento != 8){

                                        $bono_participante = new BonoParticipante();
                                        $bono_participante->id_participante = $participante->id;
                                        $bono_participante->fecha = $now->format('d-m-Y');
                                        $bono_participante->save();
                                        $status = true;
                                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'valida', 'entrada_id' => $ref, 'pedido_id' => $pedido->id, 'lector_id' => 3, 'participante_id' => $participante->id ,'created_at' => $now]);
                                    }else{
                                        $status = false;
                                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => $pedido->id, 'lector_id' => 3, 'participante_id' => $participante->id ,'created_at' => $now]);
                                    }
                                }else{
                                    $participante->estado_pedido = 'validado';
                                    $participante->save();
                                    $all_checked = true;
                                    foreach(Participante::where('id_pedido', $pedido->id)->get() as $participante) {
                                        if($participante->estado_pedido != 'validado') {
                                            $all_checked = false;
                                            continue;
                                        }
                                    }

                                    if($all_checked) {
                                        $pedido->checked = 1;
                                        $pedido->save();
                                    }
                                    Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'valida', 'entrada_id' => $ref, 'pedido_id' => $pedido->id, 'lector_id' => 3, 'participante_id' => $participante->id ,'created_at' => $now]);

                                }

                            }
                        }
                    }else{
                        $msg = 'Fecha incorrecta';
                        $status = false;
                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'fecha-incorrecta', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 3, 'participante_id' => $participante->id ,'created_at' => $now]);
                    }
                } else {

                    $msg = 'Ya hay entradas validadas';
                    $status = false;
                    Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => $pedido->id, 'lector_id' => 3, 'participante_id' => $participante->id ,'created_at' => $now]);
                }
            }
        } else {

            Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'no-existe', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 3, 'participante_id' => $participante->id ,'created_at' => $now]);
            $msg = 'El código no es válido';
            $status = false;

        }
        return redirect('/'.request()->slug_instalacion.'/api/escaner3')->with(compact('status'));
    }catch(\Exception $e){
        return redirect()->back()->with('status', false)->with('msg', 'Error, vuelva a leer el código QR');
    }
    }


    public function escaner4(Request $request) {

        $logs = Log::orderBy('id', 'desc')->whereDate('created_at', \Carbon\Carbon::today())->where('lector_id', 4)->get();
        $check = false;
        return view('instalacion.qrs.escaner4')->with(compact('logs', 'check'));
    }

    public function escanear4(Request $request) {
        try{
        $result = [];
        $status = 'failed';
        $msg = '';
        $code = unserialize($request->code)[0];
        $participante_id = unserialize($request->code)[1];
        $participante = Participante::find($participante_id);
        $evento_id = $participante->evento->id;

        $entrada = null;
        $now = \Carbon\Carbon::now();
        $check = true;
        // Comprobar qr de pedido
        if($code != null && (substr($code, 0, 2) == 'na' or substr($code, 0, 2) == 'ac') or substr($code, 0, 2) == 'ev' or substr($code, 0, 2) == 'fj') {
            $ref = $code;
            $pedido = Pedido::find($ref);
            if($pedido == null) {
                $msg = 'El pedido no es válido';
                $status = false;

                Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'no-existe', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 4, 'participante_id' => $participante->id ,'created_at' => $now]);
            } else {

                $fecha = $participante->fecha_pedido;
                $checked = 0;
                $show = false;
                if(request()->slug_instalacion == "villafranca-navidad"){
                    if($fecha == "Bono"){
                        if($now->format('d-m-Y') == "23-12-2023" or $now->format('d-m-Y') == "24-12-2023" or $now->format('d-m-Y') == "30-12-2023" or $now->format('d-m-Y') == "01-01-2024"){
                            $show = true;
                        }
                        if($now->format('d-m-Y') == "25-12-2023" or $now->format('d-m-Y') == "31-12-2023"){
                            if($now->format('H') <= 7){
                                $show = true;
                            }
                        }
                    }
                    if($fecha == '31-12-2023'){
                        $fecha = '01-01-2024';
                    }
                    if($participante->id_evento == 8){
                        if($now->format('d-m-Y') == "06-01-2024" and $now->format('H') < 22) {
                            $show = true;
                        }
                    }else{
                        if(($now->format('d-m-Y') == "06-01-2024" and $now->format('H') >= 22) or $now->format('d-m-Y') == "07-01-2024") {
                            $show = true;
                        }
                    }

                }




                if($pedido->checked == null or $show) {
                    $valido = false;
                    $caso_especial = true;
                    $validoActividad = false;
                    if(request()->slug_instalacion == "villafranca-navidad"){
                        if($fecha != "Bono"){
                            $fechaCarbon = \Carbon\Carbon::createFromFormat('d-m-Y', $fecha);
                            $fechaCarbon->addDays(1);
                            if($now->format('d-m-Y') == $fechaCarbon->format('d-m-Y') and $now->format('H') <= 7) {
                                $valido = true;
                            }
                        }

                        $caso_especial = true;

                        if($participante->id_evento == 7){
                            $caso_especial = false;
                            if(($now->format('d-m-Y') == "06-01-2024" and $now->format('H') >= 22) or $now->format('d-m-Y') == "07-01-2024") {
                                $caso_especial = true;
                            }
                        }else{
                            if($participante->id_evento == 8){
                                $caso_especial = false;
                                if($now->format('d-m-Y') == "06-01-2024" and $now->format('H') < 22) {
                                    $caso_especial = true;
                                }
                            }
                        }

                    }else{
                        // $fecha_prueba = "30-04-2024 14:30"; // testing
                        // $now = \Carbon\Carbon::parse($fecha_prueba); // testing
                        $hora_inicio = \Carbon\Carbon::parse($participante->hora_inicio);
                        $hora_fin = \Carbon\Carbon::parse($participante->hora_fin);

                        $fecha_inicio = \Carbon\Carbon::parse($participante->evento->fecha_inicio);
                        $fecha_fin = \Carbon\Carbon::parse($participante->evento->fecha_fin);
                        if($participante->tipo_entrada == "Solo DJ's"){
                            $fecha_inicio = \Carbon\Carbon::parse($participante->fecha_pedido . ' ' . $participante->hora_inicio);
                        }
                        if($now->gte($fecha_inicio) and $now->lte($fecha_fin)){
                            $validoActividad = true;
                        }

                    }

                    if($validoActividad) {
                        if($checked == 1) {
                            $msg = 'Ya hay entradas validadas';
                            $status = false;
                            Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 4, 'participante_id' => $participante->id ,'created_at' => $now]);
                        } else {
                            $msg = '';
                            $status = true;
                            if($participante->estado_pedido == "validado"){
                                $msg = 'Ya ha sido validado';
                                $status = false;
                                Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 4, 'participante_id' => $participante->id ,'created_at' => $now]);
                            }else{
                                if($fecha == "Bono"){
                                    $bono_participante = BonoParticipante::where('id_participante', $participante->id)->where('fecha', $now->format('d-m-Y'))->first();


                                    if(!$bono_participante and $participante->id_evento != 8){

                                        $bono_participante = new BonoParticipante();
                                        $bono_participante->id_participante = $participante->id;
                                        $bono_participante->fecha = $now->format('d-m-Y');
                                        $bono_participante->save();
                                        $status = true;
                                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'valida', 'entrada_id' => $ref, 'pedido_id' => $pedido->id, 'lector_id' => 4, 'participante_id' => $participante->id ,'created_at' => $now]);
                                    }else{
                                        $status = false;
                                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => $pedido->id, 'lector_id' => 4, 'participante_id' => $participante->id ,'created_at' => $now]);
                                    }
                                }else{
                                    $participante->estado_pedido = 'validado';
                                    $participante->save();
                                    $all_checked = true;
                                    foreach(Participante::where('id_pedido', $pedido->id)->get() as $participante) {
                                        if($participante->estado_pedido != 'validado') {
                                            $all_checked = false;
                                            continue;
                                        }
                                    }

                                    if($all_checked) {
                                        $pedido->checked = 1;
                                        $pedido->save();
                                    }
                                    Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'valida', 'entrada_id' => $ref, 'pedido_id' => $pedido->id, 'lector_id' => 4, 'participante_id' => $participante->id ,'created_at' => $now]);

                                }

                            }
                        }
                    }else{
                        $msg = 'Fecha incorrecta';
                        $status = false;
                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'fecha-incorrecta', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 4, 'participante_id' => $participante->id ,'created_at' => $now]);
                    }
                } else {

                    $msg = 'Ya hay entradas validadas';
                    $status = false;
                    Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => $pedido->id, 'lector_id' => 4, 'participante_id' => $participante->id ,'created_at' => $now]);
                }
            }
        } else {

            Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'no-existe', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 4, 'participante_id' => $participante->id ,'created_at' => $now]);
            $msg = 'El código no es válido';
            $status = false;

        }
        return redirect('/'.request()->slug_instalacion.'/api/escaner4')->with(compact('status'));
    }catch(\Exception $e){
        return redirect()->back()->with('status', false)->with('msg', 'Error, vuelva a leer el código QR');
    }
    }

    public function escaner5(Request $request) {

        $logs = Log::orderBy('id', 'desc')->whereDate('created_at', \Carbon\Carbon::today())->where('lector_id', 5)->get();
        $check = false;
        return view('instalacion.qrs.escaner5')->with(compact('logs', 'check'));
    }

    public function escanear5(Request $request) {
        try{
        $result = [];
        $status = 'failed';
        $msg = '';
        $code = unserialize($request->code)[0];
        $participante_id = unserialize($request->code)[1];
        $participante = Participante::find($participante_id);
        $evento_id = $participante->evento->id;

        $entrada = null;
        $now = \Carbon\Carbon::now();
        $check = true;
        // Comprobar qr de pedido
        if($code != null && (substr($code, 0, 2) == 'na' or substr($code, 0, 2) == 'ac') or substr($code, 0, 2) == 'ev' or substr($code, 0, 2) == 'fj') {
            $ref = $code;
            $pedido = Pedido::find($ref);
            if($pedido == null) {
                $msg = 'El pedido no es válido';
                $status = false;

                Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'no-existe', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 5, 'participante_id' => $participante->id ,'created_at' => $now]);
            } else {

                $fecha = $participante->fecha_pedido;
                $checked = 0;
                $show = false;
                if(request()->slug_instalacion == "villafranca-navidad"){
                    if($fecha == "Bono"){
                        if($now->format('d-m-Y') == "23-12-2023" or $now->format('d-m-Y') == "24-12-2023" or $now->format('d-m-Y') == "30-12-2023" or $now->format('d-m-Y') == "01-01-2024"){
                            $show = true;
                        }
                        if($now->format('d-m-Y') == "25-12-2023" or $now->format('d-m-Y') == "31-12-2023"){
                            if($now->format('H') <= 7){
                                $show = true;
                            }
                        }
                    }
                    if($fecha == '31-12-2023'){
                        $fecha = '01-01-2024';
                    }
                    if($participante->id_evento == 8){
                        if($now->format('d-m-Y') == "06-01-2024" and $now->format('H') < 22) {
                            $show = true;
                        }
                    }else{
                        if(($now->format('d-m-Y') == "06-01-2024" and $now->format('H') >= 22) or $now->format('d-m-Y') == "07-01-2024") {
                            $show = true;
                        }
                    }

                }




                if($pedido->checked == null or $show) {
                    $valido = false;
                    $caso_especial = true;
                    $validoActividad = false;
                    if(request()->slug_instalacion == "villafranca-navidad"){
                        if($fecha != "Bono"){
                            $fechaCarbon = \Carbon\Carbon::createFromFormat('d-m-Y', $fecha);
                            $fechaCarbon->addDays(1);
                            if($now->format('d-m-Y') == $fechaCarbon->format('d-m-Y') and $now->format('H') <= 7) {
                                $valido = true;
                            }
                        }

                        $caso_especial = true;

                        if($participante->id_evento == 7){
                            $caso_especial = false;
                            if(($now->format('d-m-Y') == "06-01-2024" and $now->format('H') >= 22) or $now->format('d-m-Y') == "07-01-2024") {
                                $caso_especial = true;
                            }
                        }else{
                            if($participante->id_evento == 8){
                                $caso_especial = false;
                                if($now->format('d-m-Y') == "06-01-2024" and $now->format('H') < 22) {
                                    $caso_especial = true;
                                }
                            }
                        }

                    }else{
                        // $fecha_prueba = "30-04-2024 14:30"; // testing
                        // $now = \Carbon\Carbon::parse($fecha_prueba); // testing
                        $hora_inicio = \Carbon\Carbon::parse($participante->hora_inicio);
                        $hora_fin = \Carbon\Carbon::parse($participante->hora_fin);

                        $fecha_inicio = \Carbon\Carbon::parse($participante->evento->fecha_inicio);
                        $fecha_fin = \Carbon\Carbon::parse($participante->evento->fecha_fin);
                        if($participante->tipo_entrada == "Solo DJ's"){
                            $fecha_inicio = \Carbon\Carbon::parse($participante->fecha_pedido . ' ' . $participante->hora_inicio);
                        }
                        if($now->gte($fecha_inicio) and $now->lte($fecha_fin)){
                            $validoActividad = true;
                        }

                    }

                    if($validoActividad) {
                        if($checked == 1) {
                            $msg = 'Ya hay entradas validadas';
                            $status = false;
                            Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 5, 'participante_id' => $participante->id ,'created_at' => $now]);
                        } else {
                            $msg = '';
                            $status = true;
                            if($participante->estado_pedido == "validado"){
                                $msg = 'Ya ha sido validado';
                                $status = false;
                                Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 5, 'participante_id' => $participante->id ,'created_at' => $now]);
                            }else{
                                if($fecha == "Bono"){
                                    $bono_participante = BonoParticipante::where('id_participante', $participante->id)->where('fecha', $now->format('d-m-Y'))->first();


                                    if(!$bono_participante and $participante->id_evento != 8){

                                        $bono_participante = new BonoParticipante();
                                        $bono_participante->id_participante = $participante->id;
                                        $bono_participante->fecha = $now->format('d-m-Y');
                                        $bono_participante->save();
                                        $status = true;
                                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'valida', 'entrada_id' => $ref, 'pedido_id' => $pedido->id, 'lector_id' => 5, 'participante_id' => $participante->id ,'created_at' => $now]);
                                    }else{
                                        $status = false;
                                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => $pedido->id, 'lector_id' => 5, 'participante_id' => $participante->id ,'created_at' => $now]);
                                    }
                                }else{
                                    $participante->estado_pedido = 'validado';
                                    $participante->save();
                                    $all_checked = true;
                                    foreach(Participante::where('id_pedido', $pedido->id)->get() as $participante) {
                                        if($participante->estado_pedido != 'validado') {
                                            $all_checked = false;
                                            continue;
                                        }
                                    }

                                    if($all_checked) {
                                        $pedido->checked = 1;
                                        $pedido->save();
                                    }
                                    Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'valida', 'entrada_id' => $ref, 'pedido_id' => $pedido->id, 'lector_id' => 5, 'participante_id' => $participante->id ,'created_at' => $now]);

                                }

                            }
                        }
                    }else{
                        $msg = 'Fecha incorrecta';
                        $status = false;
                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'fecha-incorrecta', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 5, 'participante_id' => $participante->id ,'created_at' => $now]);
                    }
                } else {

                    $msg = 'Ya hay entradas validadas';
                    $status = false;
                    Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => $pedido->id, 'lector_id' => 5, 'participante_id' => $participante->id ,'created_at' => $now]);
                }
            }
        } else {

            Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'no-existe', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 5, 'participante_id' => $participante->id ,'created_at' => $now]);
            $msg = 'El código no es válido';
            $status = false;

        }
        return redirect('/'.request()->slug_instalacion.'/api/escaner5')->with(compact('status'));
    }catch(\Exception $e){
        return redirect()->back()->with('status', false)->with('msg', 'Error, vuelva a leer el código QR');
    }
    }


    public function escaner6(Request $request) {

        $logs = Log::orderBy('id', 'desc')->whereDate('created_at', \Carbon\Carbon::today())->where('lector_id', 6)->get();
        $check = false;
        return view('instalacion.qrs.escaner6')->with(compact('logs', 'check'));
    }

    public function escanear6(Request $request) {
        try{
        $result = [];
        $status = 'failed';
        $msg = '';
        $code = unserialize($request->code)[0];
        $participante_id = unserialize($request->code)[1];
        $participante = Participante::find($participante_id);
        $evento_id = $participante->evento->id;

        $entrada = null;
        $now = \Carbon\Carbon::now();
        $check = true;
        // Comprobar qr de pedido
        if($code != null && (substr($code, 0, 2) == 'na' or substr($code, 0, 2) == 'ac') or substr($code, 0, 2) == 'ev' or substr($code, 0, 2) == 'fj') {
            $ref = $code;
            $pedido = Pedido::find($ref);
            if($pedido == null) {
                $msg = 'El pedido no es válido';
                $status = false;

                Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'no-existe', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 6, 'participante_id' => $participante->id ,'created_at' => $now]);
            } else {

                $fecha = $participante->fecha_pedido;
                $checked = 0;
                $show = false;
                if(request()->slug_instalacion == "villafranca-navidad"){
                    if($fecha == "Bono"){
                        if($now->format('d-m-Y') == "23-12-2023" or $now->format('d-m-Y') == "24-12-2023" or $now->format('d-m-Y') == "30-12-2023" or $now->format('d-m-Y') == "01-01-2024"){
                            $show = true;
                        }
                        if($now->format('d-m-Y') == "25-12-2023" or $now->format('d-m-Y') == "31-12-2023"){
                            if($now->format('H') <= 7){
                                $show = true;
                            }
                        }
                    }
                    if($fecha == '31-12-2023'){
                        $fecha = '01-01-2024';
                    }
                    if($participante->id_evento == 8){
                        if($now->format('d-m-Y') == "06-01-2024" and $now->format('H') < 22) {
                            $show = true;
                        }
                    }else{
                        if(($now->format('d-m-Y') == "06-01-2024" and $now->format('H') >= 22) or $now->format('d-m-Y') == "07-01-2024") {
                            $show = true;
                        }
                    }

                }




                if($pedido->checked == null or $show) {
                    $valido = false;
                    $caso_especial = true;
                    $validoActividad = false;
                    if(request()->slug_instalacion == "villafranca-navidad"){
                        if($fecha != "Bono"){
                            $fechaCarbon = \Carbon\Carbon::createFromFormat('d-m-Y', $fecha);
                            $fechaCarbon->addDays(1);
                            if($now->format('d-m-Y') == $fechaCarbon->format('d-m-Y') and $now->format('H') <= 7) {
                                $valido = true;
                            }
                        }

                        $caso_especial = true;

                        if($participante->id_evento == 7){
                            $caso_especial = false;
                            if(($now->format('d-m-Y') == "06-01-2024" and $now->format('H') >= 22) or $now->format('d-m-Y') == "07-01-2024") {
                                $caso_especial = true;
                            }
                        }else{
                            if($participante->id_evento == 8){
                                $caso_especial = false;
                                if($now->format('d-m-Y') == "06-01-2024" and $now->format('H') < 22) {
                                    $caso_especial = true;
                                }
                            }
                        }

                    }else{
                        // $fecha_prueba = "30-04-2024 14:30"; // testing
                        // $now = \Carbon\Carbon::parse($fecha_prueba); // testing
                        $hora_inicio = \Carbon\Carbon::parse($participante->hora_inicio);
                        $hora_fin = \Carbon\Carbon::parse($participante->hora_fin);

                        $fecha_inicio = \Carbon\Carbon::parse($participante->evento->fecha_inicio);
                        $fecha_fin = \Carbon\Carbon::parse($participante->evento->fecha_fin);
                        if($participante->tipo_entrada == "Solo DJ's"){
                            $fecha_inicio = \Carbon\Carbon::parse($participante->fecha_pedido . ' ' . $participante->hora_inicio);
                        }
                        if($now->gte($fecha_inicio) and $now->lte($fecha_fin)){
                            $validoActividad = true;
                        }

                    }

                    if($validoActividad) {
                        if($checked == 1) {
                            $msg = 'Ya hay entradas validadas';
                            $status = false;
                            Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 6, 'participante_id' => $participante->id ,'created_at' => $now]);
                        } else {
                            $msg = '';
                            $status = true;
                            if($participante->estado_pedido == "validado"){
                                $msg = 'Ya ha sido validado';
                                $status = false;
                                Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 6, 'participante_id' => $participante->id ,'created_at' => $now]);
                            }else{
                                if($fecha == "Bono"){
                                    $bono_participante = BonoParticipante::where('id_participante', $participante->id)->where('fecha', $now->format('d-m-Y'))->first();


                                    if(!$bono_participante and $participante->id_evento != 8){

                                        $bono_participante = new BonoParticipante();
                                        $bono_participante->id_participante = $participante->id;
                                        $bono_participante->fecha = $now->format('d-m-Y');
                                        $bono_participante->save();
                                        $status = true;
                                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'valida', 'entrada_id' => $ref, 'pedido_id' => $pedido->id, 'lector_id' => 6, 'participante_id' => $participante->id ,'created_at' => $now]);
                                    }else{
                                        $status = false;
                                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => $pedido->id, 'lector_id' => 6, 'participante_id' => $participante->id ,'created_at' => $now]);
                                    }
                                }else{
                                    $participante->estado_pedido = 'validado';
                                    $participante->save();
                                    $all_checked = true;
                                    foreach(Participante::where('id_pedido', $pedido->id)->get() as $participante) {
                                        if($participante->estado_pedido != 'validado') {
                                            $all_checked = false;
                                            continue;
                                        }
                                    }

                                    if($all_checked) {
                                        $pedido->checked = 1;
                                        $pedido->save();
                                    }
                                    Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'valida', 'entrada_id' => $ref, 'pedido_id' => $pedido->id, 'lector_id' => 6, 'participante_id' => $participante->id ,'created_at' => $now]);

                                }

                            }
                        }
                    }else{
                        $msg = 'Fecha incorrecta';
                        $status = false;
                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'fecha-incorrecta', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 6, 'participante_id' => $participante->id ,'created_at' => $now]);
                    }
                } else {

                    $msg = 'Ya hay entradas validadas';
                    $status = false;
                    Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => $pedido->id, 'lector_id' => 6, 'participante_id' => $participante->id ,'created_at' => $now]);
                }
            }
        } else {

            Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'no-existe', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 6, 'participante_id' => $participante->id ,'created_at' => $now]);
            $msg = 'El código no es válido';
            $status = false;

        }
        return redirect('/'.request()->slug_instalacion.'/api/escaner6')->with(compact('status'));
        }catch(\Exception $e){
            return redirect()->back()->with('status', false)->with('msg', 'Error, vuelva a leer el código QR');
        }
    }

    public function escaner7(Request $request) {

        $logs = Log::orderBy('id', 'desc')->whereDate('created_at', \Carbon\Carbon::today())->where('lector_id', 7)->get();
        $check = false;
        return view('instalacion.qrs.escaner7')->with(compact('logs', 'check'));
    }

    public function escanear7(Request $request) {
        try{
        $result = [];
        $status = 'failed';
        $msg = '';
        $code = unserialize($request->code)[0];
        $participante_id = unserialize($request->code)[1];
        $participante = Participante::find($participante_id);
        $evento_id = $participante->evento->id;

        $entrada = null;
        $now = \Carbon\Carbon::now();
        $check = true;
        // Comprobar qr de pedido
        if($code != null && (substr($code, 0, 2) == 'na' or substr($code, 0, 2) == 'ac') or substr($code, 0, 2) == 'ev' or substr($code, 0, 2) == 'fj') {
            $ref = $code;
            $pedido = Pedido::find($ref);
            if($pedido == null) {
                $msg = 'El pedido no es válido';
                $status = false;

                Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'no-existe', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 7, 'participante_id' => $participante->id ,'created_at' => $now]);
            } else {

                $fecha = $participante->fecha_pedido;
                $checked = 0;
                $show = false;
                if(request()->slug_instalacion == "villafranca-navidad"){
                    if($fecha == "Bono"){
                        if($now->format('d-m-Y') == "23-12-2023" or $now->format('d-m-Y') == "24-12-2023" or $now->format('d-m-Y') == "30-12-2023" or $now->format('d-m-Y') == "01-01-2024"){
                            $show = true;
                        }
                        if($now->format('d-m-Y') == "25-12-2023" or $now->format('d-m-Y') == "31-12-2023"){
                            if($now->format('H') <= 7){
                                $show = true;
                            }
                        }
                    }
                    if($fecha == '31-12-2023'){
                        $fecha = '01-01-2024';
                    }
                    if($participante->id_evento == 8){
                        if($now->format('d-m-Y') == "06-01-2024" and $now->format('H') < 22) {
                            $show = true;
                        }
                    }else{
                        if(($now->format('d-m-Y') == "06-01-2024" and $now->format('H') >= 22) or $now->format('d-m-Y') == "07-01-2024") {
                            $show = true;
                        }
                    }

                }




                if($pedido->checked == null or $show) {
                    $valido = false;
                    $caso_especial = true;
                    $validoActividad = false;
                    if(request()->slug_instalacion == "villafranca-navidad"){
                        if($fecha != "Bono"){
                            $fechaCarbon = \Carbon\Carbon::createFromFormat('d-m-Y', $fecha);
                            $fechaCarbon->addDays(1);
                            if($now->format('d-m-Y') == $fechaCarbon->format('d-m-Y') and $now->format('H') <= 7) {
                                $valido = true;
                            }
                        }

                        $caso_especial = true;

                        if($participante->id_evento == 7){
                            $caso_especial = false;
                            if(($now->format('d-m-Y') == "06-01-2024" and $now->format('H') >= 22) or $now->format('d-m-Y') == "07-01-2024") {
                                $caso_especial = true;
                            }
                        }else{
                            if($participante->id_evento == 8){
                                $caso_especial = false;
                                if($now->format('d-m-Y') == "06-01-2024" and $now->format('H') < 22) {
                                    $caso_especial = true;
                                }
                            }
                        }

                    }else{
                        // $fecha_prueba = "30-04-2024 14:30"; // testing
                        // $now = \Carbon\Carbon::parse($fecha_prueba); // testing
                        $hora_inicio = \Carbon\Carbon::parse($participante->hora_inicio);
                        $hora_fin = \Carbon\Carbon::parse($participante->hora_fin);

                        $fecha_inicio = \Carbon\Carbon::parse($participante->evento->fecha_inicio);
                        $fecha_fin = \Carbon\Carbon::parse($participante->evento->fecha_fin);
                        if($participante->tipo_entrada == "Solo DJ's"){
                            $fecha_inicio = \Carbon\Carbon::parse($participante->fecha_pedido . ' ' . $participante->hora_inicio);
                        }
                        if($now->gte($fecha_inicio) and $now->lte($fecha_fin)){
                            $validoActividad = true;
                        }

                    }

                    if($validoActividad) {
                        if($checked == 1) {
                            $msg = 'Ya hay entradas validadas';
                            $status = false;
                            Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 7, 'participante_id' => $participante->id ,'created_at' => $now]);
                        } else {
                            $msg = '';
                            $status = true;
                            if($participante->estado_pedido == "validado"){
                                $msg = 'Ya ha sido validado';
                                $status = false;
                                Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 7, 'participante_id' => $participante->id ,'created_at' => $now]);
                            }else{
                                if($fecha == "Bono"){
                                    $bono_participante = BonoParticipante::where('id_participante', $participante->id)->where('fecha', $now->format('d-m-Y'))->first();


                                    if(!$bono_participante and $participante->id_evento != 8){

                                        $bono_participante = new BonoParticipante();
                                        $bono_participante->id_participante = $participante->id;
                                        $bono_participante->fecha = $now->format('d-m-Y');
                                        $bono_participante->save();
                                        $status = true;
                                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'valida', 'entrada_id' => $ref, 'pedido_id' => $pedido->id, 'lector_id' => 7, 'participante_id' => $participante->id ,'created_at' => $now]);
                                    }else{
                                        $status = false;
                                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => $pedido->id, 'lector_id' => 7, 'participante_id' => $participante->id ,'created_at' => $now]);
                                    }
                                }else{
                                    $participante->estado_pedido = 'validado';
                                    $participante->save();
                                    $all_checked = true;
                                    foreach(Participante::where('id_pedido', $pedido->id)->get() as $participante) {
                                        if($participante->estado_pedido != 'validado') {
                                            $all_checked = false;
                                            continue;
                                        }
                                    }

                                    if($all_checked) {
                                        $pedido->checked = 1;
                                        $pedido->save();
                                    }
                                    Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'valida', 'entrada_id' => $ref, 'pedido_id' => $pedido->id, 'lector_id' => 7, 'participante_id' => $participante->id ,'created_at' => $now]);

                                }

                            }
                        }
                    }else{
                        $msg = 'Fecha incorrecta';
                        $status = false;
                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'fecha-incorrecta', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 7, 'participante_id' => $participante->id ,'created_at' => $now]);
                    }
                } else {

                    $msg = 'Ya hay entradas validadas';
                    $status = false;
                    Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => $pedido->id, 'lector_id' => 7, 'participante_id' => $participante->id ,'created_at' => $now]);
                }
            }
        } else {

            Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'no-existe', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 7, 'participante_id' => $participante->id ,'created_at' => $now]);
            $msg = 'El código no es válido';
            $status = false;

        }
        return redirect('/'.request()->slug_instalacion.'/api/escaner7')->with(compact('status'));
    }catch(\Exception $e){
        return redirect()->back()->with('status', false)->with('msg', 'Error, vuelva a leer el código QR');
    }
    }


    public function escaner8(Request $request) {

        $logs = Log::orderBy('id', 'desc')->whereDate('created_at', \Carbon\Carbon::today())->where('lector_id', 8)->get();
        $check = false;
        return view('instalacion.qrs.escaner8')->with(compact('logs', 'check'));
    }

    public function escanear8(Request $request) {
        try{
        $result = [];
        $status = 'failed';
        $msg = '';
        $code = unserialize($request->code)[0];
        $participante_id = unserialize($request->code)[1];
        $participante = Participante::find($participante_id);
        $evento_id = $participante->evento->id;

        $entrada = null;
        $now = \Carbon\Carbon::now();
        $check = true;
        // Comprobar qr de pedido
        if($code != null && (substr($code, 0, 2) == 'na' or substr($code, 0, 2) == 'ac') or substr($code, 0, 2) == 'ev' or substr($code, 0, 2) == 'fj') {
            $ref = $code;
            $pedido = Pedido::find($ref);
            if($pedido == null) {
                $msg = 'El pedido no es válido';
                $status = false;

                Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'no-existe', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 8, 'participante_id' => $participante->id ,'created_at' => $now]);
            } else {

                $fecha = $participante->fecha_pedido;
                $checked = 0;
                $show = false;
                if(request()->slug_instalacion == "villafranca-navidad"){
                    if($fecha == "Bono"){
                        if($now->format('d-m-Y') == "23-12-2023" or $now->format('d-m-Y') == "24-12-2023" or $now->format('d-m-Y') == "30-12-2023" or $now->format('d-m-Y') == "01-01-2024"){
                            $show = true;
                        }
                        if($now->format('d-m-Y') == "25-12-2023" or $now->format('d-m-Y') == "31-12-2023"){
                            if($now->format('H') <= 7){
                                $show = true;
                            }
                        }
                    }
                    if($fecha == '31-12-2023'){
                        $fecha = '01-01-2024';
                    }
                    if($participante->id_evento == 8){
                        if($now->format('d-m-Y') == "06-01-2024" and $now->format('H') < 22) {
                            $show = true;
                        }
                    }else{
                        if(($now->format('d-m-Y') == "06-01-2024" and $now->format('H') >= 22) or $now->format('d-m-Y') == "07-01-2024") {
                            $show = true;
                        }
                    }

                }




                if($pedido->checked == null or $show) {
                    $valido = false;
                    $caso_especial = true;
                    $validoActividad = false;
                    if(request()->slug_instalacion == "villafranca-navidad"){
                        if($fecha != "Bono"){
                            $fechaCarbon = \Carbon\Carbon::createFromFormat('d-m-Y', $fecha);
                            $fechaCarbon->addDays(1);
                            if($now->format('d-m-Y') == $fechaCarbon->format('d-m-Y') and $now->format('H') <= 7) {
                                $valido = true;
                            }
                        }

                        $caso_especial = true;

                        if($participante->id_evento == 7){
                            $caso_especial = false;
                            if(($now->format('d-m-Y') == "06-01-2024" and $now->format('H') >= 22) or $now->format('d-m-Y') == "07-01-2024") {
                                $caso_especial = true;
                            }
                        }else{
                            if($participante->id_evento == 8){
                                $caso_especial = false;
                                if($now->format('d-m-Y') == "06-01-2024" and $now->format('H') < 22) {
                                    $caso_especial = true;
                                }
                            }
                        }

                    }else{
                        // $fecha_prueba = "30-04-2024 14:30"; // testing
                        // $now = \Carbon\Carbon::parse($fecha_prueba); // testing
                        $hora_inicio = \Carbon\Carbon::parse($participante->hora_inicio);
                        $hora_fin = \Carbon\Carbon::parse($participante->hora_fin);

                        $fecha_inicio = \Carbon\Carbon::parse($participante->evento->fecha_inicio);
                        $fecha_fin = \Carbon\Carbon::parse($participante->evento->fecha_fin);
                        if($participante->tipo_entrada == "Solo DJ's"){
                            $fecha_inicio = \Carbon\Carbon::parse($participante->fecha_pedido . ' ' . $participante->hora_inicio);
                        }
                        if($now->gte($fecha_inicio) and $now->lte($fecha_fin)){
                            $validoActividad = true;
                        }

                    }

                    if($validoActividad) {
                        if($checked == 1) {
                            $msg = 'Ya hay entradas validadas';
                            $status = false;
                            Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 8, 'participante_id' => $participante->id ,'created_at' => $now]);
                        } else {
                            $msg = '';
                            $status = true;
                            if($participante->estado_pedido == "validado"){
                                $msg = 'Ya ha sido validado';
                                $status = false;
                                Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 8, 'participante_id' => $participante->id ,'created_at' => $now]);
                            }else{
                                if($fecha == "Bono"){
                                    $bono_participante = BonoParticipante::where('id_participante', $participante->id)->where('fecha', $now->format('d-m-Y'))->first();


                                    if(!$bono_participante and $participante->id_evento != 8){

                                        $bono_participante = new BonoParticipante();
                                        $bono_participante->id_participante = $participante->id;
                                        $bono_participante->fecha = $now->format('d-m-Y');
                                        $bono_participante->save();
                                        $status = true;
                                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'valida', 'entrada_id' => $ref, 'pedido_id' => $pedido->id, 'lector_id' => 8, 'participante_id' => $participante->id ,'created_at' => $now]);
                                    }else{
                                        $status = false;
                                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => $pedido->id, 'lector_id' => 8, 'participante_id' => $participante->id ,'created_at' => $now]);
                                    }
                                }else{
                                    $participante->estado_pedido = 'validado';
                                    $participante->save();
                                    $all_checked = true;
                                    foreach(Participante::where('id_pedido', $pedido->id)->get() as $participante) {
                                        if($participante->estado_pedido != 'validado') {
                                            $all_checked = false;
                                            continue;
                                        }
                                    }

                                    if($all_checked) {
                                        $pedido->checked = 1;
                                        $pedido->save();
                                    }
                                    Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'valida', 'entrada_id' => $ref, 'pedido_id' => $pedido->id, 'lector_id' => 8, 'participante_id' => $participante->id ,'created_at' => $now]);

                                }

                            }
                        }
                    }else{
                        $msg = 'Fecha incorrecta';
                        $status = false;
                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'fecha-incorrecta', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 8, 'participante_id' => $participante->id ,'created_at' => $now]);
                    }
                } else {

                    $msg = 'Ya hay entradas validadas';
                    $status = false;
                    Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => $pedido->id, 'lector_id' => 8, 'participante_id' => $participante->id ,'created_at' => $now]);
                }
            }
        } else {

            Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'no-existe', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 8, 'participante_id' => $participante->id ,'created_at' => $now]);
            $msg = 'El código no es válido';
            $status = false;

        }
        return redirect('/'.request()->slug_instalacion.'/api/escaner8')->with(compact('status'));
    }catch(\Exception $e){
        return redirect()->back()->with('status', false)->with('msg', 'Error, vuelva a leer el código QR');
    }
    }

    public function escaner9(Request $request) {

        $logs = Log::orderBy('id', 'desc')->whereDate('created_at', \Carbon\Carbon::today())->where('lector_id', 9)->get();
        $check = false;
        return view('instalacion.qrs.escaner9')->with(compact('logs', 'check'));
    }

    public function escanear9(Request $request) {
        try{
        $result = [];
        $status = 'failed';
        $msg = '';
        $code = unserialize($request->code)[0];
        $participante_id = unserialize($request->code)[1];
        $participante = Participante::find($participante_id);
        $evento_id = $participante->evento->id;

        $entrada = null;
        $now = \Carbon\Carbon::now();
        $check = true;
        // Comprobar qr de pedido
        if($code != null && (substr($code, 0, 2) == 'na' or substr($code, 0, 2) == 'ac') or substr($code, 0, 2) == 'ev' or substr($code, 0, 2) == 'fj') {
            $ref = $code;
            $pedido = Pedido::find($ref);
            if($pedido == null) {
                $msg = 'El pedido no es válido';
                $status = false;

                Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'no-existe', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 9, 'participante_id' => $participante->id ,'created_at' => $now]);
            } else {

                $fecha = $participante->fecha_pedido;
                $checked = 0;
                $show = false;
                if(request()->slug_instalacion == "villafranca-navidad"){
                    if($fecha == "Bono"){
                        if($now->format('d-m-Y') == "23-12-2023" or $now->format('d-m-Y') == "24-12-2023" or $now->format('d-m-Y') == "30-12-2023" or $now->format('d-m-Y') == "01-01-2024"){
                            $show = true;
                        }
                        if($now->format('d-m-Y') == "25-12-2023" or $now->format('d-m-Y') == "31-12-2023"){
                            if($now->format('H') <= 7){
                                $show = true;
                            }
                        }
                    }
                    if($fecha == '31-12-2023'){
                        $fecha = '01-01-2024';
                    }
                    if($participante->id_evento == 8){
                        if($now->format('d-m-Y') == "06-01-2024" and $now->format('H') < 22) {
                            $show = true;
                        }
                    }else{
                        if(($now->format('d-m-Y') == "06-01-2024" and $now->format('H') >= 22) or $now->format('d-m-Y') == "07-01-2024") {
                            $show = true;
                        }
                    }

                }




                if($pedido->checked == null or $show) {
                    $valido = false;
                    $caso_especial = true;
                    $validoActividad = false;
                    if(request()->slug_instalacion == "villafranca-navidad"){
                        if($fecha != "Bono"){
                            $fechaCarbon = \Carbon\Carbon::createFromFormat('d-m-Y', $fecha);
                            $fechaCarbon->addDays(1);
                            if($now->format('d-m-Y') == $fechaCarbon->format('d-m-Y') and $now->format('H') <= 7) {
                                $valido = true;
                            }
                        }

                        $caso_especial = true;

                        if($participante->id_evento == 7){
                            $caso_especial = false;
                            if(($now->format('d-m-Y') == "06-01-2024" and $now->format('H') >= 22) or $now->format('d-m-Y') == "07-01-2024") {
                                $caso_especial = true;
                            }
                        }else{
                            if($participante->id_evento == 8){
                                $caso_especial = false;
                                if($now->format('d-m-Y') == "06-01-2024" and $now->format('H') < 22) {
                                    $caso_especial = true;
                                }
                            }
                        }

                    }else{
                        // $fecha_prueba = "30-04-2024 14:30"; // testing
                        // $now = \Carbon\Carbon::parse($fecha_prueba); // testing
                        $hora_inicio = \Carbon\Carbon::parse($participante->hora_inicio);
                        $hora_fin = \Carbon\Carbon::parse($participante->hora_fin);

                        $fecha_inicio = \Carbon\Carbon::parse($participante->evento->fecha_inicio);
                        $fecha_fin = \Carbon\Carbon::parse($participante->evento->fecha_fin);
                        if($participante->tipo_entrada == "Solo DJ's"){
                            $fecha_inicio = \Carbon\Carbon::parse($participante->fecha_pedido . ' ' . $participante->hora_inicio);
                        }
                        if($now->gte($fecha_inicio) and $now->lte($fecha_fin)){
                            $validoActividad = true;
                        }

                    }

                    if($validoActividad) {
                        if($checked == 1) {
                            $msg = 'Ya hay entradas validadas';
                            $status = false;
                            Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 9, 'participante_id' => $participante->id ,'created_at' => $now]);
                        } else {
                            $msg = '';
                            $status = true;
                            if($participante->estado_pedido == "validado"){
                                $msg = 'Ya ha sido validado';
                                $status = false;
                                Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 9, 'participante_id' => $participante->id ,'created_at' => $now]);
                            }else{
                                if($fecha == "Bono"){
                                    $bono_participante = BonoParticipante::where('id_participante', $participante->id)->where('fecha', $now->format('d-m-Y'))->first();


                                    if(!$bono_participante and $participante->id_evento != 8){

                                        $bono_participante = new BonoParticipante();
                                        $bono_participante->id_participante = $participante->id;
                                        $bono_participante->fecha = $now->format('d-m-Y');
                                        $bono_participante->save();
                                        $status = true;
                                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'valida', 'entrada_id' => $ref, 'pedido_id' => $pedido->id, 'lector_id' => 9, 'participante_id' => $participante->id ,'created_at' => $now]);
                                    }else{
                                        $status = false;
                                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => $pedido->id, 'lector_id' => 9, 'participante_id' => $participante->id ,'created_at' => $now]);
                                    }
                                }else{
                                    $participante->estado_pedido = 'validado';
                                    $participante->save();
                                    $all_checked = true;
                                    foreach(Participante::where('id_pedido', $pedido->id)->get() as $participante) {
                                        if($participante->estado_pedido != 'validado') {
                                            $all_checked = false;
                                            continue;
                                        }
                                    }

                                    if($all_checked) {
                                        $pedido->checked = 1;
                                        $pedido->save();
                                    }
                                    Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'valida', 'entrada_id' => $ref, 'pedido_id' => $pedido->id, 'lector_id' => 9, 'participante_id' => $participante->id ,'created_at' => $now]);

                                }

                            }
                        }
                    }else{
                        $msg = 'Fecha incorrecta';
                        $status = false;
                        Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'fecha-incorrecta', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 9, 'participante_id' => $participante->id ,'created_at' => $now]);
                    }
                } else {

                    $msg = 'Ya hay entradas validadas';
                    $status = false;
                    Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'ya validada', 'entrada_id' => null, 'pedido_id' => $pedido->id, 'lector_id' => 9, 'participante_id' => $participante->id ,'created_at' => $now]);
                }
            }
        } else {

            Log::insert(['code' => $code, 'evento_id' => $evento_id, 'status' => 'no-existe', 'entrada_id' => null, 'pedido_id' => null, 'lector_id' => 9, 'participante_id' => $participante->id ,'created_at' => $now]);
            $msg = 'El código no es válido';
            $status = false;

        }
        return redirect('/'.request()->slug_instalacion.'/api/escaner9')->with(compact('status'));
    }catch(\Exception $e){
        return redirect()->back()->with('status', false)->with('msg', 'Error, vuelva a leer el código QR');
    }
    }

}
