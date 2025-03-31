<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pista;

class Desactivaciones_periodicas extends Model
{
    use HasFactory;

    protected $table = 'desactivaciones_periodicas';

    protected $fillable = [
        'id_pista',
        'dias',
        'fecha_inicio',
        'fecha_fin',
        'hora_inicio',
        'hora_fin',
        'reserva_id'
    ];

    public function pista()
    {
        return $this->hasOne(Pista::class, 'id', 'id_pista');
    }
}
