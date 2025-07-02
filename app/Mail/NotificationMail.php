<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $notificationTitle;
    public $notificationMessage;
    public $unsubscribeLink;

    /**
     * Create a new message instance.
     */
    public function __construct(string $name, string $notificationTitle, string $notificationMessage, string $unsubscribeLink)
    {
        $this->name = $name;
        $this->notificationTitle = $notificationTitle;
        $this->notificationMessage = $notificationMessage;
        $this->unsubscribeLink = $unsubscribeLink;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->notificationTitle . ' - Motionmaster',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.notificationmail',
            with: [
                'name' => $this->name,
                'notificationTitle' => $this->notificationTitle,
                'notificationMessage' => $this->notificationMessage,
                'unsubscribeLink' => $this->unsubscribeLink,
            ]
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
