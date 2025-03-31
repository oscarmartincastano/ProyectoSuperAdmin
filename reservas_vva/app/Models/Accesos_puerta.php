<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Instalacion;
use App\Models\Pista;

class Accesos_puerta extends Model
{
    use HasFactory;

    protected $table = 'accesos_puerta';

    protected $fillable = [
        'nombre',
        'estado'
    ];

    public function registros(){
        return $this->hasMany('App\Models\Registros_accesos', 'accesos_puerta_id');

    }
}