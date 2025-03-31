<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Instalacion;
use App\Models\Pista;

class Mensajes_difusion extends Model
{
    use HasFactory;

    protected $table = 'mensajes_difusion';

    protected $fillable = [
        'id_instalacion',
        'titulo',
        'contenido',
        'fecha_inicio',
        'fecha_fin',
    ];
}
