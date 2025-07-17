<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Despesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao',
        'valor',
        'data_despesa',
        'tipo',
        'categoria',
        'observacoes',
        'comprovante',
        'recorrente',
        'dia_vencimento',
        'status',
        'forma_pagamento',
        'user_id'
    ];

    protected $casts = [
        'data_despesa' => 'date',
        'valor' => 'decimal:2',
        'recorrente' => 'boolean'
    ];

    // Relacionamentos
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes para filtros
    public function scopeFixas($query)
    {
        return $query->where('tipo', 'fixa');
    }

    public function scopeVariaveis($query)
    {
        return $query->where('tipo', 'variavel');
    }

    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    public function scopePorPeriodo($query, $dataInicio, $dataFim)
    {
        return $query->whereBetween('data_despesa', [$dataInicio, $dataFim]);
    }

    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopeVencidas($query)
    {
        return $query->where('status', 'vencida')
                    ->where('data_despesa', '<', Carbon::now());
    }

    // Métodos auxiliares
    public static function totalPorTipo($tipo, $dataInicio = null, $dataFim = null)
    {
        $query = self::where('tipo', $tipo)->where('status', 'paga');
        
        if ($dataInicio && $dataFim) {
            $query->whereBetween('data_despesa', [$dataInicio, $dataFim]);
        }
        
        return $query->sum('valor');
    }

    public static function totalPorCategoria($categoria, $dataInicio = null, $dataFim = null)
    {
        $query = self::where('categoria', $categoria)->where('status', 'paga');
        
        if ($dataInicio && $dataFim) {
            $query->whereBetween('data_despesa', [$dataInicio, $dataFim]);
        }
        
        return $query->sum('valor');
    }

    public static function categorias()
    {
        return [
            'funcionarios' => 'Funcionários',
            'agua' => 'Água',
            'energia' => 'Energia Elétrica',
            'produtos' => 'Produtos e Insumos',
            'manutencao' => 'Manutenção',
            'aluguel' => 'Aluguel',
            'marketing' => 'Marketing',
            'combustivel' => 'Combustível',
            'telefone' => 'Telefone/Internet',
            'impostos' => 'Impostos',
            'equipamentos' => 'Equipamentos',
            'outros' => 'Outros'
        ];
    }

    public static function formasPagamento()
    {
        return [
            'dinheiro' => 'Dinheiro',
            'pix' => 'PIX',
            'cartao_debito' => 'Cartão de Débito',
            'cartao_credito' => 'Cartão de Crédito',
            'transferencia' => 'Transferência',
            'boleto' => 'Boleto'
        ];
    }

    // Verificar se está vencida
    public function getIsVencidaAttribute()
    {
        return $this->status === 'pendente' && $this->data_despesa < Carbon::now();
    }

    // Formatação do valor
    public function getValorFormatadoAttribute()
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
    }
}
