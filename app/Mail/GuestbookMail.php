<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables;
use Illuminate\Queue\SerializesModels;

class GuestbookMail extends Mailable
{
    use Queueable, SerializesModels;

    private array $with;

    /**
     * @param array{nome: string, cognome: string, email: string, messaggio: string} $with
     */
    public function __construct(array $with)
    {
        $this->with = $with;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Mailables\Envelope
    {
        return new Mailables\Envelope(
            subject: 'Guestbook Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Mailables\Content
    {
        return new Mailables\Content(
            view: 'emails.guestbook',
            with: $this->with
        );
    }
}
