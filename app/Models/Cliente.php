<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'telefone',
        'whatsapp',
        'email',
        'cpf',
        'endereco',
        'filial_id',
        'observacoes',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean'
    ];

    public function filial()
    {
        return $this->belongsTo(Filial::class);
    }

    public function carros()
    {
        return $this->hasMany(Carro::class);
    }

    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class);
    }
}
