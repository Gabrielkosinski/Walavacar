<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use App\Models\Cliente;
use App\Models\Carro;
use App\Models\Servico;
use App\Models\Despesa;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class RelatorioController extends Controller
{
    public function index(Request $request)
    {
        $query = Atendimento::with(['cliente', 'carro', 'servico']);

        // Aplicar filtros se fornecidos
        if ($request->filled('data_inicio')) {
            $query->whereDate('data_agendamento', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('data_agendamento', '<=', $request->data_fim);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $atendimentos = $query->orderBy('data_agendamento', 'desc')->paginate(20);

        // Estatísticas do período filtrado
        $estatisticas = [
            'total_atendimentos' => $query->count(),
            'faturamento_total' => $query->sum('valor'),
            'ticket_medio' => $query->count() > 0 ? $query->sum('valor') / $query->count() : 0,
            'clientes_unicos' => $query->distinct('cliente_id')->count('cliente_id'),
        ];

        // Atendimentos por status
        $atendimentos_por_status = $query->select('status')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Garantir que todos os status apareçam
        $status_padrao = ['agendado' => 0, 'em_andamento' => 0, 'concluido' => 0, 'cancelado' => 0];
        $atendimentos_por_status = array_merge($status_padrao, $atendimentos_por_status);

        // Serviços mais populares no período
        $servicos_populares = Servico::withCount(['atendimentos' => function($query) use ($request) {
            if ($request->filled('data_inicio')) {
                $query->whereDate('data_agendamento', '>=', $request->data_inicio);
            }
            if ($request->filled('data_fim')) {
                $query->whereDate('data_agendamento', '<=', $request->data_fim);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
        }])
        ->withSum(['atendimentos' => function($query) use ($request) {
            if ($request->filled('data_inicio')) {
                $query->whereDate('data_agendamento', '>=', $request->data_inicio);
            }
            if ($request->filled('data_fim')) {
                $query->whereDate('data_agendamento', '<=', $request->data_fim);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
        }], 'valor')
        ->get()
        ->filter(function($servico) {
            return $servico->atendimentos_count > 0;
        })
        ->sortByDesc('atendimentos_count')
        ->take(10)
        ->map(function($servico) {
            return (object) [
                'nome' => $servico->nome,
                'total_atendimentos' => $servico->atendimentos_count,
                'total_faturamento' => $servico->atendimentos_sum_valor ?? 0,
            ];
        });

        // 📊 NOVOS DADOS PARA GRÁFICOS
        
        // Faturamento por mês (últimos 6 meses)
        $faturamento_mensal = $this->getFaturamentoMensal();
        
        // Atendimentos por dia da semana
        $atendimentos_semana = $this->getAtendimentosPorDiaSemana($request);
        
        // Horários de pico
        $horarios_pico = $this->getHorariosPico($request);
        
        // Comparação com período anterior
        $comparacao_periodo = $this->getComparacaoPeriodo($request);

        // 💰 ESTATÍSTICAS DE PAGAMENTO
        $estatisticasPagamento = $this->getEstatisticasPagamento($request);

        // ⏱️ NOVAS MÉTRICAS DE TEMPO E PRODUTIVIDADE
        
        // Métricas de tempo de execução
        $metricas_tempo = $this->getMetricasTempo($request);
        
        // Ranking de eficiência por serviço
        $eficiencia_servicos = $this->getEficienciaServicos($request);
        
        // Produtividade por funcionário
        $produtividade_funcionarios = $this->getProdutividadeFuncionarios($request);
        
        // 💰 MÉTRICAS DE DESPESAS E LUCRATIVIDADE
        
        // Dados de despesas do período
        $despesas_periodo = $this->getDespesasPeriodo($request);
        
        // Análise de lucratividade
        $analise_lucratividade = $this->getAnaliseLucratividade($request);

        return view('relatorios.index', compact(
            'atendimentos',
            'estatisticas',
            'estatisticasPagamento',
            'atendimentos_por_status',
            'servicos_populares',
            'faturamento_mensal',
            'atendimentos_semana', 
            'horarios_pico',
            'comparacao_periodo',
            'metricas_tempo',
            'eficiencia_servicos',
            'produtividade_funcionarios',
            'despesas_periodo',
            'analise_lucratividade'
        ));
    }

    public function exportarPdf(Request $request)
    {
        $query = Atendimento::with(['cliente', 'carro', 'servico']);

        // Aplicar filtros se fornecidos
        if ($request->filled('data_inicio')) {
            $query->whereDate('data_agendamento', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('data_agendamento', '<=', $request->data_fim);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $atendimentos = $query->orderBy('data_agendamento', 'desc')->get();

        // Estatísticas do período filtrado
        $estatisticas = [
            'total_atendimentos' => $query->count(),
            'faturamento_total' => $query->sum('valor'),
            'ticket_medio' => $query->count() > 0 ? $query->sum('valor') / $query->count() : 0,
            'clientes_unicos' => $query->distinct('cliente_id')->count('cliente_id'),
        ];

        // Atendimentos por status
        $atendimentos_por_status = $query->select('status')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Garantir que todos os status apareçam
        $status_padrao = ['agendado' => 0, 'em_andamento' => 0, 'concluido' => 0, 'cancelado' => 0];
        $atendimentos_por_status = array_merge($status_padrao, $atendimentos_por_status);

        // Serviços mais populares no período
        $servicos_populares = Servico::withCount(['atendimentos' => function($query) use ($request) {
            if ($request->filled('data_inicio')) {
                $query->whereDate('data_agendamento', '>=', $request->data_inicio);
            }
            if ($request->filled('data_fim')) {
                $query->whereDate('data_agendamento', '<=', $request->data_fim);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
        }])
        ->withSum(['atendimentos' => function($query) use ($request) {
            if ($request->filled('data_inicio')) {
                $query->whereDate('data_agendamento', '>=', $request->data_inicio);
            }
            if ($request->filled('data_fim')) {
                $query->whereDate('data_agendamento', '<=', $request->data_fim);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
        }], 'valor')
        ->get()
        ->filter(function($servico) {
            return $servico->atendimentos_count > 0;
        })
        ->sortByDesc('atendimentos_count')
        ->take(10)
        ->map(function($servico) {
            return (object) [
                'nome' => $servico->nome,
                'total_atendimentos' => $servico->atendimentos_count,
                'total_faturamento' => $servico->atendimentos_sum_valor ?? 0,
            ];
        });

        $data = compact(
            'atendimentos',
            'estatisticas',
            'atendimentos_por_status',
            'servicos_populares',
            'request'
        );

        $pdf = Pdf::loadView('relatorios.pdf', $data);
        
        $filename = 'relatorio_' . date('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * 📊 MÉTODOS PARA GRÁFICOS E ANÁLISES AVANÇADAS
     */
    
    private function getFaturamentoMensal()
    {
        $meses = [];
        $valores = [];
        
        // Últimos 6 meses
        for ($i = 5; $i >= 0; $i--) {
            $data = Carbon::now()->subMonths($i);
            $valor = Atendimento::whereRaw("strftime('%Y-%m', created_at) = ?", [$data->format('Y-m')])
                ->where('status', '!=', 'cancelado')
                ->sum('valor');
            $meses[] = $data->format('M/Y');
            $valores[] = (float) $valor;
        }
        
        return [
            'labels' => $meses,
            'data' => $valores
        ];
    }
    
    private function getAtendimentosPorDiaSemana($request)
    {
        $query = Atendimento::query();
        
        // Aplicar filtros
        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }
        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }
        
        // Usar função compatível com SQLite
        $dados = $query->select(DB::raw("strftime('%w', created_at) as dia_semana, COUNT(*) as total"))
            ->groupBy('dia_semana')
            ->orderBy('dia_semana')
            ->pluck('total', 'dia_semana')
            ->toArray();
            
        $dias = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
        $valores = [];
        
        // SQLite strftime('%w') retorna 0=domingo, 1=segunda, etc.
        for ($i = 0; $i < 7; $i++) {
            $valores[] = $dados[$i] ?? 0;
        }
        
        return [
            'labels' => $dias,
            'data' => $valores
        ];
    }
    
    private function getHorariosPico($request)
    {
        $query = Atendimento::query();
        
        // Aplicar filtros
        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }
        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }
        
        // Usar função compatível com SQLite
        $dados = $query->select(DB::raw("strftime('%H', created_at) as hora, COUNT(*) as total"))
            ->groupBy('hora')
            ->orderBy('hora')
            ->pluck('total', 'hora')
            ->toArray();
            
        $horarios = [];
        $valores = [];
        
        for ($i = 6; $i <= 22; $i++) { // 6h às 22h
            $hora = sprintf('%02d', $i);
            $horarios[] = sprintf('%02d:00', $i);
            $valores[] = $dados[$hora] ?? 0;
        }
        
        return [
            'labels' => $horarios,
            'data' => $valores
        ];
    }
    
    private function getComparacaoPeriodo($request)
    {
        // Período atual
        $inicioAtual = $request->filled('data_inicio') ? Carbon::parse($request->data_inicio) : Carbon::now()->startOfMonth();
        $fimAtual = $request->filled('data_fim') ? Carbon::parse($request->data_fim) : Carbon::now();
        
        // Período anterior (mesmo intervalo)
        $diasDiferenca = $inicioAtual->diffInDays($fimAtual);
        $inicioAnterior = $inicioAtual->copy()->subDays($diasDiferenca + 1);
        $fimAnterior = $inicioAtual->copy()->subDay();
        
        $atual = Atendimento::whereBetween('created_at', [$inicioAtual, $fimAtual])
            ->where('status', '!=', 'cancelado');
            
        $anterior = Atendimento::whereBetween('created_at', [$inicioAnterior, $fimAnterior])
            ->where('status', '!=', 'cancelado');
            
        $faturamentoAtual = $atual->sum('valor');
        $faturamentoAnterior = $anterior->sum('valor');
        $atendimentosAtual = $atual->count();
        $atendimentosAnterior = $anterior->count();
        
        return [
            'faturamento' => [
                'atual' => (float) $faturamentoAtual,
                'anterior' => (float) $faturamentoAnterior,
                'variacao' => $faturamentoAnterior > 0 ? (($faturamentoAtual - $faturamentoAnterior) / $faturamentoAnterior) * 100 : 0
            ],
            'atendimentos' => [
                'atual' => $atendimentosAtual,
                'anterior' => $atendimentosAnterior,
                'variacao' => $atendimentosAnterior > 0 ? (($atendimentosAtual - $atendimentosAnterior) / $atendimentosAnterior) * 100 : 0
            ]
        ];
    }
    
    /**
     * ⏱️ MÉTRICAS DE TEMPO E PRODUTIVIDADE
     */
    
    private function getMetricasTempo($request)
    {
        $query = Atendimento::comCronometragem();
        
        // Aplicar filtros
        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }
        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }
        
        $atendimentos = $query->get();
        
        if ($atendimentos->isEmpty()) {
            return [
                'tempo_medio_geral' => 0,
                'tempo_minimo' => 0,
                'tempo_maximo' => 0,
                'eficiencia_media' => 0,
                'total_cronometrados' => 0
            ];
        }
        
        return [
            'tempo_medio_geral' => round($atendimentos->avg('tempo_execucao_minutos')),
            'tempo_minimo' => $atendimentos->min('tempo_execucao_minutos'),
            'tempo_maximo' => $atendimentos->max('tempo_execucao_minutos'),
            'eficiencia_media' => round($atendimentos->whereNotNull('eficiencia')->avg('eficiencia'), 1),
            'total_cronometrados' => $atendimentos->count()
        ];
    }
    
    private function getEficienciaServicos($request)
    {
        $query = Atendimento::with('servico')->comCronometragem();
        
        // Aplicar filtros
        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }
        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }
        
        $atendimentos = $query->get();
        
        $eficienciaPorServico = $atendimentos
            ->groupBy('servico.nome')
            ->map(function ($grupo) {
                $tempoMedio = $grupo->avg('tempo_execucao_minutos');
                $tempoEstimado = $grupo->first()->servico->tempo_estimado_minutos ?? 30;
                $eficiencia = $tempoEstimado > 0 ? ($tempoEstimado / $tempoMedio) * 100 : 0;
                
                return [
                    'servico' => $grupo->first()->servico->nome,
                    'tempo_medio' => round($tempoMedio),
                    'tempo_estimado' => $tempoEstimado,
                    'eficiencia' => round($eficiencia, 1),
                    'total_atendimentos' => $grupo->count()
                ];
            })
            ->sortByDesc('eficiencia')
            ->take(10)
            ->values();
            
        return $eficienciaPorServico;
    }
    
    private function getProdutividadeFuncionarios($request)
    {
        $query = Atendimento::comCronometragem()->whereNotNull('funcionario_responsavel');
        
        // Aplicar filtros
        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }
        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }
        
        $atendimentos = $query->get();
        
        $produtividadePorFuncionario = $atendimentos
            ->groupBy('funcionario_responsavel')
            ->map(function ($grupo) {
                $tempoMedio = $grupo->avg('tempo_execucao_minutos');
                $totalAtendimentos = $grupo->count();
                $notaMedia = $grupo->whereNotNull('nota_qualidade')->avg('nota_qualidade');
                
                return [
                    'funcionario' => $grupo->first()->funcionario_responsavel,
                    'total_atendimentos' => $totalAtendimentos,
                    'tempo_medio' => round($tempoMedio),
                    'nota_media' => $notaMedia ? round($notaMedia, 1) : null,
                    'produtividade_score' => $this->calcularProdutividadeScore($totalAtendimentos, $tempoMedio, $notaMedia)
                ];
            })
            ->sortByDesc('produtividade_score')
            ->take(10)
            ->values();
            
        return $produtividadePorFuncionario;
    }
    
    private function calcularProdutividadeScore($totalAtendimentos, $tempoMedio, $notaMedia)
    {
        // Score baseado em: quantidade de atendimentos, velocidade e qualidade
        $scoreQuantidade = min($totalAtendimentos * 2, 50); // Máximo 50 pontos
        $scoreVelocidade = max(0, 50 - ($tempoMedio - 30)); // Ideal: 30 min
        $scoreQualidade = $notaMedia ? ($notaMedia * 10) : 50; // Nota de 0-10
        
        return round(($scoreQuantidade + $scoreVelocidade + $scoreQualidade) / 3, 1);
    }
    
    // 💰 MÉTODOS PARA ANÁLISE DE DESPESAS E LUCRATIVIDADE
    
    private function getDespesasPeriodo($request)
    {
        $dataInicio = $request->filled('data_inicio') ? $request->data_inicio : Carbon::now()->startOfMonth();
        $dataFim = $request->filled('data_fim') ? $request->data_fim : Carbon::now()->endOfMonth();
        
        $despesas = Despesa::whereRaw("date(data_despesa) >= ? and date(data_despesa) <= ?", [
            Carbon::parse($dataInicio)->format('Y-m-d'),
            Carbon::parse($dataFim)->format('Y-m-d')
        ])->where('status', 'paga');
        return [
            'total_despesas' => $despesas->sum('valor'),
            'despesas_fixas' => $despesas->where('tipo', 'fixa')->sum('valor'),
            'despesas_variaveis' => $despesas->where('tipo', 'variavel')->sum('valor'),
            'despesas_por_categoria' => $despesas->selectRaw('categoria, SUM(valor) as total')
                ->groupBy('categoria')
                ->get()
                ->mapWithKeys(function($item) {
                    return [$item->categoria => $item->total];
                })
        ];
    }
    
    private function getAnaliseLucratividade($request)
    {
        $dataInicio = $request->filled('data_inicio') ? $request->data_inicio : Carbon::now()->startOfMonth();
        $dataFim = $request->filled('data_fim') ? $request->data_fim : Carbon::now()->endOfMonth();
        
        // Receitas do período
        $receitas = Atendimento::whereBetween('data_agendamento', [$dataInicio, $dataFim])
            ->where('status', 'finalizado')
            ->sum('valor');
            
        // Despesas do período
        $despesas = Despesa::whereBetween('data_despesa', [$dataInicio, $dataFim])
            ->where('status', 'paga')
            ->sum('valor');
            
        $lucro_bruto = $receitas - $despesas;
        $margem_lucro = $receitas > 0 ? ($lucro_bruto / $receitas) * 100 : 0;
        
        // Comparação com mês anterior
        $mesAnteriorInicio = Carbon::parse($dataInicio)->subMonth()->startOfMonth();
        $mesAnteriorFim = Carbon::parse($dataFim)->subMonth()->endOfMonth();
        
        $receitasAnterior = Atendimento::whereBetween('data_agendamento', [$mesAnteriorInicio, $mesAnteriorFim])
            ->where('status', 'finalizado')
            ->sum('valor');
            
        $despesasAnterior = Despesa::whereBetween('data_despesa', [$mesAnteriorInicio, $mesAnteriorFim])
            ->where('status', 'paga')
            ->sum('valor');
            
        $lucroAnterior = $receitasAnterior - $despesasAnterior;
        
        return [
            'receitas' => $receitas,
            'despesas' => $despesas,
            'lucro_bruto' => $lucro_bruto,
            'margem_lucro' => round($margem_lucro, 2),
            'receitas_anterior' => $receitasAnterior,
            'despesas_anterior' => $despesasAnterior,
            'lucro_anterior' => $lucroAnterior,
            'variacao_receitas' => $receitasAnterior > 0 ? (($receitas - $receitasAnterior) / $receitasAnterior) * 100 : 0,
            'variacao_despesas' => $despesasAnterior > 0 ? (($despesas - $despesasAnterior) / $despesasAnterior) * 100 : 0,
            'variacao_lucro' => $lucroAnterior != 0 ? (($lucro_bruto - $lucroAnterior) / abs($lucroAnterior)) * 100 : 0
        ];
    }

    /**
     * Obter estatísticas de pagamento do período
     */
    private function getEstatisticasPagamento(Request $request)
    {
        try {
            $query = Atendimento::query();
            
            // Aplicar filtros de data se fornecidos
            if ($request->filled('data_inicio')) {
                $query->whereDate('data_agendamento', '>=', $request->data_inicio);
            }
            
            if ($request->filled('data_fim')) {
                $query->whereDate('data_agendamento', '<=', $request->data_fim);
            }
            
            // Se não há filtros, usar período padrão (último mês)
            if (!$request->filled('data_inicio') && !$request->filled('data_fim')) {
                $query->where('data_agendamento', '>=', Carbon::now()->subMonth());
            }
            
            // Total recebido no período (apenas atendimentos concluídos com pagamento)
            $totalRecebidoPeriodo = $query->clone()
                ->whereIn('status', ['concluido', 'pago'])
                ->whereNotNull('forma_pagamento')
                ->sum('valor');
            
            // Atendimentos pagos no período
            $atendimentosPagosPeriodo = $query->clone()
                ->whereIn('status', ['concluido', 'pago'])
                ->whereNotNull('forma_pagamento')
                ->count();
            
            // Pendentes de pagamento
            $pendentesPagamento = $query->clone()
                ->where('status', 'concluido')
                ->whereNull('forma_pagamento')
                ->count();
            
            // Formas de pagamento detalhadas
            $pagamentosPorForma = $query->clone()
                ->whereIn('status', ['concluido', 'pago'])
                ->whereNotNull('forma_pagamento')
                ->select('forma_pagamento')
                ->selectRaw('COUNT(*) as quantidade')
                ->selectRaw('SUM(valor) as valor_total')
                ->groupBy('forma_pagamento')
                ->get()
                ->keyBy('forma_pagamento');
            
            return [
                'total_recebido_periodo' => $totalRecebidoPeriodo,
                'atendimentos_pagos_periodo' => $atendimentosPagosPeriodo,
                'pendentes_pagamento' => $pendentesPagamento,
                'pagamentos_por_forma' => $pagamentosPorForma
            ];
            
        } catch (\Exception $e) {
            \Log::error('Erro ao calcular estatísticas de pagamento: ' . $e->getMessage());
            
            return [
                'total_recebido_periodo' => 0,
                'atendimentos_pagos_periodo' => 0,
                'pendentes_pagamento' => 0,
                'pagamentos_por_forma' => collect()
            ];
        }
    }
}
