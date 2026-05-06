<?php

namespace App\Mail;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InquiryConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Inquiry $inquiry)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Inquiry Has Been Received - DORA',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.inquiry_confirmation',
            with: [
                'inquiry' => $this->inquiry,
            ],
        );
    }
}
