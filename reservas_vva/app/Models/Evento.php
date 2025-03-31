<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Deporte;
use App\Models\Participante;
use App\Models\Valor_campo_personalizado;
use App\Models\Participante_eventos_mes;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'eventos';

    protected $fillable = [
        'id_instalacion',
        'nombre',
        'descripcion',
        'localizacion',
        'precio_participante',
        'id_deporte',
        'fecha_inicio',
        'fecha_fin',
        'num_participantes',
        'insc_fecha_inicio',
        'insc_fecha_fin',
        'tipo_participantes',
        'id_tipo_participante',
        'renovacion_mes'
    ];

    public function deporte()
    {
        return $this->hasOne(Deporte::class, 'id', 'id_deporte');
    }

    public function participantes()
    {
        return $this->hasMany(Participante::class, 'id_evento');
    }

    public function instalacion()
    {
        return $this->hasOne(Instalacion::class, 'id', 'id_instalacion');
    }

    public function tipo_participante()
    {
        return $this->hasOne(Tipos_participante::class, 'id', 'id_tipo_participante');
    }

    /* public function participante_mes()
    {
        $participante = $this->participantes;
        $count = 0;
        foreach ($participante as $part) {
            $participante_mes = $part->participante_eventos_mes->first();
            if($participante_mes->num_mes == date('m') && $participante_mes->num_year == date('Y')){
                $count++;
            }
        }
        return $count;
    } */

    public function participante_mes()
    {
        return $this->participantes()
            ->whereHas('participante_eventos_mes', function ($query) {
                $query->where('num_mes', date('m'))
                    ->where('num_year', date('Y'));
            })->count();
    }

    public function getPlazasDisponiblesAttribute()
    {
 
        if($this->renovacion_mes == 0){
            return $this->num_participantes - $this->participantes->where('estado', 'active')->count();
        }else{
            return $this->num_participantes - $this->participante_mes();
        }
    }

    public function check_new_inscripcion($num_inscripciones)
    {
        if ($this->plazas_disponibles - $num_inscripciones >= 0) {
            return true;
        }
        return false;
    }
}
