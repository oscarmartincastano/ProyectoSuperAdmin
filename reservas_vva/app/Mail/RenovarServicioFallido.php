<?php


namespace App\Mail;


use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class RenovarServicioFallido extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $servicio;

    public function __construct(User $user, Servicio $servicio)
    {
        $this->user = $user;
        $this->servicio = $servicio;
    }

    public function build()
    {
        return $this->subject('Ha fallado la renovación del servicio')->view('mails.failservicio');
    }
}





