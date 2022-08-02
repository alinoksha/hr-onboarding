<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public string $hash;

    public function __construct(string $hash)
    {
        $this->hash = $hash;
    }

    public function build(): ResetPassword
    {
        return $this->view('emails.reset_password');
    }
}
