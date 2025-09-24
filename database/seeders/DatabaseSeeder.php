<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Esta é a instrução que faltava.
        // Ela diz ao Laravel para executar o nosso PlanosSeeder
        // e garantir que os planos são criados no banco de dados.
        $this->call([
            PlanosSeeder::class,
            // Se no futuro você tiver outros seeders, pode adicioná-los aqui.
        ]);
    }
}
