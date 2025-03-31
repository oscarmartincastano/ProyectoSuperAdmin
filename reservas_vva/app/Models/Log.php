<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pedido;
use App\Models\Flotador;
use App\Models\Participante;

class Log extends Model
{
    use HasFactory;

    protected $table = 'app_logs';

    public function entrada()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function flotador()
    {
        return $this->belongsTo(Flotador::class);
    }

    public function participante()
{
    return $this->belongsTo(Participante::class, 'participante_id');
}
}
