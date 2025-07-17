<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Carro extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'placa',
        'marca',
        'modelo',
        'ano',
        'cor',
        'observacoes'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class);
    }
}
