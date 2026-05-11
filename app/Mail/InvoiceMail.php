<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $pdfContent;

    public function __construct($data, $pdfContent)
    {
        $this->data = $data;
        $this->pdfContent = $pdfContent;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            // Subjek email bisa dinamis dengan nomor faktur
            subject: 'Invoice Pembayaran - ' . ($this->data['invoice_number'] ?? 'Rindu Bunda'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice',
            // Variabel 'data' otomatis bisa dipakai di blade emails/invoice.blade.php
            with: [
                'data' => $this->data,
            ],
        );
    }

    public function attachments(): array
    {
        // Menggunakan nomor faktur untuk nama file PDF agar lebih profesional
        $fileName = 'Invoice-' . ($this->data['invoice_number'] ?? 'Customer') . '.pdf';

        return [
            Attachment::fromData(fn () => $this->pdfContent, $fileName)
                ->withMime('application/pdf'),
        ];
    }
}