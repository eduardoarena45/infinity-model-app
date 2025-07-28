<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Plano; // Adicionamos para poder usar o nome do plano

class PlanoAprovadoNotification extends Notification
{
    use Queueable;

    protected $plano;

    /**
     * Create a new notification instance.
     */
    public function __construct(Plano $plano)
    {
        $this->plano = $plano;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Apenas no "sininho"
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Seu plano foi ativado!',
            'message' => "O seu plano '{$this->plano->nome}' está ativo. Aproveite todos os benefícios!",
            'url' => route('planos.selecionar'), // Link para a página de planos
            'type' => 'info', // Usaremos 'info' para dar uma cor azul
        ];
    }
}
