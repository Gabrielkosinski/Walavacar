<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Despesa;
use App\Models\User;
use Carbon\Carbon;

class DespesasSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        
        if (!$user) {
            $this->command->error('Nenhum usuário encontrado. Execute o seeder de usuários primeiro.');
            return;
        }

        $despesas = [
            // Despesas Fixas
            [
                'descricao' => 'Aluguel do espaço comercial',
                'valor' => 2500.00,
                'data_despesa' => Carbon::now()->subDays(25),
                'tipo' => 'fixa',
                'categoria' => 'aluguel',
                'observacoes' => 'Aluguel mensal do lava-jato',
                'recorrente' => true,
                'dia_vencimento' => 10,
                'status' => 'paga',
                'forma_pagamento' => 'transferencia',
                'user_id' => $user->id
            ],
            [
                'descricao' => 'Salário do funcionário João',
                'valor' => 1500.00,
                'data_despesa' => Carbon::now()->subDays(20),
                'tipo' => 'fixa',
                'categoria' => 'funcionarios',
                'observacoes' => 'Salário mensal + vale transporte',
                'recorrente' => true,
                'dia_vencimento' => 5,
                'status' => 'paga',
                'forma_pagamento' => 'pix',
                'user_id' => $user->id
            ],
            [
                'descricao' => 'Salário da funcionária Maria',
                'valor' => 1400.00,
                'data_despesa' => Carbon::now()->subDays(20),
                'tipo' => 'fixa',
                'categoria' => 'funcionarios',
                'observacoes' => 'Salário mensal + vale alimentação',
                'recorrente' => true,
                'dia_vencimento' => 5,
                'status' => 'paga',
                'forma_pagamento' => 'pix',
                'user_id' => $user->id
            ],
            [
                'descricao' => 'Internet e telefone',
                'valor' => 180.00,
                'data_despesa' => Carbon::now()->subDays(15),
                'tipo' => 'fixa',
                'categoria' => 'telefone',
                'observacoes' => 'Plano empresarial com internet de 200MB',
                'recorrente' => true,
                'dia_vencimento' => 15,
                'status' => 'paga',
                'forma_pagamento' => 'cartao_credito',
                'user_id' => $user->id
            ],
            
            // Despesas Variáveis
            [
                'descricao' => 'Conta de água',
                'valor' => 340.50,
                'data_despesa' => Carbon::now()->subDays(10),
                'tipo' => 'variavel',
                'categoria' => 'agua',
                'observacoes' => 'Consumo elevado devido ao verão',
                'recorrente' => false,
                'status' => 'paga',
                'forma_pagamento' => 'boleto',
                'user_id' => $user->id
            ],
            [
                'descricao' => 'Energia elétrica',
                'valor' => 520.75,
                'data_despesa' => Carbon::now()->subDays(8),
                'tipo' => 'variavel',
                'categoria' => 'energia',
                'observacoes' => 'Conta de energia do mês passado',
                'recorrente' => false,
                'status' => 'paga',
                'forma_pagamento' => 'pix',
                'user_id' => $user->id
            ],
            [
                'descricao' => 'Compra de shampoo automotivo',
                'valor' => 285.00,
                'data_despesa' => Carbon::now()->subDays(5),
                'tipo' => 'variavel',
                'categoria' => 'produtos',
                'observacoes' => '10 galões de shampoo concentrado',
                'recorrente' => false,
                'status' => 'paga',
                'forma_pagamento' => 'dinheiro',
                'user_id' => $user->id
            ],
            [
                'descricao' => 'Cera automotiva premium',
                'valor' => 180.00,
                'data_despesa' => Carbon::now()->subDays(3),
                'tipo' => 'variavel',
                'categoria' => 'produtos',
                'observacoes' => 'Cera de carnaúba para acabamento',
                'recorrente' => false,
                'status' => 'paga',
                'forma_pagamento' => 'cartao_debito',
                'user_id' => $user->id
            ],
            [
                'descricao' => 'Manutenção da lavadora de alta pressão',
                'valor' => 450.00,
                'data_despesa' => Carbon::now()->subDays(7),
                'tipo' => 'variavel',
                'categoria' => 'manutencao',
                'observacoes' => 'Troca de vedações e óleo',
                'recorrente' => false,
                'status' => 'paga',
                'forma_pagamento' => 'pix',
                'user_id' => $user->id
            ],
            [
                'descricao' => 'Combustível para aspirador',
                'valor' => 95.00,
                'data_despesa' => Carbon::now()->subDays(2),
                'tipo' => 'variavel',
                'categoria' => 'combustivel',
                'observacoes' => 'Gasolina para aspirador industrial',
                'recorrente' => false,
                'status' => 'paga',
                'forma_pagamento' => 'dinheiro',
                'user_id' => $user->id
            ],
            
            // Despesas Pendentes/Futuras
            [
                'descricao' => 'Conta de energia (próximo mês)',
                'valor' => 480.00,
                'data_despesa' => Carbon::now()->addDays(5),
                'tipo' => 'variavel',
                'categoria' => 'energia',
                'observacoes' => 'Previsão baseada no consumo atual',
                'recorrente' => false,
                'status' => 'pendente',
                'user_id' => $user->id
            ],
            [
                'descricao' => 'Impostos municipais',
                'valor' => 350.00,
                'data_despesa' => Carbon::now()->addDays(10),
                'tipo' => 'fixa',
                'categoria' => 'impostos',
                'observacoes' => 'Licença de funcionamento anual',
                'recorrente' => false,
                'status' => 'pendente',
                'user_id' => $user->id
            ]
        ];

        foreach ($despesas as $despesa) {
            Despesa::create($despesa);
        }

        $this->command->info('✅ Despesas de exemplo criadas com sucesso!');
        $this->command->info('📊 Total: ' . count($despesas) . ' despesas');
        $this->command->info('🔵 Fixas: ' . collect($despesas)->where('tipo', 'fixa')->count());
        $this->command->info('🟢 Variáveis: ' . collect($despesas)->where('tipo', 'variavel')->count());
    }
}
