<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Carro;

class CarrosSeeder extends Seeder
{
    public function run()
    {
        $carros = [
            [
                'cliente_id' => 1,
                'placa' => 'ABC-1234',
                'marca' => 'Toyota',
                'modelo' => 'Corolla',
                'cor' => 'Prata',
                'ano' => 2020,
                'observacoes' => null,
                'ativo' => true
            ],
            [
                'cliente_id' => 1,
                'placa' => 'DEF-5678',
                'marca' => 'Honda',
                'modelo' => 'Civic',
                'cor' => 'Preto',
                'ano' => 2019,
                'observacoes' => 'Carro esportivo',
                'ativo' => true
            ],
            [
                'cliente_id' => 2,
                'placa' => 'GHI-9012',
                'marca' => 'Volkswagen',
                'modelo' => 'Gol',
                'cor' => 'Branco',
                'ano' => 2018,
                'observacoes' => null,
                'ativo' => true
            ],
            [
                'cliente_id' => 3,
                'placa' => 'JKL-3456',
                'marca' => 'Ford',
                'modelo' => 'Fiesta',
                'cor' => 'Azul',
                'ano' => 2017,
                'observacoes' => null,
                'ativo' => true
            ],
            [
                'cliente_id' => 4,
                'placa' => 'MNO-7890',
                'marca' => 'Chevrolet',
                'modelo' => 'Onix',
                'cor' => 'Vermelho',
                'ano' => 2021,
                'observacoes' => 'Carro novo',
                'ativo' => true
            ]
        ];

        foreach ($carros as $carro) {
            Carro::create($carro);
        }
    }
}
