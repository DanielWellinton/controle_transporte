<?php

namespace App\Http\Controllers;

use App\Models\Viagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PortalMotoristaController extends Controller
{
    /**
     * Exibe o painel do motorista com as viagens agendadas e ativas.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->isMotorista()) {
            abort(403, 'Usuário não possui o papel de motorista.');
        }

        $motorista = $user->motorista;

        if (!$motorista) {
            return redirect()->route('home')->with('error', 'Seu cadastro de motorista ainda não foi concluído.');
        }

        // 1. Viagens ativas (em andamento ou agendadas)
        $viagens = Viagem::with(['rota.pontosDeParada', 'veiculo', 'passageiros'])
            ->where('motorista_id', $motorista->id)
            ->where('ativo', true)
            ->orderBy('data_hora_saida', 'asc')
            ->get();

        // 2. Histórico de viagens (finalizadas/inativas)
        $historico = Viagem::with(['rota', 'veiculo'])
            ->withCount(['passageiros as embarcados_count' => function ($query) {
                $query->whereNotNull('passageiros.data_hora_saida');
            }])
            ->where('motorista_id', $motorista->id)
            ->where('ativo', false)
            ->orderBy('data_hora_chegada', 'desc')
            ->paginate(10);

        return view('portal_motorista.index', compact('viagens', 'historico'));
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

        DB::transaction(function () use ($viagem) {
            $agora = now();

            // 1. Inativa e define a hora de encerramento da viagem
            $viagem->update([
                'data_hora_chegada' => $agora,
                'ativo'             => false, // ajuste para o campo usado (ex: 'ativo' => false ou 'status' => 'finalizada')
            ]);

            // 2. Registra o desembarque APENAS dos passageiros que EMBARCARAM,
            // mas que ainda não haviam registrado a chegada.
            DB::table('passageiros')
                ->where('viagem_id', $viagem->id)
                ->whereNotNull('data_hora_saida')    // Garantia: Apenas quem realmente embarcou
                ->whereNull('data_hora_chegada')     // Apenas quem ainda não tinha registrado o desembarque
                ->update([
                    'data_hora_chegada' => $agora,
                    'updated_at'        => $agora,
                ]);
        });

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
