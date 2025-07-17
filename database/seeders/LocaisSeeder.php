<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estado; // Importa o Model Estado
use App\Models\Cidade; // Importa o Model Cidade

class LocaisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fonte: https://github.com/chinnonsantos/sql-paises-estados-cidades
        // Adaptado para o Eloquent

        $estados = [
            ['id' => 1, 'nome' => 'Acre', 'uf' => 'AC'],
            ['id' => 2, 'nome' => 'Alagoas', 'uf' => 'AL'],
            ['id' => 3, 'nome' => 'Amazonas', 'uf' => 'AM'],
            ['id' => 4, 'nome' => 'Amapá', 'uf' => 'AP'],
            ['id' => 5, 'nome' => 'Bahia', 'uf' => 'BA'],
            ['id' => 6, 'nome' => 'Ceará', 'uf' => 'CE'],
            ['id' => 7, 'nome' => 'Distrito Federal', 'uf' => 'DF'],
            ['id' => 8, 'nome' => 'Espírito Santo', 'uf' => 'ES'],
            ['id' => 9, 'nome' => 'Goiás', 'uf' => 'GO'],
            ['id' => 10, 'nome' => 'Maranhão', 'uf' => 'MA'],
            ['id' => 11, 'nome' => 'Minas Gerais', 'uf' => 'MG'],
            ['id' => 12, 'nome' => 'Mato Grosso do Sul', 'uf' => 'MS'],
            ['id' => 13, 'nome' => 'Mato Grosso', 'uf' => 'MT'],
            ['id' => 14, 'nome' => 'Pará', 'uf' => 'PA'],
            ['id' => 15, 'nome' => 'Paraíba', 'uf' => 'PB'],
            ['id' => 16, 'nome' => 'Pernambuco', 'uf' => 'PE'],
            ['id' => 17, 'nome' => 'Piauí', 'uf' => 'PI'],
            ['id' => 18, 'nome' => 'Paraná', 'uf' => 'PR'],
            ['id' => 19, 'nome' => 'Rio de Janeiro', 'uf' => 'RJ'],
            ['id' => 20, 'nome' => 'Rio Grande do Norte', 'uf' => 'RN'],
            ['id' => 21, 'nome' => 'Rondônia', 'uf' => 'RO'],
            ['id' => 22, 'nome' => 'Roraima', 'uf' => 'RR'],
            ['id' => 23, 'nome' => 'Rio Grande do Sul', 'uf' => 'RS'],
            ['id' => 24, 'nome' => 'Santa Catarina', 'uf' => 'SC'],
            ['id' => 25, 'nome' => 'Sergipe', 'uf' => 'SE'],
            ['id' => 26, 'nome' => 'São Paulo', 'uf' => 'SP'],
            ['id' => 27, 'nome' => 'Tocantins', 'uf' => 'TO'],
        ];

        foreach ($estados as $estadoData) {
            Estado::create($estadoData);
        }

        // Cidades... (código das cidades omitido para brevidade, mas o processo seria o mesmo)
        // O ideal é ter um seeder separado para cidades ou carregá-las de um JSON/CSV.
        // Para simplificar e garantir que funcione, vamos adicionar algumas cidades de SP e RJ como exemplo.

        $cidades = [
            // SP
            ['estado_id' => 26, 'nome' => 'São Paulo'],
            ['estado_id' => 26, 'nome' => 'Campinas'],
            ['estado_id' => 26, 'nome' => 'Santos'],
            ['estado_id' => 26, 'nome' => 'Ribeirão Preto'],
             ['estado_id' => 26, 'nome' => 'Franca'],


            // RJ
            ['estado_id' => 19, 'nome' => 'Rio de Janeiro'],
            ['estado_id' => 19, 'nome' => 'Niterói'],
            ['estado_id' => 19, 'nome' => 'Duque de Caxias'],
        ];

        foreach ($cidades as $cidadeData) {
            Cidade::create($cidadeData);
        }
        
        $this->command->info('Estados e Cidades de exemplo foram criados com sucesso!');
    }
}