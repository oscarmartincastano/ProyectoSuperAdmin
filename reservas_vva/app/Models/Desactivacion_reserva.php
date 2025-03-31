<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pista;

class Desactivacion_reserva extends Model
{
    use HasFactory;

    protected $table = 'desactivacion_reservas';

    protected $fillable = [
        'id_pista',
        'timestamp',
    ];

    public function pista()
    {
        return $this->hasOne(Pista::class, 'id', 'id_pista');
    }
}
