<?php

namespace App\Filament\Resources\SuplayerResource\Pages;

use App\Filament\Resources\SuplayerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuplayer extends ListRecords
{
    protected static string $resource = SuplayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
