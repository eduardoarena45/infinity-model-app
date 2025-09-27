<?php

namespace App\Observers;

use App\Models\Assinatura;

class AssinaturaObserver
{
    /**
     * Handle the Assinatura "updating" event.
     * Usamos 'updating' em vez de 'updated' para garantir que o valor_pago
     * seja definido ANTES de a assinatura ser salva no banco de dados.
     */
    public function updating(Assinatura $assinatura): void
    {
        // =======================================================
        // =================== INÍCIO DA LÓGICA ==================
        // Este é o cérebro do nosso "vigia".
        // =======================================================

        // PASSO 1: O "vigia" verifica se o status da assinatura está a ser alterado PARA 'ativa'.
        // 'isDirty' verifica se o campo 'status' foi modificado nesta atualização.
        if ($assinatura->isDirty('status') && $assinatura->status === 'ativa') {

            // PASSO 2: Se o status foi alterado para 'ativa', o "vigia" verifica se o valor_pago ainda está vazio (NULL).
            // Isto previne que o valor seja sobrescrito se já tiver sido definido antes.
            if (is_null($assinatura->valor_pago)) {

                // PASSO 3: O "vigia" busca o plano associado e copia o preço para a coluna 'valor_pago'.
                // O '?? 0.00' garante que, se por algum motivo não houver plano, o valor seja 0.
                $assinatura->valor_pago = $assinatura->plano->preco ?? 0.00;
            }
        }

        // =======================================================
        // ==================== FIM DA LÓGICA =====================
        // =======================================================
    }

    // As outras funções não são necessárias para a nossa tarefa, então podem ficar vazias.

    public function created(Assinatura $assinatura): void {}
    public function updated(Assinatura $assinatura): void {}
    public function deleted(Assinatura $assinatura): void {}
    public function restored(Assinatura $assinatura): void {}
    public function forceDeleted(Assinatura $assinatura): void {}
}
