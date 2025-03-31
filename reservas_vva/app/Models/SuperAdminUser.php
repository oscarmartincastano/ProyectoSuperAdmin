<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class SuperAdminUser extends Authenticatable
{
    protected $connection = 'superadmin'; // Conexión a la base de datos 'superadmin'
    protected $table = 'users'; // Tabla asociada al modelo

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}