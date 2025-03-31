<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Servicio_Usuario extends Model
{

    protected $table = "servicio_usuario";
    protected $fillable =['fecha_expiracion','activo','id_servicio','id_usuario'];

    public function user()
    {
        return $this->hasOne(User::class, 'id_usuario');
    }

    public function servicio(){
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }

    public function recibos()
    {
        return $this->hasMany(Recibo::class, 'id_servicio_usuario', 'id');
    }

    public function recibos_sin_pago(){

        return $this->hasMany(Recibo::class, 'id_servicio_usuario', 'id')->where('estado','pendiente')->where('pedido_id',"")->orWhere('pedido_id',null)->where('id_usuario', auth()->user()->id)->where('estado','pendiente');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

}
