<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Filial;

class FiliaisSeeder extends Seeder
{
    public function run()
    {
        $filiais = [
            [
                'nome' => 'Lava Car - Matriz',
                'endereco' => 'Av. Brigadeiro Faria Lima, 1000 - São Paulo/SP',
                'telefone' => '(11) 3000-1000'
            ],
            [
                'nome' => 'Lava Car - Shopping',
                'endereco' => 'Shopping Center Norte - São Paulo/SP',
                'telefone' => '(11) 3000-2000'
            ]
        ];

        foreach ($filiais as $filial) {
            Filial::create($filial);
        }
    }
}
