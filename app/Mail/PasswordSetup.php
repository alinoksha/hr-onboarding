<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordSetup extends Mailable
{
    use Queueable, SerializesModels;

    public string $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    public function build(): PasswordSetup
    {
        return $this->view('password');
    }
}
