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
 * Confirms receipt of a quotation request to the person who sent it. Queued
 * for the same reason as NewLeadReceived — never block the HTTP response.
 */
class LeadConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Lead $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "We've received your request — {$this->lead->reference}",
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.lead-confirmation');
    }
}
