<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthManager
{
    public function handle($request, Closure $next)
    {
        $config = \Config::get('database.connections.mysql');
        $config['database'] = $request->slug_instalacion;
        $config['password'] = "#3p720hqK";
        config()->set('database.connections.mysql', $config);

        if (Auth::check()) {
            // Verificar si el usuario tiene el rol de manager
            if (Auth::user()->rol === 'manager') {
                return $next($request);
            }

            // Si no tiene el rol adecuado, redirigir con un mensaje de error
            return redirect()->route('login')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        return redirect()->guest(route('login'));
    }
}
?>