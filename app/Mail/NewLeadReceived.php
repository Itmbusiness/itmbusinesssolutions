<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Notifies ITM that a new lead has come in. Queued so a slow/unreachable SMTP
 * server never blocks the quote-request or workspace-request HTTP response.
 */
class NewLeadReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Lead $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New lead: {$this->lead->reference}",
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.new-lead');
    }
}
