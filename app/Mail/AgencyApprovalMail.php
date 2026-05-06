<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgencyApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your DORA Agency Account is Approved!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.agency_approved',
            with: [
                'user' => $this->user,
            ],
        );
    }
}
