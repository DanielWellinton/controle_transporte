<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePassageiroRequest;
use App\Http\Requests\UpdatePassageiroRequest;
use App\Models\Passageiro;
use App\Models\Viagem;
use Illuminate\Support\Facades\Auth;

class PassageiroController extends Controller
{
    public function index()
    {
        $viagens = Viagem::with(['rota.pontosDeParada', 'veiculo', 'motorista.usuario', 'passageiros'])
            ->where('ativo', true)
            ->orderBy('data_hora_saida', 'asc')
            ->get();

        return view('passageiros.index', compact('viagens'));
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
                'data_hora_saida'            => now(),
            ]
        );

        return redirect()->route('passageiros.index')->with('success', 'Sua vaga foi confirmada nesta viagem!');
    }

    public function cancelar(Viagem $viagem)
    {
        // Deleta o registro do usuário autenticado para esta viagem específica
        Passageiro::where('usuario_id', Auth::id())
            ->where('viagem_id', $viagem->id)
            ->delete();

        return redirect()->route('passageiros.index')->with('success', 'Sua participação foi cancelada.');
    }
}
