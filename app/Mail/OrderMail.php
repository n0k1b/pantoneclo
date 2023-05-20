<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $pdf;

    public function __construct(array $data, $pdf)
    {
        $this->data = $data;
        $this->pdf  = $pdf;
    }

    public function build()
    {
        // return $this->markdown('mail.order-mail');
        return $this->view('mail.order-mail-template')->attachData($this->pdf->output(), "invoice.pdf");
    }
}
