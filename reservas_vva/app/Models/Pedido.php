<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Reserva;
use App\Models\User;
use App\Models\Participante;
use App\Models\Evento;

class Pedido extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pedidos';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['estado'];

    public function reserva(){
        return $this->hasOne(Reserva::class,'id', 'id_reserva');
    }

    public function reservas(){
        return $this->hasMany(Reserva::class, 'id_pedido');
    }

    public function user(){
        return $this->belongsTo(User::class,'id_usuario','id');
    }

    public function participantes(){
        return $this->hasMany(Participante::class, 'id_pedido');
    }
    
    public function evento(){
        return $this->hasOne(Evento::class,'id', 'id_evento');
    }

    public function bono()
    {
        return $this->belongsTo(Bono::class, 'id_bono');
    }

}
