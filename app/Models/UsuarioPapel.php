<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioPapel extends Model
{
    protected $fillable = [
        'usuario_id',
        'papel_id',
        'data_hora_inicio',
        'data_hora_fim',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'data_hora_inicio' => 'datetime',
            'data_hora_fim' => 'datetime',
            'ativo' => 'boolean',
        ];
    }
}
