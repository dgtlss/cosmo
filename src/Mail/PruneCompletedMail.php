<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class PruneCompletedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $totalErrors;
    /**
     * Create a new message instance.
     */
    public function __construct($totalErrors)
    {
        $this->totalErrors = $totalErrors;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: config('cosmo.notification_credentials.mail.from'),
            to: config('cosmo.notification_credentials.mail.to'),
            subject: '🎉 Cosmo Logs Pruned Successfully!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.prunecomplete',
            with: [
                'totalErrors' => $this->totalErrors,
            ],
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
