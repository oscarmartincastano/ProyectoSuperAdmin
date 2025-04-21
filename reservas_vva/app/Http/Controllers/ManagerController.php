<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewReserva;
use App\Mail\ReservaAdmin;
use App\Models\Pista;
use App\Models\Instalacion;
use App\Models\User;
use App\Models\Permiso;
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
use App\Http\Controllers\RedsysController;
use Ssheduardo\Redsys\Facades\Redsys;
use App\Models\Servicios_adicionales;
use Intervention\Image\ImageManagerStatic as Image;
use DateTime;

class ManagerController extends Controller
{
    public function index(Request $request)
    {
        $instalaciones = Instalacion::all();
        return view('manager.index', compact('instalaciones'));
    }

    public function add_instalacion_view(Request $request)
    {
        return view('manager.instalacion.add');
    }

    public function add_instalacion(Request $request)
    {
        $data = $request->except('email', 'password', 'logo');
    $data['tipo_reservas_id'] = 1;

    // Crear la instalación
    $instalacion = Instalacion::create($data);
        User::create([
            'id_instalacion' => $instalacion->id,
            'name' => $instalacion->nombre,
            'email' => $request->email,
            'password' => \Hash::make($request->password),
            'rol' => 'admin',
            'subrol' => 'admin',
            'aprobado' => date('Y-m-d H:i:s')
        ]);
        Permiso::create([
            'id_instalacion' => $instalacion->id
        ]);

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

        }

        return redirect('/{{ request()->slug_instalacion }}/manager');
    }

    public function edit_instalacion_view(Request $request)
    {
        $instalacion = Instalacion::find($request->id);
        return view('manager.instalacion.add', compact('instalacion'));
    }

    public function edit_instalacion(Request $request)
    {
        $instalacion = Instalacion::find($request->id);
        $instalacion->update($request->except('email', 'password', 'logo'));
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
        }

        return redirect('/manager');
    }

    public function list_servicios(Request $request)
    {
        $servicios = Servicios_adicionales::all();
        return view('manager.servicios.list', compact('servicios'));
    }

    public function add_servicio_view(Request $request)
    {
        return view('manager.servicios.add');
    }

    public function add_servicio(Request $request)
    {
        $servicio = Servicios_adicionales::create($request->except('icono'));

        if ($request->icono) {
            $image = $request->file('icono');
            $img = Image::make($image->getRealPath());
            $img->orientate();
            $path = public_path() . '/img/servicios';

            $name = $servicio->id . '.png';

            if (getimagesize($image)[0] > 1000) {
                $img->resize(900, 900, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path .'/'. $name, 85, 'png');
            }else{
                $img->save($path .'/'. $name, 85, 'png');
            }
        }

        return redirect('/manager/servicios');
    }

    public function edit_servicio_view(Request $request)
    {
        $servicio = Servicios_adicionales::find($request->id);
        return view('manager.servicios.add', compact('servicio'));
    }

    public function edit_servicio(Request $request)
    {
        $servicio = Servicios_adicionales::find($request->id);
        $servicio->update($request->except('icono'));

        if ($request->icono) {
            $image = $request->file('icono');
            $img = Image::make($image->getRealPath());
            $img->orientate();
            $path = public_path() . '/img/servicios';

            $name = $servicio->id . '.png';

            if (getimagesize($image)[0] > 1000) {
                $img->resize(900, 900, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path .'/'. $name, 85, 'png');
            }else{
                $img->save($path .'/'. $name, 85, 'png');
            }
        }

        return redirect('/manager/servicios');
    }

    public function list_deportes(Request $request)
    {
        $deportes = Deporte::all();
        return view('manager.deportes.list', compact('deportes'));
    }

    public function add_deporte_view(Request $request)
    {
        return view('manager.deportes.add');
    }

    public function add_deporte(Request $request)
    {
        $deporte = Deporte::create($request->except('icono'));

        if ($request->icono) {
            $image = $request->file('icono');
            $img = Image::make($image->getRealPath());
            $img->orientate();
            $path = public_path() . '/img/deportes/icons';

            $name = $deporte->id . '.png';

            if (getimagesize($image)[0] > 1000) {
                $img->resize(900, 900, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path .'/'. $name, 85, 'png');
            }else{
                $img->save($path .'/'. $name, 85, 'png');
            }
        }

        return redirect('/manager/deportes');
    }

    public function edit_deporte_view(Request $request)
    {
        $deporte = Deporte::find($request->id);
        return view('manager.deportes.add', compact('deporte'));
    }

    public function edit_deporte(Request $request)
    {
        $deporte = Deporte::find($request->id);
        $deporte->update($request->except('icono'));

        if ($request->icono) {
            $image = $request->file('icono');
            $img = Image::make($image->getRealPath());
            $img->orientate();
            $path = public_path() . '/img/deportes/icons';

            $name = $deporte->id . '.png';

            if (getimagesize($image)[0] > 1000) {
                $img->resize(900, 900, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path .'/'. $name, 85, 'png');
            }else{
                $img->save($path .'/'. $name, 85, 'png');
            }
        }

        return redirect('/manager/deportes');
    }

    public function devoluciones(Request $request)
    {
        $pedidos = Pedido::where('estado', 'Devolucion pendiente')->orWhere('estado', 'Devuelto')->get();
        return view('manager.devoluciones.list', compact('pedidos'));
    }

    public function devolver_pedido(Request $request)
    {
        $pedido = Pedido::find($request->id);
        $pedido->estado = 'Devuelto';
        $pedido->save();


        if(substr($pedido->id, 0,4) == "vvac"){
            \DB::purge('mysql');

            $dynamic_db_name = 'reservas_vva';
            $config = \Config::get('database.connections.mysql');

            $config['database'] = $dynamic_db_name;
            $config['password'] = "#3p720hqK";
            config()->set('database.connections.mysql', $config);

            $pedido_instalacion = Pedido::find($pedido->id);

            $pedido_instalacion->estado = 'Devuelto';
            $pedido_instalacion->save();
            \DB::purge('mysql');
        }
        elseif (substr($pedido->id, 0,4) == "vfdc") {
            \DB::purge('mysql');

            $dynamic_db_name = 'rese_villafranca';
            $config = \Config::get('database.connections.mysql');

            $config['database'] = $dynamic_db_name;
            $config['password'] = "#3p720hqK";
            config()->set('database.connections.mysql', $config);

            $pedido_instalacion = Pedido::find($pedido->id);

            $pedido_instalacion->estado = 'Devuelto';
            $pedido_instalacion->save();
            \DB::purge('mysql');
        }


        try{
            $pedido = Pedido::find($request->id);
            $key = 'FDMAuJb3kp35dDAwWJIZIQtUA85LNCas';
            Redsys::setMerchantcode(config('redsys.merchantcode'));
            Redsys::setCurrency(config('redsys.currency'));
            Redsys::setTransactiontype(3);
            Redsys::setTerminal(001);
            Redsys::setEnviroment(config('redsys.env'));
            Redsys::setUrlOk('https://gestioninstalacion.es/manager');


            Redsys::setOrder($pedido->id);
            Redsys::setAmount($pedido->amount);
            $signature = Redsys::generateMerchantSignature($key);
            Redsys::setMerchantSignature($signature);

            $form = Redsys::executeRedirection();
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        return $form;
    }
}
