<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReativarPlanoNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Sentimos a sua falta! Reative o seu plano.',
            'message' => 'Perfis com planos ativos recebem mais visualizações e contatos. Invista no seu sucesso e volte a destacar-se na vitrine!',
            'url' => route('planos.selecionar'),
            'type' => 'info',
        ];
    }
}
