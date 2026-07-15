<?php

declare(strict_types=1);

namespace App\Features\Contact\Mail;

use App\Features\Contact\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class InquiryReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Inquiry $inquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【BuildOne】新しいお問い合わせ: '.$this->inquiry->company,
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'contact::mail.inquiry-received',
        );
    }
}
