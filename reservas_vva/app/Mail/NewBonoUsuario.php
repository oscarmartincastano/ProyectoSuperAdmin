<?php
namespace App\Mail;

use App\Models\BonoUsuario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class NewBonoUsuario extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $bono_usuario;

    public function __construct($user, BonoUsuario $bono_usuario)
    {
        $this->user = $user;
        $this->bono_usuario = $bono_usuario;
    }

    public function build()
    {
        return $this->subject('Nuevo bono confirmado')->view('mails.newbono');
    }
}
?>
