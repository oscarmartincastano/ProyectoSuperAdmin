<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Instalacion;
use App\Models\Pista;
use App\Models\Bono;

class Deporte extends Model
{
    protected $table = 'deportes';

    protected $fillable = [
        'id',
        'nombre',
    ];

    // Relación con Bono
    public function bonos()
    {
        return $this->hasMany(Bono::class, 'id_deporte');
    }
}
