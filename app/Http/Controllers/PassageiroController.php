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

class PassageiroController extends Controller
{
    public function index()
    {
        $usuarioId = Auth::id();

        $viagensAtivas = Viagem::with(['rota.pontosDeParada', 'motorista.usuario', 'veiculo'])
            ->where('ativo', true)
            ->whereNull('data_hora_chegada')
            ->get();

        $minhasViagens = Passageiro::with(['viagem.rota.pontosDeParada', 'pontoSaida', 'pontoChegada'])
            ->where('usuario_id', $usuarioId)
            ->whereHas('viagem', fn($q) => $q->whereNull('data_hora_chegada'))
            ->get();

        return view('passageiros.dashboard', compact('viagensAtivas', 'minhasViagens'));
    }

    // Selecionar ou Atualizar pontos de parada
    public function selecionarPontos(Request $request, Viagem $viagem)
    {
        $validated = $request->validate([
            'ponto_de_parada_saida_id'   => 'required|exists:ponto_de_paradas,id',
            'ponto_de_parada_chegada_id' => 'required|exists:ponto_de_paradas,id|different:ponto_de_parada_saida_id',
        ]);

        $passageiro = Passageiro::where('usuario_id', Auth::id())
            ->where('viagem_id', $viagem->id)
            ->first();

        // Não permite editar se já leu o QR Code e embarcou
        if ($passageiro && $passageiro->status === 'presente') {
            return redirect()->route('passageiros.dashboard')
                ->with('error', 'Você já embarcou nesta viagem e não pode mais alterar os pontos.');
        }

        Passageiro::updateOrCreate(
            [
                'usuario_id' => Auth::id(),
                'viagem_id'  => $viagem->id,
            ],
            [
                'ponto_de_parada_saida_id'   => $validated['ponto_de_parada_saida_id'],
                'ponto_de_parada_chegada_id' => $validated['ponto_de_parada_chegada_id'],
                'status'                     => 'reservado',
            ]
        );

        return redirect()->route('passageiros.dashboard')
            ->with('success', 'Pontos definidos/atualizados com sucesso!');
    }

    // Cancelar/Desistir da reserva na viagem
    public function cancelarReserva(Viagem $viagem)
    {
        $passageiro = Passageiro::where('usuario_id', Auth::id())
            ->where('viagem_id', $viagem->id)
            ->first();

        if (!$passageiro) {
            return redirect()->route('passageiros.dashboard')
                ->with('error', 'Reserva não encontrada.');
        }

        // Impede desistência caso o passageiro já tenha embarcado
        if ($passageiro->status === 'presente') {
            return redirect()->route('passageiros.dashboard')
                ->with('error', 'Você já embarcou nesta viagem e não pode desistir.');
        }

        $passageiro->delete();

        return redirect()->route('passageiros.dashboard')
            ->with('success', 'Sua reserva foi cancelada com sucesso.');
    }

    public function registrarPresencaViaQr($codigo_qr)
    {
        $viagem = Viagem::where('codigo_qr', $codigo_qr)->where('ativo', true)->firstOrFail();

        $passageiro = Passageiro::where('viagem_id', $viagem->id)
            ->where('usuario_id', Auth::id())
            ->first();

        if (!$passageiro) {
            return redirect()->route('passageiros.dashboard')
                ->with('error', 'Você não selecionou os pontos de embarque para esta viagem.');
        }

        if ($passageiro->status === 'presente') {
            return redirect()->route('passageiros.dashboard')
                ->with('info', 'Sua presença já havia sido confirmada anteriormente.');
        }

        $passageiro->update([
            'data_hora_saida' => now(),
            'status'          => 'presente',
        ]);

        return redirect()->route('passageiros.dashboard')
            ->with('success', 'Presença confirmada no veículo com sucesso!');
    }
}
