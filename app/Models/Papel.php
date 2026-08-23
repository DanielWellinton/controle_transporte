<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Papel extends Model
{
    /** @use HasFactory<\Database\Factories\PapelFactory> */
    use HasFactory;

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

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'usuario_papels', 'papel_id', 'usuario_id')
                    ->withPivot('id', 'data_hora_inicio', 'data_hora_fim', 'ativo')
                    ->withTimestamps();
    }
}
