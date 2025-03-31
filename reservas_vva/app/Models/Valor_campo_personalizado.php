<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Instalacion;
use App\Models\Pista;
use App\Models\Reserva;
use App\Models\Campos_personalizados;
use App\Models\Participante;

class Valor_campo_personalizado extends Model
{
    use HasFactory;

    protected $table = 'valor_campo_personalizado';

    protected $fillable = [
        'id_reserva',
        'id_participante',
        'id_campo',
        'valor',
    ];

    public $timestamps = false;

    protected $appends = ['campo_content'];

    public function reserva()
    {
        return $this->hasOne(Reserva::class, 'id', 'id_reserva');
    }

    public function participante()
    {
        return $this->hasOne(Participante::class, 'id', 'id_participante');
    }

    public function campo()
    {
        return $this->hasOne(Campos_personalizados::class, 'id', 'id_campo')->withTrashed();
    }

    public function getCampoContentAttribute() {
        return $this->campo_content();
    }

    public function campo_content() {
        return $this->campo;
    }
}
