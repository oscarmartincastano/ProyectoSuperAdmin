<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pista;
use App\Models\Configuracion;
use App\Models\Campos_personalizados;
use App\Models\Cobro;
use App\Models\Tipos_participante;
use App\Models\Evento;
use App\Models\Bono;

class Instalacion extends Model
{
    use HasFactory;

    protected $table = 'instalaciones';

    protected $fillable = [
        'nombre',
        'direccion',
        'tlfno',
        'html_normas',
        'servicios',
        'horario',
        'slug',
        'prefijo_pedido',
        'tipo_reservas_id'
    ];

    protected $appends = ['deportes', 'user_admin', 'users_sin_validar'];

    public function users()
    {
        return $this->hasMany(User::class, 'id_instalacion')->withTrashed();
    }

    public function tipo_reservas()
    {
        return $this->belongsTo(Tipo_reservas::class, 'tipo_reservas_id');
    }

    public function pistas()
    {
        return $this->hasMany(Pista::class, 'id_instalacion')->where('active', 1);
    }

    public function eventos()
    {
        return $this->hasMany(Evento::class, 'id_instalacion');
    }

    public function bonos()
    {
        return $this->hasMany(Bono::class, 'id_instalacion');
    }

    public function eventos_all(){
        $eventos = [];
        foreach ($this->eventos as $evento) {
            array_push($eventos, $evento->nombre);
        }
        return array_unique($eventos);
    }

    public function getEventosAllAttribute() {
        return $this->eventos_all();
    }

    public function campos_personalizados()
    {
        return $this->hasMany(Campos_personalizados::class, 'id_instalacion');
    }

    public function tipos_participante()
    {
        return $this->hasMany(Tipos_participante::class, 'id_instalacion');
    }

    public function configuracion()
    {
        return $this->hasOne(Configuracion::class, 'id_instalacion');
    }

    public function getUsersValidosAttribute() {
        return User::where('id_instalacion', $this->id)->whereNotNull('aprobado')->where('rol', 'user')->orderBy('name', 'asc')->get();
    }

    public function getDeportesAttribute() {
        return $this->deportes();
    }

    public function getDeportesClasesAttribute() {
        return $this->deportes_clases();
    }

    public function deportes() {
        $deportes = [];
        foreach ($this->pistas as $pista) {
            array_push($deportes, $pista->tipo);
        }
        return array_unique($deportes);
    }

    public function deportes_clases() {
        $deportes = Deporte::whereIn('id', $this->pistas->pluck('id_deporte'))->get();
        return $deportes;
    }

    public function getUsersSinValidarAttribute() {
        return $this->users_sin_validar();
    }

    public function users_sin_validar() {
        return User::where([['id_instalacion', $this->id], ['aprobado', null]])->get();
    }

    public function getUserAdminAttribute() {
        return $this->user_admin();
    }

    public function user_admin() {
        return User::where([['id_instalacion', $this->id], ['rol', 'admin']])->first();
    }

    public function getCobrosAttribute() {
        return $this->cobros();
    }

    public function cobros() {
        return Cobro::whereIn('id_user', User::where('id_instalacion', $this->id)->pluck('id'))->get();
    }

    public function check_reservas_dia($fecha)
    {
        $reservas = Reserva::whereIn('id_pista', Pista::where('id_instalacion', $this->id)->pluck('id'))->where('fecha', $fecha)->where('estado', 'active')->get();

        return count($reservas) ? count($reservas) : false;
    }
}
