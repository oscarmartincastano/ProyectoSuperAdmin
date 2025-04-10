<?php

namespace App\Http\Middleware;

use App\Models\SuperAdmin;
use App\Models\Instalacion;
use Closure;
use Auth;

class CheckInstalacion
{
    public function handle($request, Closure $next)
    {
        session_start();

        // Extraer el slug de la URL eliminando "https://gestioninstalacion.es/"
        $slug = str_replace('https://gestioninstalacion.es/', '', $request->slug_instalacion);

        // Verificar si la instalación ya está configurada en la sesión
        if (isset($_SESSION['bd']) && $_SESSION['bd'] == $slug) {
            // La instalación ya está configurada, continuar con la solicitud
            return $this->setDatabaseConnection($_SESSION['dynamic_db_name'], $next, $request);
        }

        // Si el usuario está autenticado y está intentando cambiar de instalación
        if (Auth::check()) {
            return redirect()->back()->with('mensaje', 'Está intentando cambiar de instalación deportiva, por favor cierre sesión antes. Gracias!');
        }

        // Consultar la base de datos superadmin para obtener la instalación correspondiente
        $superAdmin = SuperAdmin::where('url', 'https://gestioninstalacion.es/' . $slug)->first();

        if ($superAdmin) {
            // Configurar la conexión dinámica
            $_SESSION['bd'] = $slug;
            $_SESSION['dynamic_db_name'] = $superAdmin->bd_nombre;
            return $this->setDatabaseConnection($superAdmin->bd_nombre, $next, $request);
        }

        dd("Da error");
        // Si no se encuentra la instalación en la base de datos superadmin
        abort(404, 'No se encontró la instalación en la base de datos.');
    }

    private function setDatabaseConnection($dynamic_db_name, Closure $next, $request)
    {
        $config = \Config::get('database.connections.mysql');
        $config['database'] = $dynamic_db_name;
        $config['password'] = "#3p720hqK"; // Asegúrate de usar variables de entorno para mayor seguridad
        config()->set('database.connections.mysql', $config);
        \DB::purge('mysql');

        return $next($request);
    }
}
?>