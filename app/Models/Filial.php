<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filial extends Model
{
    use HasFactory;

    protected $table = 'filials';

    protected $fillable = [
        'nome',
        'endereco',
        'telefone'
    ];

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class);
    }
}
