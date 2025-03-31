<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Instalacion;
use App\Models\Reserva;
use App\Models\Participante;
use App\Models\BonoUsuario;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id_instalacion',
        'name',
        'email',
        'password',
        'cuota',
        'rol',
        'subrol',
        'aprobado',
        'pago_recurrente',
        'direccion',
        'codigo_postal',
        'tlfno',
        'date_birth',
        'max_reservas_tipo_espacio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /* protected $appends = ['reservas_activas', 'reservas_pasadas', 'reservas_canceladas']; */

    public function instalacion()
    {
        return $this->hasOne(Instalacion::class, 'id', 'id_instalacion');
    }

    public function registros(){
        return $this->hasMany('App\Models\Registro', 'user_id');
    }

    public function accesos(){
        return $this->hasMany('App\Models\Acceso', 'user_id');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class,'id_usuario');
    }

    public function cobros()
    {
        return $this->hasMany(Cobro::class,'id_user');
    }

    public function servicio_usuario(){
        return $this->hasMany(Servicio_Usuario::class, 'id_usuario');
    }

    public function recibo(){
        return $this->hasMany(Recibo::class, 'id_usuario');
    }

    public function participaciones()
    {
        return $this->hasMany(Participante::class,'id_usuario');
    }

    public function getReservasActivasAttribute() {
        return $this->reservas_activas();
    }

    public function reservas_activas()
    {
        $reservas_activas = [];

        foreach ($this->reservas as $key => $item) {
            if ($item->estado == 'active' && strtotime(date('Y-m-d H:i', $item->timestamp) . ' +' . $item->minutos_totales . ' minutes') > strtotime(date('Y-m-d H:i'))) {
                array_push($reservas_activas, $item);
            }
        }

        return $reservas_activas;
    }

    public function getReservasPasadasAttribute() {
        return $this->reservas_pasadas();
    }

    public function reservas_pasadas()
    {
        $reservas_pasadas = [];

        foreach ($this->reservas as $key => $item) {
            if ($item->estado == 'pasado') {
                array_push($reservas_pasadas, $item);
            }
            if (count($reservas_pasadas) == 10) {
               break;
            }
        }

        return $reservas_pasadas;
    }

    public function getReservasCanceladasAttribute() {
        return $this->reservas_canceladas();
    }

    public function reservas_canceladas()
    {
        $reservas_canceladas = [];

        foreach ($this->reservas as $key => $item) {
            if ($item->estado == 'canceled') {
                array_push($reservas_canceladas, $item);
            }
            if (count($reservas_canceladas) == 10) {
               break;
            }
        }

        return $reservas_canceladas;
    }

    public function numero_total_reservas_tipo($tipo_espacio)
    {
        $contador = 0;
        foreach ($this->reservas_activas as $reserva) {
            if ($reserva->pista->tipo == $tipo_espacio) {
                $contador++;
            }
        }
        return $contador;
    }

    public function check_maximo_reservas_espacio($tipo_espacio)
    {
        if (isset($this->max_reservas_tipo_espacio) && isset(unserialize($this->max_reservas_tipo_espacio)[$tipo_espacio])) {
            if ($this->numero_total_reservas_tipo($tipo_espacio) >= unserialize($this->max_reservas_tipo_espacio)[$tipo_espacio]) {
                return false;
            }
            return true;
        }
        if (isset($this->instalacion->configuracion->max_reservas_tipo_espacio) && isset(unserialize($this->instalacion->configuracion->max_reservas_tipo_espacio)[$tipo_espacio])) {
            if ($this->numero_total_reservas_tipo($tipo_espacio) >= unserialize($this->instalacion->configuracion->max_reservas_tipo_espacio)[$tipo_espacio]) {
                return false;
            }
            return true;
        }

        return true;
    }

    public function numero_reservas_perimitidas($tipo_espacio)
    {
        if (isset($this->max_reservas_tipo_espacio) && isset(unserialize($this->max_reservas_tipo_espacio)[$tipo_espacio])) {
            return unserialize($this->max_reservas_tipo_espacio)[$tipo_espacio] - $this->numero_total_reservas_tipo($tipo_espacio);
        }
        if (isset($this->instalacion->configuracion->max_reservas_tipo_espacio) && isset(unserialize($this->instalacion->configuracion->max_reservas_tipo_espacio)[$tipo_espacio])) {
            return unserialize($this->instalacion->configuracion->max_reservas_tipo_espacio)[$tipo_espacio] - $this->numero_total_reservas_tipo($tipo_espacio);
        }

        return 200;
    }


    public function bonosUsuarios()
    {
        return $this->hasMany(BonoUsuario::class, 'id_usuario');
    }
}
