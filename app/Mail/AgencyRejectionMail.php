<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgencyRejectionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public ?string $notes = null)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update on Your DORA Agency Application',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.agency_rejected',
            with: [
                'user' => $this->user,
                'notes' => $this->notes,
            ],
        );
    }
}
