<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FirstSale extends Mailable
{
    use Queueable, SerializesModels;
    
    public $first_sale;

    public function __construct($first_sale)
    {
        $this->first_sale = $first_sale;
    
        // Or use the subject method
        // $this->subject('End of Day Summary has been initiated in '.$summery_data['location_name']);
        $this->subject('First Sale Alert');
    }


    public function envelope(): Envelope
    {
        return new Envelope(
            // subject: 'End of Day Summary has been initiated in',
        );
    }

   
    public function content(): Content
    {
        return new Content(
            view: 'location.email.firstSale',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}



