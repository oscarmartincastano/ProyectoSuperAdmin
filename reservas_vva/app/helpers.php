<?php

use Illuminate\Http\Request;
use App\Models\Instalacion;
use App\Models\Pedido;
use App\Models\Reserva;
use Carbon\Carbon;
use App\Models\Pista;

function currentURL($slug = null) {
    $host = '';
    if (!empty($_SERVER['HTTP_HOST'])) {
        if($slug == null) {
            return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http").'://'.$_SERVER['HTTP_HOST'].'/vvadecordoba/';
        } else {
            return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http").'://'.$_SERVER['HTTP_HOST'].'/'.$slug.'/';
        }
    }
}

function obtener_dias_horario_mes($timestamp){
    //Obtener 1 pista cualquiera
    $fechaFromTimestamp = \Carbon\Carbon::createFromTimestamp($timestamp);
    $mesElegido = $fechaFromTimestamp->month;
    $yearElegido = $fechaFromTimestamp->year;
    $pista = Pista::first();
    foreach ($pista->horario_deserialized as $key => $item) {
        $fechas = []; //Array que guarda el timestamp de los días del mes del horario

        for ($dia = 1; $dia <= 31; $dia++) {
            $fecha = \Carbon\Carbon::createFromDate($yearElegido, $mesElegido, $dia);
            if ($fecha->month != $mesElegido) {
                // Si el día está fuera del mes actual, terminar el bucle
                break;
            }
            if (in_array($fecha->dayOfWeek, $item["dias"])) {
                $fechas[] = $fecha->timestamp;
            }
        }

        // Ordenar el array de fechas por orden ascendente
        sort($fechas);
    }
    return $fechas;
}

function sendMail($to, $title, $msg, $btn = null, $url = null) {
    $data = ['to' => $to, 'title' => $title, 'msg' => $msg, 'btn' => $btn, 'url' => $url];
    \Mail::send('mails.template', ['to' => $to, 'title' => $title, 'msg' => $msg, 'btn' => $btn, 'url' => $url], function ($m) use ($data) {
        $m->from('reservas@villanueva.es', 'Ayuntamiento de Villanueva');
        $m->to($data['to'], 'admin')->subject($data['title']);
    });
}

function generateOrderID() {
    return rand(10, 99).Str::random(4);
}

function calcular_total($secuencia,$i,$fecha,$pista){

    return ((floor(($secuencia*$i)/60) * $pista->precio) + (floor(($secuencia*$i)/60) * $pista->get_precio_extra_given_timestamp($fecha)));
}


function check_expiration($id){

    $pedido = Pedido::find($id);
    $id_reserva = $pedido->reserva->id;
    $reserva = Reserva::find($id_reserva);

    if(isset($pedido->expiration) && strtotime($pedido->expiration) > strtotime(Carbon::now())){
        return $pedido;
    }
    else{
        $pedido->delete();
        $reserva->delete();
        return false;
    }
}

function check_cookie(){
    $cookie = Cookie::get('pedido');
    return $cookie;
 }

 function check_pendientePago($id_usuario, $id_pista = null, $timestamp = null) {
    $result = false;
    $check = Reserva::where('id_usuario', $id_usuario)->where('estado', 'pendiente')->where('created_at', '<=', \Carbon\Carbon::now()->addMinutes(5));
    if($id_pista != null) { $check->where('id_pista', $id_pista); }
    if($timestamp != null) { $check->where('timestamp', $timestamp); }
    $check_count = $check->count();
    if($check_count > 0) {
        $result = $check_count;
    }
    return $result;
 }

 function exportCSV($filename, $delimiter) {
    if (!file_exists($filename) || !is_readable($filename))
            return false;
        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
 }
