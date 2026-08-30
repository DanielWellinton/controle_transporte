<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Passageiro extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    
    protected $fillable = [
        'usuario_id',
        'viagem_id',
        'ponto_de_parada_saida_id',
        'data_hora_saida',
        'ponto_de_parada_chegada_id',
        'data_hora_chegada',
    ];

    protected function casts(): array
    {
        return [
            'data_hora_saida' => 'datetime',
            'data_hora_chegada' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function viagem(): BelongsTo
    {
        return $this->belongsTo(Viagem::class);
    }

    public function pontoSaida(): BelongsTo
    {
        return $this->belongsTo(PontoDeParada::class, 'ponto_de_parada_saida_id');
    }

    public function pontoChegada(): BelongsTo
    {
        return $this->belongsTo(PontoDeParada::class, 'ponto_de_parada_chegada_id');
    }
}
