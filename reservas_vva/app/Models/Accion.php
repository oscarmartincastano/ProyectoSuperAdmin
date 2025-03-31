<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Accion extends Model
{
    protected $table ="accion";
    protected $fillable=["mensaje","cod_error"];


    public function registros(){
        return $this->hasMany('App\Models\Registro', 'accion_id');
    }
}