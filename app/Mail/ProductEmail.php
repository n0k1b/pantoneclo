<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductEmail extends Mailable
{
    use Queueable;
    use SerializesModels;
    protected $message;
    protected $contactNo;
    protected $userEmail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message, $contactNo, $userEmail)
    {
        //
        $this->message = $message;
        $this->contactNo = $contactNo;
        $this->userEmail = $userEmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.product-mail')
            ->subject('Request for product query')
            ->with(['messageText' => $this->message, 'contactNo' => $this->contactNo, 'userEmail' => $this->userEmail]);
    }
}
