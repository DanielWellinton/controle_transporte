<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Veiculo extends Model
{
    protected $fillable = [
        'descricao',
        'numero_passageiros',
        'placa',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'numero_passageiros' => 'integer',
            'ativo' => 'boolean',
        ];
    }

    public function viagens(): HasMany
    {
        return $this->hasMany(Viagem::class);
    }
}
