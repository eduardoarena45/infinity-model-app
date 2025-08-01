<?php

namespace App\Observers;

use App\Models\Acompanhante;
use Illuminate\Support\Facades\Cache;

class AcompanhanteObserver
{
    /**
     * Handle the Acompanhante "updated" event.
     */
    public function updated(Acompanhante $acompanhante): void
    {
        // Limpa todo o cache da aplicação.
        // Esta é a forma mais simples e garantida de garantir que a vitrine seja atualizada.
        Cache::flush();
    }

    /**
     * Handle the Acompanhante "deleted" event.
     */
    public function deleted(Acompanhante $acompanhante): void
    {
        // Também limpa o cache se um perfil for apagado.
        Cache::flush();
    }
}
