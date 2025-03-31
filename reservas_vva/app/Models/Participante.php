<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Evento;
use App\Models\User;
use App\Models\Valor_campo_personalizado;
use App\Models\Participante_eventos_mes;
use App\Models\BonoParticipante;

class Participante extends Model
{
    use HasFactory;

    protected $table = 'participantes';

    protected $fillable = [
        'id_evento',
        'id_usuario',
        'id_pedido',
        'estado',
        'nombre',
        'estado_pedido',
        'fecha_pedido',
        'showQR',
        'hora_inicio',
        'hora_fin',
        'tipo_entrada',
    ];

    public function evento()
    {
        return $this->hasOne(Evento::class, 'id', 'id_evento');
    }

    public function servicio()
    {
        return $this->hasOne(Servicio::class, 'id', 'id_servicio');
    }

    public function usuario()
    {
        return $this->hasOne(User::class, 'id', 'id_usuario');
    }

    public function valores_campos_personalizados()
    {
        return $this->hasMany(Valor_campo_personalizado::class, 'id_participante');
    }

    public function meses_suscrito()
    {
        return $this->hasMany(Participante_eventos_mes::class, 'id_participante');
    }

    public function getTipoEventoAttribute()
    {
        if($this->evento){
            $this->evento->renovacion_mes ? 'Mensual' : 'Casual';
        }else{
            return 'Casual';
        }
    }

    public function getUltimoMesSuscritoAttribute()
    {
        return $this->meses_suscrito->last();
    }

    /* public function getPlazasDisponiblesAttribute()
    {
        return $this->hasOne(Deporte::class, 'id', 'id_deporte');
    } */
    public function pedido()
    {
        return $this->hasOne(Pedido::class, 'id', 'id_pedido');
    }

    public function bonosParticipantes()
    {
        return $this->hasMany(BonoParticipante::class, 'id_participante');
    }

    public function appLogs()
    {
        return $this->hasMany(Log::class, 'participante_id');
    }

    public function participante_eventos_mes()
    {
        return $this->hasMany(Participante_eventos_mes::class, 'id_participante');
    }
}
