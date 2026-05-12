<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// tambahan
use Illuminate\Mail\Mailables\Attachment;
use App\Mail\ReservasiMail;

class ReservasiMail extends Mailable
{
    use Queueable, SerializesModels;

    // =========================
    // DATA
    // =========================
    public $data;
    public $pdfContent;

    // =========================
    // CONSTRUCT
    // =========================
    public function __construct($data, $pdfContent)
    {
        $this->data = $data;
        $this->pdfContent = $pdfContent;
    }

    // =========================
    // SUBJECT EMAIL
    // =========================
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Reservasi',
        );
    }

    // =========================
    // ISI EMAIL (VIEW)
    // =========================
    public function content(): Content
    {
        return new Content(
            view: 'emails.reservasi',
            with: [
                'data' => $this->data,
            ],
        );
    }

    // =========================
    // ATTACHMENT PDF
    // =========================
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, 'struk-reservasi.pdf')
                ->withMime('application/pdf'),
        ];
    }
}