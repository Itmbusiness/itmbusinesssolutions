<?php

namespace App\Mail;

use App\Models\MarketingSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmMarketingSubscription extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public MarketingSubscription $subscription) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Confirm your ITM Business Solutions email updates');
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.confirm-marketing-subscription');
    }
}
