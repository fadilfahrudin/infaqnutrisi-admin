<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Donasi;

class PaymentThankYou extends Mailable
{
    use Queueable, SerializesModels;

    public $donasi;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Donasi $donasi)
    {
        $this->donasi = $donasi;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Pembayaran Berhasil')->from('info@semangatbantu.com')->view('emails.paymentThankYou');
    }
}
