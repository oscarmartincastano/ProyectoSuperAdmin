<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;
use Ssheduardo\Redsys\Facades\Redsys;
use MikeMcLin\WpPassword\Facades\WpPassword;

use App\Mail\NewReserva;
use App\Mail\NewInscripcion;

use App\Models\Pedido;
use App\Models\Participante;
use App\Models\Evento;
use App\Models\User;

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/* Route::get('/arreglo-admin-reserva', 'InstalacionController@arreglos_reservas'); */
Route::get('testphp', fn() => phpinfo());

Route::get('/', function () {
    return view('index');
});

Route::get('/agente-digitalizador', function () {
    return view('agenteDigitalizador');
});

Route::get('/debug_status', function () {
    if (config('app.debug')) {
        return 1;
    } else {
        return '';
    }
});

route::get('/backups416546454653', function () {
    \Illuminate\Support\Facades\Artisan::call('database:backup');
});
Route::get('/condiciones-generales', function () {
    return view('index.condicionesgeneraleshome');
});

Route::get('/privacidad', function () {
    return view('index.privacidad');
});
Route::get('/terminos-condiciones', function () {
    return view('index.tyc');
});
Route::get('/seleccion-instalacion', 'UserController@buscarInstalacion');
Route::get('/seleccion-instalacion/search', 'UserController@search')->name('search');

Route::get('cache', function () {
    $exitCode2 = \Artisan::call('config:clear');
    $exitCode3 = \Artisan::call('route:clear');
    $exitCode4 = \Artisan::call('view:clear');
    return 'Cache correctamente';
});

Route::middleware(['cors'])->group(function () {
    Route::post('notificacion', 'RedsysController@notificacion');
    Route::post('notificacion2', 'RedsysController@notificacion2');
    Route::get('ajax/checkIntervalo', function (Request $request) {
        $result = false;
        $check = \App\Models\Reserva::where('id_usuario', $request->id_usuario)
            ->where('id_pista', $request->id_pista)
            ->where('timestamp', $request->timestamp)
            ->where('estado', 'pendiente')
            ->where('created_at', '<=', \Carbon\Carbon::now()->addMinutes(5))
            ->first();
        if ($check != null) {
            $result = true;
        }
        return $result;
    });
});

//Route::get('redsys/pagar/{id_pedido}', 'RedsysController@pagar_pendiente')->name('redsys.pagar');

Route::get('import', function () {
    $filename = 'users.csv';
    $delimiter = ';';
    $users = exportCSV($filename, $delimiter);

    /*foreach($users as $user) {
        App\Models\User::create([
            'id_instalacion' => 1,
            'name' => $user['name'],
            'email' => $user['email'],
            'tlfno' => $user['tlf'],
            'direccion' => $user['address'],
            'codigo_postal' => $user['cp'],
            'password' => $user['pass'],
            'aprobado' => date('Y-m-d H:i:s'),
            'rol' => 'user',
        ]);
    }

    App\Models\User::create([
        'id_instalacion' => 1,
        'name' => 'Taller',
        'email' => 'desarrolloweb@tallerempresarial.es',
        'password' => '$2a$12$KwAgYaIQewbPOAZmenzsLeRBnsrAffukiJJ8aJyGCqrlhCs1Z1fWO',
        'aprobado' => date('Y-m-d H:i:s'),
        'rol' => 'admin'
    ]);*/

    echo 'Importación correcta';
});

/* Route::get('/vvadecordoba', function() {
    return view('mant');
}); */

Route::group(['prefix' => 'manager'], function () {
    Route::group(['prefix' => '/', 'middleware' => 'auth_manager'], function () {
        Route::get('/', 'ManagerController@index');

        Route::group(['prefix' => 'instalaciones'], function () {
            Route::get('/', 'ManagerController@index');
            Route::get('/add', 'ManagerController@add_instalacion_view');
            Route::post('/add', 'ManagerController@add_instalacion');

            Route::group(['prefix' => '{id}'], function () {
                Route::get('/', 'ManagerController@ver_instalacion');
                Route::get('/edit', 'ManagerController@edit_instalacion_view');
                Route::post('/edit', 'ManagerController@edit_instalacion');
            });
        });

        Route::group(['prefix' => 'servicios'], function () {
            Route::get('/', 'ManagerController@list_servicios');

            Route::get('/add', 'ManagerController@add_servicio_view');
            Route::post('/add', 'ManagerController@add_servicio');

            Route::group(['prefix' => '{id}'], function () {
                Route::get('/', 'ManagerController@edit_servicio_view');
                Route::post('/', 'ManagerController@edit_servicio');
            });
        });

        Route::group(['prefix' => 'deportes'], function () {
            Route::get('/', 'ManagerController@list_deportes');

            Route::get('/add', 'ManagerController@add_deporte_view');
            Route::post('/add', 'ManagerController@add_deporte');

            Route::group(['prefix' => '{id}'], function () {
                Route::get('/', 'ManagerController@edit_deporte_view');
                Route::post('/', 'ManagerController@');
            });
        });

        Route::group(['prefix' => 'devoluciones'], function () {
            Route::get('/', 'ManagerController@devoluciones');
            Route::post('{id}/devolver', 'ManagerController@devolver_pedido');
        });
    });
});

Route::group(['prefix' => '{slug_instalacion}', 'middleware' => 'check_instalacion'], function () {
    Route::get('/pruebaemail', function () {
        // Aquí debes crear o obtener un usuario y un participante de prueba
        $user = User::find(3555); // Cambia el ID según lo necesites
        $participante = Participante::find(517); // Cambia el ID según lo necesites
        // Asegúrate de que los modelos existen
        if ($user && $participante) {
            Mail::to($user->email)->send(new NewInscripcion($user, $participante));

            return 'Correo enviado exitosamente!';
        }

        return 'Usuario o participante no encontrado.';
    });

    route::get('/pruebaevento', function () {
        $evento = Participante::where('id_pedido', 'evbo83vFqp')->first()->evento;
        $participantes = Participante::where('id_pedido', 'evbo83vFqp')->get();
        $pedido = Pedido::where('id', 'evbo83vFqp')->first();

        $pedido->update(['estado' => 'pagado']);
        Participante::where('id_pedido', 'evbo83vFqp')->update(['estado' => 'active']);

        if (request()->slug_instalacion == 'villafranca-navidad' or request()->slug_instalacion == 'villafranca-actividades' or request()->slug_instalacion == 'ciprea24' or request()->slug_instalacion == 'eventos-bodega' or request()->slug_instalacion == 'feria-jamon-villanuevadecordoba') {
            \Mail::to($pedido->user->email)->send(new NewInscripcion($pedido->user, $participantes[0]));
        } else {
            foreach ($participantes as $particip) {
                \Mail::to($pedido->user->email)->send(new NewInscripcion($pedido->user, $particip));
            }
        }
    });

    Route::get('/loginadmin43213/{id}', function () {
        $user = \App\Models\User::find(request()->id);
        auth()->login($user);
        return redirect(request()->slug_instalacion);
    });

    Route::get('/terminos-de-compra', function () {
        return view('instalacion.terminos_compra');
    });
    Route::get('/terminos-de-compra/en', function () {
        return view('instalacion.terminos_compra_en');
    });
    Route::get('/testParticipantes', function () {
        $participante = \App\Models\Participante::where('id', 1764)->first();
        if (isset($participante->valores_campos_personalizados[1])) {
            if ($participante->valores_campos_personalizados[1]->valor == "Solo DJ's") {
                echo '3';
            } else {
                echo '18';
            }
            dd($participante->valores_campos_personalizados[1]->valor);
        }
    });

    Route::group(['prefix' => 'api'], function () {
        Route::get('/', 'UserController@escaner');
        Route::get('/escaner2', 'UserController@escaner2');
        Route::get('/escaner3', 'UserController@escaner3');
        Route::get('/escaner4', 'UserController@escaner4');
        Route::get('/escaner5', 'UserController@escaner5');
        Route::get('/escaner6', 'UserController@escaner6');
        Route::get('/escaner7', 'UserController@escaner7');
        Route::get('/escaner8', 'UserController@escaner8');
        Route::get('/escaner9', 'UserController@escaner9');

        Route::post('logs', 'UserController@logs');
        Route::post('escanear', 'UserController@escanear');
        Route::post('escanear2', 'UserController@escanear2');
        Route::post('escanear3', 'UserController@escanear3');
        Route::post('escanear4', 'UserController@escanear4');
        Route::post('escanear5', 'UserController@escanear5');
        Route::post('escanear6', 'UserController@escanear6');
        Route::post('escanear7', 'UserController@escanear7');
        Route::post('escanear8', 'UserController@escanear8');
        Route::post('escanear9', 'UserController@escanear9');
    });
    Route::get('/descargar-entradas/{id}', 'UserController@descargar_entradas')->name('descargar-entradas');

    Route::get('redsys/pagar/{id_pedido}', 'RedsysController@pagar_pendiente')->name('redsys.pagar');

    route::get('/backups45687987934', function () {
        \Illuminate\Support\Facades\Artisan::call('database:backup');
    });

    Route::get('/recibo321456123', 'InstalacionController@generar_recibo_servicios');
    Route::get('/recibo564644155', 'InstalacionController@generar_recibo_servicios_anual');
    Route::get('/recibo564643311', 'InstalacionController@generar_recibo_trim_sem');

    Route::get('/checkentrada123321', 'InstalacionController@enviocorreoentradas');
    Route::get('/crearentrada123321', 'InstalacionController@crearentradascron');

    /* Route::get('/pruebarecibo','InstalacionController@generar_recibo_servicios_pruebas'); */

    Route::get('/pago_recibos321456123/{tipo}', 'RedsysController@bluclepago');

    route::get('gestion-listado', 'UserController@index_listado');
    Route::post('gestion-listado', 'UserController@check')->name('password.check');

    /* Route::get('/', 'UserController@index')->name('pista'); */

    Route::get('/', 'UserController@index_nuevo')->name('pista');

    Route::get('/new', 'UserController@index_nuevo');

    Route::get('/new/mis-reservas', 'UserController@mis_reservas_new');
    Route::get('/new/perfil', 'UserController@mi_perfil_new');
    Route::get('/new/mis-eventos', 'UserController@mis_eventos');
    Route::get('/new/mis-bonos', 'BonosController@mis_bonos');
    Route::get('/new/mis-servicios', 'UserController@mis_servicios');
    Route::post('/new/perfil', 'UserController@edit_perfil');
    Route::get('/new/contacto', 'UserController@contacto_instalacion_new');

    Route::get('/pistas-por-deporte/{deporte}/{fecha}', 'UserController@pistas_por_deportes_fecha');
    Route::get('/pistas-por-deporte-mes/{deporte}/{mes}/{year}', 'UserController@pistas_por_deportes_mes');

    //Dar de baja un servicio
    Route::get('/new/mis-servicios/{servicio}/baja', 'UserController@baja_servicio')->name('servicio.baja');

    //Ver recibos
    Route::get('/new/mis-recibos', 'UserController@ver_recibos');

    Route::get('/normas', 'UserController@normas_instalacion');
    /* Route::get('/contacto', 'UserController@contacto_instalacion'); */
    Route::get('/noticias', 'UserController@anuncios_publicos');
    Route::get('/contacto', 'UserController@contacto_instalacion_new');
    Route::post('/contacto', 'UserController@enviar_contacto_instalacion');
    Route::get('/condiciones-generales', 'UserController@condiciones_generales');
    Route::get('/privacidad', 'UserController@privacidad');
    Route::get('/terminos-condiciones', 'UserController@terminos_condiciones');
    Route::post('/aperturaadmin', 'AperturaController@index');
    Route::post('/apertura-torno', 'AperturaController@tornoentrada');
    Route::post('/salida-torno', 'AperturaController@tornosalida');
    Route::post('/entrada-portillo', 'AperturaController@portilloentrada');
    Route::post('/salida-portillo', 'AperturaController@portillosalida');
    Route::post('/apertura-gym', 'AperturaController@apertura_gym');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->middleware('guest')
        ->name('login_instalacion');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('guest')
        ->name('login_instalacion');
    Route::get('/mantener-instalacion', 'UserController@mantener_instalacion');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->middleware('guest')
        ->name('forgot_password_instalacion');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('guest');

    Route::get('/register', [RegisteredUserController::class, 'create_user_instalacion'])
        ->middleware('guest')
        ->name('register_user_instalacion');

    Route::post('/register', [RegisteredUserController::class, 'store_instalacion'])->middleware('guest');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->middleware('guest')
        ->name('password.reset_user_instalacion');

    Route::get('/prueba3', function () {
        $exitCode2 = \Artisan::call('config:clear');
        $exitCode3 = \Artisan::call('route:cache');
        $exitCode4 = \Artisan::call('view:clear');

        echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
    });

    Route::get('/prueba5', function () {
        check_cookie();
    });

    Route::get('redsys', ['as' => 'redsys', 'uses' => 'RedsysController@index']);
    Route::get('/ko', 'RedsysController@ko');
    Route::get('/ok', 'RedsysController@ok');
    /* Route::get('/notificacion', 'RedsysController@notificacion'); */
    Route::post('/notificacion', 'RedsysController@notificacion')->middleware('cors');
    Route::post('/notificacion2', 'RedsysController@notificacion2')->middleware('cors');

    Route::get('/evento/{id}', 'UserController@inscripcion_evento');
    Route::get('/pago_recurrente', 'RedsysController@bluclepago');
    Route::middleware(['auth_instalacion'])->group(function () {
        Route::get('/servicios/{servicio}/contratar', 'ServiciosController@contratar')->name('contratar_servicio');
        Route::get('/servicios/{servicio}/contratar-de-nuevo', 'ServiciosController@contratar_de_nuevo')->name('contratar_nuevo_servicio');
        Route::get('/servicios/{servicio}/{recibo}/renovar', 'ServiciosController@renovarservicio')->name('renovar.servicio');

        Route::post('/servicios/{servicio}/contratar-de-nuevo', 'RedsysController@contratar_servicio_nuevo');
        Route::post('/servicios/{servicio}/contratar', 'RedsysController@contratarservicio');
        Route::post('/servicios/{servicio}/{recibo}/renovar', 'RedsysController@renovar_servicio');

        //Contratar bono
        Route::get('/bonos/{bono}/contratar', 'BonosController@contratar')->name('contratar_bono');
        Route::post('/bonos/{bono}/contratar', 'RedsysController@pagobono');

        Route::prefix('evento/{id}')->group(function () {
            Route::post('', 'RedsysController@pago_inscripcion');
        });
        /* Route::get('/mis-reservas', 'UserController@mis_reservas');
        Route::get('/mis-eventos', 'UserController@mis_eventos');
        Route::post('/mis-reservas/{id}/cancel', 'UserController@cancel_reservas');
        Route::get('/perfil', 'UserController@perfil');
        Route::post('/perfil/edit', 'UserController@edit_perfil'); */
        Route::post('/mis-reservas/{id}/cancel', 'UserController@cancel_reservas');
        Route::get('/mis-reservas', 'UserController@mis_reservas_new');
        Route::get('/mis-eventos', 'UserController@mis_eventos');
        Route::get('/mis-eventos/{id}/renovar', 'RedsysController@pago_renovacion');
        Route::get('/perfil', 'UserController@mi_perfil_new');
        Route::post('/perfil', 'UserController@edit_perfil');
        Route::post('/perfil/delete', 'UserController@delete_perfil')->name('delete.perfil');
        Route::get('/anuncios', 'UserController@anuncios');
    });

    Route::group(['prefix' => 'admin', 'middleware' => 'auth_admin_instalacion'], function () {
        Route::get('/', 'InstalacionController@index');
        Route::prefix('reservas')->group(function () {
            Route::get('/', 'InstalacionController@index');
            Route::get('/list', 'InstalacionController@listado_todas_reservas');
            Route::get('/list-piscina', 'InstalacionController@listado_reservas_piscina')->name('reservas.list_piscina');
            Route::get('/list-piscina-asistentes', 'InstalacionController@listado_asistentes_piscina')->name('reservas.asistentes_list_piscina');
            Route::post('/list-datatable', 'InstalacionController@reservas_list_datatable')->name('reservas.list_datatable');
            Route::get('/periodicas', 'InstalacionController@reservas_periodicas');
            Route::get('/periodicas/add', 'InstalacionController@add_reservas_periodicas_view');
            Route::post('/periodicas/add', 'InstalacionController@add_reservas_periodicas')->name('add_reserva_periodica');
            Route::get('/periodicas/{id}/borrar', 'InstalacionController@borrar_reservas_periodicas');

            Route::get('/desactivaciones', 'InstalacionController@desactivaciones_periodicas');
            Route::get('/desactivaciones/add', 'InstalacionController@add_desactivaciones_periodicas_view');
            Route::post('/desactivaciones/add', 'InstalacionController@add_desactivaciones_periodicas')->name('add_desactivacion');
            Route::get('/desactivaciones/{id}/borrar', 'InstalacionController@borrar_desactivaciones_periodicas');

            Route::get('/cancelar-reserva/{id}', 'InstalacionController@cancelar_reserva');
            Route::get('/actualizar-asistencia/{id}/{estado}', 'InstalacionController@actualizar_asistencia')->name('reserva.actualizar_asistencia');

            Route::get('/numero/{fecha}', 'InstalacionController@numero_reservas_dia_por_pista');
            Route::get('/{fecha}', 'InstalacionController@reservas_dia');
            Route::get('/{fecha}/{id_pista}', 'InstalacionController@reservas_dia_por_pista');
            Route::post('/validar/{id}', 'InstalacionController@validar_reserva');
            Route::post('/reschedule/{id}', 'InstalacionController@reschedule_reserva');
            Route::get('/{id_pista}/reservar/{timestamp}', 'InstalacionController@hacer_reserva_view');
            Route::post('/{id_pista}/reservar/{timestamp}', 'InstalacionController@hacer_reserva');
            Route::post('/{id_pista}/desactivar/{timestamp}', 'InstalacionController@desactivar_tramo');
            Route::post('/{id_pista}/activar/{timestamp}', 'InstalacionController@activar_tramo');
            Route::get('/{id_pista}/desactivar-dia/{dia}', 'InstalacionController@desactivar_dia');
            Route::get('/{id_pista}/activar-dia/{dia}', 'InstalacionController@activar_dia');
            Route::post('/periodicas/comprobar-reservas', 'InstalacionController@comprobar_reservas_en_reserva_periodica')->name('comprobar_reservas_reserva_periodica');
        });

        Route::prefix('orders')->group(function () {
            Route::get('/', 'InstalacionController@listado_pedidos');
            Route::get('create-entradas', 'InstalacionController@crear_entradas');
            Route::post('create-entradas', 'InstalacionController@crear_entradas_form');
            Route::get('informes', 'InstalacionController@informes_pedidos')->name('pedidos.informes');
            Route::get('{tipo_pedido}', 'InstalacionController@listado_pedidos');
            Route::get('reservas', 'InstalacionController@listado_pedidos_reservas');
            Route::get('eventos', 'InstalacionController@listado_pedidos_eventos');
            Route::get('eventos/search', 'InstalacionController@listado_pedidos_eventos_search');

            Route::get('servicios', 'ServiciosController@listado_pedidos');
            Route::get('{id}/devolver', 'InstalacionController@devolver_pedido');
            /* Route::get('{id}/devolver-manager','InstalacionController@devolver_pedido_manager')->name('devolucion.manager'); */
            Route::get('{id}/print', 'InstalacionController@print_order');
            Route::get('{id}/send', 'InstalacionController@send_order');
            Route::get('{id}/send_pedido', 'InstalacionController@send_pedido');
            Route::get('{id}', 'InstalacionController@ver_pedido');
            Route::get('/ver-pdf/{id}/ver', 'InstalacionController@ver_pdf_entradas');
        });

        Route::prefix('puertas')->group(function () {
            Route::get('/listado', 'InstalacionController@listado_accesos')->name('edit_config_inst');
            Route::get('/usuarios_accesos', 'InstalacionController@listado_usuarios')->name('listado_usuarios');
            Route::get('/usuarios_accesos/{id}/borrar', 'InstalacionController@eliminar_usuario_acceso')->name('usuario_delete');
            Route::get('/usuarios_accesos/{id}/edit', 'InstalacionController@edit_usuario_acceso')->name('usuario_edit');
            Route::post('/usuarios_accesos/{id}/update', 'InstalacionController@edit_usuario_acceso_form')->name('usuario_update');
            Route::get('/usuarios_accesos/crear', 'InstalacionController@vista_nuevo_acceso')->name('usuarios_create');
            Route::post('/usuarios_accesos/guardar', 'InstalacionController@nuevo_acceso')->name('usuario_store');
            Route::get('/usuarios_accesos/{id}', 'InstalacionController@ver_accesos_usuario')->name('usuario_accesos');
        });

        Route::prefix('servicios')->group(function () {
            Route::get('/', 'InstalacionController@listar_servicios_clientes');
        });

        Route::prefix('mensajes')->group(function () {
            Route::get('/', 'InstalacionController@list_msg');
            Route::get('/add', 'InstalacionController@create_msg_view');
            Route::post('/add', 'InstalacionController@create_msg');

            Route::prefix('{id}')->group(function () {
                Route::get('/edit', 'InstalacionController@edit_msg_view');
                Route::post('/edit', 'InstalacionController@edit_msg');

                Route::get('/delete', 'InstalacionController@delete_msg');
            });
        });

        Route::prefix('pistas')->group(function () {
            Route::get('/', 'InstalacionController@pistas');
            Route::get('add', 'InstalacionController@add_pista_view');
            Route::post('add/annadir', 'InstalacionController@add_pista')->name('add_pista');
            Route::prefix('{id}')->group(function () {
                Route::get('edit', 'InstalacionController@edit_pista_view');
                Route::post('edit/annadir', 'InstalacionController@edit_pista')->name('edit_pista');
                Route::get('desactivar', 'InstalacionController@desactivar_pista')->name('desactivar_pista');
            });
        });

        Route::prefix('eventos')->group(function () {
            Route::get('/', 'InstalacionController@list_eventos');
            Route::get('/add', 'InstalacionController@create_evento_view');
            Route::post('/add', 'InstalacionController@create_evento');

            Route::get('/lectores', 'InstalacionController@listado_lectores');

            Route::get('/tipos-clientes', 'InstalacionController@tipos_participante');
            Route::get('/tipos-clientes/add', 'InstalacionController@add_tipos_participante_view');
            Route::post('/tipos-clientes/add', 'InstalacionController@add_tipos_participante');
            Route::get('/tipos-clientes/remove/{id}', 'InstalacionController@remove_tipo_participante');

            Route::get('/listado-participantes', 'InstalacionController@list_participantes');

            Route::get('/informes-participantes', 'InstalacionController@view_informes_participantes')->name('participantes_informes');

            Route::get('/checkin', 'InstalacionController@checkin_participantes');
            Route::post('/checkinFilter', 'InstalacionController@checkin_participantes')->name('checkin_participantes_filter');

            Route::prefix('{id}')->group(function () {
                Route::get('', 'InstalacionController@ver_evento');
                Route::get('edit', 'InstalacionController@create_evento_view');
                Route::post('edit', 'InstalacionController@create_evento');

                Route::get('delete', 'InstalacionController@delete_evento');

                Route::prefix('participante')->group(function () {
                    Route::get('{id_participante}', 'InstalacionController@edit_participante_view');
                    Route::post('{id_participante}', 'InstalacionController@edit_participante');

                    Route::get('{id_participante}/delete', 'InstalacionController@delete_participante');
                });

                /* Route::get('/edit', 'InstalacionController@edit_msg_view');
                Route::post('/edit', 'InstalacionController@edit_msg');

                Route::get('/delete', 'InstalacionController@delete_msg'); */
            });
        });

        Route::prefix('users')->group(function () {
            Route::get('/', 'InstalacionController@users');
            Route::get('/novalid', 'InstalacionController@users_no_valid');
            Route::get('add', 'InstalacionController@add_user_view');
            Route::post('add/annadir', 'InstalacionController@add_user')->name('add_user');
            Route::post('/list-datatable', 'InstalacionController@users_list_datatable')->name('users.list_datatable');
            Route::prefix('{id}')->group(function () {
                Route::get('/', 'InstalacionController@edit_user_view');
                Route::post('/', 'InstalacionController@editar_user');
                Route::get('/cambiar-foto', 'InstalacionController@cambiar_foto_user');
                Route::get('/validar', 'InstalacionController@validar_user');
                Route::get('/borrar-permanente', 'InstalacionController@borrar_permanente_user');
                Route::get('/ver', 'InstalacionController@ver_user');
                Route::post('/update-codigo-tarjeta', 'InstalacionController@update_codigo_tarjeta_user');
                Route::post('/check-codigo-tarjeta-exist', 'InstalacionController@check_codigo_tarjeta_exist_user');
                Route::get('/cobro/add', 'InstalacionController@user_add_cobro_view');
                Route::post('/cobro/add', 'InstalacionController@user_add_cobro');
                Route::get('/recibo/add', 'InstalacionController@user_add_recibo_view');
                Route::post('/recibo/add', 'InstalacionController@user_add_recibo');
                Route::get('/recibo/{id_recibo}/delete', 'InstalacionController@user_delete_recibo');
                Route::get('/recibo/{recibo}/edit', 'InstalacionController@edit_recibo');
                Route::post('/recibo/{recibo}/edit', 'InstalacionController@editar_recibo_post');
                Route::post('/{servicio_id}/desactivarservicio', 'InstalacionController@desactivar')->name('updateActivo');
                Route::get('/borrar-recibos', 'InstalacionController@borrarRecibosSinPedido')->name('borrarRecibos');
                Route::get('/servicios/add', 'InstalacionController@user_add_servicio_view');
                Route::post('/servicios/add', 'InstalacionController@user_add_servicio');

                Route::get('/desactivar', 'InstalacionController@desactivar_user');
                Route::post('/update-maximas-reservas', 'InstalacionController@update_max_reservas_user');
            });
        });

        Route::prefix('cobro')->group(function () {
            Route::get('/', 'InstalacionController@list_cobros');
            Route::get('/add', 'InstalacionController@add_cobro_view');
            Route::post('/add', 'InstalacionController@add_cobro');
            Route::prefix('{id}')->group(function () {
                Route::get('/', 'InstalacionController@edit_cobro_view');
                Route::post('/', 'InstalacionController@edit_cobro');
                Route::get('/delete', 'InstalacionController@delete_cobro');
            });
        });

        Route::prefix('configuracion')->group(function () {
            Route::get('/instalacion', 'InstalacionController@configuracion_instalacion')->name('edit_config_inst');
            Route::get('/instalacion/edit/{tipo}', 'InstalacionController@edit_info');
            Route::post('/instalacion/edit/{tipo}', 'InstalacionController@editar_info');
            Route::get('/instalacion/edit/galeria/delete/{nombre_archivo}', 'InstalacionController@eliminar_imagen_galeria');

            Route::get('/pistas-reservas', 'InstalacionController@configuracion_pistas_reservas');
            Route::post('configuracion/edit', 'InstalacionController@edit_configuracion')->name('edit_config');

            Route::get('/dias-festivos', 'InstalacionController@list_dias_festivos');
            Route::get('/dias-festivos/{id}/edit', 'InstalacionController@edit_view_dia_festivo');
            Route::post('/dias-festivos/{id}/guardar-dia', 'InstalacionController@edit_dia_festivo')->name('edit_festivo');
            Route::get('/dias-festivos/{id}/delete', 'InstalacionController@delete_dia_festivo');

            Route::get('/add-dias-festivos', 'InstalacionController@configuracion_dias_festivos');
            Route::post('configuracion/guardar-dia-festivo', 'InstalacionController@almacenar_festivo')->name('add_festivo');

            /* Servicios */

            Route::get('/servicios', 'ServiciosController@index')->name('servicios');
            Route::get('/servicios/crear', 'ServiciosController@create');
            Route::post('/servicios/store', 'ServiciosController@store')->name('crear_servicio');
            Route::get('/servicios/{servicio}/edit', 'ServiciosController@edit')->name('editar_servicio');
            Route::post('/servicios/{servicio}/update', 'ServiciosController@update')->name('update_servicio');
            Route::get('/servicios/{servicio}/delete', 'ServiciosController@delete')->name('delete_servicio');
            Route::get('/servicios/listar_participantes', 'InstalacionController@list_participantes_servicios')->name('delete_servicio');
            Route::get('/servicios/deportes/{deporte}', 'ServiciosController@deporte');
            Route::post('/servicios/exportar_abonados', 'ServiciosController@exportar_abonados')->name('exportar_abonados');

            /* Bonos */

            Route::get('/bonos', 'BonosController@index')->name('bonos');
            Route::get('/bonos/crear', 'BonosController@create');
            Route::post('/bonos/store', 'BonosController@store')->name('crear_bono');
            Route::get('/bonos/{bono}/edit', 'BonosController@edit')->name('editar_bono');
            Route::post('/bonos/{bono}/update', 'BonosController@update')->name('update_bono');
            Route::get('/bonos/deportes/{deporte}', 'BonosController@deporte');
            Route::get('/bonos/{bono}/delete', 'BonosController@delete')->name('delete_bono');
        });

        Route::prefix('campos-adicionales')->group(function () {
            Route::get('/', 'InstalacionController@campos_adicionales');
            Route::get('/campos-personalizados', 'InstalacionController@view_campos_personalizados');
            Route::post('/campos-personalizados', 'InstalacionController@add_campos_personalizados');
            Route::get('/campos-personalizados/{id}', 'InstalacionController@view_edit_campos_personalizados');
            Route::post('/campos-personalizados/{id}', 'InstalacionController@edit_campos_personalizados');
            Route::get('/campos-personalizados/{id}/delete', 'InstalacionController@delete_campos_personalizados');
        });
    });

    Route::group(['prefix' => '{deporte}'], function () {
        Route::get('/', 'UserController@pistas');
        Route::group(['prefix' => '{id_pista}'], function () {
            Route::get('/', 'UserController@pistas');
            Route::group(['middleware' => 'auth_instalacion'], function () {
                Route::get('/{timestamp}', 'UserController@reserva');
                //Route::post('/{timestamp}/reserva', 'UserController@reservar');
                Route::post('/{timestamp}/reserva', 'RedsysController@pago');
            });
        });
    });
});

Route::get('ok', function () {
    // Params Redsys - ID ORDER

    // Busca slug

    // Redirecciona a pago completado con slug correcto
});

/* Route::get('/peticion', function($requestHttpMethod, $fechaHoraUTC, $requestUri, $param, $bodyParam, $idInstalacion) {
    $secretKey = "secret";
    $requestContentBase64String = "";
    $nonce = com_create_guid();
    $uriEncode = urlencode($requestUri);
    $now = new \DateTime();
    $timestamp = $now->getTimestamp();

    $APPId = "appkey";

    $firma = $APPId . $requestHttpMethod . $uriEncode . $timestamp . $nonce;

    $hmac = hash_hmac('sha256', $firma, $secretKey);

}); */
