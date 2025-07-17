<?php

namespace App\Observers;

use App\Models\Atendimento;

class AtendimentoObserver
{
    /**
     * Handle the Atendimento "updating" event.
     * Detecta mudanças de status para cronometragem automática
     */
    public function updating(Atendimento $atendimento): void
    {
        // Se o status está mudando para "em_andamento" e ainda não tem início
        if ($atendimento->isDirty('status') && 
            $atendimento->status === 'em_andamento' && 
            !$atendimento->inicio_servico) {
            
            $atendimento->inicio_servico = now();
            
            // Se não tem funcionário responsável, tenta pegar do usuário logado
            if (!$atendimento->funcionario_responsavel && auth()->check()) {
                $atendimento->funcionario_responsavel = auth()->user()->name;
            }
        }
        
        // Se o status está mudando para "concluido" e ainda não tem fim
        if ($atendimento->isDirty('status') && 
            $atendimento->status === 'concluido' && 
            !$atendimento->fim_servico && 
            $atendimento->inicio_servico) {
            
            $atendimento->fim_servico = now();
            
            // Calcular tempo de execução
            $tempoExecucao = $atendimento->inicio_servico->diffInMinutes(now());
            $atendimento->tempo_execucao_minutos = $tempoExecucao;
        }
    }

    /**
     * Handle the Atendimento "created" event.
     */
    public function created(Atendimento $atendimento): void
    {
        // Se já criado como "em_andamento", inicia cronometragem
        if ($atendimento->status === 'em_andamento' && !$atendimento->inicio_servico) {
            $atendimento->update(['inicio_servico' => now()]);
        }
    }
}
