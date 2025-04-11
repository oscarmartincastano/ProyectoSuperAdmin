<?php

namespace App\Http\Controllers;

use App\Mail\RenovarServicio;
use App\Models\Desactivacion_reserva;
use App\Models\Servicio;
use Illuminate\Http\Request;
use IlluminateHttpRequest;
use Ssheduardo\Redsys\Facades\Redsys;
use App\Http\Requests;
use App\Mail\NewInscripcion;
use App\Mail\NewServicio;
use App\Mail\NewBonoUsuario;
use App\Models\Instalacion;
use App\Models\Pedido;
use App\Models\User;
use App\Models\Pista;
use App\Models\Reserva;
use App\Models\Servicio_Usuario;
use App\Models\Recibo;
use \Carbon\Carbon;
use Cookie;
use Illuminate\Support\Facades\DB;
use App\Models\Valor_campo_personalizado;
use App\Mail\NewReserva;
use App\Mail\RenovarServicioFallido;
use App\Models\Evento;
use App\Models\Participante;
use App\Models\Participante_eventos_mes;
use App\Models\Acceso;
use App\Models\Bono;
use App\Models\BonoUsuario;
use Illuminate\Support\Facades\Mail;
use Sermepa\Tpv\Tpv;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Accion;


class RedsysController extends Controller
{
    public function index(Request $request)
    {
        try{

            $key = config('redsys.key_'.$request->slug_instalacion);
            Redsys::setMerchantcode(config('redsys.merchantcode_'.$request->slug_instalacion));
            Redsys::setEnviroment(config('redsys.env_'.$request->slug_instalacion));

            Redsys::setCurrency(config('redsys.currency'));
            Redsys::setTransactiontype(config('redsys.transactionType'));
            Redsys::setTerminal(config('redsys.terminal'));
            Redsys::setMethod(config('redsys.method'));

            $notif_url = 'https://'.$_SERVER['HTTP_HOST'].'/'.$request->slug_instalacion.'/notificacion';
            Redsys::setNotification($notif_url);




            $ok= 'https://'.$_SERVER['HTTP_HOST'].'/'.$request->slug_instalacion.'/ok';
            $ko= 'https://'.$_SERVER['HTTP_HOST'].'/'.$request->slug_instalacion.'/ko';
            Redsys::setUrlOk($ok);
            Redsys::setUrlKo($ko);
            Redsys::setVersion(config('redsys.version'));
            Redsys::setTradeName(config('redsys.tradename'));
            Redsys::setTitular(config('redsys.titular'));

            //Redsys::set3DS("{'threeDSInfo':'CardData'}");

            /* Redsys::setOrder(generateOrderID()); */
            Redsys::setOrder('52859qu1T59Y');
            Redsys::setProductDescription('Prueba');
            Redsys::setAmount(1);

            $signature = Redsys::generateMerchantSignature($key);
            Redsys::setMerchantSignature($signature);

            $form = Redsys::createForm();
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        return $form;
    }

    /********************************************** Pago reservas *******************************************************/
    public function pago(Request $request)
    {

        $pedido_id = self::reserva($request);
        if (!$pedido_id) {
            return redirect()->back()->with('error', 'No se ha completado la reserva porque nos se han seleccionado entradas.');
        }
        $pedido = Pedido::find($pedido_id);
        $pista = Pista::find($request->id_pista);
        $amount = $pedido->amount;
        if ($amount == 0) {
            $pedido->update([
                'estado' => 'pagado',
                'id_reserva' => $request->servicios_contratados,
            ]);
            Reserva::find($pedido->id_reserva)->update(['estado' => 'active']);
            Reserva::where('id_pedido', $pedido_id)->update(['estado' => 'active']);
            return redirect('/'.$request->slug_instalacion.'/mis-reservas')->with('message', 'Reserva realizada con éxito.');
        }

        $description = $pista->nombre;
        Cookie::queue('pedido', $pedido_id, 3);


        return $this->pagar_redsys($request->slug_instalacion,$amount,$pedido_id,$description);

        /* check_expiration($pedido_id); */
    }



    public function reserva(Request $request) {

        $pista = Pista::find($request->id_pista);
        if (!$pista->check_reserva_valida($request->timestamp)) {
            return 0;
        }

        $minutos_totales = $request->secuencia * $request->tarifa;

        $timestamps[0] = (int)$request->timestamp;

        if ($request->tarifa > 1) {
            for ($i=1; $i < $request->tarifa; $i++) {
                $timestamps[$i] = \Carbon\Carbon::parse(date('d-m-Y H:i:s', $request->timestamp))->addMinutes($request->secuencia*$i)->timestamp;
            }
        }

        $reservas_ids = [];


        if($request->slug_instalacion =='vvadecordoba'){
            if ($pista->id != 10) {
                $reserva = Reserva::create([
                    'id_pista' => $request->id_pista,
                    'id_usuario' => auth()->user()->id,
                    'timestamp' => $request->timestamp,
                    'horarios' => serialize($timestamps),
                    'fecha' => date('Y/m/d', $request->timestamp),
                    'hora' => date('Hi', $request->timestamp),
                    'tarifa' => $request->tarifa,
                    'minutos_totales' => $minutos_totales,
                    'estado'=>'pendiente'
                ]);


                array_push($reservas_ids, $reserva->id);

                if (isset($request->observaciones)) {
                    $reserva->update(['observaciones' => $request->observaciones]);
                }
            }
        }else{
            $reserva = Reserva::create([
                'id_pista' => $request->id_pista,
                'id_usuario' => auth()->user()->id,
                'timestamp' => $request->timestamp,
                'horarios' => serialize($timestamps),
                'fecha' => date('Y/m/d', $request->timestamp),
                'hora' => date('Hi', $request->timestamp),
                'tarifa' => $request->tarifa,
                'minutos_totales' => $minutos_totales,
                'estado'=>'pendiente'
            ]);

            array_push($reservas_ids, $reserva->id);

            if (isset($request->observaciones)) {
                $reserva->update(['observaciones' => $request->observaciones]);
            }
        }


        if (isset($pista->bloqueo)){
            $bloqueos = unserialize($pista->bloqueo);


            foreach ($timestamps as $tiempos){
            foreach ($bloqueos as $item){

                $bloquearpista= New Desactivacion_reserva();
                $bloquearpista->id_pista = $item;
                $bloquearpista->timestamp = $tiempos;
                $bloquearpista->reserva_id = $reserva->id;
                $bloquearpista->save();


            }}
        }


        $precio_mas = 0;
        $reserva_multiple_id = null;
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
                                        'id_usuario' => auth()->user()->id,
                                        'timestamp' => $request->timestamp,
                                        'horarios' => serialize($timestamps),
                                        'fecha' => date('Y/m/d', $request->timestamp),
                                        'hora' => date('Hi', $request->timestamp),
                                        'tarifa' => $request->tarifa,
                                        'tipo' => $key,
                                        'minutos_totales' => $minutos_totales,
                                        'estado' => 'pendiente'
                                    ]);

                                    if (isset($pista->bloqueo)) {
                                        $bloqueos = unserialize($pista->bloqueo);

                                        foreach ($bloqueos as $item) {

                                            $bloquearpista = New Desactivacion_reserva();
                                            $bloquearpista->id_pista = $item;
                                            $bloquearpista->timestamp = $request->timestamp;
                                            $bloquearpista->reserva_id = $reserva->id;
                                            $bloquearpista->save();


                                        }
                                    }

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
                            return 0;
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

                }else{
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
        if($request->slug_instalacion == "la-guijarrosa" || $request->slug_instalacion == "santaella"){
            $deporte_id = $pista->id_deporte;
            $bono_usuario = BonoUsuario::where('id_usuario', auth()->user()->id)
                                ->where('estado', 'active')
                                ->where('num_usos', '>', 0)
                                ->whereHas('bono', function($query) use ($deporte_id) {
                                    $query->where('id_deporte', $deporte_id);
                                })
                                ->first();

            if ($bono_usuario) {
                $amount = 0;
                $bono_usuario->num_usos -= 1;
                $bono_usuario->save();
            } else {
                // Si no hay bono, se calcula el precio normal
                $precio_intervalo = $pista->get_precio_total_given_timestamp($request->timestamp);
                $amount = $request->tarifa * $precio_intervalo;
            }
        }else{
            $precio_intervalo = $pista->get_precio_total_given_timestamp($request->timestamp, $precio_mas);

            $amount = $request->tarifa * $precio_intervalo;
        }

        $pedido = new Pedido();

        $pedido_id=$pista->instalacion->prefijo_pedido.generateOrderID();
        $pedido->id = $pedido_id;
        $pedido->amount = $amount;
        $pedido->id_usuario = auth()->user()->id;
        $pedido->id_reserva = $reserva->id;
        $pedido->estado = "En proceso";
       /*  $pedido->expiration = $expiration; */
        $pedido->save();

        if ($reserva_multiple_id) {
            Reserva::where('reserva_multiple', $reserva_multiple_id)->update(['id_pedido' => $pedido_id]);
        }

        Reserva::whereIn('id', $reservas_ids)->update(['id_pedido' => $pedido_id]);

        return $pedido_id;

        /* Mail::to(auth()->user()->instalacion->user_admin->email)->send(new NewReserva(auth()->user(), $reserva)); */

       /*  return redirect("/{$request->slug_instalacion}/mis-reservas"); */
    }

    public function pagar_pendiente($id_pedido, Request $request) {
        $id_pedido=$request->id_pedido;
        $pedido = Pedido::find(substr($id_pedido, 0, -1));

        $pista = $pedido->reserva->pista;
        $description = $pedido->reserva->pista->nombre;
        $now = Carbon::now();
        $expiration = Carbon::parse($pedido->created_at)->addMinutes('10');

        if($expiration->greaterThan($now)|| $pedido->reserva->reserva_periodica ) {
            return $this->pagar_redsys($request->slug_instalacion,$pedido->amount,$id_pedido,$description);

        } else {
            return redirect()->back()->with('error', 'La reserva ha expirado, intentalo de nuevo.');
        }
    }

    /************************************************* Eventos  ***********************************************************************************/
    public function pago_inscripcion(Request $request)
    {
        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();

        $evento = Evento::find($request->id);


        if(request()->slug_instalacion == "feria-jamon-villanuevadecordoba"){
            if($evento->id == 3 || $evento->id == 4){
                $numentradas = substr($request->campo_adicional[0][14], 0, 1);
            }elseif($evento->id == 6 || $evento->id == 7){
                $numentradas = substr($request->campo_adicional[0][15], 0, 1);
            }


            if (!$evento->check_new_inscripcion($numentradas)) {
                return redirect()->back()->with('error', 'No se ha completado la compra de los bonos porque no queda suficiente stock.');
            }
        }else{
            if (!$evento->check_new_inscripcion(count($request->campo_adicional))) {
                return redirect()->back()->with('error', 'No se ha completado la inscripción porque no quedan suficientes plazas.');
            }
        }


        $pedido_id = self::inscripcion_evento($request);
        if (!$pedido_id) {
            return redirect()->back()->with('error', 'No se ha completado la inscripcón.');
        }
        $pedido = Pedido::find($pedido_id);
        $evento = Participante::where('id_pedido', $pedido_id)->first()->evento;
        $participantes = Participante::where('id_pedido', $pedido_id)->get();

        $amount = $pedido->amount;
        if ($amount == 0) {
            $pedido->update(['estado' => 'pagado']);
            Participante::where('id_pedido', $pedido_id)->update(['estado' => 'active']);

            foreach ($participantes as $particip) {
                if(request()->slug_instalacion != "feria-jamon-villanuevadecordoba"){
                    \Mail::to($pedido->user->email)->send(new NewInscripcion($pedido->user, $particip));
                }
                // \Mail::to('javisanchezlopezdev@gmail.com')->send(new NewInscripcion($pedido->user, $particip));
            }
            if (request()->slug_instalacion == "villafranca-navidad" or request()->slug_instalacion == "villafranca-actividades" or request()->slug_instalacion == "ciprea24" or request()->slug_instalacion == "eventos-bodega") {
                return redirect('/'.$request->slug_instalacion.'/new/mis-eventos')->with('message', 'Compra realizada con éxito. Si no has recibido el correo con las entradas, revisa la bandeja de spam o descárgatelas de nuevo en "Mis eventos".');
            }elseif (request()->slug_instalacion == "feria-jamon-villanuevadecordoba") {
                return redirect('/'.$request->slug_instalacion.'/new/mis-eventos')->with('message', 'Compra realizada con éxito. Si no has recibido el correo con los bonos, revisa la bandeja de spam o descárgatelas de nuevo en "Mis eventos".');
            }
            else{

                return redirect('/'.$request->slug_instalacion.'/new/mis-eventos')->with('message', 'Inscripción realizada con éxito.');
            }
        }

        $description = $participantes->count() . ' participantes - ' . $evento->nombre;
        Cookie::queue('pedido', $pedido_id, 3);

        /* check_expiration($pedido_id); */


          // if($request->slug_instalacion=="demo"){
            //    return $this->pagar_redsys_recurrente($request->slug_instalacion,$amount,$pedido_id,$description);
            //}




        return $this->pagar_redsys($request->slug_instalacion,$amount,$pedido_id,$description);

    }

    public function pago_renovacion(Request $request)
    {
        /* $evento = Evento::find($request->id);

        if (!$evento->check_new_inscripcion(count($request->campo_adicional))) {
            return redirect()->back()->with('error', 'No se ha completado la inscripción porque no quedan suficientes plazas.');
        } */

        $pedido_id = self::renovar_evento($request);

        if (!$pedido_id) {
            return redirect()->back()->with('error', 'No se ha completado la inscripcón.');
        }
        $pedido = Pedido::find($pedido_id);
        $evento = Participante_eventos_mes::where('id_pedido', $pedido_id)->first()->participante->evento;
        $participante = Participante_eventos_mes::where('id_pedido', $pedido_id)->first()->participante;

        $amount = $pedido->amount;

        if ($amount == 0) {
            $pedido->update(['estado' => 'pagado']);
            Participante::where('id_pedido', $pedido_id)->update(['estado' => 'active']);
            return redirect('/'.$request->slug_instalacion.'/new/mis-eventos')->with('message', 'Inscripción realizada con éxito.');
        }

        $description = 'Renovación mes '. strftime('%B', strtotime('01-' . $participante->ultimo_mes_suscrito->num_mes . '-'.date('Y'))) . ' - ' . $evento->nombre;
        Cookie::queue('pedido', $pedido_id, 3);

        /* check_expiration($pedido_id); */

        return $this->pagar_redsys($request->slug_instalacion,$amount,$pedido_id,$description);

    }

    public function inscripcion_evento(Request $request)
    {
        $evento = Evento::find($request->id);

        $pedido = new Pedido();

        $pedido_id=$evento->instalacion->prefijo_pedido.generateOrderID();
        $pedido->id = $pedido_id;
        $pedido->id_usuario = auth()->user()->id;
        $pedido->id_evento = $evento->id;
        $pedido->estado = "En proceso";
        $slugInstalacion = trim(request()->slug_instalacion);
        $showQR = $slugInstalacion == "villafranca-navidad" ||
          $slugInstalacion == "villafranca-actividades" ||
          $slugInstalacion == "ciprea24" ||
          $slugInstalacion == "eventos-bodega" ||
          $slugInstalacion == "feria-jamon-villanuevadecordoba" ? "true" : "false";
        /*  $pedido->expiration = $expiration; */

        if(request()->slug_instalacion == "villafranca-navidad" ||  request()->slug_instalacion == "villafranca-actividades" or request()->slug_instalacion == "ciprea24" or request()->slug_instalacion == "eventos-bodega"){

            foreach($request->campo_adicional as $campo){
                DB::table('pedido_participante_log')->insert([
                    'user_id' => auth()->user()->id,
                    'nombre_participante' => $campo[1],
                    'tipo_entrada' => serialize($campo),
                    'precio' => $campo['precio'],
                    'pedido_id' => $pedido_id,

                ]);

            }

        }
        $precio_total = 0;
        if(request()->slug_instalacion == "feria-jamon-villanuevadecordoba"){
            foreach ($request->campo_adicional as $campo) {

                if($evento->id == 3 || $evento->id == 4){
                    $numentradas = substr($campo[14], 0, 1);
                }elseif($evento->id == 6 || $evento->id == 7){
                    $numentradas = substr($campo[15], 0, 2);
                }

                $precio_total = $campo['precio'];
                //bucle para recorrer el numero de entradas y crear los participantes
                for ($i = 0; $i < $numentradas; $i++) {
                    $participante = Participante::create([
                        'id_evento' => $evento->id,
                        'nombre' => $campo[1].'_'.$i,
                        'id_usuario' => auth()->user()->id,
                        'estado' => 'active', //pendiente
                        'id_pedido' => $pedido_id,
                        'showQR' => $showQR
                    ]);

                    foreach ($campo as $id_campo => $valor) {
                        if ($id_campo != 'precio') {
                            Valor_campo_personalizado::create([
                                'id_participante' => $participante->id,
                                'id_campo' => $id_campo,
                                'valor' => $valor
                            ]);
                        }
                    }

                    DB::table('pedido_participante_log')->insert([
                        'user_id' => auth()->user()->id,
                        'nombre_participante' => $campo[1].'_'.$i,
                        'tipo_entrada' => serialize($campo),
                        'precio' => ($campo['precio'])/$numentradas,
                        'pedido_id' => $pedido_id,

                    ]);
                }
            }
        }
        else{
            foreach ($request->campo_adicional as $campo) {
                $precio_total += $campo['precio'];
                if(request()->slug_instalacion == "villafranca-navidad"){
                    if(array_key_exists(5, $campo)){
                        $fecha = $campo[5];
                    }else{
                        if(str_contains($campo[6], "Tarde")){
                            $fecha = "6 de enero (2€)";
                        }else{
                            $fecha = "6 de enero (3€)";
                        }
                    }
                    $meses = [
                        'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio',
                        'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
                    ];
                    $parts = explode(" ", $fecha);


                    $dia = intval($parts[0]);

                    $mes = array_search(strtolower($parts[2]), $meses) + 1;

                    $anio = date('Y');

                    $fechaFormateada = sprintf('%02d-%02d-%04d', $dia, $mes, $anio);
                    $fecha = Carbon::parse($fechaFormateada)->format('d-m-Y');
                    if($parts[0] == "Bono"){
                        $fecha = "Bono";
                    }
                    $participante = Participante::create([
                        'id_evento' => $evento->id,
                        'nombre' => $campo[1],
                        'id_usuario' => auth()->user()->id,
                        'estado' => 'active', //pendiente
                        'fecha_pedido' => $fecha,
                        'id_pedido' => $pedido_id,
                        'showQR' => $showQR
                    ]);
                }else{
                    $fechaFormateada = Carbon::parse($evento->fecha_inicio)->format('d-m-Y');
                    if(request()->slug_instalacion == "villafranca-actividades"){
                        $hora_inicio = $request->hora_inicio;
                        if(!array_key_exists('7', $campo)){
                            $campo[7] = null;
                        }else{
                            if($campo[7] == "Solo DJ's"){
                                $fechaFormateada = "03-03-2024";
                                $hora_inicio = "01:30";
                            }
                        }
                        $participante = Participante::create([
                            'id_evento' => $evento->id,
                            'nombre' => $campo[1],
                            'id_usuario' => auth()->user()->id,
                            'estado' => 'active', //pendiente
                            'fecha_pedido' => $fechaFormateada,
                            'id_pedido' => $pedido_id,
                            'hora_inicio' => $hora_inicio,
                            'hora_fin' => $request->hora_fin,
                            'tipo_entrada' => $campo[7],
                            'showQR' => $showQR
                        ]);

                    }else{

                        if(request()->slug_instalacion == "la-guijarrosa"){
                            $participante = Participante::create([
                                'id_evento' => $evento->id,
                                'nombre' => $campo[2],
                                'id_usuario' => auth()->user()->id,
                                'estado' => 'active', //pendiente
                                'id_pedido' => $pedido_id,

                            ]);
                        }else{

                            $participante = Participante::create([
                                'id_evento' => $evento->id,
                                'nombre' => $campo[1],
                                'id_usuario' => auth()->user()->id,
                                'estado' => 'active', //pendiente
                                'id_pedido' => $pedido_id,
                                'showQR' => $showQR
                            ]);
                        }

                        foreach ($campo as $id_campo => $valor) {
                            if ($id_campo != 'precio') {
                                Valor_campo_personalizado::create([
                                    'id_participante' => $participante->id,
                                    'id_campo' => $id_campo,
                                    'valor' => $valor
                                ]);
                            }
                        }
                    }


                }
            }





            $year = date('d')>31 && date('m') == 12 ? date('Y') + 1 : date('Y');



            if ($evento->renovacion_mes) {
                $fecha=date('d')<=31 ? date('m') : date('m') + 1;


                Participante_eventos_mes::create([
                    'id_participante' => $participante->id,
                    'id_pedido' => $pedido_id,
                    'num_mes' => $fecha,
                    'num_year' => $year
                ]);
            }
            /* foreach ($campo as $id_campo => $valor) {
                if ($id_campo != 'precio') {
                    Valor_campo_personalizado::create([
                        'id_participante' => $participante->id,
                        'id_campo' => $id_campo,
                        'valor' => $valor
                    ]);
                }
            } */
        }

        $pedido->amount = $precio_total;
        $pedido->save();
        return $pedido_id;
    }

    public function renovar_evento(Request $request)
    {
        $participante = Participante::find($request->id);
        $evento = $participante->evento;

        $campos_participante= $participante->valores_campos_personalizados[sizeof($participante->valores_campos_personalizados) - 1];

        if($campos_participante-> id_campo >= 7 ){


            $tipo_inscripcion= $participante->valores_campos_personalizados[sizeof($participante->valores_campos_personalizados) - 1];

            $valorestabla= unserialize($evento->tipo_participante->campos_personalizados[sizeof($evento->tipo_participante->campos_personalizados) - 1]->opciones);

            foreach ($valorestabla as $valor){
                $usuario= substr($tipo_inscripcion->valor,0,6);
                $tabla = substr($valor['texto'],0,6);

                if($usuario == $tabla){


                    $precio= $evento->precio_participante + $valor['pextra'];



                }


            }

        }else{

            $precio= $evento->precio_participante;

        }


        $pedido = new Pedido();

        $pedido_id=$evento->instalacion->prefijo_pedido.generateOrderID();
        $pedido->id = $pedido_id;
        $pedido->amount = $precio;
        $pedido->id_usuario = auth()->user()->id;
        $pedido->id_evento = $evento->id;
        $pedido->estado = "En proceso";
        /*  $pedido->expiration = $expiration; */
        $pedido->save();

        $year = date('d')>31 && date('m') == 12 ? date('Y') + 1 : date('Y');


        $fecha=date('d')<=31 ? date('m') : date('m') + 1;




        Participante_eventos_mes::create([
            'id_participante' => $participante->id,
            'id_pedido' => $pedido_id,
            'num_mes' =>  $fecha,
            'num_year' => $year
        ]);

        return $pedido_id;
    }

    /*************************************************************************************************************************************** */


    /************************************************* Servicios ***********************************************************************************/

    public function contratarservicio(Request $request){
        $pedido_id = self::inscripcion_servicio($request);
        if (!$pedido_id) {
            return redirect()->back()->with('error', 'No se ha completado la inscripcón.');
        }
        $pedido = Pedido::find($pedido_id);
        $servicio = Servicio::find($pedido->id_servicio);
        if(request()->slug_instalacion == "los-agujetas-de-villafranca"){
            foreach ($request->campo as $participante_campo){
                $participante = new Participante();
                $participante->id_pedido = $pedido_id;
                $participante->id_usuario = auth()->user()->id;
                $participante->id_servicio = $servicio->id;
                $participante->estado = 'active';
                $participante->save();
                foreach ($participante_campo as $id_campo => $valor) {
                    Valor_campo_personalizado::create([
                        'id_participante' => $participante->id,
                        'id_campo' => $id_campo,
                        'valor' => $valor
                    ]);
                }

            }
        }

        $amount = $pedido->amount;



        if ($amount == 0) {

            $pedido->update(['estado' => 'pagado']);
            $servicio_usuario = new Servicio_Usuario();

            $acceso = new Acceso();
            $acceso->activo = 'on';
            $acceso->inicio = \Carbon\Carbon::parse($servicio_usuario->created_at)->format('Y-m-d');
            $acceso->fin = \Carbon\Carbon::now()->addMonth();
            if(request()->slug_instalacion == "santaella"){
                $acceso->apertura = "08:30:00";
                $acceso->cierre = "22:00:00";
            }else{
                $acceso->apertura = "09:00:00";
                $acceso->cierre = "21:00:00";
            }
            $acceso->user_id = $pedido->id_usuario;
            $acceso->save();

            if($servicio->duracion == 'mensual'){
                $servicio_usuario->fecha_expiracion = \Carbon\Carbon::now()->addMonth();
            }else if($servicio->duracion == "diario"){
                $servicio_usuario->fecha_expiracion = \Carbon\Carbon::now()->addDay()->startOfDay()->addDays(1);
            }
            else if($servicio->duracion =="semanal"){
                $servicio_usuario->fecha_expiracion = Carbon::now()->addDay(7);
            }else if($servicio->duracion =="quincenal"){
                $servicio_usuario->fecha_expiracion = Carbon::now()->addDay(15);
            }else if($servicio->duracion =="anual"){
                $servicio_usuario->fecha_expiracion = Carbon::now()->addYear();
            }
            else if($servicio->duracion == "trimestral"){
                $servicio_usuario->fecha_expiracion = Carbon::now()->addMonth(3);
                $acceso->fin = \Carbon\Carbon::now()->addMonth(3);
                $acceso->save();
            }
            else if($servicio->duracion == "semestral"){
                $servicio_usuario->fecha_expiracion = Carbon::now()->addMonth(6);
                $acceso->fin = \Carbon\Carbon::now()->addMonth(6);
                $acceso->save();
            }
            $servicio_usuario->activo = 'si';
            $servicio_usuario->id_servicio = $pedido->id_servicio;
            $servicio_usuario->id_usuario = $pedido->id_usuario;
            $servicio_usuario->save();

            $recibo = new Recibo();
            $recibo->amount = $pedido->amount;
            $recibo->id_servicio = $pedido->id_servicio;
            $recibo->id_usuario = $pedido->id_usuario;
            $recibo->pedido_id = $pedido->id;
            $recibo->id_servicio_usuario = $servicio_usuario->id;
            $recibo->tipo = 'servicio';
            $recibo->save();
            // \Mail::to($pedido->user->email)->send(new NewServicio($pedido->user, $servicio));


            return redirect('/'.$request->slug_instalacion.'/new/mis-servicios')->with('message', 'Inscripción realizada con éxito.');
        }

        $description = ' Contratar servicio - ' . $servicio->nombre;
        Cookie::queue('pedido', $pedido_id, 3);

        /* check_expiration($pedido_id); */

        switch ($servicio->formapago) {


            case "recurrente":
                return $this->pagar_redsys_recurrente($request->slug_instalacion,$amount,$pedido_id,$description);
                break;

            case "tarjeta":
                return $this->pagar_redsys($request->slug_instalacion,$amount,$pedido_id,$description);
                break;

            default:
                return $this->pagar_redsys($request->slug_instalacion,$amount,$pedido_id,$description);
        }

       // return $this->pagar_redsys($request->slug_instalacion,$amount,$pedido_id,$description);


    }

    public function inscripcion_servicio(Request $request)
    {
        $servicio = Servicio::find($request->servicio);

        $pedido = new Pedido();

        $pedido_id=$servicio->instalacion->prefijo_pedido.generateOrderID();
        $pedido->id = $pedido_id;
        $pedido->id_usuario = auth()->user()->id;
        $pedido->id_servicio = $servicio->id;
        $pedido->estado = "En proceso";
        /*  $pedido->expiration = $expiration; */


        // $precio_total = $request->precio_servicio;
        if(request()->slug_instalacion == "los-agujetas-de-villafranca"){
            $precio_total = $request->precio_servicio;
        }else{
            $precio_total = $servicio->precio;
        }

        $pedido->amount = $precio_total;
        $pedido->save();
        return $pedido_id;
    }



    public function contratar_servicio_nuevo(Request $request){

        $pedido_id = self::inscripcion_servicio($request);

        if (!$pedido_id) {
            return redirect()->back()->with('error', 'No se ha completado la renovación.');
        }

        $pedido = Pedido::find($pedido_id);
        $servicio = Servicio::find($pedido->id_servicio);
        if(request()->slug_instalacion == "los-agujetas-de-villafranca"){
            $participante = new Participante();
            $participante->id_pedido = $pedido_id;
            $participante->id_usuario = auth()->user()->id;
            $participante->id_servicio = $servicio->id;
            $participante->estado = 'active';
            $participante->save();
            if($request->campo){
                foreach ($request->campo as $id_campo => $valor) {
                    Valor_campo_personalizado::create([
                        'id_participante' => $participante->id,
                        'id_campo' => $id_campo,
                        'valor' => $valor
                    ]);
                }

            }
        }

        $amount = $pedido->amount;
        if ($amount == 0) {
            $pedido->update(['estado' => 'pagado']);
            Participante::where('id_pedido', $pedido_id)->update(['estado' => 'active']);

            $servicio_usuario = Servicio_Usuario::where('id_servicio', $pedido->id_servicio)->where('id_usuario', $pedido->id_usuario)->first();

            if($servicio->duracion == 'mensual'){
                $servicio_usuario->fecha_expiracion = \Carbon\Carbon::now()->addMonth();
            }else if($servicio->duracion == "diario"){
                $servicio_usuario->fecha_expiracion = \Carbon\Carbon::now()->addDay()->startOfDay()->addDays(1);
            }else if($servicio->duracion =="semanal"){
                $servicio_usuario->fecha_expiracion = Carbon::now()->addDay(7);
            }else if($servicio->duracion =="quincenal"){
                $servicio_usuario->fecha_expiracion = Carbon::now()->addDay(15);
            }else if($servicio->duracion =="anual"){
                $servicio_usuario->fecha_expiracion = Carbon::now()->addYear();
            }else if($servicio->duracion == "trimestral"){
                $servicio_usuario->fecha_expiracion = Carbon::now()->addMonth(3);
            }
            else if($servicio->duracion == "semestral"){
                $servicio_usuario->fecha_expiracion = Carbon::now()->addMonth(6);
            }
            $servicio_usuario->activo = 'si';
            $servicio_usuario->id_servicio = $pedido->id_servicio;
            $servicio_usuario->id_usuario = $pedido->id_usuario;
            $servicio_usuario->save();

            $recibo = new Recibo();
            $recibo->amount = $pedido->amount;
            $recibo->id_servicio = $pedido->id_servicio;
            $recibo->id_usuario = $pedido->id_usuario;
            $recibo->pedido_id = $pedido->id;
            $recibo->id_servicio_usuario = $servicio_usuario->id;
            $recibo->tipo = 'servicio';
            $recibo->save();

                \Mail::to($pedido->user->email)->send(new NewServicio($pedido->user, $servicio));


            return redirect('/'.$request->slug_instalacion.'/new/mis-servicios')->with('message', 'Inscripción realizada con éxito.');
        }

        $description = ' Contratar servicio - ' . $servicio->nombre;
        Cookie::queue('pedido', $pedido_id, 3);

        /* check_expiration($pedido_id); */

        switch ($servicio->formapago) {


            case "recurrente":
                return $this->pagar_redsys_recurrente($request->slug_instalacion,$amount,$pedido_id,$description);
                break;

            case "tarjeta":
                return $this->pagar_redsys($request->slug_instalacion,$amount,$pedido_id,$description);
                break;

            default:
                return $this->pagar_redsys($request->slug_instalacion,$amount,$pedido_id,$description);
        }

        //return $this->pagar_redsys($request->slug_instalacion,$amount,$pedido_id,$description);

    }

    public function renovar_servicio(Request $request){
        $pedido_id = self::inscripcion_servicio($request);

        if (!$pedido_id) {
            return redirect()->back()->with('error', 'No se ha completado la renovación.');
        }

        $pedido = Pedido::find($pedido_id);
        $servicio = Servicio::find($pedido->id_servicio);


        $amount = $pedido->amount;
        if ($amount == 0) {
            $pedido->update(['estado' => 'pagado']);
            Participante::where('id_pedido', $pedido_id)->update(['estado' => 'active']);

            $recibos_no_pagados = Recibo::where('id_usuario', $pedido->id_usuario)->where('estado', 'pendiente')->where(function ($query) {
                $query->whereNull('pedido_id')->orWhere('pedido_id', '');
            })->count();


            if($recibos_no_pagados >= 1){
                $acceso = Acceso::where('user_id',$pedido->id_usuario)->first();
                $acceso->activo = 'off';
                $acceso->save();
            }
            else{
                $acceso = Acceso::where('user_id',$pedido->id_usuario)->first();
                $acceso->activo = 'on';
                $acceso->save();
            }


            $servicio_usuario = Servicio_Usuario::where('id_servicio', $pedido->id_servicio)->where('id_usuario', $pedido->id_usuario)->first();

            if($servicio->duracion == 'mensual'){
                $servicio_usuario->fecha_expiracion = \Carbon\Carbon::now()->addMonth();
            }else if($servicio->duracion == "diario"){
                $servicio_usuario->fecha_expiracion = \Carbon\Carbon::now()->addDay()->startOfDay()->addDays(1);
            }else if($servicio->duracion =="semanal"){
                $servicio_usuario->fecha_expiracion = Carbon::now()->addDay(7);
            }else if($servicio->duracion =="quincenal"){
                $servicio_usuario->fecha_expiracion = Carbon::now()->addDay(15);
            }else if($servicio->duracion =="anual"){
                $servicio_usuario->fecha_expiracion = Carbon::now()->addYear();
            }

            $servicio_usuario->activo = 'si';
            $servicio_usuario->id_servicio = $pedido->id_servicio;
            $servicio_usuario->id_usuario = $pedido->id_usuario;
            $servicio_usuario->save();

            $recibo = Recibo::find($request->recibo);

            $recibo->estado = 'pagado';

            $recibo->pedido_id = $pedido->id;
            $recibo->save();
            \Mail::to($pedido->user->email)->send(new NewServicio($pedido->user, $servicio));
                // \Mail::to($pedido->user->email)->send(new NewServicio($pedido->user, $servicio));


            return redirect('/'.$request->slug_instalacion.'/new/mis-servicios')->with('message', 'Inscripción realizada con éxito.');
        }

        $description = ' Renovar servicio - ' . $servicio->nombre;
        Cookie::queue('pedido', $pedido_id, 3);

        /* check_expiration($pedido_id); */


        switch ($servicio->formapago) {


            case "recurrente":
                return $this->pagar_redsys_recurrente($request->slug_instalacion,$amount,$pedido_id,$description);
                break;

            case "tarjeta":
                return $this->pagar_redsys($request->slug_instalacion,$amount,$pedido_id,$description);
                break;

            default:
                return $this->pagar_redsys($request->slug_instalacion,$amount,$pedido_id,$description);
        }

        //return $this->pagar_redsys($request->slug_instalacion,$amount,$pedido_id,$description);


    }



/*************************************************************************************************************************************** */


    /************************************************* Bonos ***********************************************************************************/

    public function pagobono(Request $request)
    {

        $pedido_id = self::comprabono($request);
        $pedido = Pedido::find($pedido_id);
        $bono = Bono::find($pedido->id_bono);
        $bono_usuario = BonoUsuario::where('id_pedido', $pedido_id)->first();
        $servicio = Servicio::find($bono->id_deporte);


        if (!$pedido_id) {
            return redirect()->back()->with('error', 'No se ha completado la compra del bono.');
        }
        $pedido = Pedido::find($pedido_id);

        $amount = $pedido->amount;
        if ($amount == 0) {
            $pedido->update(['estado' => 'pagado']);
            $bono_usuario->estado = 'active';
            $bono_usuario->save();

            if($servicio->tipo_espacio == 9){
                $acceso=new Acceso();
                $acceso->activo = "on";
                $acceso->inicio= \Carbon\Carbon::now()->format('Y-m-d');
                $acceso->apertura = '08:30:00';
                $acceso->cierre= '22:00:00';
                $acceso->user_id= $pedido->id_usuario;
                $acceso->estado = 'salido';
                $acceso->num_usos = $bono->num_usos;
                $acceso->tipo = 'bono';
                $acceso->save();
            }



            \Mail::to($pedido->user->email)->send(new NewBonoUsuario($pedido->user, $bono_usuario));


            return redirect('/'.$request->slug_instalacion.'/new/mis-servicios')->with('message', 'Inscripción realizada con éxito.');
        }

        $description = $bono->nombre;
        Cookie::queue('pedido', $pedido_id, 3);


        return $this->pagar_redsys($request->slug_instalacion,$amount,$pedido_id,$description);

        /* check_expiration($pedido_id); */
    }


    public function comprabono(Request $request) {

        $bono = Bono::find($request->bono);
        $pedido = new Pedido();

        $pedido_id=$bono->instalacion->prefijo_pedido.generateOrderID();
        $pedido->id = $pedido_id;
        $pedido->amount = $bono->precio;
        $pedido->id_usuario = auth()->user()->id;
        $pedido->id_bono = $bono->id;
        $pedido->estado = "En proceso";
        $pedido->save();

        $bono_usuario = new BonoUsuario();
        $bono_usuario->num_usos = $bono->num_usos;
        $bono_usuario->precio = $bono->precio;
        $bono_usuario->id_usuario = auth()->user()->id;
        $bono_usuario->id_bono = $bono->id;
        $bono_usuario->estado = 'En proceso';
        $bono_usuario->id_pedido = $pedido->id;
        $bono_usuario->save();

        return $pedido_id;


    }

/*************************************************************************************************************************************** */




    /************************************************** Funciones de pagos **************************************************************************************************************************************** */


    public function ok(Request $request){
        $params = $request->Ds_MerchantParameters;
        $signature = $request->Ds_Signature;
        if (request()->slug_instalacion == "superate"){
            return redirect('/'.$request->slug_instalacion.'/new/mis-eventos')->with('message', 'Compra realizada con éxito.');
        }
        $decod  = Redsys::getMerchantParameters($params);

        /* return $decod; */
        $pedido_id = explode('-', $decod['Ds_Order'])[0];

        $pedido = Pedido::find($pedido_id);
        $instalacion_id = $pedido->user->id_instalacion;

        $instalacion = Instalacion::find($instalacion_id);
        $instalacion_slug = $instalacion->slug;

        $participante = Participante::where('id_pedido', $pedido_id)->first();
        if($participante==null){
            return redirect('/'.$instalacion_slug.'/mis-reservas')->with('message', 'Reserva realizada con éxito. Puede ver sus reservas en el apartado Mis Reservas.');
        }elseif($participante->count()) {

            if (request()->slug_instalacion == "villafranca-navidad" or request()->slug_instalacion == "villafranca-actividades" or request()->slug_instalacion == "ciprea24" or request()->slug_instalacion == "eventos-bodega") {
                return redirect('/'.$instalacion_slug.'/new/mis-eventos')->with('message', 'Compra realizada con éxito. Si no has recibido el correo con las entradas, revisa la bandeja de spam o descárgatelas de nuevo en "Mis eventos".');
            }elseif (request()->slug_instalacion == "feria-jamon-villanuevadecordoba") {
                return redirect('/'.$request->slug_instalacion.'/new/mis-eventos')->with('message', 'Compra realizada con éxito. Si no has recibido el correo con los bonos, revisa la bandeja de spam o descárgatelas de nuevo en "Mis eventos".');
            }
            else{
                if(request()->slug_instalacion == "los-agujetas-de-villafranca"){
                    return redirect('/'.$instalacion_slug.'/new/mis-servicios')->with('message', 'Inscripción realizada con éxito.');

                }else{
                    return redirect('/'.$instalacion_slug.'/new/mis-eventos')->with('message', 'Inscripción realizada con éxito.');

                }
            }

            // return redirect('/'.$instalacion_slug.'/mis-eventos')->with('message', 'Se ha inscrito al evento correctamente.');
        }
        if($pedido->id_servicio!=null){
            return redirect('/'.$instalacion_slug.'/mis-servicios')->with('message', 'Reserva realizada con éxito. Puede ver sus reservas en el apartado Mis Reservas.');

        }
        return redirect('/'.$instalacion_slug.'/mis-reservas')->with('message', 'Reserva realizada con éxito. Puede ver sus reservas en el apartado Mis Reservas.');

    }


    public function ko(Request $request) {
        // Datos Redsys
        if (request()->slug_instalacion == "superate"){
            return redirect('/'.$request->slug_instalacion.'/new/mis-eventos')->with('error', 'El método de pago ha fallado, vuelva a intentarlo o utilice otra tarjeta.');
        }
        $version = $request->Ds_SignatureVersion;
        $params = $request->Ds_MerchantParameters;
        $signature = $request->Ds_Signature;

        $decod  = Redsys::getMerchantParameters($params);

        /* return $decod; */
        $pay_date = $decod['Ds_Date'];
        $pay_time = $decod['Ds_Hour'];
        $pay_amount = $decod['Ds_Amount'];
        $pay_response = $decod['Ds_Response'];
        $pedido_id = explode('-', $decod['Ds_Order'])[0];

        $pedido = Pedido::withTrashed()->where('id', $pedido_id)->first();
        $instalacion_id = $pedido->user->id_instalacion;

        $instalacion = Instalacion::find($instalacion_id);
        $instalacion_slug = $instalacion->slug;

        Participante_eventos_mes::where('id_pedido', $pedido_id)->delete();
        Participante::where('id_pedido', $pedido_id)->delete();

        /* if ($participante->count()) {
            return redirect("/{$instalacion_slug}/mis-eventos");
        } */

        return redirect('/'.$instalacion_slug)->with('error', 'El método de pago ha fallado, vuelva a intentarlo o utilice otra tarjeta.');

    }


    public function notificacion(Request $request){
        /* \DB::table('redsys_log')->insert([
            'ds_date' => 123,
            'ds_hour' => 123,
            'ds_amount' => 123,
            'ds_response' => 123,
            'pedido_id' => 123,
            'version' => 123,
            'params' => 123,
            'signature' => 123,
            'created_at' => Carbon::now()
        ]); */

        /* $pedido_id = null;*/
        if($request->slug_instalacion == "superate" || $request->slug_instalacion == "khalifa-padel" || $request->slug_instalacion == "mauxiliadoralugo" ){
            $key = config('redsys.key_'.$request->slug_instalacion);
        }else{
            $key = 'FDMAuJb3kp35dDAwWJIZIQtUA85LNCas';
        }

        // Datos Redsys
        $version = $request->Ds_SignatureVersion;
        $params = $request->Ds_MerchantParameters;
        $signature = $request->Ds_Signature;
        $decod  = Redsys::getMerchantParameters($params);

        /* return $decod; */
        $pay_date = $decod['Ds_Date'];
        $pay_time = $decod['Ds_Hour'];
        $pay_amount = $decod['Ds_Amount'];
        $pay_response = $decod['Ds_Response'];
        $pedido_id = explode('-', $decod['Ds_Order'])[0];
        if($request->slug_instalacion == "demo"){
            session(['pedido_id' => $pedido_id]);
        }

        \DB::table('redsys_log')->insert([
            'ds_date' => $pay_date,
            'ds_hour' => $pay_time,
            'ds_amount' => $pay_amount,
            'ds_response' => $pay_response,
            'pedido_id' => $pedido_id,
            'version' => $version,
            'params' => $params,
            'signature' => $signature,
            'created_at' => Carbon::now()
        ]);

        $signature_gen = Redsys::generateMerchantSignatureNotification($key, $params);

        if($signature === $signature_gen) {
            if(intval($pay_response) <= 99){
                $pedido = Pedido::find($pedido_id);
                $pedido->estado = "pagado";
                $pedido->save();

                if($pedido->id_reserva != null){

                    $reserva = Reserva::find($pedido->id_reserva);
                    $reserva->estado = 'active';
                    $reserva->save();

                    Reserva::where('id_pedido', $pedido_id)->update(['estado' => 'active']);

                    $user = User::find($reserva->id_usuario);
                    // Enviar email de confirmacion reserva
                    \Mail::to($user->email)->send(new NewReserva($user, $reserva));
                    /* foreach ($participantes as $particip) {
                        Mail::to($pedido->user->email)->send(new NewInscripcion($pedido->user, $particip));
                    } */}

                if($pedido->id_evento != null){


                    $evento = Participante::where('id_pedido', $pedido->id)->first()->evento;
                    $participantes = Participante::where('id_pedido', $pedido->id)->get();



                    $pedido->update(['estado' => 'pagado']);
                    Participante::where('id_pedido', $pedido->id)->update(['estado' => 'active']);

                    if(request()->slug_instalacion == "villafranca-navidad" or request()->slug_instalacion == "villafranca-actividades" or request()->slug_instalacion == "ciprea24" or request()->slug_instalacion == "eventos-bodega" or request()->slug_instalacion == "feria-jamon-villanuevadecordoba"){
                        \Mail::to($pedido->user->email)->send(new NewInscripcion($pedido->user, $participantes[0]));

                    }else{
                        foreach ($participantes as $particip) {
                            \Mail::to($pedido->user->email)->send(new NewInscripcion($pedido->user, $particip));

                        }
                    }



                }

                if($pedido->id_servicio !=null){

                    $servicio = Servicio::find($pedido->id_servicio);
                    $servicio_user = Servicio_Usuario::where('id_usuario',$pedido->id_usuario)->where('id_servicio',$pedido->id_servicio)->first();

                    if(isset($servicio_user)){
                        $servicio_usuario = $servicio_user;

                    }else{
                        $servicio_usuario = new Servicio_Usuario();
                    }

                    if($servicio->duracion == 'mensual'){
                        $servicio_usuario->fecha_expiracion = \Carbon\Carbon::now()->addMonth();
                    }else if($servicio->duracion == "diario"){
                        $servicio_usuario->fecha_expiracion = \Carbon\Carbon::now()->addDay()->startOfDay()->addDays(1);
                    }else if($servicio->duracion =="semanal"){
                        $servicio_usuario->fecha_expiracion = Carbon::now()->addDay(7);
                    }else if($servicio->duracion =="quincenal"){
                        $servicio_usuario->fecha_expiracion = Carbon::now()->addDay(15);
                    }else if($servicio->duracion =="anual"){
                        $servicio_usuario->fecha_expiracion = Carbon::now()->addYear();
                    }else if($servicio->duracion == "trimestral"){
                        $servicio_usuario->fecha_expiracion = Carbon::now()->addMonth(3);
                        $acceso->fin = \Carbon\Carbon::now()->addMonth(3);
                        $acceso->save();
                    }
                    else if($servicio->duracion == "semestral"){
                        $servicio_usuario->fecha_expiracion = Carbon::now()->addMonth(6);
                        $acceso->fin = \Carbon\Carbon::now()->addMonth(6);
                        $acceso->save();
                    }
                    $servicio_usuario->activo = 'si';
                    $servicio_usuario->id_servicio = $pedido->id_servicio;
                    $servicio_usuario->id_usuario = $pedido->id_usuario;
                    $servicio_usuario->save();

                    if(count($servicio_usuario->recibos)==0){
                        $recibo = new Recibo();
                        $recibo->amount = $pedido->amount;
                        $recibo->id_servicio = $pedido->id_servicio;
                        $recibo->id_usuario = $pedido->id_usuario;
                        $recibo->tipo = 'servicio';
                        $recibo->id_servicio_usuario = $servicio_usuario->id;
                        $recibo->pedido_id = $pedido->id;
                        if(request()->slug_instalacion != "villafranca-navidad"){
                            $recibo->estado = "pagado";

                        }
                        $recibo->save();

                        // Al ser nuevo en el servicio, se le crea un nuevo acceso en on
                        $acceso=new Acceso();
                        $acceso->activo = "on";
                        $acceso->inicio= \Carbon\Carbon::now()->format('Y-m-d');
                        if($servicio->duracion == "trimestral"){
                            $acceso->fin = \Carbon\Carbon::now()->addMonth(3)->format('Y-m-d');
                        }elseif ($servicio->duracion == "semestral") {
                            $acceso->fin = \Carbon\Carbon::now()->addMonth(6)->format('Y-m-d');
                        }else{
                            $acceso->fin= \Carbon\Carbon::now()->addMonth()->format('Y-m-d');
                        }
                        if(request()->slug_instalacion == "santaella"){
                            $acceso->apertura = "08:30:00";
                            $acceso->cierre = "22:00:00";
                        }else{
                            $acceso->apertura = "09:00:00";
                            $acceso->cierre = "21:00:00";
                        }
                        $acceso->user_id= $pedido->id_usuario;
                        $acceso->save();

                    }else{
                        $recibo= Recibo::where('id_servicio_usuario',$servicio_usuario->id)->where('pedido_id',null)->first();
                        $recibo->pedido_id = $pedido->id;
                        if(request()->slug_instalacion != "villafranca-navidad"){
                            $recibo->estado = "pagado";

                        }

                        $recibo->save();

                        // Una vez se cambia el estado del recibo a pagado se comprueba si el usuario tiene mas recibos pendientes
                        $recibos_no_pagados = Recibo::where('id_usuario', $pedido->id_usuario)->where('estado', 'pendiente')->where(function ($query) {
                            $query->whereNull('pedido_id')->orWhere('pedido_id', '');
                        })->count();

                        // Si tiene mas de 1 recibo pendiente no se le da acceso
                        if($recibos_no_pagados >= 1){
                            $acceso = Acceso::where('user_id',$pedido->id_usuario)->first();
                            $acceso->activo = 'off';
                            $acceso->save();
                        }
                        else{
                            $acceso = Acceso::where('user_id',$pedido->id_usuario)->first();
                            $acceso->activo = 'on';
                            $acceso->save();
                        }


                        if ($servicio->duracion == 'mensual') {
                            /* $servicio_usuario->fecha_expiracion =  \Carbon\Carbon::parse($recibo->created_at)->addMonth()->startOfMonth()->addDays(7); */
                            $servicio_usuario->fecha_expiracion = \Carbon\Carbon::parse($recibo->created_at)->addMonth();
                        }else if($servicio->duracion == "diario"){
                            $servicio_usuario->fecha_expiracion = \Carbon\Carbon::now()->addDay()->startOfDay()->addDays(1);
                        } else if ($servicio->duracion == "semanal") {
                            $servicio_usuario->fecha_expiracion = \Carbon\Carbon::parse($recibo->created_at)->addDay(7);
                        } else if ($servicio->duracion == "quincenal") {
                            $servicio_usuario->fecha_expiracion = \Carbon\Carbon::parse($recibo->created_at)->addDay(15);
                        }else if($servicio->duracion == "anual"){
                            $servicio_usuario->fecha_expiracion = \Carbon\Carbon::parse($recibo->created_at)->addYear();
                        }else if($servicio->duracion == "trimestral"){
                            $servicio_usuario->fecha_expiracion = Carbon::now()->addMonth(3);
                            $acceso->fin = \Carbon\Carbon::now()->addMonth(3);
                            $acceso->save();
                        }
                        else if($servicio->duracion == "semestral"){
                            $servicio_usuario->fecha_expiracion = Carbon::now()->addMonth(6);
                            $acceso->fin = \Carbon\Carbon::now()->addMonth(6);
                            $acceso->save();
                        }

                        $servicio_usuario->save();

                    }


                    \Mail::to($pedido->user->email)->send(new NewServicio($pedido->user, $servicio));
                }

                $pedido->update(['estado' => 'pagado']);
                if(request()->slug_instalacion == "villafranca-navidad" or request()->slug_instalacion == "villafranca-actividades" or request()->slug_instalacion == "ciprea24"){
                    Participante::where('id_pedido', $pedido_id)->update(['estado' => 'active']);
                }

                /* \Mail::to($pedido->user->email)->send(new NewServicio($pedido->user, $servicio)); */

            }


        }else{

            $pedido = Pedido::find($pedido_id);
            $pedido->estado = "cancelado";
            $pedido->save();

            if($pedido->id_reserva != null) {
                $reserva = Reserva::find($pedido->id_reserva);
                $reserva->estado = 'canceled';
                $reserva->save();

                Reserva::where('id_pedido', $pedido_id)->update(['estado' => 'canceled']);
                Reserva::where('id_pedido', $pedido_id)->delete();
                $reserva->delete();
            }
            if($pedido->id_evento != null) {
                Participante::where('id_pedido', $pedido_id)->delete();
                Participante_eventos_mes::where('id_pedido', $pedido_id)->delete();
            }


            $pedido->delete();

        }

    } /*else{
            \DB::table('ok_errors')->insert(['exception' => 'Firma no válida: '.$pedido_id, 'created_at' => Carbon::now()]);
        }  */

    public function notificacion2(Request $request) {


        /* $pedido_id = null;*/
        if ($request->slug_instalacion == "superate" || $request->slug_instalacion == "khalifa-padel" || $request->slug_instalacion == "mauxiliadoralugo") {
            $key = config('redsys.key_' . $request->slug_instalacion);
        } else {
            $key = 'FDMAuJb3kp35dDAwWJIZIQtUA85LNCas';
        }
        $redsys = new \Sermepa\Tpv\Tpv();
        $decod = $redsys->getMerchantParameters($request->Ds_MerchantParameters);
        // Datos Redsys
        $version = $request->Ds_SignatureVersion;
        $params = $request->Ds_MerchantParameters;
        $signature = $request->Ds_Signature;


        /* return $decod; */
        $pay_date = $decod['Ds_Date'];
        $pay_time = $decod['Ds_Hour'];
        $pay_amount = $decod['Ds_Amount'];
        $pay_response = $decod['Ds_Response'];
        $pay_token = $decod['Ds_Merchant_Identifier'];
        $pedido_id = explode('-', $decod['Ds_Order'])[0];
        if($request->slug_instalacion == "demo"){
            session(['pedido_id' => $pedido_id]);
        }
        \DB::table('redsys_log')->insert([
            'ds_date' => $pay_date,
            'ds_hour' => $pay_time,
            'ds_amount' => $pay_amount,
            'ds_response' => $pay_response,
            'pedido_id' => $pedido_id,
            'version' => $version,
            'params' => $params,
            'signature' => $signature,
            'created_at' => Carbon::now()
        ]);

        $signature_gen = Redsys::generateMerchantSignatureNotification($key, $params);

        if ($signature === $signature_gen) {
            if (intval($pay_response) <= 99) {
                $pedido = Pedido::find($pedido_id);
                $pedido->estado = "pagado";
                $pedido->save();

                $user = User::find($pedido->id_usuario);
                $user->token_redsys = $pay_token;
                $user->save();
                if ($pedido->id_reserva != null) {

                    $reserva = Reserva::find($pedido->id_reserva);
                    $reserva->estado = 'active';
                    $reserva->save();

                    Reserva::where('id_pedido', $pedido_id)->update(['estado' => 'active']);

                    $user = User::find($reserva->id_usuario);

                    // Enviar email de confirmacion reserva
                    \Mail::to($user->email)->send(new NewReserva($user, $reserva));
                    /* foreach ($participantes as $particip) {
                        Mail::to($pedido->user->email)->send(new NewInscripcion($pedido->user, $particip));
                    } */
                }

                if ($pedido->id_evento != null) {


                    $evento = Participante::where('id_pedido', $pedido->id)->first()->evento;
                    $participantes = Participante::where('id_pedido', $pedido->id)->get();


                    $pedido->update(['estado' => 'pagado']);
                    Participante::where('id_pedido', $pedido->id)->update(['estado' => 'active']);

                    if(request()->slug_instalacion == "villafranca-navidad" or request()->slug_instalacion == "villafranca-actividades" or request()->slug_instalacion == "ciprea24"){
                        \Mail::to($pedido->user->email)->send(new NewInscripcion($pedido->user, $participantes[0]));

                    }else{
                        foreach ($participantes as $particip) {
                            \Mail::to($pedido->user->email)->send(new NewInscripcion($pedido->user, $particip));

                        }
                    }


                }

                if ($pedido->id_servicio != null) {

                    $servicio = Servicio::find($pedido->id_servicio);
                    $servicio_user = Servicio_Usuario::where('id_usuario', $pedido->id_usuario)->where('id_servicio', $pedido->id_servicio)->first();

                    if (isset($servicio_user)) {
                        $servicio_usuario = $servicio_user;

                    } else {
                        $servicio_usuario = new Servicio_Usuario();

                    }

                    if ($servicio->duracion == 'mensual') {
                        $servicio_usuario->fecha_expiracion = \Carbon\Carbon::now()->addMonth();
                    }else if($servicio->duracion == "diario"){
                        $servicio_usuario->fecha_expiracion = \Carbon\Carbon::now()->addDay()->startOfDay()->addDays(1);
                    } else if ($servicio->duracion == "semanal") {
                        $servicio_usuario->fecha_expiracion = Carbon::now()->addDay(7);
                    } else if ($servicio->duracion == "quincenal") {
                        $servicio_usuario->fecha_expiracion = Carbon::now()->addDay(15);
                    }else if($servicio->duracion == "anual"){
                        $servicio_usuario->fecha_expiracion = Carbon::now()->addYear();
                    }else if($servicio->duracion == "trimestral"){
                        $servicio_usuario->fecha_expiracion = Carbon::now()->addMonth(3);
                    }
                    else if($servicio->duracion == "semestral"){
                        $servicio_usuario->fecha_expiracion = Carbon::now()->addMonth(6);
                    }
                    $servicio_usuario->activo = 'si';
                    $servicio_usuario->id_servicio = $pedido->id_servicio;
                    $servicio_usuario->id_usuario = $pedido->id_usuario;
                    $servicio_usuario->save();

                    if (count($servicio_usuario->recibos) == 0) {
                        $recibo = new Recibo();
                        $recibo->amount = $pedido->amount;
                        $recibo->id_servicio = $pedido->id_servicio;
                        $recibo->id_usuario = $pedido->id_usuario;
                        $recibo->tipo = 'servicio';
                        $recibo->id_servicio_usuario = $servicio_usuario->id;
                        $recibo->pedido_id = $pedido->id;

                        $recibo->estado = "pagado";


                        $recibo->save();

                        // Al ser nuevo en el servicio, se le crea un nuevo acceso en on
                        $acceso=new Acceso();
                        $acceso->activo = "on";
                        $acceso->inicio= \Carbon\Carbon::now()->format('Y-m-d');
                        if($servicio->duracion == "trimestral"){
                            $acceso->fin = \Carbon\Carbon::now()->addMonth(3)->format('Y-m-d');
                        }elseif ($servicio->duracion == "semestral") {
                            $acceso->fin = \Carbon\Carbon::now()->addMonth(6)->format('Y-m-d');
                        }else{
                            $acceso->fin= \Carbon\Carbon::now()->addMonth()->format('Y-m-d');
                        }
                        if(request()->slug_instalacion == "santaella"){
                            $acceso->apertura = "08:30:00";
                            $acceso->cierre = "22:00:00";
                        }else{
                            $acceso->apertura = "09:00:00";
                            $acceso->cierre = "21:00:00";
                        }
                        $acceso->user_id= $pedido->id_usuario;
                        $acceso->save();

                    } else {
                        $recibo = Recibo::where('id_servicio_usuario', $servicio_usuario->id)->where('pedido_id', null)->first();
                        $recibo->pedido_id = $pedido->id;

                        $recibo->estado = "pagado";



                        $recibo->save();

                        // Una vez se cambia el estado del recibo a pagado se comprueba si el usuario tiene mas recibos pendientes
                        $recibos_no_pagados = Recibo::where('id_usuario', $pedido->id_usuario)->where('estado', 'pendiente')->where(function ($query) {
                            $query->whereNull('pedido_id')->orWhere('pedido_id', '');
                        })->count();

                        // Si tiene mas de 1 recibo pendiente no se le da acceso
                        if($recibos_no_pagados >= 1){
                            $acceso = Acceso::where('user_id',$pedido->id_usuario)->first();
                            $acceso->activo = 'off';
                            $acceso->save();
                        }
                        else{
                            $acceso = Acceso::where('user_id',$pedido->id_usuario)->first();
                            $acceso->activo = 'on';
                            $acceso->save();
                        }

                        if ($servicio->duracion == 'mensual') {
                            $servicio_usuario->fecha_expiracion = \Carbon\Carbon::parse($recibo->created_at)->addMonth();
                            // $servicio_usuario->fecha_expiracion =  \Carbon\Carbon::parse($recibo->created_at)->addMonth()->startOfMonth()->addDays(7);
                        }else if($servicio->duracion == "diario"){
                            $servicio_usuario->fecha_expiracion = \Carbon\Carbon::now()->addDay()->startOfDay()->addDays(1);
                        } else if ($servicio->duracion == "semanal") {
                            $servicio_usuario->fecha_expiracion = \Carbon\Carbon::parse($recibo->created_at)->addDay(7);
                        } else if ($servicio->duracion == "quincenal") {
                            $servicio_usuario->fecha_expiracion = \Carbon\Carbon::parse($recibo->created_at)->addDay(15);
                        }else if ($servicio->duracion == "anual") {
                            $servicio_usuario->fecha_expiracion = \Carbon\Carbon::parse($recibo->created_at)->addYear();
                        }else if($servicio->duracion == "trimestral"){
                            $servicio_usuario->fecha_expiracion = Carbon::now()->addMonth(3);
                            $acceso->fin = \Carbon\Carbon::now()->addMonth(3);
                            $acceso->save();
                        }
                        else if($servicio->duracion == "semestral"){
                            $servicio_usuario->fecha_expiracion = Carbon::now()->addMonth(6);
                            $acceso->fin = \Carbon\Carbon::now()->addMonth(6);
                            $acceso->save();
                        }

                        $servicio_usuario->save();



                    }
                    \Mail::to($pedido->user->email)->send(new NewServicio($pedido->user, $servicio));

                }

                if ($pedido->id_bono != null) {
                    $bono_usuario = BonoUsuario::find($pedido->id_bono);
                    $bono_usuario->estado = 'active';
                    $bono_usuario->save();

                    if($servicio->tipo_espacio == 9){
                        $acceso=new Acceso();
                        $acceso->activo = "on";
                        $acceso->inicio= \Carbon\Carbon::now()->format('Y-m-d');
                        $acceso->apertura = '08:30:00';
                        $acceso->cierre= '22:00:00';
                        $acceso->user_id= $pedido->id_usuario;
                        $acceso->estado = 'salido';
                        $acceso->num_usos = $bono->num_usos;
                        $acceso->tipo = 'bono';
                        $acceso->save();
                    }

                    BonoUsuario::where('id_pedido', $pedido_id)->update(['estado' => 'active']);

                    $user = User::find($bono_usuario->id_usuario);
                    // Enviar email de confirmacion reserva
                    \Mail::to($user->email)->send(new NewBonoUsuario($user, $bono_usuario));
                }


                $pedido->update(['estado' => 'pagado']);


            }


        } else {

            $pedido = Pedido::find($pedido_id);
            $pedido->estado = "cancelado";
            $pedido->save();

            if ($pedido->id_reserva != null) {
                $reserva = Reserva::find($pedido->id_reserva);
                $reserva->estado = 'canceled';
                $reserva->save();

                Reserva::where('id_pedido', $pedido_id)->update(['estado' => 'canceled']);
                Reserva::where('id_pedido', $pedido_id)->delete();
                $reserva->delete();
            }
            if ($pedido->id_evento != null) {
                Participante::where('id_pedido', $pedido_id)->delete();
                Participante_eventos_mes::where('id_pedido', $pedido_id)->delete();
            }

            if ($pedido->id_bono != null) {
                BonoUsuario::where('id_pedido', $pedido_id)->update(['estado' => 'canceled']);
                BonoUsuario::where('id_pedido', $pedido_id)->delete();
            }


            $pedido->delete();

        }
        if(isset($pedido) and $pedido_id and $servicio_usuario){
            \DB::table('notificacion_log')->insert([
                'user_id' => $pedido->id_usuario,
                'pedido_id' => $pedido_id,
                'servicio_usuario_id' => $servicio_usuario->id,
                'fecha_expiracion' => $servicio_usuario->fecha_expiracion,
                'created_at' => Carbon::now()
            ]);
        }
    }

    public function bluclepago(Request $request){

        $recibos=Recibo::where('tipo',$request->tipo)->where('pedido_id',null)->orderby('id','asc')->get();

        $instalacion=Instalacion::where('slug',$request->slug_instalacion)->get();


       // $usuarios= User::where('token_redsys','!=',null)->get();

        foreach ($recibos as $recibo) {

                    switch ($request->tipo){
                                case('servicio'):
                                    if($recibo->servicio->formapago=="recurrente") {
                                        $fecha=\Carbon\Carbon::now();
                                        $ano=$fecha->year;
                                        $fecha->format("F"); // Inglés.
                                        $mes = $fecha->formatLocalized('%B');
                                        $description = $recibo->servicio->nombre  . '-' .$mes.' '.$ano ;


                                        $pedidoid = $this->generar_pedido($recibo,$instalacion[0]);
                                        //$this->pagar_redsys_recurrente($request->slug, $recibo->amount, $pedidoid, $description);
                                        $resultado= $this->cobro_recurrente($recibo->user,$pedidoid,$recibo->amount,$description,$request->slug_instalacion,$recibo);
                                        if($resultado){
                                            $recibo->pedido_id= $pedidoid;
                                            $recibo->save();

                                            $servicio = $recibo->servicio;
                                            $servicio_user = Servicio_Usuario::where('id_usuario',$recibo->id_usuario)->where('id_servicio',$servicio->id)->first();


                                            $servicio_usuario = $servicio_user;


                                            if($servicio->duracion == 'mensual'){
                                                $servicio_usuario->fecha_expiracion =  \Carbon\Carbon::now()->startOfDay()->addMonth();
                                            }else if($servicio->duracion == "diario"){
                                                $servicio_usuario->fecha_expiracion = \Carbon\Carbon::now()->addDay()->startOfDay()->addDays(1);
                                            }else if($servicio->duracion =="semanal"){
                                                $servicio_usuario->fecha_expiracion =   \Carbon\Carbon::parse($servicio_usuario->fecha_expiracion)->addDay(7);
                                            }else if($servicio->duracion =="quincenal"){
                                                $servicio_usuario->fecha_expiracion = \Carbon\Carbon::parse($servicio_usuario->fecha_expiracion)->addDay(15);
                                            }else if($servicio->duracion =="anual"){
                                                $servicio_usuario->fecha_expiracion = Carbon::now()->addYear();
                                            }else if($servicio->duracion == "trimestral"){
                                                $servicio_usuario->fecha_expiracion = Carbon::now()->addMonth(3);
                                                $acceso->fin = \Carbon\Carbon::now()->addMonth(3);
                                                $acceso->save();
                                            }
                                            else if($servicio->duracion == "semestral"){
                                                $servicio_usuario->fecha_expiracion = Carbon::now()->addMonth(6);
                                                $acceso->fin = \Carbon\Carbon::now()->addMonth(6);
                                                $acceso->save();
                                            }
                                            $servicio_usuario->activo = 'si';
                                            $servicio_usuario->id_servicio = $servicio->id;
                                            $servicio_usuario->id_usuario = $recibo->id_usuario;
                                            $servicio_usuario->save();

                                            \Mail::to($recibo->user->email)->send(new RenovarServicio($recibo->user, $servicio));


                                        }else{
                                            $servicio = $recibo->servicio;
                                            // \Mail::to($recibo->user->email)->send(new RenovarServicioFallido($recibo->user, $servicio));
                                            echo $recibo->id;
                                        }

                                    }
                                    break;

                                case('escuela'):
                                    break;

                    }
        }




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

    public function cobro_recurrente($user,$pedidoid,$amount,$description,$instalacion,$recibo){

        if($user->token_redsys and false){
            $customerId = $user->token_redsys;
            if($customerId == null){
                return false;
            }

            try {

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
                    return "true";
                    // $redsys->executeRedirection();
                } else {
                    return "false";
                }


                // $parameters = ['DS_MERCHANT_COF_TYPE' => 'R'];
                // $redsys->setParameters($parameters);

                //  $form = $redsys->createForm();


                } catch (\Sermepa\Tpv\TpvException $e) {
                return $e->getMessage();
                // echo $e->getMessage();
            }
        }
    }

    public function pagar_redsys_recurrente($slug,$amount,$pedido_id,$description){



                /* check_expiration($pedido_id); */
                $notif_url = 'https://'.$_SERVER['HTTP_HOST'].'/'.$slug.'/notificacion2';
                $ok= 'https://'.$_SERVER['HTTP_HOST'].'/'.$slug.'/ok';
                $ko= 'https://'.$_SERVER['HTTP_HOST'].'/'.$slug.'/ko';

                try {
                    $key = config('redsys.key_'.$slug);

                    $redsys = new \Sermepa\Tpv\Tpv();
                    $redsys->setIdentifier();
                    $redsys->setMerchantcode(config('redsys.merchantcode_'.$slug));
                    $redsys->setCurrency(config('redsys.currency'));
                    $redsys->setTransactiontype(config('redsys.transactionType'));
                    $redsys->setTerminal(config('redsys.terminal'));
                    $redsys->setMethod('C'); //Solo pago con tarjeta, no mostramos iupay
                    $redsys->setVersion(config('redsys.version')); // HMAC_SHA256_V1
                    $redsys->setTradeName(config('redsys.tradename'));
                    $redsys->setTitular(config('redsys.titular'));
                    $redsys->setEnvironment(config('redsys.env_'.$slug)); //Entorno

                    $redsys->setNotification($notif_url);
                    $redsys->setUrlOk($ok);
                    $redsys->setUrlKo($ko);
                    $redsys->setProductDescription($description);
                    $redsys->setAmount($amount);
                    $redsys->setOrder($pedido_id);
                    // $redsys->setMerchantDirectPayment(true);

                    $signature = $redsys->generateMerchantSignature($key);
                    $redsys->setMerchantSignature($signature);

                    // $parameters = ['DS_MERCHANT_COF_TYPE' => 'R'];
                    // $redsys->setParameters($parameters);

                    //$form = $redsys->createForm();
                    $redsys->executeRedirection();
                } catch (\Sermepa\Tpv\TpvException $e) {
                    echo $e->getMessage();
                }

            }

    public function pagar_redsys($slug,$amount,$pedido_id,$description){
                try{

                    $key = config('redsys.key_'.$slug);
                    Redsys::setMerchantcode(config('redsys.merchantcode_'.$slug));
                    Redsys::setEnviroment(config('redsys.env_'.$slug));



                    Redsys::setCurrency(config('redsys.currency'));

                    Redsys::setTransactiontype(config('redsys.transactionType'));
                    Redsys::setTerminal(config('redsys.terminal'));
                    Redsys::setMethod(config('redsys.method'));
                    $notif_url = 'https://'.$_SERVER['HTTP_HOST'].'/'.$slug.'/notificacion';
                    Redsys::setNotification($notif_url);

                    $ok= 'https://'.$_SERVER['HTTP_HOST'].'/'.$slug.'/ok';
                    $ko= 'https://'.$_SERVER['HTTP_HOST'].'/'.$slug.'/ko';


                    Redsys::setUrlOk($ok);
                    Redsys::setUrlKo($ko);

                    Redsys::setVersion(config('redsys.version'));
                    Redsys::setTradeName(config('redsys.tradename'));
                    Redsys::setTitular(config('redsys.titular'));

                    //Redsys::set3DS("{'threeDSInfo':'CardData'}");

                    Redsys::setOrder($pedido_id);
                    Redsys::setProductDescription($description);
                    Redsys::setAmount($amount);

                    $signature = Redsys::generateMerchantSignature($key);
                    Redsys::setMerchantSignature($signature);

                    /*  $form = Redsys::createForm(); */
                    $form = Redsys::executeRedirection();
                }
                catch(Exception $e){
                    echo $e->getMessage();
                }
                return $form;

            }


    /* ******************************************************************************************************************************************************* */

}


