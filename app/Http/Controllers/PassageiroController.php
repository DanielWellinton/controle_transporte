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
        $request->validate([
            'ponto_de_parada_saida_id' => 'required|exists:pontos_de_parada,id',
            'ponto_de_parada_chegada_id' => 'required|exists:pontos_de_parada,id|different:ponto_de_parada_saida_id',
        ], [
            'ponto_de_parada_chegada_id.different' => 'O ponto de desembarque deve ser diferente do ponto de embarque.',
        ]);
    
        $viagem = Viagem::with('rota.pontosDeParada')->findOrFail($viagem->id);
    
        // Obtém o registro pivot dos pontos selecionados para verificar a coluna 'ordem'
        $pontoSaida = $viagem->rota->pontosDeParada->firstWhere('id', $request->ponto_de_parada_saida_id);
        $pontoChegada = $viagem->rota->pontosDeParada->firstWhere('id', $request->ponto_de_parada_chegada_id);
    
        if (!$pontoSaida || !$pontoChegada) {
            return back()->withErrors(['msg' => 'Pontos de parada inválidos para esta viagem.']);
        }
    
        $ordemSaida = $pontoSaida->pivot->ordem ?? 0;
        $ordemChegada = $pontoChegada->pivot->ordem ?? 0;
    
        // VALIDACAO: O desembarque precisa ser posterior ao embarque
        if ($ordemChegada <= $ordemSaida) {
            return back()->withInput()->withErrors([
                'ponto_de_parada_chegada_id' => 'O ponto de desembarque deve ser posterior ao ponto de embarque. O veículo não retorna na rota.'
            ]);
        }
    
        // Salva a reserva do passageiro...
        // ...
    
        return redirect()->back()->with('success', 'Pontos de embarque e desembarque selecionados com sucesso!');
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

    // Adicione este método no PassageiroController
    public function exibirViagem(Viagem $viagem)
    {
        // Garante que a viagem esteja ativa
        if (!$viagem->ativo || $viagem->data_hora_chegada !== null) {
            return redirect()->route('passageiros.dashboard')
                ->with('error', 'Esta viagem não está mais disponível.');
        }

        $viagem->load(['rota.pontosDeParada', 'motorista.usuario', 'veiculo']);

        // Busca se o passageiro já tem uma reserva nessa viagem para pré-selecionar os pontos
        $reserva = Passageiro::where('usuario_id', Auth::id())
            ->where('viagem_id', $viagem->id)
            ->first();

        return view('passageiros.viagem-detalhes', compact('viagem', 'reserva'));
    }
}
