<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Viagem extends Model
{    
    protected $fillable = [
        'codigo_qr',
        'data_hora_saida',
        'data_hora_chegada',
        'rota_id',
        'motorista_id',
        'veiculo_id',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'data_hora_saida' => 'datetime',
            'data_hora_chegada' => 'datetime',
            'ativo' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($viagem) {
            $viagem->codigo_qr = (string) Str::uuid();
        });
    }

    public function rota(): BelongsTo
    {
        return $this->belongsTo(Rota::class);
    }

    public function motorista(): BelongsTo
    {
        return $this->belongsTo(Motorista::class);
    }

    public function veiculo(): BelongsTo
    {
        return $this->belongsTo(Veiculo::class);
    }

    public function passageiros()
    {
        return $this->belongsToMany(User::class, 'passageiros', 'viagem_id', 'usuario_id')
                    ->withPivot('ponto_de_parada_saida_id', 'data_hora_saida', 'ponto_de_parada_chegada_id', 'data_hora_chegada')
                    ->withTimestamps();
    }
}
