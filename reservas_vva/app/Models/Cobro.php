<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pista;
use App\Models\Valor_campo_personalizado;

class Cobro extends Model
{
    use HasFactory;

    protected $table = 'cobros';

    protected $fillable = [
        'fecha',
        'concepto',
        'forma',
        'cantidad',
        'id_user',
        'notas',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }
}
