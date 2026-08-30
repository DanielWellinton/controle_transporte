<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePassageiroRequest;
use App\Http\Requests\UpdatePassageiroRequest;
use Illuminate\Http\Request;
use App\Models\Passageiro;
use App\Models\Viagem;
use App\Models\User;
use App\Models\PontoDeParada;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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

    public function salvarPontos(Request $request, Viagem $viagem)
    {
        $request->validate([
            'ponto_de_parada_saida_id' => [
                'required',
                Rule::exists(PontoDeParada::class, 'id')
            ],
            'ponto_de_parada_chegada_id' => [
                'required',
                'different:ponto_de_parada_saida_id',
                Rule::exists(PontoDeParada::class, 'id')
            ],
        ]);

        // Atualiza se existir ou cria um novo registro
        Passageiro::updateOrCreate(
            [
                'usuario_id' => Auth::id(),
                'viagem_id'  => $viagem->id,
            ],
            [
                'ponto_de_parada_saida_id'   => $request->ponto_de_parada_saida_id,
                'ponto_de_parada_chegada_id' => $request->ponto_de_parada_chegada_id,
                'data_hora_saida'            => now(),
            ]
        );

        return redirect()->route('passageiros.index')->with('success', 'Sua vaga foi confirmada nesta viagem!');
    }

    // Altere a assinatura para receber (Viagem $viagem)
    public function cancelar(Viagem $viagem)
    {
        // Deleta o registro do usuário autenticado para esta viagem específica
        Passageiro::where('usuario_id', Auth::id())
            ->where('viagem_id', $viagem->id)
            ->delete();

        return redirect()->route('passageiros.index')->with('success', 'Sua participação foi cancelada.');
    }
}
