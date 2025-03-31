<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Participante;

class Participante_eventos_mes extends Model
{
    use HasFactory;

    protected $table = 'participante_eventos_mes';

    protected $fillable = [
        'id_participante',
        'id_pedido',
        'num_mes',
        'num_year',
    ];

    public function participante()
    {
        return $this->hasOne(Participante::class, 'id', 'id_participante');
    }

    public function pedido()
    {
        return $this->hasOne(Pedido::class, 'id', 'id_pedido');
    }

    public function evento(){
        return $this->participante->evento;
    }
}
