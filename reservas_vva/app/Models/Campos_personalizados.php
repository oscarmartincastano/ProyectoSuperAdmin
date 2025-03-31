<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Instalacion;
use App\Models\Pista;

class Campos_personalizados extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'campos_personalizados';

    protected $fillable = [
        'id_instalacion',
        'tipo',
        'label',
        'required',
        'opciones',
        'all_pistas',
    ];

    public function instalacion()
    {
        return $this->hasOne(Instalacion::class, 'id', 'id_instalacion');
    }

    public function pistas()
    {
        return $this->belongsToMany(Pista::class, 'pistas_campos', 'id_campo', 'id_pista');
    }
}
