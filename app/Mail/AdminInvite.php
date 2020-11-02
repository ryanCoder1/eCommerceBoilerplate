<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
require app_path().'/Configs/appconstants.php';

class AdminInvite extends Mailable
{
  use Queueable, SerializesModels;

  public $token;
  public $privilege;
  public $invitedBy;
  public $name;
  public $website_name = WEBSITE;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($token, $privilege, $invitedBy, $name)
  {
    $this->token = $token;
    $this->privilege = $privilege;
    $this->invitedBy = $invitedBy;
    $this->name = $name;

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
        ->subject('Invite to Admin panel')
        ->view('emails.adminInvite');
  }
}
