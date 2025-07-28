<?php

namespace App\Notifications;

use App\Models\Assinatura;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PlanoExpiraHojeNotification extends Notification
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
        return [
            'title' => 'URGENTE: O seu plano expira hoje!',
            'message' => "O seu plano '{$this->assinatura->plano->nome}' expira hoje às {$this->assinatura->data_fim->format('H:i')}. Renove agora para manter o seu perfil em destaque.",
            'url' => route('planos.selecionar'),
            'type' => 'error',
        ];
    }
}
