<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plano;

class PlanosSeeder extends Seeder
{
    public function run(): void
    {
        // Plano Grátis
        Plano::create([
            'nome' => 'Plano Grátis',
            'slug' => 'gratis',
            'descricao' => 'O ponto de partida confiável com selo de verificação e WhatsApp visível.',
            'preco' => 0.00,
            'limite_fotos' => 4,
            'limite_videos' => 0, // <-- CORREÇÃO: Limite explícito de 0
            'destaque' => false,
        ]);

        // Plano Simples (Mudei o nome para Básico para corresponder à sua interface)
        Plano::create([
            'nome' => 'Plano Básico',
            'slug' => 'basico',
            'descricao' => 'Mais espaço para encantar com o dobro de fotos e melhor posicionamento.',
            'preco' => 29.90,
            'limite_fotos' => 10,
            'limite_videos' => 4, // <-- CORREÇÃO: Limite de 4 vídeos
            'destaque' => false,
        ]);

        // Plano Premium
        Plano::create([
            'nome' => 'Plano Premium',
            'slug' => 'premium',
            'descricao' => 'Destaque-se no topo da página com o máximo de visibilidade e recursos.',
            'preco' => 59.90,
            'limite_fotos' => 50,
            'limite_videos' => 20, // <-- CORREÇÃO: Limite de 20 vídeos
            'destaque' => true,
        ]);
    }
}
