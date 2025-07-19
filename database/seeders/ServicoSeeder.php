<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Servico;

class ServicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servicos = [
            [
                'nome' => 'Lavagem Simples',
                'descricao' => 'Lavagem externa básica com água e sabão',
                'preco' => 15.00,
                'tempo_estimado' => 30,
                'ativo' => true
            ],
            [
                'nome' => 'Lavagem Completa',
                'descricao' => 'Lavagem externa e interna completa, pneus e motor',
                'preco' => 25.00,
                'tempo_estimado' => 60,
                'ativo' => true
            ],
            [
                'nome' => 'Enceramento',
                'descricao' => 'Aplicação de cera para proteção da pintura',
                'preco' => 40.00,
                'tempo_estimado' => 45,
                'ativo' => true
            ],
            [
                'nome' => 'Lavagem de Motor',
                'descricao' => 'Limpeza do compartimento do motor',
                'preco' => 20.00,
                'tempo_estimado' => 30,
                'ativo' => true
            ],
            [
                'nome' => 'Aspiração Interna',
                'descricao' => 'Aspiração dos bancos, tapetes e porta-malas',
                'preco' => 10.00,
                'tempo_estimado' => 20,
                'ativo' => true
            ],
            [
                'nome' => 'Detalhamento Premium',
                'descricao' => 'Serviço completo com lavagem, enceramento, limpeza interna e externa detalhada',
                'preco' => 80.00,
                'tempo_estimado' => 120,
                'ativo' => true
            ]
        ];

        foreach ($servicos as $servico) {
            Servico::create($servico);
        }
    }
}
