<?php

namespace App\Mail;

use App\Models\Callback;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CallBackMail extends Mailable
{
    use Queueable;
    use SerializesModels;
    public array $data;

    /**
     * Create a new message instance.
     */
    // public function __construct(public Callback $callback)
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('jurijlunjov@yandex.ru', 'SimpleCMS'),
            subject: 'Call Back',
            tags: ['callback']
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'client_manikur.mail_callback',
            with: [
                'phone' => $this->data['phone'],
                'name' => $this->data['name'],
                'send' => $this->data['send'],
            ]
            // text: 'emails.mail_callback-text'
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
