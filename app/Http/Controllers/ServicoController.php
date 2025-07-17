<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\Http\Request;

class ServicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Servico::query();

        // Filtro por nome/descrição
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('ativo', (bool) $request->status);
        }

        $servicos = $query->orderBy('nome')->paginate(15);

        return view('servicos.index', compact('servicos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('servicos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'preco' => 'required|numeric|min:0',
            'tempo_estimado' => 'nullable|integer|min:1',
            'ativo' => 'boolean'
        ]);

        Servico::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'preco' => $request->preco,
            'tempo_estimado' => $request->tempo_estimado ?? 30,
            'ativo' => $request->has('ativo') ? true : false
        ]);

        return redirect()->route('servicos.index')
                        ->with('success', 'Serviço criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Servico $servico)
    {
        return view('servicos.show', compact('servico'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Servico $servico)
    {
        return view('servicos.edit', compact('servico'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Servico $servico)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'preco' => 'required|numeric|min:0',
            'tempo_estimado' => 'nullable|integer|min:1',
            'ativo' => 'boolean'
        ]);

        $servico->update([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'preco' => $request->preco,
            'tempo_estimado' => $request->tempo_estimado ?? 30,
            'ativo' => $request->has('ativo') ? true : false
        ]);

        return redirect()->route('servicos.index')
                        ->with('success', 'Serviço atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Servico $servico)
    {
        try {
            $servico->delete();
            return redirect()->route('servicos.index')
                            ->with('success', 'Serviço excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('servicos.index')
                            ->with('error', 'Erro ao excluir serviço. Verifique se não há atendimentos vinculados.');
        }
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(Servico $servico)
    {
        try {
            $servico->update(['ativo' => !$servico->ativo]);
            $status = $servico->ativo ? 'ativado' : 'desativado';
            
            return redirect()->route('servicos.index')
                            ->with('success', "Serviço {$status} com sucesso!");
        } catch (\Exception $e) {
            return redirect()->route('servicos.index')
                            ->with('error', 'Erro ao alterar status do serviço.');
        }
    }
}
