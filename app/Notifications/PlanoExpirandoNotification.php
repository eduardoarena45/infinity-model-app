<?php

namespace App\Notifications;

use App\Models\Assinatura;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PlanoExpirandoNotification extends Notification
{
    use Queueable;

    protected $assinatura;

    public function __construct(Assinatura $assinatura)
    {
        $this->assinatura = $assinatura;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $diasRestantes = now()->diffInDays($this->assinatura->data_fim, false);
        $textoDias = ($diasRestantes > 1) ? "{$diasRestantes} dias" : "1 dia";

        return [
            'title' => 'Atenção: O seu plano está a expirar!',
            'message' => "Falta apenas {$textoDias} para o seu plano '{$this->assinatura->plano->nome}' expirar. Renove para não perder os benefícios.",
            'url' => route('planos.selecionar'),
            'type' => 'warning',
        ];
    }
}
