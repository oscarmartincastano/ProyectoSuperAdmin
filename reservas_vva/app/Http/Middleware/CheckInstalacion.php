<?php

namespace App\Http\Middleware;

use App\Models\Instalacion;
use Closure;
use Auth;
use Session;

class CheckInstalacion
{
    public function handle($request, Closure $next)
    {
        session_start();

        $variablebd=true;
        if(isset($_SESSION['bd'])) {
            if($_SESSION['bd']==$request->slug_instalacion) {
                switch ($request->slug_instalacion) {
                    case'vvadecordoba':

                        $dynamic_db_name = 'reservas_vva';
                        $config = \Config::get('database.connections.mysql');

                        $config['database'] = $dynamic_db_name;
                        $config['password'] = "#3p720hqK";
                        config()->set('database.connections.mysql', $config);
                        \DB::purge('mysql');

                        break;

                    case'campamentos-vva':

                        $dynamic_db_name = 'rese_multivillanueva';
                        $config = \Config::get('database.connections.mysql');

                        $config['database'] = $dynamic_db_name;
                        $config['password'] = "#3p720hqK";
                        config()->set('database.connections.mysql', $config);
                        \DB::purge('mysql');

                        break;

                    case'villafranca-de-cordoba':
                        $dynamic_db_name = 'rese_villafranca';
                        $config = \Config::get('database.connections.mysql');

                        $config['database'] = $dynamic_db_name;
                        $config['password'] = "#3p720hqK";
                        config()->set('database.connections.mysql', $config);
                        \DB::purge('mysql');

                        break;

                    case'la-guijarrosa':

                        $dynamic_db_name = 'rese_guijarrosa';

                        $config = \Config::get('database.connections.mysql');

                        $config['database'] = $dynamic_db_name;
                        $config['password'] = "#3p720hqK";
                        config()->set('database.connections.mysql', $config);
                        \DB::purge('mysql');

                        break;

                    case'superate':

                        $dynamic_db_name = 'rese_superate';

                        $config = \Config::get('database.connections.mysql');

                        $config['database'] = $dynamic_db_name;
                        $config['password'] = "#3p720hqK";
                        config()->set('database.connections.mysql', $config);
                        \DB::purge('mysql');
    
                        break;
                    
                    case'demo':

                        $dynamic_db_name = 'rese_demo';

                        $config = \Config::get('database.connections.mysql');

                        $config['database'] = $dynamic_db_name;
                        $config['password'] = "#3p720hqK";
                        config()->set('database.connections.mysql', $config);
                        \DB::purge('mysql');

                        break;
                    case 'los-agujetas-de-villafranca':

                        $dynamic_db_name = 'rese_agujetas';

                        $config = \Config::get('database.connections.mysql');

                        $config['database'] = $dynamic_db_name;
                        $config['password'] = "#3p720hqK";
                        config()->set('database.connections.mysql', $config);
                        \DB::purge('mysql');

                        break;


                    case 'mercadillos-villafranca-de-cordoba':

                        $dynamic_db_name = 'rese_mercadillos';

                        $config = \Config::get('database.connections.mysql');

                        $config['database'] = $dynamic_db_name;
                        $config['password'] = "#3p720hqK";
                        config()->set('database.connections.mysql', $config);
                        \DB::purge('mysql');

                        break;

                    case 'reservas-montoro':
                        $dynamic_db_name = 'rese_montoro';
                        $config = \Config::get('database.connections.mysql');
                        $config['database'] = $dynamic_db_name;
                        $config['password'] = "#3p720hqK";
                        config()->set('database.connections.mysql', $config);
                        \DB::purge('mysql');

                        break;

                    case'khalifa-padel':

                        $dynamic_db_name = 'rese_khalifapadel';
                        $config = \Config::get('database.connections.mysql');

                        $config['database'] = $dynamic_db_name;
                        $config['password'] = "#3p720hqK";
                        config()->set('database.connections.mysql', $config);
                        \DB::purge('mysql');

                        break;

                    case 'eventos-bodega':

                        $dynamic_db_name = 'eventos_bodega';
                        $config = \Config::get('database.connections.mysql');
                        $config['database'] = $dynamic_db_name;
                        $config['password'] = "#3p720hqK";
                        config()->set('database.connections.mysql', $config);
                        \DB::purge('mysql');
                        break;

                    case 'villafranca-navidad':


                        $dynamic_db_name = 'rese_villafranca_navidad';
                        $config = \Config::get('database.connections.mysql');

                        $config['database'] = $dynamic_db_name;
                        $config['password'] = "#3p720hqK";
                        config()->set('database.connections.mysql', $config);
                        \DB::purge('mysql');

                        break;

                    case 'villafranca-actividades':

                            $dynamic_db_name = 'rese_villafranca_actividades';
                            $config = \Config::get('database.connections.mysql');

                            $config['database'] = $dynamic_db_name;
                            $config['password'] = "#3p720hqK";
                            config()->set('database.connections.mysql', $config);
                            \DB::purge('mysql');
                            break;

                    case 'ciprea24':

                        $dynamic_db_name = 'ciprea24entradas';
                        $config = \Config::get('database.connections.mysql');

                        $config['database'] = $dynamic_db_name;
                        $config['password'] = "#3p720hqK";
                        config()->set('database.connections.mysql', $config);
                        \DB::purge('mysql');
                        break;

                    case 'feria-jamon-villanuevadecordoba':

                        $dynamic_db_name = 'rese_jamon';
                        $config = \Config::get('database.connections.mysql');

                        $config['database'] = $dynamic_db_name;
                        $config['password'] = "#3p720hqK";
                        config()->set('database.connections.mysql', $config);
                        \DB::purge('mysql');
                    break;

                    case 'mauxiliadoralugo':

                        $dynamic_db_name = 'rese_clgmauxiliadora';
                        $config = \Config::get('database.connections.mysql');

                        $config['database'] = $dynamic_db_name;
                        $config['password'] = "#3p720hqK";
                        config()->set('database.connections.mysql', $config);
                        \DB::purge('mysql');
                    break;

                    case 'santaella':

                        $dynamic_db_name = 'rese_santaella';
                        $config = \Config::get('database.connections.mysql');

                        $config['database'] = $dynamic_db_name;
                        $config['password'] = "#3p720hqK";
                        config()->set('database.connections.mysql', $config);
                        \DB::purge('mysql');
                    break;

                    case 'santaella':
                    
                        $dynamic_db_name = 'rese_santaella';
                        $config = \Config::get('database.connections.mysql');
                        
                        $config['database'] = $dynamic_db_name;
                        $config['password'] = "#3p720hqK";
                        config()->set('database.connections.mysql', $config);
                        \DB::purge('mysql');
                    break;

                    case'manager':
                        $dynamic_db_name = 'manager_reservas';
                        $config = \Config::get('database.connections.mysql');

                        $config['database'] = $dynamic_db_name;
                        $config['password'] = "#3p720hqK";
                        config()->set('database.connections.mysql', $config);
                        \DB::purge('mysql');

                        break;
                    default:
                        abort(404);
                        break;

                }
                $variablebd=false;
            } else {
                if (Auth::check()) {
                    return redirect()->back()->with('mensaje','Está intentando cambiar de instalación deportiva, por favor cierre sesión antes. Gracias!');
                }
                //Auth::logout();
            }
        }

        if($variablebd) {
            switch ($request->slug_instalacion) {
                case'vvadecordoba':
                    $_SESSION['bd']='vvadecordoba';
                    $dynamic_db_name = 'reservas_vva';
                    $config = \Config::get('database.connections.mysql');

                    $config['database'] = $dynamic_db_name;
                    $config['password'] = "#3p720hqK";
                    config()->set('database.connections.mysql', $config);
                    \DB::purge('mysql');

                    break;

                case'campamentos-vva':
                    $_SESSION['bd']='campamentos-vva';
                    $dynamic_db_name = 'rese_multivillanueva';
                    $config = \Config::get('database.connections.mysql');

                    $config['database'] = $dynamic_db_name;
                    $config['password'] = "#3p720hqK";
                    config()->set('database.connections.mysql', $config);
                    \DB::purge('mysql');

                    break;
                case'villafranca-de-cordoba':
                    $_SESSION['bd']='villafranca-de-cordoba';
                    $dynamic_db_name = 'rese_villafranca';
                    $config = \Config::get('database.connections.mysql');
                    $config['database'] = $dynamic_db_name;
                    $config['password'] = "#3p720hqK";
                    config()->set('database.connections.mysql', $config);
                    \DB::purge('mysql');

                    break;
                case'la-guijarrosa':
                    $_SESSION['bd']='la-guijarrosa';
                    $dynamic_db_name = 'rese_guijarrosa';

                    $config = \Config::get('database.connections.mysql');

                    $config['database'] = $dynamic_db_name;
                    $config['password'] = "#3p720hqK";
                    config()->set('database.connections.mysql', $config);
                    \DB::purge('mysql');

                    break;
                case'superate':
                    $_SESSION['bd']='superate';
                    $dynamic_db_name = 'rese_superate';

                    $config = \Config::get('database.connections.mysql');

                    $config['database'] = $dynamic_db_name;
                    $config['password'] = "#3p720hqK";
                    config()->set('database.connections.mysql', $config);
                    \DB::purge('mysql');
    
                    break;
                case'demo':

                $dynamic_db_name = 'rese_demo';

                    $config = \Config::get('database.connections.mysql');
                    $_SESSION['bd']='demo';
                    $config['database'] = $dynamic_db_name;
                    $config['password'] = "#3p720hqK";
                    config()->set('database.connections.mysql', $config);
                    \DB::purge('mysql');

                    break;

                case 'mercadillos-villafranca-de-cordoba':

                    $dynamic_db_name = 'rese_mercadillos';
                    $_SESSION['bd']='mercadillos-villafranca-de-cordoba';
                    $config = \Config::get('database.connections.mysql');

                    $config['database'] = $dynamic_db_name;
                    $config['password'] = "#3p720hqK";
                    config()->set('database.connections.mysql', $config);
                    \DB::purge('mysql');

                    break;

                case 'reservas-montoro':
                    $dynamic_db_name = 'rese_montoro';
                    $_SESSION['bd']='reservas-montoro';
                    $config = \Config::get('database.connections.mysql');
                    $config['database'] = $dynamic_db_name;
                    $config['password'] = "#3p720hqK";
                    config()->set('database.connections.mysql', $config);
                    \DB::purge('mysql');
                    break;

                case 'ciprea24':
                    $dynamic_db_name = 'ciprea24entradas';
                    $_SESSION['bd']='ciprea24';
                    $config = \Config::get('database.connections.mysql');
                    $config['database'] = $dynamic_db_name;
                    $config['password'] = "#3p720hqK";
                    config()->set('database.connections.mysql', $config);
                    \DB::purge('mysql');
                    break;

                case 'los-agujetas-de-villafranca':

                    $dynamic_db_name = 'rese_agujetas';
                    $_SESSION['bd']='los-agujetas-de-villafranca';
                    $config = \Config::get('database.connections.mysql');

                    $config['database'] = $dynamic_db_name;
                    $config['password'] = "#3p720hqK";
                    config()->set('database.connections.mysql', $config);
                    \DB::purge('mysql');

                    break;

                case 'khalifa-padel':

                    $dynamic_db_name = 'rese_khalifapadel';
                    $_SESSION['bd']='khalifa-padel';
                    $config = \Config::get('database.connections.mysql');

                    $config['database'] = $dynamic_db_name;
                    $config['password'] = "#3p720hqK";
                    config()->set('database.connections.mysql', $config);
                    \DB::purge('mysql');

                    break;

                case 'mauxiliadoralugo':

                    $dynamic_db_name = 'rese_clgmauxiliadora';
                    $_SESSION['bd']='mauxiliadoralugo';
                    $config = \Config::get('database.connections.mysql');

                    $config['database'] = $dynamic_db_name;
                    $config['password'] = "#3p720hqK";
                    config()->set('database.connections.mysql', $config);
                    \DB::purge('mysql');

                    break;

                    case 'santaella':
                        $_SESSION['bd']='santaella';
                        $dynamic_db_name = 'rese_santaella';
                        $config = \Config::get('database.connections.mysql');
                        $config['database'] = $dynamic_db_name;
                        $config['password'] = "#3p720hqK";
                        config()->set('database.connections.mysql', $config);
                        \DB::purge('mysql');
                        break;

                    case 'feria-jamon-villanuevadecordoba':

                        $dynamic_db_name = 'rese_jamon';
                        $_SESSION['bd']='feria-jamon-villanuevadecordoba';
                        $config = \Config::get('database.connections.mysql');

                        $config['database'] = $dynamic_db_name;
                        $config['password'] = "#3p720hqK";
                        config()->set('database.connections.mysql', $config);
                        \DB::purge('mysql');

                        break;

                case 'villafranca-navidad':


                    $dynamic_db_name = 'rese_villafranca_navidad';
                    $_SESSION['bd']='villafranca-navidad';
                    $config = \Config::get('database.connections.mysql');

                    $config['database'] = $dynamic_db_name;
                    $config['password'] = "#3p720hqK";
                    config()->set('database.connections.mysql', $config);
                    \DB::purge('mysql');

                    break;

                case 'villafranca-actividades':

                        $dynamic_db_name = 'rese_villafranca_actividades';
                        $_SESSION['bd']='villafranca-actividades';
                        $config = \Config::get('database.connections.mysql');

                        $config['database'] = $dynamic_db_name;
                        $config['password'] = "#3p720hqK";
                        config()->set('database.connections.mysql', $config);
                        \DB::purge('mysql');
                        break;

                case 'eventos-bodega':
                    $dynamic_db_name = 'eventos_bodega';
                    $_SESSION['bd']='eventos-bodega';
                    $config = \Config::get('database.connections.mysql');
                    $config['database'] = $dynamic_db_name;
                    $config['password'] = "#3p720hqK";
                    config()->set('database.connections.mysql', $config);
                    \DB::purge('mysql');
                    break;

                case'manager':
                    $_SESSION['bd']='manager';
                    $dynamic_db_name = 'manager_reservas';
                    $config = \Config::get('database.connections.mysql');
                    $config['database'] = $dynamic_db_name;
                    $config['password'] = "#3p720hqK";
                    config()->set('database.connections.mysql', $config);
                    \DB::purge('mysql');

                    break;
                default:
                    abort(404);
                    break;
            }
        }

        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        if ($instalacion || $request->slug_instalacion == 'manager') {
            return $next($request);
        }
        abort(404);
    }
}
?>
