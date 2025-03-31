<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Evento;
use App\Models\Pista;
use App\Models\Valor_campo_personalizado;

class Usuario_participante extends Model
{
    use HasFactory;

    protected $table = 'usuario_participante';

    protected $fillable = [
        'id_usuario',
        'id_participante',
    ];

    /* public function getPlazasDisponiblesAttribute()
    {
        return $this->hasOne(Deporte::class, 'id', 'id_deporte');
    } */
}
