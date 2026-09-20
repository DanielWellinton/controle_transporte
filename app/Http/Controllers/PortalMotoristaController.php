<?php

namespace App\Http\Controllers;

use App\Models\Viagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalMotoristaController extends Controller
{
    /**
     * Exibe o painel do motorista com as viagens agendadas e ativas.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->isMotorista()) {
            abort(403, 'Usuário não cadastrado como motorista.');
        }
        $motorista = $user->motorista;
        // Busca viagens atribuídas ao motorista logado para a data atual (ou futuras)
        $viagens = Viagem::with(['rota.pontosDeParada', 'veiculo', 'passageiros'])
            ->where('motorista_id', $motorista->id)
            ->where('ativo', true)
            ->orderBy('data_hora_saida', 'asc')
            ->get();

        return view('portal_motorista.index', compact('viagens'));
    }

    /**
     * Exibe os detalhes e a lista de passageiros de uma viagem específica.
     */
    public function show(Request $request, Viagem $viagem)
    {
        $this->autorizarMotorista($request, $viagem);

        $viagem->load([
            'rota.pontosDeParada', 
            'veiculo', 
            'passageiros' => function ($query) {
                $query->withPivot('ponto_de_parada_saida_id', 'ponto_de_parada_chegada_id', 'data_hora_saida');
            }
        ]);

        return view('portal_motorista.show', compact('viagem'));
    }

    /**
     * Inicia a viagem.
     */
    public function iniciar(Request $request, Viagem $viagem)
    {
        $this->autorizarMotorista($request, $viagem);

        $viagem->update([
            'data_hora_saida' => now(),
        ]);

        return redirect()->back()->with('success', 'Viagem iniciada com sucesso!');
    }

    /**
     * Finaliza a viagem.
     */
    public function finalizar(Request $request, Viagem $viagem)
    {
        $this->autorizarMotorista($request, $viagem);

        $viagem->update([
            'data_hora_chegada' => now(),
        ]);

        return redirect()->back()->with('success', 'Viagem finalizada com sucesso!');
    }

    /**
     * Gera visão simplificada e limpa para impressão/conferência de lista de chamada.
     */
    public function imprimir(Request $request, Viagem $viagem)
    {
        $this->autorizarMotorista($request, $viagem);

        $viagem->load(['rota', 'veiculo', 'passageiros']);

        return view('portal_motorista.imprimir', compact('viagem'));
    }

    /**
     * Auxiliar para validar se a viagem pertence ao motorista logado.
     */
    private function autorizarMotorista(Request $request, Viagem $viagem)
    {
        $motorista = $request->user()->motorista;

        if (!$motorista || ($viagem->motorista_id !== $motorista->id && !$request->user()->isAdmin())) {
            abort(403, 'Você não tem permissão para gerenciar esta viagem.');
        }
    }
}
