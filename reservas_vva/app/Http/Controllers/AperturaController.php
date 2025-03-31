<?php


namespace App\Http\Controllers;


use App\Models\Acceso;
use App\Models\Configuracion;
use App\Models\Accesos_puerta;
use App\Models\Registro;
use Illuminate\Http\Request;

class AperturaController extends Controller
{



    public function index(Request $request){

        $latitudmax=37.9076485;
        $latitudmin=37.7052128;

        $longitudmax=-4.7203644;
        $longitudmin=-4.78371922;

        $ip_usuario=$_SERVER['REMOTE_ADDR'];

            if(auth()->user()->rol == "admin") {


                $abrir_puerta = Accesos_puerta::find(2);

                $abrir_puerta->estado = 'abrir';
                $abrir_puerta->save();

                $registro= New Registro();

                $registro->accion_id = 1;
                $registro->user_id = auth()->user()->id;
                $registro->estado = 'entrada_portillo';
                $registro->tipo = 'botón';
                $registro->save();

                return response()->json(['success'=>"abre"]);

            }else {

                $latitud = $request->lat;
                $longitud = $request->lng;


                $apertura = Acceso::where('user_id', auth()->user()->id)->get();

                /* if ($latitud < $latitudmax && $latitudmin < $latitud && $longitud < $longitudmax && $longitud > $longitudmin) { */

                    if ($apertura[0]->activo == 'on') {


                        $fechahoy = strtotime(date('Y-m-d'));
                        $fechainiciobd = strtotime($apertura[0]->inicio);
                        $fechafinbd = strtotime($apertura[0]->fin);


                        $hora_momento = strtotime(date('H:i:s'));

                        $horaapertura = strtotime($apertura[0]->apertura);
                        $horacierre = strtotime($apertura[0]->cierre);


                        if ($apertura[0]->fin == null) {

                            $fechafinbd = strtotime(date("Y-m-d", strtotime("+1 day")));
                        } else {
                            $fechafinbd = strtotime($apertura[0]->fin);
                        }


                        if ($fechahoy >= $fechainiciobd && $fechahoy <= $fechafinbd) {

                            if ($horaapertura <= $hora_momento && $hora_momento <= $horacierre) {

                                $abrir_puerta = Accesos_puerta::find(1);

                                $abrir_puerta->estado = 'abrir';
                                $abrir_puerta->save();

                                $registro = New Registro();

                                $registro->accion_id = 1;
                                $registro->user_id = auth()->user()->id;
                                $registro->estado = 'entrada_portillo';
                                $registro->tipo = 'botón';
                                $registro->save();


                                return response()->json(['success' => 'ok',]);
                            } else {
                                $registro = New Registro();

                                $registro->accion_id = 4;
                                $registro->user_id = auth()->user()->id;
                                $registro->save();

                                return response()->json(['error' => 'No tiene permitido abrir la puerta en este horario']);


                            }

                        } else {
                            $registro = New Registro();

                            $registro->accion_id = 3;
                            $registro->user_id = auth()->user()->id;
                            $registro->save();

                            return response()->json(['error' => 'No es posible abrir la puerta ']);
                        }


                    } else {
                        $registro = New Registro();

                        $registro->accion_id = 4;
                        $registro->user_id = auth()->user()->id;
                        $registro->save();
                        return response()->json(['error' => 'El usuario no se encuentra activo']);

                    }


                /* }
                else{

                    $registro = New Registro();

                    $registro->accion_id = 5;
                    $registro->user_id = auth()->user()->id;
                    $registro->save();
                    return response()->json(['error' => 'El usuario no se encuentra fuera de la ubicación correcta']);

                } */

            }
            //return response()->json(['error'=>'Mal']);
    }

    public function tornoentrada(Request $request){

        $latitudmax=37.6342732;
        $latitudmin=37.6332732;

        $longitudmax=-4.8658202;
        $longitudmin=-4.8667537;

        $ip_usuario=$_SERVER['REMOTE_ADDR'];
        //$ip=$_SERVER['SERVER_ADDR']; //127.0.0.1

        /* $url="http://".$ipservidor."/?cmd=e&tipo=1&hash=".$hash.""; */
        //$url="http://192.168.1.203/".$hash;
            if(auth()->user()->rol == "admin") {


                $abrir_puerta = Accesos_puerta::find(1);

                $abrir_puerta->estado = 'abrir';
                $abrir_puerta->save();

                $registro= New Registro();

                $registro->accion_id = 1;
                $registro->user_id = auth()->user()->id;
                $registro->estado = "entrada_torno";
                $registro->tipo = 'botón';
                $registro->save();

                return response()->json(['success'=>"abre"]);

            }else {

                $latitud = $request->lat;
                $longitud = $request->lng;


                $apertura = Acceso::where('user_id', auth()->user()->id)->get();


                /* if ($latitud < $latitudmax && $latitudmin < $latitud && $longitud < $longitudmax && $longitud > $longitudmin) { */
                    if ($apertura[0]->activo == 'on') {


                        $fechahoy = strtotime(date('Y-m-d'));
                        $fechainiciobd = strtotime($apertura[0]->inicio);
                        $fechafinbd = strtotime($apertura[0]->fin);


                        $hora_momento = strtotime(date('H:i:s'));

                        $horaapertura = strtotime($apertura[0]->apertura);
                        $horacierre = strtotime($apertura[0]->cierre);


                        if ($apertura[0]->fin == null) {

                            $fechafinbd = strtotime(date("Y-m-d", strtotime("+1 day")));
                        } else {
                            $fechafinbd = strtotime($apertura[0]->fin);
                        }


                        if ($fechahoy >= $fechainiciobd && $fechahoy <= $fechafinbd) {

                            if ($horaapertura <= $hora_momento && $hora_momento <= $horacierre) {

                                $abrir_puerta = Accesos_puerta::find(1);

                                $abrir_puerta->estado = 'abrir';
                                $abrir_puerta->save();



                                $registro = New Registro();


                                $registro->accion_id = 1;
                                $registro->user_id = auth()->user()->id;
                                $registro->estado = "entrada_torno";
                                $registro->tipo = 'botón';
                                $registro->save();


                                return response()->json(['success' => 'ok']);
                            } else {
                                $registro = New Registro();

                                $registro->accion_id = 4;
                                $registro->user_id = auth()->user()->id;
                                $registro->estado = "no_permiso";
                                $registro->save();

                                return response()->json(['error' => 'No tiene permitido abrir la puerta en este horario']);


                            }

                        } else {
                            $registro = New Registro();

                            $registro->accion_id = 3;
                            $registro->user_id = auth()->user()->id;
                            $registro->estado = "no_posible_abrir";
                            $registro->save();

                            return response()->json(['error' => 'No es posible abrir la puerta ']);
                        }


                    } else {
                        $registro = New Registro();

                        $registro->accion_id = 4;
                        $registro->user_id = auth()->user()->id;
                        $registro->estado = "usuario_no_activo";
                        $registro->save();
                        return response()->json(['error' => 'El usuario no se encuentra activo']);

                    }


                /* }
                else{

                    $registro = New Registro();

                    $registro->accion_id = 5;
                    $registro->user_id = auth()->user()->id;
                    $registro->estado = "no_ubicacion";
                    $registro->save();
                    return response()->json(['error' => 'El usuario no se encuentra fuera de la ubicación correcta']);

                 } */

            }
            //return response()->json(['error'=>'Mal']);
    }

    public function tornosalida(Request $request){

        $latitudmax=37.6342732;
        $latitudmin=37.6332732;

        $longitudmax=-4.8658202;
        $longitudmin=-4.8667537;

        $ip_usuario=$_SERVER['REMOTE_ADDR'];


        //$ip=$_SERVER['SERVER_ADDR']; //127.0.0.1

        /* $url="http://".$ipservidor."/?cmd=e&tipo=1&hash=".$hash.""; */
        //$url="http://192.168.1.203/".$hash;

            if(auth()->user()->rol == "admin") {


                $abrir_puerta = Accesos_puerta::find(3);

                $abrir_puerta->estado = 'abrir';
                $abrir_puerta->save();

                $registro= New Registro();

                $registro->accion_id = 1;
                $registro->user_id = auth()->user()->id;
                $registro->estado = "salida_torno";
                $registro->tipo = 'botón';
                $registro->save();

                return response()->json(['success'=>"abre"]);

            }else {

                $latitud = $request->lat;
                $longitud = $request->lng;


                $apertura = Acceso::where('user_id', auth()->user()->id)->get();


                /* if ($latitud < $latitudmax && $latitudmin < $latitud && $longitud < $longitudmax && $longitud > $longitudmin) { */

                    if ($apertura[0]->activo == 'on') {


                        $fechahoy = strtotime(date('Y-m-d'));
                        $fechainiciobd = strtotime($apertura[0]->inicio);
                        $fechafinbd = strtotime($apertura[0]->fin);


                        $hora_momento = strtotime(date('H:i:s'));

                        $horaapertura = strtotime($apertura[0]->apertura);
                        $horacierre = strtotime($apertura[0]->cierre);


                        if ($apertura[0]->fin == null) {

                            $fechafinbd = strtotime(date("Y-m-d", strtotime("+1 day")));
                        } else {
                            $fechafinbd = strtotime($apertura[0]->fin);
                        }


                        if ($fechahoy >= $fechainiciobd && $fechahoy <= $fechafinbd) {

                            if ($horaapertura <= $hora_momento && $hora_momento <= $horacierre) {

                                $abrir_puerta = Accesos_puerta::find(3);

                                $abrir_puerta->estado = 'abrir';
                                $abrir_puerta->save();

                                $registro = New Registro();

                                $registro->accion_id = 1;
                                $registro->user_id = auth()->user()->id;
                                $registro->estado = "salida_torno";
                                $registro->tipo = 'botón';
                                $registro->save();


                                return response()->json(['success' => 'ok',]);
                            } else {
                                $registro = New Registro();

                                $registro->accion_id = 4;
                                $registro->user_id = auth()->user()->id;
                                $registro->save();

                                return response()->json(['error' => 'No tiene permitido abrir la puerta en este horario']);


                            }

                        } else {
                            $registro = New Registro();

                            $registro->accion_id = 3;
                            $registro->user_id = auth()->user()->id;
                            $registro->save();

                            return response()->json(['error' => 'No es posible abrir la puerta ']);
                        }


                    } else {
                        $registro = New Registro();

                        $registro->accion_id = 4;
                        $registro->user_id = auth()->user()->id;
                        $registro->save();
                        return response()->json(['error' => 'El usuario no se encuentra activo']);

                    }


                /* }
                else{

                    $registro = New Registro();

                    $registro->accion_id = 5;
                    $registro->user_id = auth()->user()->id;
                    $registro->save();
                    return response()->json(['error' => 'El usuario no se encuentra fuera de la ubicación correcta']);

                } */

            }
            //return response()->json(['error'=>'Mal']);
    }


    public function portilloentrada(Request $request){

        $latitudmax=37.6342732;
        $latitudmin=37.6332732;

        $longitudmax=-4.8658202;
        $longitudmin=-4.8667537;

        $ip_usuario=$_SERVER['REMOTE_ADDR'];


                $latitud = $request->lat;
                $longitud = $request->lng;


                $apertura = Acceso::where('user_id', auth()->user()->id)->get();


                /* if ($latitud < $latitudmax && $latitudmin < $latitud && $longitud < $longitudmax && $longitud > $longitudmin) { */

                    if ($apertura[0]->activo == 'on') {


                        $fechahoy = strtotime(date('Y-m-d'));
                        $fechainiciobd = strtotime($apertura[0]->inicio);
                        $fechafinbd = strtotime($apertura[0]->fin);


                        $hora_momento = strtotime(date('H:i:s'));

                        $horaapertura = strtotime($apertura[0]->apertura);
                        $horacierre = strtotime($apertura[0]->cierre);


                        if ($apertura[0]->fin == null) {

                            $fechafinbd = strtotime(date("Y-m-d", strtotime("+1 day")));
                        } else {
                            $fechafinbd = strtotime($apertura[0]->fin);
                        }


                        if ($fechahoy >= $fechainiciobd && $fechahoy <= $fechafinbd) {

                            if ($horaapertura <= $hora_momento && $hora_momento <= $horacierre) {

                                $abrir_puerta = Accesos_puerta::find(5);

                                $abrir_puerta->estado = 'cerrar';
                                $abrir_puerta->save();

                                $registro = New Registro();

                                $registro->accion_id = 1;
                                $registro->user_id = auth()->user()->id;
                                $registro->estado = "entrada_portillo_usuario";
                                $registro->tipo = 'botón';
                                $registro->save();


                                return response()->json(['success' => 'ok',]);
                            } else {
                                $registro = New Registro();

                                $registro->accion_id = 4;
                                $registro->user_id = auth()->user()->id;
                                $registro->save();

                                return response()->json(['error' => 'No tiene permitido abrir la puerta en este horario']);


                            }

                        } else {
                            $registro = New Registro();

                            $registro->accion_id = 3;
                            $registro->user_id = auth()->user()->id;
                            $registro->save();

                            return response()->json(['error' => 'No es posible abrir la puerta ']);
                        }


                    } else {
                        $registro = New Registro();

                        $registro->accion_id = 4;
                        $registro->user_id = auth()->user()->id;
                        $registro->save();
                        return response()->json(['error' => 'El usuario no se encuentra activo']);

                    }
    }

    public function portillosalida(Request $request){

        $latitudmax=37.6342732;
        $latitudmin=37.6332732;

        $longitudmax=-4.8658202;
        $longitudmin=-4.8667537;

        $ip_usuario=$_SERVER['REMOTE_ADDR'];


                $latitud = $request->lat;
                $longitud = $request->lng;


                $apertura = Acceso::where('user_id', auth()->user()->id)->get();


                /* if ($latitud < $latitudmax && $latitudmin < $latitud && $longitud < $longitudmax && $longitud > $longitudmin) { */

                    if ($apertura[0]->activo == 'on') {


                        $fechahoy = strtotime(date('Y-m-d'));
                        $fechainiciobd = strtotime($apertura[0]->inicio);
                        $fechafinbd = strtotime($apertura[0]->fin);


                        $hora_momento = strtotime(date('H:i:s'));

                        $horaapertura = strtotime($apertura[0]->apertura);
                        $horacierre = strtotime($apertura[0]->cierre);


                        if ($apertura[0]->fin == null) {

                            $fechafinbd = strtotime(date("Y-m-d", strtotime("+1 day")));
                        } else {
                            $fechafinbd = strtotime($apertura[0]->fin);
                        }


                        if ($fechahoy >= $fechainiciobd && $fechahoy <= $fechafinbd) {

                            if ($horaapertura <= $hora_momento && $hora_momento <= $horacierre) {

                                $abrir_puerta = Accesos_puerta::find(6);

                                $abrir_puerta->estado = 'abrir';
                                $abrir_puerta->save();

                                $registro = New Registro();

                                $registro->accion_id = 1;
                                $registro->user_id = auth()->user()->id;
                                $registro->estado = "entrada_portillo_usuario";
                                $registro->tipo = 'botón';
                                $registro->save();


                                return response()->json(['success' => 'ok',]);
                            } else {
                                $registro = New Registro();

                                $registro->accion_id = 4;
                                $registro->user_id = auth()->user()->id;
                                $registro->save();

                                return response()->json(['error' => 'No tiene permitido abrir la puerta en este horario']);


                            }

                        } else {
                            $registro = New Registro();

                            $registro->accion_id = 3;
                            $registro->user_id = auth()->user()->id;
                            $registro->save();

                            return response()->json(['error' => 'No es posible abrir la puerta ']);
                        }


                    } else {
                        $registro = New Registro();

                        $registro->accion_id = 4;
                        $registro->user_id = auth()->user()->id;
                        $registro->save();
                        return response()->json(['error' => 'El usuario no se encuentra activo']);

                    }
    }


    public function apertura_gym(Request $request){

        $latitudmax=37.6342732;
        $latitudmin=37.6332732;

        $longitudmax=-4.8658202;
        $longitudmin=-4.8667537;

        $ip_usuario=$_SERVER['REMOTE_ADDR'];

        if(auth()->user()->rol == "admin") {


            $abrir_puerta = Accesos_puerta::find(4);

            $abrir_puerta->estado = 'abrir';
            $abrir_puerta->save();

            $registro= New Registro();

            $registro->accion_id = 1;
            $registro->user_id = auth()->user()->id;
            $registro->estado = "entrada_gimnasio_usuario";
            $registro->tipo = 'botón';
            $registro->save();

            return response()->json(['success'=>"abre"]);

        }else{


                $latitud = $request->lat;
                $longitud = $request->lng;


                $apertura = Acceso::where('user_id', auth()->user()->id)->get();


                /* if ($latitud < $latitudmax && $latitudmin < $latitud && $longitud < $longitudmax && $longitud > $longitudmin) { */

                    if ($apertura[0]->activo == 'on') {


                        $fechahoy = strtotime(date('Y-m-d'));
                        $fechainiciobd = strtotime($apertura[0]->inicio);
                        $fechafinbd = strtotime($apertura[0]->fin);


                        $hora_momento = strtotime(date('H:i:s'));

                        $horaapertura = strtotime($apertura[0]->apertura);
                        $horacierre = strtotime($apertura[0]->cierre);


                        if ($apertura[0]->fin == null) {

                            $fechafinbd = strtotime(date("Y-m-d", strtotime("+1 day")));
                        } else {
                            $fechafinbd = strtotime($apertura[0]->fin);
                        }


                        if ($fechahoy >= $fechainiciobd && $fechahoy <= $fechafinbd) {

                            if ($horaapertura <= $hora_momento && $hora_momento <= $horacierre) {

                                $abrir_puerta = Accesos_puerta::find(4);

                                $abrir_puerta->estado = 'abrir';
                                $abrir_puerta->save();

                                $registro = New Registro();

                                $registro->accion_id = 1;
                                $registro->user_id = auth()->user()->id;
                                $registro->estado = "entrada_gimnasio_usuario";
                                $registro->tipo = 'botón';
                                $registro->save();


                                return response()->json(['success' => 'ok',]);
                            } else {
                                $registro = New Registro();

                                $registro->accion_id = 4;
                                $registro->user_id = auth()->user()->id;
                                $registro->save();

                                return response()->json(['error' => 'No tiene permitido abrir la puerta en este horario']);


                            }

                        } else {
                            $registro = New Registro();

                            $registro->accion_id = 3;
                            $registro->user_id = auth()->user()->id;
                            $registro->save();

                            return response()->json(['error' => 'No es posible abrir la puerta ']);
                        }


                    } else {
                        $registro = New Registro();

                        $registro->accion_id = 4;
                        $registro->user_id = auth()->user()->id;
                        $registro->save();
                        return response()->json(['error' => 'El usuario no se encuentra activo']);

                    }
    }
    }


    public function reset(){

            $ip_usuario = $_SERVER['REMOTE_ADDR'];


            //$ip=$_SERVER['SERVER_ADDR']; //127.0.0.1
            //$ip=$_SERVER['REMOTE_ADDR'];
        $configuracionsevidor= Configuracion::where("campo","ip")->get();
        $ipservidor=$configuracionsevidor[0]->valor;
            $ip = "127.0.0.1";
            //$fecha= date("ndY"); // 050622
            $fechamata = date("njY");
            $clave = "Mata22";//5
            $hash = md5($clave . $ip . $fechamata);// f4e2151f3208b65ee6d4e4881189904b
            //$hash=$clave.$ip.$fechamata;// f4e2151f3208b65ee6d4e4881189904b
            $url = "http://".$ipservidor."/?cmd=e&tipo=3&hash=" . $hash . "";
            //$url="http://192.168.1.203/".$hash;


            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $resultado = curl_exec($ch);
            curl_close($ch);

        return response()->json(['success' => 'ok',]);


    }


}
