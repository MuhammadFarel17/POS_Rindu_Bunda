<?php

namespace App\Filament\Resources\PegawaiResource\Pages;

use App\Filament\Resources\PegawaiResource;
use App\Http\Controllers\NotificationController; // Tambahan sesuai modul
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePegawai extends CreateRecord
{
    protected static string $resource = PegawaiResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Hook yang dijalankan tepat setelah data berhasil disimpan ke database
     * Disesuaikan dari logika Pembeli di modul untuk Pegawai
     */
    protected function afterCreate(): void
    {
        // 1. Ambil data pegawai yang baru saja disimpan
        $pegawai = $this->record;

        // 2. Ambil data User terkait (untuk mendapatkan email)
        $user = $pegawai->user;

        // 3. Siapkan nomor telepon
        // Membersihkan karakter selain angka agar bersih saat dikirim ke API Fonnte
        $nomorWa = preg_replace('/[^0-9]/', '', $pegawai->telepon);
        $passwordTeks = "password123"; // Sesuaikan dengan default password sistem kamu

        // 4. Susun Pesan
        $pesan = "Halo *{$pegawai->nama_pegawai}*,\n\n" .
                 "Selamat! Data pegawai Anda telah berhasil didaftarkan.\n" .
                 "Berikut adalah detail akun login Anda:\n" .
                 "Email: {$user->email}\n" .
                 "Password: {$passwordTeks}\n\n" .
                 "Segera lakukan pergantian password demi keamanan akun Anda.";

        // 5. Panggil NotificationController untuk kirim pesan
        // Pastikan nama method di controller kamu adalah 'sendMessage'
        $wa = app(NotificationController::class);
        $wa->sendMessage($nomorWa, $pesan);
    }
}