<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuperAdmin;
use App\Models\SuperAdminUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

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

        // Obtener las instalaciones y deserializar los campos
        $aDatos = [];
        $aDatos['instalaciones_visualizacion'] = collect($secondaryConnection->table('permisos')->get())->map(function ($item) {
            return (array) $item;
        });
        $aDatos['instalaciones'] = $secondaryConnection->table('instalaciones')->get();

        return view('superadmin.edit', compact('ayuntamiento', 'aDatos'));
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
            'tipo_calendario' => $request->input('calendario'),
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
            $this->actualizarTipoCalendario($request);
            foreach ($request->all() as $key => $value) {
                if (preg_match('/^nombre_(\d+)$/', $key, $matches)) {
                    $instalacionId = $matches[1];

                    $request->validate(
                        [
                            "nombre_$instalacionId" => 'required',
                            "direccion_$instalacionId" => 'required',
                            "tlfno_$instalacionId" => 'required',
                            "slug_$instalacionId" => 'required',
                        ],
                        [
                            "nombre_$instalacionId.required" => 'El campo nombre es obligatorio.',
                            "direccion_$instalacionId.required" => 'El campo dirección es obligatorio.',
                            "slug_$instalacionId.required" => 'El campo slug es obligatorio.',
                        ],
                    );

                    $secondaryConnection
                        ->table('instalaciones')
                        ->where('id', $instalacionId)
                        ->update([
                            'nombre' => $value,
                            'direccion' => $request->input("direccion_$instalacionId"),
                            'tlfno' => $request->input("tlfno_$instalacionId"),
                            'slug' => $request->input("slug_$instalacionId"),
                        ]);

                        $permiso = $secondaryConnection->table('permisos')->where('id_instalacion', $instalacionId)->first();
                    // Actualizar los campos adicionales de visualización
                    foreach ($request->all() as $key => $value) {
                        // Verificar si la clave comienza con "ver_" y no es "ver_sponsor"
                        if (Str::startsWith($key, 'ver_') && $key !== 'ver_sponsor') {
                            // Extraer el nombre de la columna y el ID de la instalación desde la clave
                            if (preg_match('/^(ver_.*)_(\d+)$/', $key, $matches)) {
                                $columnName = $matches[1]; // Nombre de la columna (por ejemplo, "ver_normas")

                                if ($permiso) {
                                    // Actualizar el campo en la base de datos
                                    $secondaryConnection
                                        ->table('permisos')
                                        ->where('id_instalacion', $instalacionId)
                                        ->update([
                                            $columnName => $value, // Actualizar el campo dinámicamente
                                        ]);
                                }
                            }
                        }
                    }
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
            'slug' => 'required',
            'politica' => 'nullable',
        ]);

        // Crear la instalación en la base de datos secundaria
        $instalacionId = $secondaryConnection->table('instalaciones')->insertGetId([
            'nombre' => $request->input('nombre'),
            'direccion' => $request->input('direccion'),
            'tlfno' => $request->input('tlfno'),
            'slug' => $request->input('slug'),
            'tipo_reservas_id' => 1,
            'politica' => $request->input('politica'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function actualizarTipoCalendario(Request $request)
    {
        // Configurar la conexión dinámica a la base de datos app_reservas
        config([
            'database.connections.dynamic' => [
                'driver' => 'mysql',
                'host' => 'localhost',
                'port' => '3306',
                'database' => 'app_reservas',
                'username' => env('DB_USERNAME', 'reservas_vva'),
                'password' => env('DB_PASSWORD', '#3p720hqK'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
            ],
        ]);

        // Conectar a la base de datos dinámica
        $dynamicConnection = DB::connection('dynamic');

        // Recorrer todos los campos del request
        foreach ($request->all() as $key => $value) {
            // Verificar si la clave comienza con "slug_"
            if (preg_match('/^slug_(\d+)$/', $key, $matches)) {
                $instalacionId = $matches[1]; // Extraer el ID de la instalación
                $slug = $value; // El valor del campo slug
                // Buscar el registro que coincida con el slug
                $registro = $dynamicConnection->table('instalaciones')->where('slug', $slug)->first();

                if (!$registro) {
                    return response()->json(['error' => 'Registro no encontrado'], 404);
                }
                // Actualizar el campo tipo_calendario
                $dynamicConnection
                    ->table('instalaciones')
                    ->where('slug', $slug)
                    ->update([
                        'tipo_calendario' => $request->input('calendario'),
                    ]);

                return response()->json(['success' => 'Campo tipo_calendario actualizado correctamente']);
            }
        }

        return response()->json(['error' => 'No se encontró ningún slug válido en la solicitud'], 400);
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
            return redirect()
                ->route('superadmin.index')
                ->with('error', 'Error al eliminar el registro o la base de datos: ' . $e->getMessage());
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

        $this->validateRequest($request);

        $url = $this->processUrl($request->input('url'));
        $bdNombre = $request->input('bd_nombre');

        try {
            $this->createOrUpdateDatabase($bdNombre, $request);
        } catch (\Exception $e) {
            $this->handleDatabaseError($bdNombre, $e);
        }

        $this->createSuperAdminRecord($request, $url, $bdNombre);

        return redirect()->route('superadmin.index')->with('success', 'Ayuntamiento creado con éxito.');
    }

    private function validateRequest(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'url' => 'required',
            'bd_nombre' => 'required',
            'direccion' => 'required',
            'tlfno' => 'required',
            'slug' => 'required',
        ]);
    }

    private function processUrl($url)
    {
        if (empty($url)) {
            throw new \Exception('El campo URL no puede estar vacío.');
        }

        if (!str_starts_with($url, 'https://gestioninstalacion.es/')) {
            if (str_starts_with($url, 'http://') || str_contains($url, 'gestioninstalacion.es')) {
                throw new \Exception('La URL debe comenzar con https://gestioninstalacion.es/ o solo el nombre.');
            }

            $url = 'https://gestioninstalacion.es/' . str_replace(' ', '-', $url);
        }

        return strtolower($url);
    }

    private function createOrUpdateDatabase($bdNombre, Request $request)
    {
        $databaseExists = DB::select('SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?', [$bdNombre]);

        if (empty($databaseExists)) {
            $this->createDatabase($bdNombre);
            $this->initializeDatabase($bdNombre, $request);
        } else {
            $this->updateDatabase($bdNombre, $request);
        }
    }

    private function createDatabase($bdNombre)
    {
        DB::statement("CREATE DATABASE `$bdNombre` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    private function initializeDatabase($bdNombre, Request $request)
    {
        $dynamicConnection = $this->configureDynamicConnection($bdNombre);

        $this->executeSqlFile($dynamicConnection, base_path('plantilla.sql'));

        $idInstalacion = $dynamicConnection->table('instalaciones')->insertGetId([
            'nombre' => $request->input('name'),
            'direccion' => $request->input('direccion'),
            'tlfno' => $request->input('tlfno'),
            'slug' => $request->input('slug'),
            'tipo_reservas_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $dynamicConnection->table('permisos')->insert([
            'id_instalacion' => $idInstalacion,
        ]);

        
    }

    private function updateDatabase($bdNombre, Request $request)
    {
        $dynamicConnection = $this->configureDynamicConnection($bdNombre);

        $instalacion = $dynamicConnection->table('instalaciones')->first();

        if ($instalacion) {
            $dynamicConnection
                ->table('instalaciones')
                ->where('id', $instalacion->id)
                ->update([
                    'nombre' => $request->input('name'),
                    'direccion' => $request->input('direccion'),
                    'tlfno' => $request->input('tlfno'),
                    'slug' => $request->input('slug'),
                    'updated_at' => now(),
                ]);
        } else {
            $dynamicConnection->table('instalaciones')->insert([
                'nombre' => $request->input('name'),
                'direccion' => $request->input('direccion'),
                'tlfno' => $request->input('tlfno'),
                'slug' => $request->input('slug'),
                'tipo_reservas_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function configureDynamicConnection($bdNombre)
    {
        config([
            'database.connections.dynamic' => [
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
            ],
        ]);

        return DB::connection('dynamic');
    }

    private function executeSqlFile($connection, $filePath)
    {
        $sql = file_get_contents($filePath);
        $queries = array_filter(array_map('trim', explode(';', $sql)));

        foreach ($queries as $query) {
            if (!empty($query)) {
                $connection->statement($query);
            }
        }
    }

    private function handleDatabaseError($bdNombre, \Exception $e)
    {
        DB::statement("DROP DATABASE IF EXISTS `$bdNombre`");
        throw new \Exception('Error al crear o conectar a la base de datos: ' . $e->getMessage());
    }

    private function createSuperAdminRecord(Request $request, $url, $bdNombre)
    {
        SuperAdmin::on('superadmin')->create([
            'name' => $request->input('name'),
            'url' => $url,
            'bd_nombre' => $bdNombre,
        ]);
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
        $superadmin_bds = SuperAdmin::all();
        return view('superadmin.create-user', compact('superadmin_bds'));
    }

    public function createUser(Request $request)
    {
        try {
            // Validar los datos del formulario
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'password' => 'required|string|min:8|confirmed',
            ]);
    
            // Verificar si la base de datos seleccionada es diferente de 'superadmin'
            if ($request->input('database') != 'superadmin') {
                // Configurar la conexión dinámica
                $dinamicConnection = $this->configureDynamicConnection($request->input('database'));
    
                // Verificar si el email ya existe en la tabla 'users'
                $emailExists = $dinamicConnection->table('users')->where('email', $request->input('email'))->exists();
    
                if ($emailExists) {
                    return redirect()->back()->withInput()->withErrors(['email' => 'El correo electrónico ya está en uso en la base de datos seleccionada.']);
                }
    
                // Crear el usuario en la tabla 'users' de la conexión dinámica
                $dinamicConnection->table('users')->insert([
                    'id_instalacion' => $request->input('instalacion'),
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                    'rol' => $request->input('rol'), // Rol predeterminado
                    'subrol' => $request->input('subrol'), // Subrol desde el formulario
                    'aprobado' => now(), // Aprobado por defecto
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
    
                return redirect()->route('superadmin.index')->with('success', 'Usuario creado con éxito en la instalación seleccionada.');
            }
    
            // Crear el usuario en la conexión 'superadmin'
            \App\Models\SuperAdminUser::on('superadmin')->create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);
    
            return redirect()->route('superadmin.index')->with('success', 'Usuario creado con éxito.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Capturar errores de validación específicos
            $errors = $e->validator->errors();
            if ($errors->has('email')) {
                return redirect()->back()->withInput()->withErrors(['email' => 'El correo electrónico ya está en uso.']);
            }
            if ($errors->has('password')) {
                return redirect()->back()->withInput()->withErrors(['password' => 'La contraseña debe tener al menos 8 caracteres y coincidir con la confirmación.']);
            }
            return redirect()->back()->withInput()->withErrors($errors);
        } catch (\Exception $e) {
            // Capturar otros errores y redirigir con el mensaje de error
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function getInstalaciones(Request $request)
{
    $database = $request->input('database');

    if ($database && $database != 'superadmin') {
        // Configurar la conexión dinámica
        $dinamicConnection = $this->configureDynamicConnection($database);

        // Obtener las instalaciones
        $instalaciones = $dinamicConnection->table('instalaciones')->select('id', 'nombre')->get();
        return response()->json($instalaciones);
    }

    return response()->json([]);
}

public function updateUserRole(Request $request, $id, $userId)
{
    $ayuntamiento = SuperAdmin::find($id);

    if (!$ayuntamiento) {
        return redirect()->route('superadmin.index')->with('error', 'Ayuntamiento no encontrado.');
    }

    // Configurar la conexión dinámica
    $dinamicConnection = $this->configureDynamicConnection($ayuntamiento->bd_nombre);

    // Actualizar el rol y subrol del usuario
    $dinamicConnection->table('users')->where('id', $userId)->update([
        'rol' => $request->input('rol'),
        'subrol' => $request->input('subrol'),
    ]);

    return redirect()->route('superadmin.editUsers', $id)->with('success', 'Rol y subrol actualizados con éxito.');
}

public function deleteUser($id, $userId)
{
    $ayuntamiento = SuperAdmin::find($id);

    if (!$ayuntamiento) {
        return redirect()->route('superadmin.index')->with('error', 'Ayuntamiento no encontrado.');
    }

    // Configurar la conexión dinámica
    $dinamicConnection = $this->configureDynamicConnection($ayuntamiento->bd_nombre);

    try {
        // Obtener los id_participante relacionados con el id_usuario
        $idParticipantes = $dinamicConnection->table('participantes')
            ->where('id_usuario', $userId)
            ->pluck('id');

        // Tablas que usan id_participante
        $tablesWithIdParticipante = [
            'bono_participante',
            'participante_eventos_mes',
        ];

        foreach ($tablesWithIdParticipante as $table) {
            if ($dinamicConnection->getSchemaBuilder()->hasTable($table)) {
                $dinamicConnection->table($table)->whereIn('id_participante', $idParticipantes)->delete();
            }
        }

        // Tablas que usan id_usuario
        $tablesWithIdUsuario = [
            'servicio_usuario',
            'reservas',
            'recibo',
            'pedidos',
            'participantes',
            'bono_usuario',
        ];

        foreach ($tablesWithIdUsuario as $table) {
            if ($dinamicConnection->getSchemaBuilder()->hasTable($table)) {
                $dinamicConnection->table($table)->where('id_usuario', $userId)->delete();
            }
        }

        // Eliminar el usuario de la tabla principal
        $dinamicConnection->table('users')->where('id', $userId)->delete();

        return redirect()->route('superadmin.editUsers', $id)->with('success', 'Usuario eliminado con éxito.');
    } catch (\Exception $e) {
        return redirect()->route('superadmin.editUsers', $id)->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
    }
}

public function editUsers(Request $request, $id)
{
    $ayuntamiento = SuperAdmin::find($id);

    if (!$ayuntamiento) {
        return redirect()->route('superadmin.index')->with('error', 'Ayuntamiento no encontrado.');
    }

    // Configurar la conexión dinámica
    $dinamicConnection = $this->configureDynamicConnection($ayuntamiento->bd_nombre);

    // Buscar usuarios por nombre o email si se envía un término de búsqueda
    $query = $dinamicConnection->table('users');
    if ($request->has('search') && !empty($request->search)) {
        $query->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%');
    }

    // Paginación de 15 usuarios por página
    $usuarios = $query->paginate(15);

    return view('superadmin.edit-user', compact('ayuntamiento', 'usuarios'));
}
}
