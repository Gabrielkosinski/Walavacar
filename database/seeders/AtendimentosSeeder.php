<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Atendimento;
use Carbon\Carbon;

class AtendimentosSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        
        $atendimentos = [
            [
                'cliente_id' => 1,
                'carro_id' => 1,
                'servico_id' => 1,
                'filial_id' => 1,
                'status' => 'concluido',
                'data_agendamento' => $now->copy()->subDays(2),
                'data_inicio' => $now->copy()->subDays(2)->addHour(),
                'data_fim' => $now->copy()->subDays(2)->addHours(1.5),
                'valor' => 25.00,
                'observacoes' => 'Serviço realizado com sucesso'
            ],
            [
                'cliente_id' => 2,
                'carro_id' => 3,
                'servico_id' => 2,
                'filial_id' => 1,
                'status' => 'concluido',
                'data_agendamento' => $now->copy()->subDays(1),
                'data_inicio' => $now->copy()->subDays(1)->addHour(),
                'data_fim' => $now->copy()->subDays(1)->addHours(2),
                'valor' => 45.00,
                'observacoes' => null
            ],
            [
                'cliente_id' => 3,
                'carro_id' => 4,
                'servico_id' => 4,
                'filial_id' => 1,
                'status' => 'em_andamento',
                'data_agendamento' => $now,
                'data_inicio' => $now->copy()->subMinutes(30),
                'data_fim' => null,
                'valor' => 80.00,
                'observacoes' => 'Lavagem detalhada em andamento'
            ],
            [
                'cliente_id' => 4,
                'carro_id' => 5,
                'servico_id' => 1,
                'filial_id' => 1,
                'status' => 'agendado',
                'data_agendamento' => $now->copy()->addHours(2),
                'data_inicio' => null,
                'data_fim' => null,
                'valor' => 25.00,
                'observacoes' => null
            ],
            [
                'cliente_id' => 1,
                'carro_id' => 2,
                'servico_id' => 3,
                'filial_id' => 1,
                'status' => 'agendado',
                'data_agendamento' => $now->copy()->addDay(),
                'data_inicio' => null,
                'data_fim' => null,
                'valor' => 35.00,
                'observacoes' => 'Enceramento agendado para amanhã'
            ],
            [
                'cliente_id' => 2,
                'carro_id' => 3,
                'servico_id' => 5,
                'filial_id' => 1,
                'status' => 'concluido',
                'data_agendamento' => $now->copy()->subDays(3),
                'data_inicio' => $now->copy()->subDays(3)->addHour(),
                'data_fim' => $now->copy()->subDays(3)->addHours(1.5),
                'valor' => 20.00,
                'observacoes' => 'Aspiração completa'
            ]
        ];

        foreach ($atendimentos as $atendimento) {
            Atendimento::create($atendimento);
        }
    }
}
