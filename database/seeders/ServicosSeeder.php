<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servico;

class ServicosSeeder extends Seeder
{
    public function run()
    {
        $servicos = [
            [
                'nome' => 'Lavagem Simples',
                'descricao' => 'Lavagem externa do veículo com shampoo e cera',
                'preco' => 25.00,
                'tempo_estimado' => 30,
                'ativo' => true
            ],
            [
                'nome' => 'Lavagem Completa',
                'descricao' => 'Lavagem externa e interna do veículo',
                'preco' => 45.00,
                'tempo_estimado' => 60,
                'ativo' => true
            ],
            [
                'nome' => 'Enceramento',
                'descricao' => 'Aplicação de cera premium no veículo',
                'preco' => 35.00,
                'tempo_estimado' => 45,
                'ativo' => true
            ],
            [
                'nome' => 'Lavagem Detalhada',
                'descricao' => 'Lavagem completa com limpeza de motor e detalhamento',
                'preco' => 80.00,
                'tempo_estimado' => 120,
                'ativo' => true
            ],
            [
                'nome' => 'Aspiração',
                'descricao' => 'Limpeza interna completa do veículo',
                'preco' => 20.00,
                'tempo_estimado' => 30,
                'ativo' => true
            ]
        ];

        foreach ($servicos as $servico) {
            Servico::create($servico);
        }
    }
}
