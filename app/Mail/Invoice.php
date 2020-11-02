<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Invoice extends Mailable
{
    use Queueable, SerializesModels;

    public $header;
    public $transactions;
    public $totals;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($header, $transactions, $totals)
    {
      $this->header = $header;
      $this->transactions = $transactions;
      $this->totals = $totals;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
          ->from($address = 'fieldjobstracker@gmail.com' , $name = ucfirst($this->header->outfitName))
          ->subject('Invoice')
          ->view('emails.sendInvoice');
    }
}
