<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class MailTemplate extends Mailable
{
    use Queueable, SerializesModels;

    public $asunto;
    public $html_mensaje;

    public function __construct($asunto, $html_mensaje)
    {
        $this->asunto = $asunto;
        $this->html_mensaje = $html_mensaje;
    }

    public function build()
    {
        return $this->subject($this->asunto)->view('mails.template_mail');
    }
}
?>