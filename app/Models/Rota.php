<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rota extends Model
{
    protected $fillable = [
        'descricao',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
        ];
    }

    public function pontosDeParada(): BelongsToMany
    {
        return $this->belongsToMany(PontoDeParada::class)
                    ->withPivot('ordem', 'ativo')
                    ->orderBy('ponto_de_parada_rota.ordem')
                    ->withTimestamps();
    }

    public function viagens(): HasMany
    {
        return $this->hasMany(Viagem::class);
    }
}
