<?php

namespace App\Filament\Resources\ReturPenjualanResource\Pages;

use App\Filament\Resources\ReturPenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReturPenjualan extends EditRecord
{
    protected static string $resource = ReturPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
