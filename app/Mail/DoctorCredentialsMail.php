<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DoctorCredentialsMail extends Mailable
{
    use SerializesModels;

    public $name;
    public $email;
    public $password;

    public function __construct($name, $email, $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Welcome to Health Nest - Your Login Credentials')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.doctor_credentials');
    }
}
