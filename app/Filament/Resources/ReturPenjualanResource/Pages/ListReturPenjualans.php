<?php

namespace App\Filament\Resources\ReturPenjualanResource\Pages;

use App\Filament\Resources\ReturPenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReturPenjualans extends ListRecords
{
    protected static string $resource = ReturPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
