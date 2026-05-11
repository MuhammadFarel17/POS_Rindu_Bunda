<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    // ✅ Deklarasikan properti agar bisa diakses oleh $this
    public $dataAtribut; 
    public $pdfContent;

    public function __construct($dataAtribut, $pdfContent)
    {
        $this->dataAtribut = $dataAtribut;
        $this->pdfContent = $pdfContent;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nota Pembelian - ' . $this->dataAtribut['invoice_number'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pembelian', // ✅ Pastikan file ini ada di resources/views/emails/pembelian.blade.php
            with: [
                'pembelian' => $this->dataAtribut, // ✅ Kirim data ke view
            ],
        );
    }

    public function attachments(): array
    {
        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(fn () => $this->pdfContent, 'nota-pembelian.pdf')
                ->withMime('application/pdf'),
        ];
    }
}