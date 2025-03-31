<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table="servicios";
    protected $fillable=[
        'nombre',
        'tipo',
        'duracion',
        'precio',
        'tipo_espacio',
        'pista_id',
        'descripcion',
        'reservas',
        'instalacion_id',
        'formapago',
        ];





    public function pista(){

            return $this->hasOne(Pista::class, 'id', 'pista_id');

    }

    public function espacio(){

        return $this->hasOne(Deporte::class, 'id', 'tipo_espacio');
    }

    public function instalacion(){
        return $this->hasOne(Instalacion::class, 'id', 'instalacion_id');

    }

    public function servicio_usuario()
    {
        return $this->hasMany(Servicio_Usuario::class, 'id_servicio');
    }

    public function tipos_participante()
    {
        return $this->hasOne(Tipos_participante::class, 'id', 'id_tipo_participante');
    }

    public function recibo()
    {
        return $this->hasMany(recibo::class, 'id_servicio');
    }

    public function participantes()
    {
        return $this->hasMany(Participante::class, 'id_servicio');
    }

}
