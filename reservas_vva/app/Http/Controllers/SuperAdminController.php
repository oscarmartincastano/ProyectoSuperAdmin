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

    // Usar la conexión 'superadmin'
    $superadminConnection = DB::connection('superadmin');

    $aDatos = [];
    $aDatos['instalaciones'] = $secondaryConnection->table('instalaciones')->get();
    $aDatos['usuarios_superadmin'] = $superadminConnection->table('users')->get(); // Ejemplo de consulta a la conexión superadmin

    // Deserializar el campo horario para cada instalación
    foreach ($aDatos['instalaciones'] as $instalacion) {
        $horario = @unserialize($instalacion->horario);

        // Validar si el horario es un array
        if (is_array($horario)) {
            $instalacion->horario = $horario;
        } else {
            // Si no es un array válido, inicializarlo como un array vacío
            $instalacion->horario = [];
        }
    }

    return view('superadmin.edit', compact('ayuntamiento', 'aDatos'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'url' => 'required',
        'bd_nombre' => 'required',
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

    // Actualizar las instalaciones existentes
    foreach ($request->all() as $key => $value) {
        if (preg_match('/^nombre_(\d+)$/', $key, $matches)) {
            $instalacionId = $matches[1];

            $secondaryConnection
                ->table('instalaciones')
                ->where('id', $instalacionId)
                ->update([
                    'nombre' => $value,
                    'direccion' => $request->input("direccion_$instalacionId"),
                    'tlfno' => $request->input("tlfno_$instalacionId"),
                    'html_normas' => $request->input("html_normas_$instalacionId"),
                    'servicios' => $request->input("servicios_$instalacionId"),
                    'horario' => serialize($request->input("horario_$instalacionId")),
                    'slug' => $request->input("slug_$instalacionId"),
                    'politica' => $request->input("politica_$instalacionId"),
                    'condiciones' => $request->input("condiciones_$instalacionId"),
                    'ver_normas' => $request->input("ver_normas_$instalacionId"),
                    'ver_servicios' => $request->input("ver_servicios_$instalacionId"),
                    'ver_horario' => $request->input("ver_horario_$instalacionId"),
                    'ver_politica' => $request->input("ver_politica_$instalacionId"),
                    'ver_condiciones' => $request->input("ver_condiciones_$instalacionId"),
                ]);
        }
    }

    // Verificar si se han enviado datos para una nueva instalación
    if ($request->has('nombre') && $request->has('direccion')) {
        $this->agregarInstalacion($request, $secondaryConnection);
    }

    return redirect()->route('superadmin.index')->with('success', 'Ayuntamiento actualizado con éxito.');
}

private function agregarInstalacion(Request $request, $secondaryConnection)
{
    // Validar los datos de la instalación
    $request->validate([
        'nombre' => 'required',
        'direccion' => 'required',
        'tlfno' => 'nullable',
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
        // Eliminar la base de datos si existe
        DB::statement("DROP DATABASE IF EXISTS `$bdNombre`");
    } catch (\Exception $e) {
        return redirect()->route('superadmin.index')->with('error', 'Error al eliminar la base de datos: ' . $e->getMessage());
    }

    // Eliminar el registro del ayuntamiento
    $ayuntamiento->delete();

    return redirect()->route('superadmin.index')->with('success', 'Ayuntamiento y base de datos eliminados con éxito.');
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
    
        // Crear la base de datos
        $bdNombre = $request->input('bd_nombre');
    
        try {
            // Verificar si la base de datos ya existe
            $databaseExists = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$bdNombre]);
    
            if (!empty($databaseExists)) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['bd_nombre' => 'La base de datos ya existe. Por favor, elige un nuevo nombre.']);
            }
    
            // Crear la base de datos
            DB::statement("CREATE DATABASE `$bdNombre` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
            // Configurar una conexión dinámica para la nueva base de datos
            config(['database.connections.dynamic' => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => $bdNombre,
                'username' => env('DB_USERNAME', 'root'),
                'password' => env('DB_PASSWORD', ''),
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
        } catch (\Exception $e) {
            // Si ocurre un error, eliminar la base de datos creada y devolver el error
            DB::statement("DROP DATABASE IF EXISTS `$bdNombre`");
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear la base de datos: ' . $e->getMessage()]);
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
