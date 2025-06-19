<?php

namespace App\Filament\Resources\AcompanhanteResource\Pages;

use App\Filament\Resources\AcompanhanteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAcompanhante extends EditRecord
{
    protected static string $resource = AcompanhanteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
