<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Acompanhante;
use App\Models\Servico;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Cria o seu utilizador Administrador.
        User::factory()->create([
            'name' => 'eduardo',
            'email' => 'eduardo.arena45@gmail.com',
            'password' => Hash::make('eduardo255035'),
            'is_admin' => true,
        ]);

        // 2. Roda os seeders para criar os dados essenciais.
        $this->call([
            ServicoSeeder::class,
            LocaisSeeder::class, // <-- ADICIONE ESTA LINHA
        ]);
    }
}