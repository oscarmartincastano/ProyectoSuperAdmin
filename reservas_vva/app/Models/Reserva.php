<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pista;
use App\Models\Pedido;
use App\Models\Valor_campo_personalizado;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reserva extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'reservas';

    protected $fillable = [
        'id_pista',
        'id_usuario',
        'id_pedido',
        'timestamp',
        'horarios',
        'tarifa',
        'fecha',
        'hora',
        'minutos_totales',
        'estado',
        'estado_asistencia',
        'tipo',
        'observaciones',
        'observaciones_admin',
        'reserva_periodica',
        'reserva_multiple',
        'creado_por',
    ];

    protected $appends = ['valores_campos_pers'];

    protected $with = ['pedido'];

    public function pista()
    {
        return $this->hasOne(Pista::class, 'id', 'id_pista');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_usuario');
    }

    public function valores_campos_personalizados()
    {
        return $this->hasMany(Valor_campo_personalizado::class, 'id_reserva');
    }

    public function getValoresCamposPersAttribute() {
        return $this->valores_campos_personalizados;
    }

    public function getHorariosDeserializedAttribute() {
        return $this->horariosDeserializado();
    }

    public function horariosDeserializado() {
        $horarios = unserialize($this->horarios);
        return $horarios;
    }

    public function getFormatedUpdatedAtAttribute() {
        return $this->updated_at_formateado();
    }

    public function updated_at_formateado() {
        $date = date('d/m H:i', strtotime($this->updated_at));
        return $date;
    }

    public function pedido() {
        return $this->belongsTo('App\Models\Pedido', 'id_pedido');
    }
}
