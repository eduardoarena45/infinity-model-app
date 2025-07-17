<?php

namespace App\Filament\Resources\AcompanhanteResource\Pages;

use App\Filament\Resources\AcompanhanteResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str; // Importa a classe Str para manipular texto

class CreateAcompanhante extends CreateRecord
{
    protected static string $resource = AcompanhanteResource::class;

    /**
     * Este método é executado ANTES de o perfil ser criado.
     * Vamos padronizar o nome da cidade.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Garante que o nome da cidade seja sempre guardado da mesma forma
        // (remove espaços e coloca a primeira letra de cada palavra em maiúsculo)
        if (isset($data['cidade'])) {
            $data['cidade'] = Str::title(trim($data['cidade']));
        }

        // O resto dos dados continua normalmente
        return $data;
    }

    // A função afterCreate() não é necessária para este problema,
    // então o ficheiro fica mais limpo e focado.
}
