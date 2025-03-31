<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthInstalacion
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::check() && auth()->user()->rol == 'admin') {
                return redirect(auth()->user()->instalacion->slug . '/admin');
            }
            return $next($request);
        }
        /* Auth::logout(); */
        return redirect()->guest(route('login_instalacion', ['slug_instalacion' => $request->slug_instalacion]));
    }
}
?>