<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;




class NotificacionEntradas extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($pedidoLogNoCorrespondidos)
    {
        $this->pedidoLogNoCorrespondidos = $pedidoLogNoCorrespondidos;
    }

    public function build()
    {
        return $this->subject('Entrada no creada.')
                ->view('mails.entradanocreada')
                ->with([
                    'pedidoLogNoCorrespondidos' => $this->pedidoLogNoCorrespondidos
                ]);
    }
}