<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Evento;
use App\Models\User;
use App\Models\Valor_campo_personalizado;

class Tipos_participante extends Model
{
    use HasFactory;

    protected $table = 'tipos_participante';

    protected $fillable = [
        'nombre',
        'id_instalacion',
    ];    

    public function campos_personalizados()
    {
        return $this->belongsToMany(Campos_personalizados::class, 'tipos_participante_campos', 'id_tipo_participante', 'id_campo');
    }

}
