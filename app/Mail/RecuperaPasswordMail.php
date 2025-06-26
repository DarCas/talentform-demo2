<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables;
use Illuminate\Queue\SerializesModels;

class RecuperaPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    private array $with;

    /**
     * @param array{usernm: string, passwd: string} $with
     */
    function __construct(array $with)
    {
        $this->with = $with;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Mailables\Envelope
    {
        return new Mailables\Envelope(
            subject: 'Guestbook: recupera password',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Mailables\Content
    {
        return new Mailables\Content(
            view: 'emails.recupera-password',
            with: $this->with
        );
    }
}
