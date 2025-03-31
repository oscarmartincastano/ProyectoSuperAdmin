<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Instalacion;
use App\Models\Pista;

class Pistas_campos_relation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pistas_campos';

    protected $fillable = [
        'id_pista',
        'id_campo',
    ];

    public $timestamps = false;
}
