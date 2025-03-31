<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Instalacion;
use App\Models\Pista;

class Servicios_adicionales extends Model
{
    protected $table = 'servicios_adicionales';

    protected $fillable = [
        'id',
        'nombre',
    ];
}
