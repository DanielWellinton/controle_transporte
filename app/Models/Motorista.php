<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Motorista extends Model
{
    protected $fillable = [
        'usuario_id',
        'cnh',
        'data_validade_cnh',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'data_validade_cnh' => 'date',
            'ativo' => 'boolean',
        ];
    }

    /**
     * Relacionamento: O Motorista pertence a um Usuário.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function viagens(): HasMany
    {
        return $this->hasMany(Viagem::class);
    }
}
