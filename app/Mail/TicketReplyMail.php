<?php

namespace App\Mail;

use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Ticket $ticket,
        public TicketReply $reply,
        public bool $isForClient = true,
    ) {
        $this->subject(
            ($isForClient ? 'Có phản hồi mới cho ticket' : 'Khách hàng vừa trả lời ticket')
            . ' #' . $ticket->id
        );
    }

    /**
     * @return TicketReplyMail
     */

    public function build(): TicketReplyMail
    {
        return $this->view('emails.ticket-reply')
            ->with([
                'ticket' => $this->ticket,
                'reply'  => $this->reply,
                'isForClient' => $this->isForClient,
            ]);
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ticket Reply Mail',
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
}
