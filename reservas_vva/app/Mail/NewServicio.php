<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Servicio;

class NewServicio extends Mailable
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
        return $this->subject('Nuevo servicio contratado')->view('mails.newservicio');
    }
}