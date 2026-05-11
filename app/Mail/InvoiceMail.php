<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
// Tambahkan ini agar bisa mengirim attachment dari data PDF
use Illuminate\Mail\Mailables\Attachment;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    // Properti untuk menyimpan data retur dan konten file PDF
    public $retur;
    public $pdfContent;

    /**
     * Create a new message instance.
     * Kita masukkan data retur dan konten PDF lewat constructor
     */
    public function __construct($retur, $pdfContent)
    {
        $this->retur = $retur;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // Subjek email yang akan dilihat pelanggan
            subject: 'Nota Retur Penjualan - POS Rindu Bunda',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // Arahkan ke file view blade untuk isi body emailnya
            view: 'emails.retur',
            with: [
                'retur' => $this->retur,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            // Membuat lampiran PDF langsung dari data memori (pdfContent)
            Attachment::fromData(fn () => $this->pdfContent, 'Nota-Retur.pdf')
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