<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Atendimento;
use App\Models\Cliente;
use App\Models\Servico;
use App\Models\Carro;
use Carbon\Carbon;

class AtendimentosTestSeeder extends Seeder
{
    public function run(): void
    {
        // Criar alguns clientes de teste se não houver suficientes
        if (Cliente::count() < 10) {
            Cliente::factory(15)->create();
        }

        // Criar alguns carros de teste
        if (Carro::count() < 15) {
            $clientes = Cliente::all();
            foreach ($clientes->take(15) as $cliente) {
                if (!$cliente->carros()->exists()) {
                    Carro::factory()->create(['cliente_id' => $cliente->id]);
                }
            }
        }

        // Obter dados existentes
        $clientes = Cliente::all();
        $servicos = Servico::all();
        $carros = Carro::all();

        if ($clientes->isEmpty() || $servicos->isEmpty() || $carros->isEmpty()) {
            $this->command->error('É necessário ter clientes, serviços e carros no banco antes de executar este seeder.');
            return;
        }

        // Gerar atendimentos dos últimos 6 meses
        $dataInicio = Carbon::now()->subMonths(6);
        $dataFim = Carbon::now();

        $status = ['agendado', 'em_andamento', 'concluido', 'cancelado'];
        $statusWeights = [15, 10, 70, 5]; // Pesos para distribuição realista

        // Gerar 200 atendimentos distribuídos
        for ($i = 0; $i < 200; $i++) {
            // Data aleatória nos últimos 6 meses
            $dataAtendimento = Carbon::createFromTimestamp(
                rand($dataInicio->timestamp, $dataFim->timestamp)
            );

            // Horário mais realista (7h às 18h, com picos no meio-dia e final da tarde)
            $hora = $this->getHorarioRealista();
            $dataAtendimento->setTime($hora, rand(0, 59));

            // Status com distribuição realista
            $statusEscolhido = $this->getStatusComPeso($status, $statusWeights);

            // Valor baseado no serviço
            $servico = $servicos->random();
            $valorBase = $servico->preco ?? rand(20, 100);
            $valor = $valorBase + rand(-5, 15); // Variação no preço

            // ⏱️ Dados de cronometragem para atendimentos concluídos
            $inicioServico = null;
            $fimServico = null;
            $tempoExecucao = null;
            $funcionarioResponsavel = null;
            $notaQualidade = null;
            
            if ($statusEscolhido === 'concluido') {
                $inicioServico = $dataAtendimento->copy()->addMinutes(rand(5, 30));
                $tempoExecucao = rand(10, 120); // 10 a 120 minutos
                $fimServico = $inicioServico->copy()->addMinutes($tempoExecucao);
                $funcionarioResponsavel = ['João Silva', 'Maria Santos', 'Pedro Lima', 'Ana Costa', 'Carlos Oliveira'][rand(0, 4)];
                $notaQualidade = rand(60, 100) / 10; // Nota de 6.0 a 10.0
            } elseif ($statusEscolhido === 'em_andamento') {
                $inicioServico = $dataAtendimento->copy()->addMinutes(rand(5, 20));
                $funcionarioResponsavel = ['João Silva', 'Maria Santos', 'Pedro Lima', 'Ana Costa', 'Carlos Oliveira'][rand(0, 4)];
            }

            Atendimento::create([
                'cliente_id' => $clientes->random()->id,
                'carro_id' => $carros->random()->id,
                'servico_id' => $servico->id,
                'data_agendamento' => $dataAtendimento,
                'status' => $statusEscolhido,
                'valor' => max(10, $valor), // Valor mínimo de 10
                'observacoes' => 'Atendimento de teste gerado automaticamente',
                'created_at' => $dataAtendimento,
                'updated_at' => $dataAtendimento,
                // ⏱️ Campos de cronometragem
                'inicio_servico' => $inicioServico,
                'fim_servico' => $fimServico,
                'tempo_execucao_minutos' => $tempoExecucao,
                'funcionario_responsavel' => $funcionarioResponsavel,
                'nota_qualidade' => $notaQualidade,
            ]);
        }

        $this->command->info('✅ 200 atendimentos de teste criados com sucesso!');
        $this->command->info('📊 Dados distribuídos de forma realista nos últimos 6 meses');
    }

    private function getHorarioRealista()
    {
        // Distribuição de horários mais realista
        $horarios = [
            7 => 5,   // 7h - 5%
            8 => 10,  // 8h - 10% 
            9 => 15,  // 9h - 15%
            10 => 12, // 10h - 12%
            11 => 8,  // 11h - 8%
            12 => 5,  // 12h - 5% (horário de almoço)
            13 => 15, // 13h - 15% (pico pós-almoço)
            14 => 20, // 14h - 20% (pico da tarde)
            15 => 15, // 15h - 15%
            16 => 10, // 16h - 10%
            17 => 3,  // 17h - 3%
            18 => 2,  // 18h - 2%
        ];

        $totalPeso = array_sum($horarios);
        $aleatorio = rand(1, $totalPeso);
        
        $pesoAcumulado = 0;
        foreach ($horarios as $hora => $peso) {
            $pesoAcumulado += $peso;
            if ($aleatorio <= $pesoAcumulado) {
                return $hora;
            }
        }
        
        return 14; // Fallback para 14h
    }

    private function getStatusComPeso($status, $pesos)
    {
        $totalPeso = array_sum($pesos);
        $aleatorio = rand(1, $totalPeso);
        
        $pesoAcumulado = 0;
        for ($i = 0; $i < count($status); $i++) {
            $pesoAcumulado += $pesos[$i];
            if ($aleatorio <= $pesoAcumulado) {
                return $status[$i];
            }
        }
        
        return 'concluido'; // Fallback
    }
}
