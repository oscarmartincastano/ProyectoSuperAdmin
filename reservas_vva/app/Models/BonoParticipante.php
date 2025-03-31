<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Participante;

class BonoParticipante extends Model
{
    use HasFactory;

    protected $table = 'bono_participante';

    public function participante()
    {
        return $this->belongsTo(Participante::class, 'id_participante');
    }
}
