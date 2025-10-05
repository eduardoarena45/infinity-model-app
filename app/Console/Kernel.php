<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Os comandos Artisan personalizados da aplicação.
     *
     * Aqui você registra todos os comandos que criou manualmente.
     */
    protected $commands = [
        \App\Console\Commands\GenerateSitemap::class, // 👈 adicionamos o comando do sitemap
    ];

    /**
     * Define o agendamento de comandos (cron).
     */
    protected function schedule(Schedule $schedule)
    {
        // Executa o comando uma vez por dia
        $schedule->command('sitemap:generate')->daily();
    }

    /**
     * Registra as rotas e comandos Artisan padrão.
     */
    protected function commands()
    {
        // Carrega automaticamente todos os comandos na pasta app/Console/Commands
        $this->load(__DIR__.'/Commands');

        // Carrega comandos definidos em routes/console.php (se existir)
        require base_path('routes/console.php');
    }
}
