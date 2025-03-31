<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    protected $table="registro";
    protected $fillable=['fecha_apertura','accion_id','user_id'];



    public function usuarios(){
        return $this->belongsTo('App\Models\User','user_id');

    }

    public function acciones(){
        return $this->belongsTo('App\Models\Accion','accion_id');

    }


}