<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;


class BonoUsuario extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bono_usuario';
    protected $fillable=['num_usos','precio','id_usuario','id_bono','estado','id_pedido'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function bono()
    {
        return $this->belongsTo(Bono::class, 'id_bono');
    }
}
