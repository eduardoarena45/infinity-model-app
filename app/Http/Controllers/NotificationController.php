<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Mark all unread notifications for the authenticated user as read.
     */
    public function markAsRead(Request $request): JsonResponse
    {
        // Esta linha encontra todas as notificações não lidas do utilizador
        // e atualiza o campo 'read_at' na base de dados, marcando-as como lidas.
        $request->user()->unreadNotifications->markAsRead();

        // =======================================================
        // =================== INÍCIO DA ALTERAÇÃO ==================
        // Em vez de retornar uma resposta vazia, agora retornamos um JSON
        // para que o nosso JavaScript saiba que a operação foi um sucesso.
        // =======================================================

        return response()->json(['success' => true]);

        // =======================================================
        // ==================== FIM DA ALTERAÇÃO =====================
        // =======================================================
    }
}
