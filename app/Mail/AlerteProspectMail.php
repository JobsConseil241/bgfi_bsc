<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AlerteProspectMail extends Mailable
{
    use Queueable, SerializesModels;
    public $numero;
    public $source; 

    /**
     * Create a new message instance.
     */
    public function __construct($numero, $source = 'Système')
    {
        $this->numero = $numero;
        $this->source = $source;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Alerte Prospect Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
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

    public function build()
    {
        return $this->subject("[Prospect] Numéro à contacter - {$this->numero}")
                    ->view('emails.alerte_prospect');
    }
}
