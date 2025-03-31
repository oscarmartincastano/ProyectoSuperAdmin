<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogRecibosDiario extends Model
{
    use HasFactory;

    protected $table = 'log_recibos_diario';

    protected $fillable = [
        'id_servicio_usuario',
        'id_usuario',
        'mensaje',
        'fecha_expiracion',
    ];
}
