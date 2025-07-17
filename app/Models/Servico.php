<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Servico extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'preco',
        'tempo_estimado',
        'ativo',
        // ⏱️ Novos campos
        'tempo_estimado_minutos',
        'descricao_tecnica'
    ];

    protected $casts = [
        'preco' => 'decimal:2',
        'ativo' => 'boolean'
    ];

    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class);
    }
    
    /**
     * Retorna o tempo estimado formatado
     */
    public function getTempoEstimadoFormatadoAttribute()
    {
        $minutos = $this->tempo_estimado_minutos;
        
        if (!$minutos) {
            return 'Não definido';
        }
        
        $horas = intval($minutos / 60);
        $min = $minutos % 60;
        
        if ($horas > 0) {
            return $horas . 'h ' . $min . 'min';
        }
        
        return $min . 'min';
    }
    
    /**
     * Calcula tempo médio real baseado nos atendimentos concluídos
     */
    public function getTempoMedioRealAttribute()
    {
        $tempoMedio = $this->atendimentos()
            ->comCronometragem()
            ->avg('tempo_execucao_minutos');
            
        return $tempoMedio ? round($tempoMedio) : null;
    }
    
    /**
     * Calcula eficiência média do serviço
     */
    public function getEficienciaMediaAttribute()
    {
        if (!$this->tempo_estimado_minutos || !$this->tempo_medio_real) {
            return null;
        }
        
        return round(($this->tempo_estimado_minutos / $this->tempo_medio_real) * 100, 1);
    }
}
