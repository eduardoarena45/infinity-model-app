<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servico;

class ServicoSeeder extends Seeder
{
    public function run(): void
    {
        $servicos = [
            'Jantar Romântico', 'Viagens', 'Eventos Corporativos',
            'Massagem Relaxante', 'Festa Privada', 'Cinema',
            'Teatro', 'Show', 'Acompanhante para Casais'
        ];

        foreach ($servicos as $servico) {
            Servico::create(['nome' => $servico]);
        }
    }
}