<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plano; // Importa o Model Plano

class PlanosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Plano Grátis
        Plano::create([
            'nome' => 'Plano Grátis',
            'slug' => 'gratis',
            'descricao' => 'O ponto de partida confiável com selo de verificação e WhatsApp visível.',
            'preco' => 0.00,
            'limite_fotos' => 4,
            'permite_videos' => false,
            'destaque' => false,
        ]);

        // Plano Simples
        Plano::create([
            'nome' => 'Plano Simples',
            'slug' => 'simples',
            'descricao' => 'Mais espaço para encantar com o dobro de fotos e melhor posicionamento.',
            'preco' => 29.90,
            'limite_fotos' => 8,
            'permite_videos' => false,
            'destaque' => false,
        ]);

        // Plano Premium
        Plano::create([
            'nome' => 'Plano Premium',
            'slug' => 'premium',
            'descricao' => 'Destaque-se no topo da página com o máximo de visibilidade e recursos.',
            'preco' => 59.90,
            'limite_fotos' => 16,
            'permite_videos' => true,
            'destaque' => true,
        ]);
    }
}
