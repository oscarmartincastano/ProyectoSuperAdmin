<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;

    protected $table = 'permisos';

    protected $fillable = [
        'id_instalacion',
        'ver_normas', // Reemplaza con los nombres reales de las columnas
        'ver_servicios',
        'ver_horario',
        'ver_politica',
        'ver_condiciones',
        'ver_mapa',
        'ver_deportes',
        'ver_normas_admin',
        'ver_servicios_admin',
        'ver_horario_admin',
        'ver_politica_admin',
        'ver_condiciones_admin',
        'ver_mapa_admin',
        'ver_deportes_admin',
    ];

    public function instalacion()
    {
        return $this->belongsTo(Instalacion::class, 'id_instalacion');
    }
}