<?php

namespace App\Http\Controllers;

use App\Models\Despesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DespesaController extends Controller
{
    public function index(Request $request)
    {
        $query = Despesa::with('user')->orderBy('data_despesa', 'desc');
        
        // Filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->whereBetween('data_despesa', [$request->data_inicio, $request->data_fim]);
        }
        
        $despesas = $query->paginate(20);
        
        // Estatísticas rápidas
        $totalFixas = Despesa::fixas()->where('status', 'paga')->sum('valor');
        $totalVariaveis = Despesa::variaveis()->where('status', 'paga')->sum('valor');
        $totalPendentes = Despesa::pendentes()->sum('valor');
        $totalVencidas = Despesa::vencidas()->sum('valor');
        
        return view('despesas.index', compact(
            'despesas', 
            'totalFixas', 
            'totalVariaveis', 
            'totalPendentes', 
            'totalVencidas'
        ));
    }

    public function create()
    {
        return view('despesas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
            'data_despesa' => 'required|date',
            'tipo' => 'required|in:fixa,variavel',
            'categoria' => 'required|string',
            'observacoes' => 'nullable|string',
            'comprovante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'recorrente' => 'boolean',
            'dia_vencimento' => 'nullable|integer|min:1|max:31',
            'status' => 'required|in:pendente,paga,vencida',
            'forma_pagamento' => 'nullable|string'
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        
        // Upload do comprovante
        if ($request->hasFile('comprovante')) {
            $data['comprovante'] = $request->file('comprovante')
                ->store('comprovantes', 'public');
        }

        Despesa::create($data);

        return redirect()->route('despesas.index')
            ->with('success', 'Despesa cadastrada com sucesso!');
    }

    public function show(Despesa $despesa)
    {
        return view('despesas.show', compact('despesa'));
    }

    public function edit(Despesa $despesa)
    {
        return view('despesas.edit', compact('despesa'));
    }

    public function update(Request $request, Despesa $despesa)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
            'data_despesa' => 'required|date',
            'tipo' => 'required|in:fixa,variavel',
            'categoria' => 'required|string',
            'observacoes' => 'nullable|string',
            'comprovante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'recorrente' => 'boolean',
            'dia_vencimento' => 'nullable|integer|min:1|max:31',
            'status' => 'required|in:pendente,paga,vencida',
            'forma_pagamento' => 'nullable|string'
        ]);

        $data = $request->all();
        
        // Upload do novo comprovante
        if ($request->hasFile('comprovante')) {
            // Deletar comprovante anterior
            if ($despesa->comprovante) {
                Storage::disk('public')->delete($despesa->comprovante);
            }
            
            $data['comprovante'] = $request->file('comprovante')
                ->store('comprovantes', 'public');
        }

        $despesa->update($data);

        return redirect()->route('despesas.index')
            ->with('success', 'Despesa atualizada com sucesso!');
    }

    public function destroy(Despesa $despesa)
    {
        // Deletar comprovante se existir
        if ($despesa->comprovante) {
            Storage::disk('public')->delete($despesa->comprovante);
        }
        
        $despesa->delete();

        return redirect()->route('despesas.index')
            ->with('success', 'Despesa excluída com sucesso!');
    }
    
    // Métodos adicionais para relatórios
    public function relatorio(Request $request)
    {
        $dataInicio = $request->get('data_inicio', Carbon::now()->startOfMonth());
        $dataFim = $request->get('data_fim', Carbon::now()->endOfMonth());
        
        // Totais por tipo
        $totalFixas = Despesa::totalPorTipo('fixa', $dataInicio, $dataFim);
        $totalVariaveis = Despesa::totalPorTipo('variavel', $dataInicio, $dataFim);
        
        // Despesas por categoria
        $despesasPorCategoria = Despesa::porPeriodo($dataInicio, $dataFim)
            ->where('status', 'paga')
            ->selectRaw('categoria, SUM(valor) as total')
            ->groupBy('categoria')
            ->get();
            
        // Evolução mensal
        $evolucaoMensal = Despesa::where('status', 'paga')
            ->selectRaw('strftime("%Y", data_despesa) as ano, strftime("%m", data_despesa) as mes, SUM(valor) as total')
            ->groupBy('ano', 'mes')
            ->orderBy('ano', 'desc')
            ->orderBy('mes', 'desc')
            ->limit(12)
            ->get();
        
        return view('despesas.relatorio', compact(
            'totalFixas',
            'totalVariaveis', 
            'despesasPorCategoria',
            'evolucaoMensal',
            'dataInicio',
            'dataFim'
        ));
    }
    
    public function marcarComoPaga(Despesa $despesa)
    {
        $despesa->update(['status' => 'paga']);
        
        return back()->with('success', 'Despesa marcada como paga!');
    }
}
