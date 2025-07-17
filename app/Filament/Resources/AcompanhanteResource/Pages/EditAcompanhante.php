<?php

namespace App\Filament\Resources\AcompanhanteResource\Pages;

use App\Filament\Resources\AcompanhanteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Acompanhante; // Importa o Model Acompanhante

class EditAcompanhante extends EditRecord
{
    protected static string $resource = AcompanhanteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Esta função é o "tradutor" que faltava.
     * Ela é executada ANTES de o formulário ser preenchido com os dados.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Carrega o registro completo da acompanhante, incluindo a relação com a cidade e o estado.
        $acompanhante = Acompanhante::with('cidade.estado')->find($data['id']);

        // Se a acompanhante tiver uma cidade salva, nós encontramos o ID do estado.
        if ($acompanhante && $acompanhante->cidade) {
            $data['estado_id'] = $acompanhante->cidade->estado_id;
        }

        // Retorna os dados para o formulário, agora com o 'estado_id' incluído.
        return $data;
    }


    /**
     * Esta função é executada logo após o perfil ser salvo.
     * Ela vai aprovar as mídias automaticamente.
     */
    protected function afterSave(): void
    {
        // Verifica se o status do perfil principal foi alterado para "aprovado"
        if ($this->record->status === 'aprovado') {
            
            // Procura por todas as mídias (fotos/vídeos) deste perfil
            // que ainda estão com o status "pendente" e atualiza-as para "aprovado".
            $this->record->midias()->where('status', 'pendente')->update(['status' => 'aprovado']);
        }
    }
}
