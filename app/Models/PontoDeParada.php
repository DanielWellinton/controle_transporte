<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PontoDeParada extends Model
{
    protected $fillable = [
        'descricao',
        'latitude',
        'longitude',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'ativo' => 'boolean',
        ];
    }

    public function rotas(): BelongsToMany
    {
        return $this->belongsToMany(
            Rota::class, 
            'rota_ponto_de_paradas', 
            'ponto_de_parada_id', 
            'rota_id'
        )
        ->withPivot('ordem', 'ativo')
        ->withTimestamps();
    }
}
