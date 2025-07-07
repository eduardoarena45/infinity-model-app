<?php

namespace App\Filament\Resources\AssinaturaResource\Pages;

use App\Filament\Resources\AssinaturaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssinaturas extends ListRecords
{
    protected static string $resource = AssinaturaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
