<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PickupRequestedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Order $order, public string $awb)
    {
        //
    }

    public function build()
    {
        return $this->view('emails.pickup_requested')
            ->with([
                'order' => $this->order,
                'awb' => $this->awb,
            ])
            ->subject('Your Pickup (Resi: ' . $this->awb . ') has been Scheduled');
    }
}
