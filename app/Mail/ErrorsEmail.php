<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ErrorsEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $errors;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($errors)
    {
      $this->errors = $errors;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
          ->from($address = 'ryan.lackey1@yahoo.com' , $name = 'field jobs tracker')
          ->subject('eCommerce Errors')
          ->view('emails.errors')
          ->with('errors', $this->errors);
    }
}
