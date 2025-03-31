<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dias_festivos extends Model
{
    use HasFactory;

    protected $table = 'dias_festivos';
   

    protected $fillable = ['dia_festivo'];
}
