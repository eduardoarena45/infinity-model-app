<?php

namespace App\Filament\Resources\AcompanhanteResource\Pages;

use App\Filament\Resources\AcompanhanteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAcompanhantes extends ListRecords
{
    protected static string $resource = AcompanhanteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
