<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $resetLink;

    public function __construct($email, $resetLink)
    {
        $this->email = $email;
        $this->resetLink = $resetLink;
    }

    public function build()
    {
        return $this->markdown('emails.resetpassword')
                    ->subject('Password Reset')
                    ->with(['resetLink' => $this->resetLink]);
    }
}
