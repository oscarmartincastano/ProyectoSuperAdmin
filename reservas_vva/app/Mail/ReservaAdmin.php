<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Reserva;

class ReservaAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $reserva;

    public function __construct(User $user, Reserva $reserva)
    {
        $this->user = $user;
        $this->reserva = $reserva;
    }

    public function build()
    {
        return $this->subject('Nueva reserva')->view('mails.adminreserva');
    }
}
?>