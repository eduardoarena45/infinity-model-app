<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servico;

class ServicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // APAGUE A LISTA ANTIGA E COLOQUE A SUA NOVA LISTA AQUI DENTRO
        $servicos = [
            // Exemplo:
            // 'Seu Novo Serviço 1',
            // 'Seu Novo Serviço 2',
            // 'Seu Novo Serviço 3',
            // 'etc...'
            
            // SUBSTITUA ESTA LISTA PELA SUA:
            'sexo vaginal', 'sexo anal', 'fetiche',
            'Massagem Relaxante', 'com local', 'sem local',
            'um pouquinho de tudo a gente faz', 'viagem', 'Acompanhante para Casais'
        ];

        // Este código irá criar cada serviço da sua nova lista
        foreach ($servicos as $servico) {
            Servico::firstOrCreate(['nome' => $servico]);
        }
    }
}
