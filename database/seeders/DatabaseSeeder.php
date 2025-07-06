<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Acompanhante;
use App\Models\Servico;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Cria o seu utilizador Administrador
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@site.com',
            'password' => Hash::make('password'), // A senha é 'password'
            'is_admin' => true,
        ]);

        // 2. Roda o seeder para criar os serviços
        $this->call(ServicoSeeder::class);
        $servicos = Servico::all();

        // 3. Cria 20 perfis de acompanhantes usando a nossa factory
        Acompanhante::factory(20)->create()->each(function ($acompanhante) use ($servicos) {
            // 4. Para cada perfil criado, atribui de 2 a 5 serviços aleatórios
            $acompanhante->servicos()->attach(
                $servicos->random(rand(2, 5))->pluck('id')->toArray()
            );
        });
    }
}
