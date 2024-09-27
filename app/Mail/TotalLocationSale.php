<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TotalLocationSale extends Mailable
{
    use Queueable, SerializesModels;

    public $totalLocationSale;
    /**
     * Create a new message instance.
     */
    public function __construct($totalLocationSale)
    {
        $this->totalLocationSale = $totalLocationSale;
        // $this->subject($totalLocationSale['sale_location']." location total sales just exceeds $".$totalLocationSale['exceed_sale']);
        $this->subject('Total Sales Alert');

    }

    
    public function envelope(): Envelope
    {
        return new Envelope(
            // subject: 'Total Location Sale',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'location.email.totalLocationSale',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
