<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'ajax/*',
        '*/notificacion',
        '*/notificacion2',//prueba esto para la exceptcion y mañana te devuelvo el dinero.
        'redsys/*'
    ];
}
