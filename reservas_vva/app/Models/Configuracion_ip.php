<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{

    protected $table ="configuracion_ip";
    protected $fillable=["campo","valor"];



}