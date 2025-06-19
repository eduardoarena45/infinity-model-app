<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Acompanhante;

class AcompanhanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Perfil de Exemplo 1
        Acompanhante::create([
            'nome_artistico' => 'Luna Morena',
            'imagem_principal_url' => 'https://placehold.co/400x600/EAD2AC/333?text=Luna',
            'idade' => 24,
            'cidade' => 'São Paulo',
            'estado' => 'SP',
            'descricao_curta' => 'Universitária simpática e uma ótima companhia para jantares e eventos.',
            'valor_hora' => 400.00,
            'whatsapp' => '11912345678',
        ]);

        // Perfil de Exemplo 2
        Acompanhante::create([
            'nome_artistico' => 'Melissa',
            'imagem_principal_url' => 'https://placehold.co/400x600/D4A5A5/333?text=Melissa',
            'idade' => 29,
            'cidade' => 'Rio de Janeiro',
            'estado' => 'RJ',
            'descricao_curta' => 'Conversa inteligente e sorriso contagiante. Adoro cinema e boa música.',
            'valor_hora' => 550.00,
            'whatsapp' => '21987654321',
        ]);
    }
}