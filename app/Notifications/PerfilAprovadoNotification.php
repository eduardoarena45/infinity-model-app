<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PerfilAprovadoNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via(object $notifiable): array
    {
        // Vamos guardar a notificação apenas na base de dados por agora.
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Estes são os dados que serão guardados na coluna 'data' da tabela de notificações.
        return [
            'title' => 'Parabéns! O seu perfil foi aprovado!',
            'message' => 'O seu perfil já está visível para todos os visitantes do site.',
            'icon' => 'check-circle', // Podemos usar isto para mostrar um ícone verde
            'url' => route('profile.edit'), // Link para onde a notificação leva
            'type' => 'success', // Adiciona o tipo para podermos estilizar no frontend
        ];
    }
}
