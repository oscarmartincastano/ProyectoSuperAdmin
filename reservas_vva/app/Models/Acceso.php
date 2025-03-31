<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Acceso extends Model
{

    protected $table='acceso';
    protected $fillable=['activo','inicio','fin','apertura','cierre','user_id'];



    public function usuarios(){
        return $this->belongsTo('App\Models\User','user_id');

    }

}