<?php

namespace App\Filament\Resources\MidiaResource\Pages;

use App\Filament\Resources\MidiaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMidia extends EditRecord
{
    protected static string $resource = MidiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
