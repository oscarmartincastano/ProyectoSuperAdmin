<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthManager
{
    public function handle($request, Closure $next)
    {
        $config = \Config::get('database.connections.mysql');
        $config['database'] = 'manager_reservas';
        $config['password'] = "#3p720hqK";
        config()->set('database.connections.mysql', $config);
        if (Auth::check()) {
            return $next($request);
        }
        return redirect()->guest(route('login'));
    }
}
?>