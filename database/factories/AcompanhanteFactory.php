<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcompanhanteFactory extends Factory
{
    public function definition(): array
    {
        // Lista de cidades e estados para os nossos testes
        $locais = [
            ['cidade' => 'Franca', 'estado' => 'SP'],
            ['cidade' => 'Ribeirão Preto', 'estado' => 'SP'],
            ['cidade' => 'São Paulo', 'estado' => 'SP'],
            ['cidade' => 'Campinas', 'estado' => 'SP'],
            ['cidade' => 'Rio de Janeiro', 'estado' => 'RJ'],
            ['cidade' => 'Belo Horizonte', 'estado' => 'MG'],
        ];
        $local = $this->faker->randomElement($locais);

        return [
            // Cria um utilizador novo para cada perfil de acompanhante
            'user_id' => User::factory(),
            'nome_artistico' => $this->faker->firstName(),
            'imagem_principal_url' => 'perfis/placeholder.jpg', // Usaremos uma imagem padrão
            'data_nascimento' => $this->faker->dateTimeBetween('-35 years', '-18 years'),
            'cidade' => $local['cidade'],
            'estado' => $local['estado'],
            'descricao_curta' => $this->faker->paragraph(2),
            'valor_hora' => $this->faker->randomFloat(2, 150, 600),
            'whatsapp' => $this->faker->numerify('###########'),
            'is_verified' => $this->faker->boolean(70), // 70% de chance de ser verdadeiro
            'is_featured' => $this->faker->boolean(20), // 20% de chance de ser verdadeiro
            'status' => 'aprovado', // Todos os perfis de teste já nascem aprovados
        ];
    }
}