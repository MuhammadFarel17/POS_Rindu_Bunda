<?php

namespace App\Filament\Resources\GajiPegawaiResource\Pages;

use App\Filament\Resources\GajiPegawaiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGajiPegawai extends CreateRecord
{
    protected static string $resource = GajiPegawaiResource::class;

    /**
     * Menangani kalau status masih kosong sebelum data dibuat
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Jika status tidak dipilih, otomatis jadi 'draft'
        $data['status'] = $data['status'] ?? 'draft';
        
        return $data;
    }

    /**
     * Tambahan untuk tombol simpan di bagian bawah
     */
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Simpan Slip Gaji'), // Mengubah tulisan tombol 'Create'
            $this->getCancelFormAction(),
        ];
    }
    
    /**
     * Redirect setelah berhasil simpan kembali ke halaman List
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}