<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClientesSeeder extends Seeder
{
    public function run()
    {
        $clientes = [
            [
                'filial_id' => 1,
                'nome' => 'João Silva',
                'telefone' => '(11) 99999-1234',
                'email' => 'joao@email.com',
                'cpf' => '123.456.789-01',
                'endereco' => 'Rua das Flores, 123 - São Paulo/SP',
                'observacoes' => 'Cliente preferencial',
                'ativo' => true
            ],
            [
                'filial_id' => 1,
                'nome' => 'Maria Santos',
                'telefone' => '(11) 98888-5678',
                'email' => 'maria@email.com',
                'cpf' => '987.654.321-09',
                'endereco' => 'Av. Paulista, 456 - São Paulo/SP',
                'observacoes' => null,
                'ativo' => true
            ],
            [
                'filial_id' => 1,
                'nome' => 'Pedro Oliveira',
                'telefone' => '(11) 97777-9012',
                'email' => 'pedro@email.com',
                'cpf' => '555.444.333-22',
                'endereco' => 'Rua Augusta, 789 - São Paulo/SP',
                'observacoes' => 'Desconto de 10%',
                'ativo' => true
            ],
            [
                'filial_id' => 1,
                'nome' => 'Ana Costa',
                'telefone' => '(11) 96666-3456',
                'email' => 'ana@email.com',
                'cpf' => '111.222.333-44',
                'endereco' => 'Rua Oscar Freire, 321 - São Paulo/SP',
                'observacoes' => null,
                'ativo' => true
            ]
        ];

        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }
    }
}
