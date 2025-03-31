<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperAdmin extends Model
{
    use HasFactory;

    protected $connection = 'superadmin'; // Usar la conexión 'superadmin'
    protected $table = 'superadmin'; // Cambia esto al nombre correcto de la tabla en la base de datos 'superadmin'

    protected $fillable = [
        'name',
        'url',
        'bd_nombre',
    ];
}
