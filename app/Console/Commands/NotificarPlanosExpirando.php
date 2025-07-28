<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Assinatura;
use App\Models\User;
use App\Notifications\PlanoExpirandoNotification;
use App\Notifications\PlanoExpiraHojeNotification;
use App\Notifications\ReativarPlanoNotification;
use Carbon\Carbon;

class NotificarPlanosExpirando extends Command
{
    protected $signature = 'app:notificar-planos-expirando';
    protected $description = 'Verifica e notifica sobre assinaturas que estão a expirar, que expiram hoje ou que já expiraram.';

    public function handle()
    {
        $this->info('Iniciando verificação de assinaturas...');
        $this->comment('-----------------------------------------');

        $this->notificarPlanosPrestesAExpirar();
        $this->comment('-----------------------------------------');
        $this->notificarPlanosQueExpiramHoje();
        $this->comment('-----------------------------------------');
        $this->notificarPlanosExpiradosRecentemente();
        $this->comment('-----------------------------------------');

        $this->info('Processo de notificação concluído.');
    }

    private function notificarPlanosPrestesAExpirar()
    {
        $this->info('A verificar planos prestes a expirar...');
        $diasParaVerificar = [3, 2, 1];

        foreach ($diasParaVerificar as $dias) {
            $dataAlvo = Carbon::now()->addDays($dias)->toDateString();
            $this->line("A procurar por assinaturas que expiram em {$dias} dias ({$dataAlvo})...");
            
            $assinaturas = Assinatura::with('user', 'plano')
                ->where('status', 'ativa')
                ->whereDate('data_fim', $dataAlvo)
                ->get();

            if ($assinaturas->isEmpty()) {
                $this->line("Nenhuma assinatura encontrada.");
                continue;
            }

            foreach ($assinaturas as $assinatura) {
                if ($assinatura->user) {
                    $assinatura->user->notify(new PlanoExpirandoNotification($assinatura));
                    $this->info("-> Notificação de {$dias} dias enviada para: {$assinatura->user->email}");
                }
            }
        }
    }

    private function notificarPlanosQueExpiramHoje()
    {
        $this->info('A verificar planos que expiram hoje...');
        $dataAlvo = Carbon::today()->toDateString();
        $this->line("A procurar por assinaturas que expiram em {$dataAlvo}...");

        $assinaturas = Assinatura::with('user', 'plano')
            ->where('status', 'ativa')
            ->whereDate('data_fim', $dataAlvo)
            ->get();

        if ($assinaturas->isEmpty()) {
            $this->line("Nenhuma assinatura encontrada.");
            return;
        }

        foreach ($assinaturas as $assinatura) {
            if ($assinatura->user) {
                $assinatura->user->notify(new PlanoExpiraHojeNotification($assinatura));
                $this->info("-> Notificação de 'expira hoje' enviada para: {$assinatura->user->email}");
            }
        }
    }

    private function notificarPlanosExpiradosRecentemente()
    {
        $this->info('A verificar planos expirados para reativação...');
        $dataAlvo = Carbon::now()->subDays(2)->toDateString();
        $this->line("A procurar por utilizadores cuja última assinatura expirou em {$dataAlvo}...");
        
        $utilizadoresParaNotificar = User::whereHas('assinaturas', function ($query) use ($dataAlvo) {
            $query->whereDate('data_fim', $dataAlvo);
        })->whereDoesntHave('assinaturas', function ($query) {
            $query->where('status', 'ativa');
        })->get();

        if ($utilizadoresParaNotificar->isEmpty()) {
            $this->line("Nenhum utilizador encontrado para notificar.");
            return;
        }

        foreach ($utilizadoresParaNotificar as $user) {
            $user->notify(new ReativarPlanoNotification());
            $this->info("-> Lembrete de reativação enviado para: {$user->email}");
        }
    }
}
