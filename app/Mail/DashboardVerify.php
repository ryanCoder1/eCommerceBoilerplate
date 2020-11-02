<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
require app_path().'/Configs/appconstants.php';

class DashboardVerify extends Mailable
{
    use Queueable, SerializesModels;

    public $verifyToken;
    public $name;
    public $website_name = WEBSITE;
    public $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($verifyToken, $name, $email)
    {
        $this->verifyToken = $verifyToken;
        $this->name = $name;
        $this->email = $email;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this
        ->from($address = EMAIL_PROVIDER , $name = $this->name)
        ->subject('Dashboard Verification')
        ->view('emails.dashboardVerify');
    }
}
