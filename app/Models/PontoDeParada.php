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
        return $this->belongsToMany(Rota::class)
                    ->withPivot('ordem', 'ativo')
                    ->withTimestamps();
    }
}
