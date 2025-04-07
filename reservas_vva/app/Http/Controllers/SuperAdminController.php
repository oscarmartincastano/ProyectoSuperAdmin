<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuperAdmin;
use App\Models\SuperAdminUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SuperAdminController extends Controller
{
    public function index()
    {
        $ayuntamientos = SuperAdmin::all();
        // return $ayuntamientos;
        return view('superadmin.index', compact('ayuntamientos'));
    }

    public function edit($id)
    {
        $ayuntamiento = SuperAdmin::find($id);
    
        if (!$ayuntamiento) {
            return redirect()->route('superadmin.index')->with('error', 'Ayuntamiento no encontrado.');
        }
    
        $bd_nombre = $ayuntamiento->bd_nombre;
    
        // Configuración de la conexión a la base de datos secundaria
        $secondaryDbConfig = [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'port' => '3306',
            'database' => $bd_nombre,
            'username' => 'reservas_vva',
            'password' => '#3p720hqK',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ];
    
        config(['database.connections.secondary' => $secondaryDbConfig]);
        $secondaryConnection = DB::connection('secondary');
    
        // Obtener los servicios desde las tablas
        $servicios = $secondaryConnection->table('servicios')->get();
        $serviciosAdicionales = $secondaryConnection->table('servicios_adicionales')->get();
    
        // Obtener las instalaciones y deserializar los campos
        $aDatos = [];
        $aDatos['instalaciones'] = $secondaryConnection->table('instalaciones')->get();
        foreach ($aDatos['instalaciones'] as $instalacion) {
            $instalacion->horario = @unserialize($instalacion->horario) ?: [];
            $instalacion->servicios = @unserialize($instalacion->servicios) ?: [];
        }
    
        return view('superadmin.edit', compact('ayuntamiento', 'aDatos', 'servicios', 'serviciosAdicionales'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'url' => 'required',
            'bd_nombre' => 'required',
            'ver_sponsor' => 'required|boolean',
        ]);

        // Obtener el ayuntamiento
        $ayuntamiento = SuperAdmin::find($id);
    
        if (!$ayuntamiento) {
            return redirect()->route('superadmin.index')->with('error', 'Ayuntamiento no encontrado.');
        }
    
        // Validar y procesar la URL
        $url = $request->input('url');
        if (!str_starts_with($url, 'https://gestioninstalacion.es/')) {
            if (str_starts_with($url, 'http://') || str_contains($url, 'gestioninstalacion.es')) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['url' => 'La URL debe comenzar con https://gestioninstalacion.es/ o solo el nombre.']);
            }
            $url = 'https://gestioninstalacion.es/' . str_replace(' ', '-', $url);
        }
        $url = strtolower($url);
    
        // Actualizar los datos del ayuntamiento
        $ayuntamiento->update([
            'name' => $request->input('name'),
            'url' => $url,
            'bd_nombre' => $request->input('bd_nombre'),
            'ver_sponsor' => $request->input('ver_sponsor'),
        ]);

        // Configurar la conexión a la base de datos secundaria
        $bd_nombre = $request->input('bd_nombre');
        $secondaryDbConfig = [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'port' => '3306',
            'database' => $bd_nombre,
            'username' => 'reservas_vva',
            'password' => '#3p720hqK',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ];
    
        config(['database.connections.secondary' => $secondaryDbConfig]);
        $secondaryConnection = DB::connection('secondary');
    
        // Verificar si hay instalaciones en la base de datos secundaria
        $instalaciones = $secondaryConnection->table('instalaciones')->count();
    
        if ($instalaciones == 0) {
            // Si no hay instalaciones, llamar a la función agregarInstalacion
            $this->agregarInstalacion($request, $secondaryConnection);
        } else {
            // Actualizar las instalaciones existentes
            foreach ($request->all() as $key => $value) {
                if (preg_match('/^nombre_(\d+)$/', $key, $matches)) {
                    $instalacionId = $matches[1];

                    $request->validate([
                        "nombre_$instalacionId" => 'required',
                        "direccion_$instalacionId" => 'required',
                        "tlfno_$instalacionId" => 'required',
                        "html_normas_$instalacionId" => 'nullable',
                        "servicios_$instalacionId" => 'nullable',
                        "slug_$instalacionId" => 'required',
                        "politica_$instalacionId" => 'nullable',
                        "condiciones_$instalacionId" => 'nullable',
                        "horario_$instalacionId" => 'array',
                    ], [
                        "nombre_$instalacionId.required" => 'El campo nombre es obligatorio.',
                        "direccion_$instalacionId.required" => 'El campo dirección es obligatorio.',
                        "slug_$instalacionId.required" => 'El campo slug es obligatorio.',
                        "horario_$instalacionId.array" => 'El campo horario debe ser un array.',
                    ]);
    
                    $secondaryConnection
                        ->table('instalaciones')
                        ->where('id', $instalacionId)
                        ->update([
                            'nombre' => $value,
                            'direccion' => $request->input("direccion_$instalacionId"),
                            'tlfno' => $request->input("tlfno_$instalacionId"),
                            'html_normas' => $request->input("html_normas_$instalacionId"),
                            'servicios' => serialize($request->input("servicios_$instalacionId")), // Serializar los servicios seleccionados
                            'horario' => serialize($request->input("horario_$instalacionId")), // Serializar el horario
                            'slug' => $request->input("slug_$instalacionId"),
                            'politica' => $request->input("politica_$instalacionId"),
                            'condiciones' => $request->input("condiciones_$instalacionId"),
                            "ver_normas" => $request->input("ver_normas_$instalacionId"),
                            "ver_servicios" => $request->input("ver_servicios_$instalacionId"),
                            "ver_horario" => $request->input("ver_horario_$instalacionId"),
                            "ver_politica" => $request->input("ver_politica_$instalacionId"),
                            "ver_condiciones" => $request->input("ver_condiciones_$instalacionId"),
                        ]);
                }
            }
        }
    
        return redirect()->route('superadmin.index')->with('success', 'Ayuntamiento actualizado con éxito.');
    }

private function agregarInstalacion(Request $request, $secondaryConnection)
{
    // Validar los datos de la instalación
    $request->validate([
        'nombre' => 'required',
        'direccion' => 'required',
        'tlfno' => 'required',
        'html_normas' => 'nullable',
        'servicios' => 'nullable',
        'slug' => 'required',
        'politica' => 'nullable',
        'condiciones' => 'nullable',
        'horario' => 'array',
    ]);

    // Crear la instalación en la base de datos secundaria
    $instalacionId = $secondaryConnection->table('instalaciones')->insertGetId([
        'nombre' => $request->input('nombre'),
        'direccion' => $request->input('direccion'),
        'tlfno' => $request->input('tlfno'),
        'html_normas' => $request->input('html_normas'),
        'servicios' => $request->input('servicios'),
        'slug' => $request->input('slug'),
        'tipo_reservas_id' => 1,
        'politica' => $request->input('politica'),
        'condiciones' => $request->input('condiciones'),
        'horario' => serialize($request->input('horario')), // Serializar el horario
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

public function destroy($id)
{
    // Obtener el ayuntamiento
    $ayuntamiento = SuperAdmin::find($id);

    if (!$ayuntamiento) {
        return redirect()->route('superadmin.index')->with('error', 'Ayuntamiento no encontrado.');
    }

    // Obtener el nombre de la base de datos
    $bdNombre = $ayuntamiento->bd_nombre;

    try {
        // Verificar cuántos registros están conectados a la misma base de datos
        $registrosConectados = SuperAdmin::where('bd_nombre', $bdNombre)->count();

        if ($registrosConectados > 1) {
            // Si hay más de un registro conectado, eliminar solo el registro actual
            $ayuntamiento->delete();
        } else {
            // Si solo hay un registro conectado, eliminar la base de datos y el registro
            DB::statement("DROP DATABASE IF EXISTS `$bdNombre`");
            $ayuntamiento->delete();
        }
    } catch (\Exception $e) {
        return redirect()->route('superadmin.index')->with('error', 'Error al eliminar el registro o la base de datos: ' . $e->getMessage());
    }

    return redirect()->route('superadmin.index')->with('success', 'Ayuntamiento eliminado con éxito.');
}

    public function create()
    {
        return view('superadmin.create');
    }

    public function store(Request $request)
{
    set_time_limit(300); // Aumenta el límite a 300 segundos

    $request->validate([
        'name' => 'required',
        'url' => 'required',
        'bd_nombre' => 'required', // Validar que el campo bd_nombre sea obligatorio
        'direccion' => 'required',
        'tlfno' => 'required',
        'slug' => 'required',
    ]);

    // Obtener el valor del campo URL
    $url = $request->input('url');

    // Validar y procesar la URL
    if (empty($url)) {
        return redirect()
            ->back()
            ->withInput()
            ->withErrors(['url' => 'El campo URL no puede estar vacío.']);
    }

    if (!str_starts_with($url, 'https://gestioninstalacion.es/')) {
        if (str_starts_with($url, 'http://') || str_contains($url, 'gestioninstalacion.es')) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['url' => 'La URL debe comenzar con https://gestioninstalacion.es/ o solo el nombre.']);
        }

        $url = 'https://gestioninstalacion.es/' . str_replace(' ', '-', $url);
    }

    // Convertir la URL a minúsculas
    $url = strtolower($url);

    // Crear o conectar a la base de datos
    $bdNombre = $request->input('bd_nombre');

    try {
        // Verificar si la base de datos ya existe
        $databaseExists = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$bdNombre]);

        if (empty($databaseExists)) {
            // Crear la base de datos si no existe
            DB::statement("CREATE DATABASE `$bdNombre` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            // Configurar una conexión dinámica para la nueva base de datos
            config(['database.connections.dynamic' => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => $bdNombre,
                'username' => env('DB_USERNAME', 'reservas_vva'),
                'password' => env('DB_PASSWORD', '#3p720hqK'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
            ]]);

            $dynamicConnection = DB::connection('dynamic');

            // Leer el archivo SQL y ejecutar las consultas
            $sqlFilePath = base_path('plantilla.sql'); // Ruta al archivo SQL
            $sql = file_get_contents($sqlFilePath);

            // Dividir las consultas por punto y coma
            $queries = array_filter(array_map('trim', explode(';', $sql)));

            foreach ($queries as $query) {
                if (!empty($query)) {
                    $dynamicConnection->statement($query);
                }
            }

            // Insertar una nueva instalación en la tabla `instalaciones`
            $dynamicConnection->table('instalaciones')->insert([
                'nombre' => $request->input('name'),
                'direccion' => $request->input('direccion'),
                'tlfno' => $request->input('tlfno'),
                'slug' => $request->input('slug'),
                'tipo_reservas_id' => 1, // Puedes ajustar este valor según sea necesario
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            // Conectar a la base de datos existente
            config(['database.connections.dynamic' => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => $bdNombre,
                'username' => env('DB_USERNAME', 'reservas_vva'),
                'password' => env('DB_PASSWORD', '#3p720hqK'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
            ]]);

            $dynamicConnection = DB::connection('dynamic');

            // Verificar si hay instalaciones en la tabla
            $instalacion = $dynamicConnection->table('instalaciones')->first();

            if ($instalacion) {
                // Actualizar la instalación existente
                $dynamicConnection->table('instalaciones')->where('id', $instalacion->id)->update([
                    'nombre' => $request->input('name'),
                    'direccion' => $request->input('direccion'),
                    'tlfno' => $request->input('tlfno'),
                    'slug' => $request->input('slug'),
                    'updated_at' => now(),
                ]);
            } else {
                // Crear una nueva instalación si no hay registros
                $dynamicConnection->table('instalaciones')->insert([
                    'nombre' => $request->input('name'),
                    'direccion' => $request->input('direccion'),
                    'tlfno' => $request->input('tlfno'),
                    'slug' => $request->input('slug'),
                    'tipo_reservas_id' => 1, // Puedes ajustar este valor según sea necesario
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    } catch (\Exception $e) {
        // Si ocurre un error, eliminar la base de datos creada y devolver el error
        if (empty($databaseExists)) {
            DB::statement("DROP DATABASE IF EXISTS `$bdNombre`");
        }
        return redirect()
            ->back()
            ->withInput()
            ->withErrors(['error' => 'Error al crear o conectar a la base de datos: ' . $e->getMessage()]);
    }

    // Crear el ayuntamiento en la conexión 'superadmin'
    SuperAdmin::on('superadmin')->create([
        'name' => $request->input('name'),
        'url' => $url,
        'bd_nombre' => $bdNombre, // Guardar el nombre de la base de datos
    ]);

    return redirect()->route('superadmin.index')->with('success', 'Ayuntamiento creado con éxito.');
}

    public function login()
    {
        return view('superadmin.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // Usar la conexión 'superadmin' para autenticar
        $user = \App\Models\SuperAdminUser::on('superadmin')->where('email', $credentials['email'])->first();
    
        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user); // Ahora funciona correctamente
            $request->session()->regenerate();
            return redirect()->route('superadmin.index');
        }
    
        return back()
            ->withErrors([
                'email' => 'Las credenciales no coinciden con nuestros registros.',
            ])
            ->onlyInput('email');
    }

public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('superadmin.login');
}

    public function showCreateUserForm()
{
    return view('superadmin.create-user');
}

public function createUser(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Crear el usuario en la conexión 'superadmin'
    \App\Models\SuperAdminUser::on('superadmin')->create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'password' => Hash::make($request->input('password')),
    ]);

    return redirect()->route('superadmin.index')->with('success', 'Usuario creado con éxito.');
}
}
