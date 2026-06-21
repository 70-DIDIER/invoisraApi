<?php

namespace App\Mail;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Document $document,
        public string $pdfPath,
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->document->type === 'invoice'
            ? "Facture {$this->document->number}"
            : "Devis {$this->document->number}";

        return new Envelope(
            subject: "{$subject} - {$this->document->company->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.document',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfPath),
        ];
    }
}
