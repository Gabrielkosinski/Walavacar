<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use App\Models\Cliente;
use App\Models\Carro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Debug - verificar se usuário está logado
        if (!$user) {
            return redirect('/login')->with('error', 'Você precisa estar logado para acessar o dashboard');
        }
        
        \Log::info('Dashboard: Usuário logado - ' . $user->name . ' (' . $user->email . ')');
        
        try {
            // 🚀 Cache para estatísticas básicas (5 minutos)
            $estatisticas = Cache::remember('dashboard_stats', 300, function () {
                $mesAtual = now()->format('Y-m');
                return [
                    'total_atendimentos' => Atendimento::count(),
                    'atendimentos_hoje' => Atendimento::whereDate('created_at', today())->count(),
                    'faturamento_mes' => Atendimento::whereRaw("strftime('%Y-%m', created_at) = ?", [$mesAtual])
                        ->sum('valor') ?: 0,
                    'clientes_ativos' => Cliente::count()
                ];
            });
            
            // 💰 Estatísticas de pagamento do dia
            $estatisticasPagamento = Cache::remember('dashboard_pagamento_stats', 300, function () {
                $hoje = today()->format('Y-m-d');
                return [
                    'total_recebido_hoje' => Atendimento::whereRaw("date(data_pagamento) = ?", [$hoje])
                        ->where('pago', true)
                        ->sum('valor_pago') ?: 0,
                    'pagamentos_por_forma' => Atendimento::whereRaw("date(data_pagamento) = ?", [$hoje])
                        ->where('pago', true)
                        ->selectRaw('forma_pagamento, COUNT(*) as quantidade, SUM(valor_pago) as total')
                        ->groupBy('forma_pagamento')
                        ->get()
                        ->keyBy('forma_pagamento'),
                    'atendimentos_pagos_hoje' => Atendimento::whereRaw("date(data_pagamento) = ?", [$hoje])
                        ->where('pago', true)
                        ->count(),
                    'pendentes_pagamento' => Atendimento::whereIn('status', ['pronto', 'aguardando_pagamento'])
                        ->where(function($query) {
                            $query->where('pago', false)->orWhereNull('pago');
                        })
                        ->count()
                ];
            });
            
            // 🎯 Queries otimizadas - apenas campos necessários
            $emAndamento = Atendimento::select(['id', 'cliente_id', 'carro_id', 'valor', 'status', 'created_at', 'observacoes'])
                ->with([
                    'cliente:id,nome,telefone,whatsapp',
                    'carro:id,marca,modelo,placa'
                ])
                ->where('status', 'em_andamento')
                ->limit(10) // Limitar para performance
                ->get();
                
            $prontos = Atendimento::select(['id', 'cliente_id', 'carro_id', 'valor', 'status', 'created_at', 'observacoes'])
                ->with([
                    'cliente:id,nome,telefone,whatsapp',
                    'carro:id,marca,modelo,placa'
                ])
                ->where('status', 'pronto')
                ->limit(10) // Limitar para performance
                ->get();
                
            // 🔥 Carregar aguardando pagamento também de forma otimizada
            $aguardandoPagamento = Atendimento::select(['id', 'cliente_id', 'carro_id', 'valor', 'status', 'created_at', 'observacoes'])
                ->with([
                    'cliente:id,nome,telefone,whatsapp',
                    'carro:id,marca,modelo,placa'
                ])
                ->where('status', 'aguardando_pagamento')
                ->limit(10)
                ->get();
                
            // 🎯 Buscar próximo da fila (primeiro atendimento em fila)
            $proximoDaFila = Atendimento::select(['id', 'cliente_id', 'carro_id', 'servico_id', 'valor', 'status', 'created_at', 'observacoes'])
                ->with([
                    'cliente:id,nome,telefone,whatsapp',
                    'carro:id,marca,modelo,placa',
                    'servico:id,nome,descricao'
                ])
                ->where('status', 'em_fila')
                ->orderBy('created_at', 'asc') // Primeiro que entrou na fila
                ->first();
                
            // 🔄 Se não houver em fila, buscar em 'aguardando'
            if (!$proximoDaFila) {
                $proximoDaFila = Atendimento::select(['id', 'cliente_id', 'carro_id', 'servico_id', 'valor', 'status', 'created_at', 'observacoes'])
                    ->with([
                        'cliente:id,nome,telefone,whatsapp',
                        'carro:id,marca,modelo,placa',
                        'servico:id,nome,descricao'
                    ])
                    ->where('status', 'aguardando')
                    ->orderBy('created_at', 'asc')
                    ->first();
            }
                
            // Outras variáveis
            $filaEspera = Atendimento::select(['id', 'cliente_id', 'carro_id', 'valor', 'status', 'created_at', 'observacoes'])
                ->whereIn('status', ['em_fila', 'aguardando'])
                ->with(['cliente:id,nome', 'carro:id,marca,modelo,placa'])
                ->orderBy('created_at', 'asc')
                ->limit(5)
                ->get();
            $proximosAtendimentos = collect();
            $notificacoes = [];
            $atendimentos = collect();
            
            return view('dashboard', compact(
                'estatisticas', 'estatisticasPagamento', 'atendimentos', 'emAndamento', 'prontos', 
                'aguardandoPagamento', 'proximosAtendimentos', 'notificacoes', 
                'filaEspera', 'proximoDaFila'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Erro no dashboard: ' . $e->getMessage());
            
            // Retornar view com dados mínimos em caso de erro
            return view('dashboard', [
                'estatisticas' => ['total_atendimentos' => 0, 'atendimentos_hoje' => 0, 'faturamento_mes' => 0, 'clientes_ativos' => 0],
                'estatisticasPagamento' => ['total_recebido_hoje' => 0, 'pagamentos_por_forma' => collect(), 'atendimentos_pagos_hoje' => 0, 'pendentes_pagamento' => 0],
                'atendimentos' => collect(),
                'emAndamento' => collect(),
                'prontos' => collect(),
                'aguardandoPagamento' => collect(),
                'proximosAtendimentos' => collect(),
                'notificacoes' => [],
                'filaEspera' => collect(),
                'proximoDaFila' => null
            ]);
        }
    }
}
