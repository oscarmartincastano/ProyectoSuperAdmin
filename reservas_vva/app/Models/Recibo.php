<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Recibo extends Model
{
    use SoftDeletes;

    protected $table = "recibo";
    protected $fillable =['amount','id_servicio','id_usuario','pedido_id','created_at'];

    public function user()
    {
        return $this->hasOne(User::class, 'id','id_usuario');
    }

    public function servicio(){
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }

    public function servicioUsuario()
    {
        return $this->belongsTo(ServicioUsuario::class, 'id_servicio_usuario', 'id');
    }



}
