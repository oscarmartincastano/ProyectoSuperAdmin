<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthAdminInstalacion
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && auth()->user()->instalacion->slug == $request->slug_instalacion && auth()->user()->rol == 'admin') {
            return $next($request);
        }
        Auth::logout();
        return redirect()->guest(route('login_instalacion',['slug_instalacion' => $request->slug_instalacion]));
    }
}
?>