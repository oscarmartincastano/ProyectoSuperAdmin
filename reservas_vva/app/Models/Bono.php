<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Pedido;

class Bono extends Model
{

    use SoftDeletes;

    protected $table='bonos';
    protected $fillable=['nombre','num_usos','precio','id_deporte','descripcion','activo','id_instalacion'];

    // Relación con Deporte
    public function deporte()
    {
        return $this->belongsTo(Deporte::class, 'id_deporte');
    }

    public function instalacion()
    {
        return $this->hasOne(Instalacion::class, 'id', 'id_instalacion');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_bono');
    }

    public function bonoUsuarios()
    {
        return $this->hasMany(BonoUsuario::class, 'id_bono');
    }

}