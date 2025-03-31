<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuperAdmin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        $aDatos = [];
        // $aDatos['pistas'] = $secondaryConnection->table('pistas')->get();
        $aDatos['instalaciones'] = $secondaryConnection->table('instalaciones')->get();

        // Deserializar el campo horario para cada pista y manejar ambos casos (dias o intervalo)
        // foreach ($aDatos['pistas'] as $pista) {
        //     $horario = unserialize($pista->horario);

        //     // Si el horario tiene la clave 'intervalo', adaptarlo a la estructura esperada
        //     if (isset($horario[0]['intervalo'])) {
        //         foreach ($horario as &$item) {
        //             $item['dias'] = ['intervalo']; // Agregar una clave ficticia para mantener consistencia
        //         }
        //     }

        //     $pista->horario = $horario;
        // }

        // Deserializar el campo horario para cada instalación y manejar ambos casos (dias o intervalo)
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

        // Actualizar las instalaciones
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

        return redirect()->route('superadmin.index');
    }

    public function destroy($id)
    {
        $ayuntamiento = SuperAdmin::find($id);
        $ayuntamiento->delete();
        return redirect()->route('superadmin.index');
    }

    public function create()
    {
        return view('superadmin.create');
    }

    public function store(Request $request)
    {
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
            // Si la URL comienza con "http://" o contiene "gestioninstalacion.es" pero no es válida, rechazarla
            if (str_starts_with($url, 'http://') || str_contains($url, 'gestioninstalacion.es')) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['url' => 'La URL debe comenzar con https://gestioninstalacion.es/ o solo el nombre.']);
            }

            // Si es un valor como "oscar", concatenar la base
            $url = 'https://gestioninstalacion.es/' . str_replace(' ', '-', $url);
        }

        // Convertir la URL a minúsculas
        $url = strtolower($url);

        // Crear el ayuntamiento
        SuperAdmin::create([
            'name' => $request->input('name'),
            'url' => $url,
            'bd_nombre' => $request->input('bd_nombre'), // Asegúrate de incluir este campo
        ]);

        return redirect()->route('superadmin.index');
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

        if (Auth::attempt($credentials)) {
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

    \App\Models\User::create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'password' => Hash::make($request->input('password')),
    ]);

    return redirect()->route('superadmin.index')->with('success', 'Usuario creado con éxito.');
}
}
