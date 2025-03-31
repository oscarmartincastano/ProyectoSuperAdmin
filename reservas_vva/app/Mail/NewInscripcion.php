<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Participante;

class NewInscripcion extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $participante;
    public $slug;

    public function __construct(User $user, Participante $participante)
    {
        $this->user = $user;
        $this->participante = $participante;
        $this->slug = request()->slug_instalacion;
    }

    public function build()
    {
        return $this->subject('Nueva inscripción confirmada')->view('mails.newinscripcion');
    }
}
?>