<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $confirmLink;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $mail, $randomCode)
    {
        //Skapa länk för att bekräfta e-postadress
        $this->name = $name;
        $this->confirmLink = env('APP_URL') . '/verify-email.php?email=' . urlencode($mail) . '&code=' . $randomCode;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Välkommen till Motion Master - bekräfta din e-postadress',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'mail.welcomemail',
            text: 'mail.welcomemail-plain'
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
