<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendReclamationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data, $user;

    /**
     * Create a new message instance.
     */
    public function __construct(array $data, $user)
    {
        $this->data = $data;
        $this->user = $user;
    }


    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->subject('Reclamation Effectuée')
            ->view('emails.NotificationAvisEmail')
            ->with(['data' => $this->data, 'user' => $this->user]);
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
