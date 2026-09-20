<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePassageiroRequest;
use App\Http\Requests\UpdatePassageiroRequest;
use App\Models\Passageiro;
use App\Models\Viagem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PassageiroController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Filtra apenas viagens ativas por padrão
        $query = Viagem::where('ativo', true)
            ->with(['rota', 'motorista.usuario', 'veiculo', 'passageiros']);

        // 1. Busca por Texto (Rota, Motorista ou Placa/Veículo)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('rota', function ($qRota) use ($search) {
                    $qRota->where('descricao', 'like', "%{$search}%");
                })
                    ->orWhereHas('motorista.usuario', function ($qUser) use ($search) {
                        $qUser->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('veiculo', function ($qVeiculo) use ($search) {
                        $qVeiculo->where('descricao', 'like', "%{$search}%")
                            ->orWhere('placa', 'like', "%{$search}%");
                    });
            });
        }

        // 2. Filtro de Intervalo de Datas
        if ($request->filled('data_inicio')) {
            $query->whereDate('data_hora_saida', '>=', $request->input('data_inicio'));
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('data_hora_saida', '<=', $request->input('data_fim'));
        }

        // 3. Filtro por Situação da Inscrição do Usuário
        if ($request->filled('inscricao')) {
            if ($request->input('inscricao') === 'inscrito') {
                $query->whereHas('passageiros', function ($q) use ($userId) {
                    $q->where('users.id', $userId);
                });
            } elseif ($request->input('inscricao') === 'nao_inscrito') {
                $query->whereDoesntHave('passageiros', function ($q) use ($userId) {
                    $q->where('users.id', $userId);
                });
            }
        }

        // Ordenação e Paginação (exemplo: 9 itens por página para bater com o grid 3x3)
        $viagens = $query->orderBy('data_hora_saida', 'asc')->paginate(9);

        return view('dashboard', compact('viagens'));
    }

    // Exibe o mapa para a viagem selecionada
    public function selecionarPontos(Viagem $viagem)
    {
        $viagem->load(['rota.pontosDeParada', 'veiculo', 'motorista.usuario']);

        $passageiro = Passageiro::where('usuario_id', Auth::id())
            ->where('viagem_id', $viagem->id)
            ->first();

        return view('passageiros.selecionar-pontos', compact('viagem', 'passageiro'));
    }

    public function salvarPontos(StorePassageiroRequest $request, Viagem $viagem)
    {
        $validated = $request->validated();

        // Atualiza se existir ou cria um novo registro
        Passageiro::updateOrCreate(
            [
                'usuario_id' => Auth::id(),
                'viagem_id'  => $viagem->id,
            ],
            [
                'ponto_de_parada_saida_id'   => $validated['ponto_de_parada_saida_id'],
                'ponto_de_parada_chegada_id' => $validated['ponto_de_parada_chegada_id'],
                'data_hora_saida'            => null,
            ]
        );

        return redirect()->route('dashboard')->with('success', 'Sua vaga foi confirmada nesta viagem!');
    }

    public function cancelar(Viagem $viagem)
    {
        // Deleta o registro do usuário autenticado para esta viagem específica
        Passageiro::where('usuario_id', Auth::id())
            ->where('viagem_id', $viagem->id)
            ->delete();

        return redirect()->route('dashboard')->with('success', 'Sua participação foi cancelada.');
    }

    public function historico(Request $request)
    {
        $userId = Auth::id();

        // Filtra viagens onde o usuário atual é passageiro
        $query = Viagem::whereHas('passageiros', function ($q) use ($userId) {
            $q->where('usuario_id', $userId);
        })
            ->with([
                'rota',
                'motorista.usuario',
                'veiculo',
                'passageiros' => function ($q) use ($userId) {
                    $q->where('usuario_id', $userId)
                        ->withPivot([
                            'ponto_de_parada_saida_id',
                            'data_hora_saida',
                            'ponto_de_parada_chegada_id',
                            'data_hora_chegada'
                        ]);
                }
            ]);

        // 1. Busca por Texto (Rota, Motorista ou Veículo)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('rota', function ($qRota) use ($search) {
                    $qRota->where('descricao', 'like', "%{$search}%");
                })
                    ->orWhereHas('motorista.usuario', function ($qUser) use ($search) {
                        $qUser->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('veiculo', function ($qVeiculo) use ($search) {
                        $qVeiculo->where('descricao', 'like', "%{$search}%")
                            ->orWhere('placa', 'like', "%{$search}%");
                    });
            });
        }

        // 2. Intervalo de Datas da Viagem
        if ($request->filled('data_inicio')) {
            $query->whereDate('data_hora_saida', '>=', $request->input('data_inicio'));
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('data_hora_saida', '<=', $request->input('data_fim'));
        }

        // 3. Status do Embarque na tabela passageiros
        if ($request->filled('status_embarque')) {
            if ($request->input('status_embarque') === 'embarcado') {
                $query->whereHas('passageiros', function ($q) use ($userId) {
                    $q->where('usuario_id', $userId)
                        ->whereNotNull('passageiros.data_hora_saida');
                });
            } elseif ($request->input('status_embarque') === 'pendente') {
                $query->whereHas('passageiros', function ($q) use ($userId) {
                    $q->where('usuario_id', $userId)
                        ->whereNull('passageiros.data_hora_saida');
                });
            }
        }

        $viagens = $query->orderBy('data_hora_saida', 'desc')->paginate(10);

        // Opcional: Carregar as descrições dos pontos de parada de uma vez só se necessário
        $pontosIds = $viagens->pluck('passageiros')->flatten()->pluck('pivot.ponto_de_parada_saida_id')
            ->merge($viagens->pluck('passageiros')->flatten()->pluck('pivot.ponto_de_parada_chegada_id'))
            ->filter()
            ->unique();

        $pontos = \App\Models\PontoDeParada::whereIn('id', $pontosIds)->pluck('descricao', 'id');

        return view('passageiros.historico', compact('viagens', 'pontos'));
    }
}
