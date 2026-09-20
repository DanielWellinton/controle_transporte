<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'data_nascimento', 'telefone'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'data_nascimento' => 'date',
        ];
    }

    public function papeis()
    {
        return $table = $this->belongsToMany(Papel::class, 'usuario_papels', 'usuario_id', 'papel_id')
                    ->withPivot('id', 'data_hora_inicio', 'data_hora_fim', 'ativo')
                    ->withTimestamps();
    }

    /**
     * Relacionamento: O Usuário possui um perfil de Motorista.
     */
    public function motorista(): HasOne
    {
        return $this->hasOne(Motorista::class, 'usuario_id');
    }

    public function viagensComoPassageiro()
    {
        return $this->belongsToMany(Viagem::class, 'passageiros', 'usuario_id', 'viagem_id')
                    ->withPivot('ponto_de_parada_saida_id', 'data_hora_saida', 'ponto_de_parada_chegada_id', 'data_hora_chegada')
                    ->withTimestamps();
    }

    /**
     * Verifica se o usuário possui um papel específico ativo.
     */
    public function temPapel(string $descricao): bool
    {
        return $this->papeis()
                    ->where('descricao', $descricao)
                    ->where('papels.ativo', true)
                    ->wherePivot('ativo', true)
                    ->where(function ($query) {
                        $query->whereNull('usuario_papels.data_hora_fim')
                              ->orWhere('usuario_papels.data_hora_fim', '>', now());
                    })
                    ->exists();
    }

    public function isAdminin(): bool
    {
        return $this->temPapel('Admin');
    }

    public function isMotorista(): bool
    {
        return $this->temPapel('Motorista');
    }

    public function isPassageiro(): bool
    {
        return $this->temPapel('Passageiro');
    }
}
