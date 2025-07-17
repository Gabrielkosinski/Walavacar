<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use App\Models\Cliente;
use App\Models\Carro;
use App\Models\Servico;
use App\Models\Filial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AtendimentoController extends Controller
{
    public function index()
    {
        $atendimentos = Atendimento::with(['cliente', 'carro', 'servico'])
            ->orderBy('data_agendamento', 'desc')
            ->paginate(15);
        
        return view('atendimentos.index', compact('atendimentos'));
    }

    public function create()
    {
        $clientes = Cliente::where('ativo', true)->orderBy('nome')->get();
        $carros = Carro::with('cliente')->where('ativo', true)->orderBy('marca')->get();
        $servicos = Servico::where('ativo', true)->orderBy('nome')->get();
        $filiais = Filial::orderBy('nome')->get();
        
        return view('atendimentos.create', compact('clientes', 'carros', 'servicos', 'filiais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'carro_id' => 'required|exists:carros,id',
            'servico_id' => 'required|exists:servicos,id',
            'data_agendamento' => 'required|date',
            'valor' => 'required|numeric|min:0',
        ]);

        Atendimento::create($request->all());

        return redirect()->route('atendimentos.index')
            ->with('success', 'Atendimento criado com sucesso!');
    }

    public function show(Atendimento $atendimento)
    {
        $atendimento->load(['cliente', 'carro', 'servico', 'filial']);
        return view('atendimentos.show', compact('atendimento'));
    }

    public function edit(Atendimento $atendimento)
    {
        $clientes = Cliente::where('ativo', true)->orderBy('nome')->get();
        $carros = Carro::with('cliente')->where('ativo', true)->orderBy('marca')->get();
        $servicos = Servico::where('ativo', true)->orderBy('nome')->get();
        $filiais = Filial::orderBy('nome')->get();
        
        return view('atendimentos.edit', compact('atendimento', 'clientes', 'carros', 'servicos', 'filiais'));
    }

    public function update(Request $request, Atendimento $atendimento)
    {
        $request->validate([
            'cliente_id' => 'sometimes|exists:clientes,id',
            'carro_id' => 'sometimes|exists:carros,id',
            'servico_id' => 'sometimes|exists:servicos,id',
            'data_agendamento' => 'sometimes|required|date',
            'status' => 'sometimes|required|in:aguardando,em_fila,em_andamento,concluido,cancelado',
            'forma_pagamento' => 'sometimes|nullable|in:dinheiro,pix,credito,debito',
            'valor' => 'sometimes|required|numeric|min:0',
            'observacoes' => 'sometimes|nullable|string'
        ]);

        $atendimento->update($request->all());

        // Se for uma requisição AJAX (do dashboard mobile)
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Atendimento atualizado com sucesso!']);
        }

        return redirect()->route('atendimentos.index')
            ->with('success', 'Atendimento atualizado com sucesso!');
    }

    public function destroy(Atendimento $atendimento)
    {
        $atendimento->delete();

        return redirect()->route('atendimentos.index')
            ->with('success', 'Atendimento excluído com sucesso!');
    }

    public function storeJson(Request $request)
    {
        try {
            $request->validate([
                'cliente.nome' => 'required|string|max:255',
                'cliente.whatsapp' => 'required|string|max:15',
                'cliente.telefone' => 'nullable|string|max:15',
                'carro.placa' => 'required|string|max:8',
                'carro.marca' => 'required|string|max:50',
                'carro.modelo' => 'required|string|max:50',
                'carro.cor' => 'required|string|max:30',
                'carro.ano' => 'nullable|integer|min:1990|max:2025',
                'servicos' => 'required|array|min:1',
                'servicos.*' => 'exists:servicos,id',
                'valor_total' => 'required|numeric|min:0',
                'observacoes' => 'nullable|string'
            ]);

            DB::beginTransaction();

            // 1. Criar ou encontrar cliente
            $clienteData = $request->cliente;
            $cliente = Cliente::firstOrCreate(
                ['whatsapp' => $clienteData['whatsapp']],
                [
                    'nome' => $clienteData['nome'],
                    'telefone' => $clienteData['telefone'] ?? null,
                    'whatsapp' => $clienteData['whatsapp'],
                    'filial_id' => 1, // Padrão primeira filial
                    'ativo' => true
                ]
            );

            // 2. Criar ou encontrar carro
            $carroData = $request->carro;
            $carro = Carro::firstOrCreate(
                ['placa' => $carroData['placa']],
                [
                    'marca' => $carroData['marca'],
                    'modelo' => $carroData['modelo'],
                    'cor' => $carroData['cor'],
                    'ano' => $carroData['ano'],
                    'cliente_id' => $cliente->id
                ]
            );

            // 3. Criar UM atendimento com múltiplos serviços
            $valorTotal = 0;
            $servicosDetalhes = [];
            
            // Calcular valor total e coletar detalhes dos serviços
            foreach ($request->servicos as $servicoId) {
                $servico = Servico::find($servicoId);
                $valorTotal += $servico->preco;
                $servicosDetalhes[] = $servico->nome . ' (R$ ' . number_format($servico->preco, 2, ',', '.') . ')';
            }
            
            // Criar apenas UM atendimento
            $atendimento = Atendimento::create([
                'cliente_id' => $cliente->id,
                'carro_id' => $carro->id,
                'servico_id' => $request->servicos[0], // Serviço principal (primeiro selecionado)
                'filial_id' => 1,
                'status' => 'aguardando',
                'data_agendamento' => now(),
                'valor' => $valorTotal, // Valor total de todos os serviços
                'observacoes' => $request->observacoes ? $request->observacoes . ' | Serviços: ' . implode(', ', $servicosDetalhes) : 'Serviços: ' . implode(', ', $servicosDetalhes)
            ]);

            // Adicionar à fila automaticamente
            $atendimento->adicionarNaFila();

            // Associar todos os serviços ao atendimento (se houver tabela pivot)
            if (method_exists($atendimento, 'servicos')) {
                $atendimento->servicos()->attach($request->servicos);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Atendimento iniciado com sucesso!',
                'data' => [
                    'cliente' => $cliente,
                    'carro' => $carro,
                    'atendimento' => $atendimento,
                    'servicos_count' => count($request->servicos),
                    'valor_total' => $valorTotal
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Chamar próximo da fila para atendimento
     */
    public function chamarProximo(Request $request)
    {
        try {
            $proximoAtendimento = Atendimento::proximoDaFila(1); // Filial 1 por padrão
            
            if (!$proximoAtendimento) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não há veículos na fila de espera.'
                ]);
            }
            
            $proximoAtendimento->iniciarAtendimento();
            
            return response()->json([
                'success' => true,
                'message' => 'Próximo atendimento iniciado com sucesso!',
                'data' => [
                    'atendimento' => $proximoAtendimento->load(['cliente', 'carro', 'servico'])
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Obter fila atual
     */
    public function obterFila(Request $request)
    {
        try {
            $fila = Atendimento::filaOrdenada(1); // Filial 1 por padrão
            $proximo = Atendimento::proximoDaFila(1);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'fila' => $fila,
                    'proximo' => $proximo ? $proximo->load(['cliente', 'carro', 'servico']) : null,
                    'total_na_fila' => $fila->count()
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Finalizar atendimento com opções - move para diferentes status
     */
    public function finalizar(Request $request, Atendimento $atendimento)
    {
        try {
            // Validar se o atendimento pode ser finalizado
            if (!in_array($atendimento->status, ['em_andamento', 'aguardando'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este atendimento não pode ser finalizado no status atual'
                ], 400);
            }

            // Obter status solicitado (padrão: pronto)
            $novoStatus = $request->input('status', 'pronto');
            
            // Validar status permitidos
            $statusPermitidos = ['pronto', 'aguardando_pagamento', 'finalizado', 'concluido'];
            if (!in_array($novoStatus, $statusPermitidos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status inválido'
                ], 400);
            }

            $dadosAtualizacao = [
                'status' => $novoStatus,
                'data_conclusao' => now(),
                'concluido_por' => auth()->id()
            ];

            // Se for finalizado com pagamento
            if ($novoStatus === 'finalizado' && $request->has('forma_pagamento')) {
                $dadosAtualizacao = array_merge($dadosAtualizacao, [
                    'forma_pagamento' => $request->input('forma_pagamento'),
                    'valor_pago' => $request->input('valor_pago', $atendimento->valor),
                    'data_pagamento' => now(),
                    'pago' => true
                ]);
            }

            // Atualizar atendimento
            $atendimento->update($dadosAtualizacao);

            // Mensagens personalizadas
            $mensagens = [
                'pronto' => 'Veículo marcado como pronto para retirada!',
                'finalizado' => 'Atendimento pago e finalizado com sucesso!',
                'concluido' => 'Atendimento concluído!'
            ];

            // Log da ação
            \Log::info("Atendimento {$atendimento->id} finalizado com status '{$novoStatus}' por " . auth()->user()->name);

            return response()->json([
                'success' => true,
                'message' => $mensagens[$novoStatus] ?? 'Atendimento finalizado!',
                'atendimento' => $atendimento->load(['cliente', 'carro', 'servico']),
                'novo_status' => $novoStatus
            ]);

        } catch (\Exception $e) {
            \Log::error("Erro ao finalizar atendimento {$atendimento->id}: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao finalizar atendimento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancelar atendimento
     */
    public function cancelar(Request $request, Atendimento $atendimento)
    {
        try {
            // Validar se o atendimento pode ser cancelado
            if (in_array($atendimento->status, ['finalizado', 'cancelado'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este atendimento não pode ser cancelado'
                ], 400);
            }

            // Obter motivo do cancelamento
            $motivo = $request->input('motivo', 'cancelado_usuario');

            // Atualizar status para cancelado
            $atendimento->update([
                'status' => 'cancelado',
                'motivo_cancelamento' => $motivo,
                'data_cancelamento' => now(),
                'cancelado_por' => auth()->id()
            ]);

            // Log da ação
            \Log::info("Atendimento {$atendimento->id} cancelado por " . auth()->user()->name . " - Motivo: {$motivo}");

            return response()->json([
                'success' => true,
                'message' => 'Atendimento cancelado com sucesso',
                'atendimento' => $atendimento->load(['cliente', 'carro', 'servico'])
            ]);

        } catch (\Exception $e) {
            \Log::error("Erro ao cancelar atendimento {$atendimento->id}: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao cancelar atendimento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Registrar pagamento quando cliente busca o veículo
     */
    public function registrarPagamento(Request $request, Atendimento $atendimento)
    {
        try {
            $request->validate([
                'forma_pagamento' => 'required|in:dinheiro,pix,credito,debito',
                'valor_pago' => 'required|numeric|min:0',
                'desconto' => 'nullable|numeric|min:0|max:100',
                'observacoes_pagamento' => 'nullable|string'
            ]);

            // Validar se o atendimento está pronto para pagamento
            if (!in_array($atendimento->status, ['pronto', 'concluido', 'aguardando_pagamento'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Atendimento deve estar pronto ou concluído para registrar pagamento'
                ], 400);
            }

            // Calcular valor final com desconto
            $valorOriginal = $atendimento->valor;
            $desconto = $request->desconto ?? 0;
            $valorDesconto = ($valorOriginal * $desconto) / 100;
            $valorFinal = $valorOriginal - $valorDesconto;
            $valorPago = $request->valor_pago;

            // Atualizar atendimento com dados do pagamento
            $atendimento->update([
                'status' => 'finalizado',
                'forma_pagamento' => $request->forma_pagamento,
                'valor_original' => $valorOriginal,
                'valor_desconto' => $valorDesconto,
                'valor_final' => $valorFinal,
                'valor_pago' => $valorPago,
                'data_pagamento' => now(),
                'recebido_por' => auth()->id(),
                'observacoes_pagamento' => $request->observacoes_pagamento,
                'pago' => true
            ]);

            // Log da transação
            \Log::info("Pagamento registrado para atendimento {$atendimento->id}: {$request->forma_pagamento} - R$ {$valorPago}");

            return response()->json([
                'success' => true,
                'message' => 'Pagamento registrado com sucesso!',
                'atendimento' => $atendimento->load(['cliente', 'carro', 'servico']),
                'comprovante_url' => route('atendimentos.comprovante', $atendimento)
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos: ' . implode(', ', $e->validator->errors()->all())
            ], 422);

        } catch (\Exception $e) {
            \Log::error("Erro ao registrar pagamento do atendimento {$atendimento->id}: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao registrar pagamento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gerar comprovante de pagamento
     */
    public function gerarComprovante(Atendimento $atendimento)
    {
        try {
            // Validar se o atendimento foi pago
            if (!$atendimento->pago && $atendimento->status !== 'finalizado') {
                return redirect()->back()->with('error', 'Comprovante disponível apenas para atendimentos pagos');
            }

            $atendimento->load(['cliente', 'carro', 'servico', 'filial', 'usuarioConcluiu', 'usuarioRecebeu']);

            return view('atendimentos.comprovante', compact('atendimento'));

        } catch (\Exception $e) {
            \Log::error("Erro ao gerar comprovante do atendimento {$atendimento->id}: " . $e->getMessage());
            
            return redirect()->back()->with('error', 'Erro ao gerar comprovante: ' . $e->getMessage());
        }
    }
}
