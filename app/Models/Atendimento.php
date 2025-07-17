<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Atendimento extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'carro_id',
        'servico_id',
        'filial_id',
        'status',
        'posicao_fila',
        'data_entrada_fila',
        'data_agendamento',
        'data_inicio',
        'data_fim',
        'data_conclusao',
        'concluido_por',
        'valor',
        'valor_original',
        'valor_desconto',
        'valor_final',
        'valor_pago',
        'data_pagamento',
        'recebido_por',
        'forma_pagamento',
        'pago',
        'motivo_cancelamento',
        'data_cancelamento',
        'cancelado_por',
        'observacoes',
        'observacoes_pagamento',
        // ⏱️ Novos campos de cronometragem
        'inicio_servico',
        'fim_servico',
        'tempo_execucao_minutos',
        'funcionario_responsavel',
        'observacoes_tecnicas',
        'nota_qualidade'
    ];

    protected $casts = [
        'data_agendamento' => 'datetime',
        'data_entrada_fila' => 'datetime',
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
        'data_conclusao' => 'datetime',
        'data_pagamento' => 'datetime',
        'data_cancelamento' => 'datetime',
        'pago' => 'boolean',
        'valor' => 'decimal:2',
        // ⏱️ Novos campos de cronometragem
        'inicio_servico' => 'datetime',
        'fim_servico' => 'datetime',
        'nota_qualidade' => 'decimal:2',
        'valor_original' => 'decimal:2',
        'valor_desconto' => 'decimal:2',
        'valor_final' => 'decimal:2',
        'valor_pago' => 'decimal:2'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function carro()
    {
        return $this->belongsTo(Carro::class);
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }

    public function filial()
    {
        return $this->belongsTo(Filial::class);
    }

    // Accessor para manter compatibilidade com a view
    public function getValorTotalAttribute()
    {
        return $this->valor;
    }

    /**
     * Métodos para controle de fila
     */
    
    // Obter próximo da fila
    public static function proximoDaFila($filialId = null)
    {
        return self::where('status', 'em_fila')
            ->when($filialId, fn($q) => $q->where('filial_id', $filialId))
            ->orderBy('posicao_fila')
            ->orderBy('data_entrada_fila')
            ->first();
    }
    
    // Obter todos na fila ordenados
    public static function filaOrdenada($filialId = null)
    {
        return self::where('status', 'em_fila')
            ->when($filialId, fn($q) => $q->where('filial_id', $filialId))
            ->orderBy('posicao_fila')
            ->orderBy('data_entrada_fila')
            ->with(['cliente', 'carro', 'servico'])
            ->get();
    }
    
    // Adicionar à fila
    public function adicionarNaFila()
    {
        $ultimaPosicao = self::where('filial_id', $this->filial_id)
            ->where('status', 'em_fila')
            ->max('posicao_fila') ?? 0;
            
        $this->update([
            'status' => 'em_fila',
            'posicao_fila' => $ultimaPosicao + 1,
            'data_entrada_fila' => now()
        ]);
        
        return $this;
    }
    
    // Chamar próximo da fila
    public function iniciarAtendimento()
    {
        // Salvar a posição atual antes de atualizar
        $posicaoAtual = $this->posicao_fila;
        
        $this->update([
            'status' => 'em_andamento',
            'data_inicio' => now(),
            'posicao_fila' => null,
            'data_entrada_fila' => null
        ]);
        
        // Reorganizar posições da fila apenas se havia uma posição válida
        if ($posicaoAtual) {
            self::where('filial_id', $this->filial_id)
                ->where('status', 'em_fila')
                ->where('posicao_fila', '>', $posicaoAtual)
                ->decrement('posicao_fila');
        }
            
        return $this;
    }
    
    // Obter posição na fila
    public function getPosicaoFilaAttribute()
    {
        if ($this->status !== 'em_fila') {
            return null;
        }
        
        return self::where('filial_id', $this->filial_id)
            ->where('status', 'em_fila')
            ->where('data_entrada_fila', '<=', $this->data_entrada_fila)
            ->count();
    }

    /**
     * Relacionamentos para controle de usuários
     */
    public function usuarioConcluiu()
    {
        return $this->belongsTo(User::class, 'concluido_por');
    }

    public function usuarioRecebeu()
    {
        return $this->belongsTo(User::class, 'recebido_por');
    }

    /**
     * Métodos para controle de pagamento e relatórios
     */
    
    // Finalizar atendimento (pronto para buscar)
    public function finalizar($usuarioId = null)
    {
        $this->update([
            'status' => 'concluido',
            'data_conclusao' => now(),
            'concluido_por' => $usuarioId ?? auth()->id()
        ]);
        
        return $this;
    }
    
    // Registrar pagamento
    public function registrarPagamento($dadosPagamento)
    {
        $valorOriginal = $this->valor;
        $desconto = $dadosPagamento['desconto'] ?? 0;
        $valorDesconto = ($valorOriginal * $desconto) / 100;
        $valorFinal = $valorOriginal - $valorDesconto;
        
        $this->update([
            'status' => 'pago',
            'forma_pagamento' => $dadosPagamento['forma_pagamento'],
            'valor_original' => $valorOriginal,
            'valor_desconto' => $valorDesconto,
            'valor_final' => $valorFinal,
            'valor_pago' => $dadosPagamento['valor_pago'],
            'data_pagamento' => now(),
            'recebido_por' => $dadosPagamento['recebido_por'] ?? auth()->id(),
            'observacoes_pagamento' => $dadosPagamento['observacoes_pagamento'] ?? null
        ]);
        
        return $this;
    }
    
    // Scopes para relatórios
    public function scopePagosNoPeriodo($query, $dataInicio, $dataFim)
    {
        return $query->where('status', 'pago')
            ->whereBetween('data_pagamento', [$dataInicio, $dataFim]);
    }
    
    public function scopePorFormaPagamento($query, $formaPagamento)
    {
        return $query->where('forma_pagamento', $formaPagamento);
    }
    
    public function scopeFaturamentoMes($query, $mes = null, $ano = null)
    {
        $mes = $mes ?? now()->month;
        $ano = $ano ?? now()->year;
        
        return $query->where('status', 'pago')
            ->whereMonth('data_pagamento', $mes)
            ->whereYear('data_pagamento', $ano);
    }
    
    // ⏱️ SISTEMA DE CRONOMETRAGEM AUTOMÁTICA
    
    /**
     * Inicia o cronômetro quando o status muda para 'em_andamento'
     */
    public function iniciarServico($funcionarioResponsavel = null)
    {
        $this->update([
            'status' => 'em_andamento',
            'inicio_servico' => now(),
            'funcionario_responsavel' => $funcionarioResponsavel ?? auth()->user()->name ?? 'Sistema'
        ]);
        
        return $this;
    }
    
    /**
     * Finaliza o cronômetro quando o status muda para 'concluido'
     */
    public function finalizarServico($observacoesTecnicas = null, $notaQualidade = null)
    {
        $inicioServico = $this->inicio_servico;
        $fimServico = now();
        
        // Calcular tempo em minutos
        $tempoExecucao = $inicioServico ? $inicioServico->diffInMinutes($fimServico) : null;
        
        $this->update([
            'status' => 'concluido',
            'fim_servico' => $fimServico,
            'tempo_execucao_minutos' => $tempoExecucao,
            'observacoes_tecnicas' => $observacoesTecnicas,
            'nota_qualidade' => $notaQualidade
        ]);
        
        return $this;
    }
    
    /**
     * Retorna o tempo de execução formatado
     */
    public function getTempoExecucaoFormatadoAttribute()
    {
        if (!$this->tempo_execucao_minutos) {
            return null;
        }
        
        $horas = intval($this->tempo_execucao_minutos / 60);
        $minutos = $this->tempo_execucao_minutos % 60;
        
        if ($horas > 0) {
            return $horas . 'h ' . $minutos . 'min';
        }
        
        return $minutos . 'min';
    }
    
    /**
     * Calcula a eficiência baseada no tempo padrão do serviço
     */
    public function getEficienciaAttribute()
    {
        if (!$this->tempo_execucao_minutos || !$this->servico?->tempo_estimado_minutos) {
            return null;
        }
        
        $tempoEstimado = $this->servico->tempo_estimado_minutos;
        $tempoReal = $this->tempo_execucao_minutos;
        
        // Eficiência = (Tempo Estimado / Tempo Real) * 100
        $eficiencia = ($tempoEstimado / $tempoReal) * 100;
        
        return round($eficiencia, 1);
    }
    
    /**
     * Retorna status da eficiência
     */
    public function getStatusEficienciaAttribute()
    {
        $eficiencia = $this->eficiencia;
        
        if (!$eficiencia) return null;
        
        if ($eficiencia >= 100) return 'excelente';
        if ($eficiencia >= 80) return 'bom';
        if ($eficiencia >= 60) return 'regular';
        return 'ruim';
    }
    
    /**
     * Scope para atendimentos com cronometragem
     */
    public function scopeComCronometragem($query)
    {
        return $query->whereNotNull('inicio_servico')
                    ->whereNotNull('fim_servico')
                    ->whereNotNull('tempo_execucao_minutos');
    }
    
    /**
     * Scope para atendimentos por funcionário
     */
    public function scopePorFuncionario($query, $funcionario)
    {
        return $query->where('funcionario_responsavel', $funcionario);
    }
}
