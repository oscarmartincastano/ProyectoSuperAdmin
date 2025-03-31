<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pista;

class Reservas_periodicas extends Model
{
    use HasFactory;

    protected $table = 'reservas_periodicas';

    protected $fillable = [
        'id_pista',
        'id_user',
        'dias',
        'fecha_inicio',
        'fecha_fin',
        'hora_inicio',
        'hora_fin',
    ];

    public function pista()
    {
        return $this->hasOne(Pista::class, 'id', 'id_pista');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }
}
