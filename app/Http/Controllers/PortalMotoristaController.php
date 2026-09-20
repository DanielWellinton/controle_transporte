<?php

namespace App\Http\Controllers;

use App\Models\Viagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PortalMotoristaController extends Controller
{

    /**
     * Exibe o painel do motorista apenas com as viagens agendadas e ativas.
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

        // Viagens ativas (em andamento ou agendadas)
        $viagens = Viagem::with(['rota.pontosDeParada', 'veiculo', 'passageiros'])
            ->where('motorista_id', $motorista->id)
            ->where('ativo', true)
            ->orderBy('data_hora_saida', 'asc')
            ->get();

        return view('portal_motorista.index', compact('viagens'));
    }

    /**
     * Exibe o histórico de viagens finalizadas/inativas com busca e filtros.
     */
    public function historico(Request $request)
    {
        $user = $request->user();

        if (!$user->isMotorista()) {
            abort(403, 'Usuário não possui o papel de motorista.');
        }

        $motorista = $user->motorista;

        if (!$motorista) {
            return redirect()->route('home')->with('error', 'Seu cadastro de motorista ainda não foi concluído.');
        }

        // Query base para viagens inativas/finalizadas
        $query = Viagem::with(['rota', 'veiculo'])
            ->withCount(['passageiros as embarcados_count' => function ($q) {
                $q->whereNotNull('passageiros.data_hora_saida');
            }])
            ->where('motorista_id', $motorista->id)
            ->where('ativo', false);

        // 1. Filtro por Busca (Nome/Descrição da Rota ou Placa/Veículo)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('rota', function ($qRota) use ($search) {
                    $qRota->where('descricao', 'like', "%{$search}%");
                })
                ->orWhereHas('veiculo', function ($qVeiculo) use ($search) {
                    $qVeiculo->where('descricao', 'like', "%{$search}%")
                             ->orWhere('placa', 'like', "%{$search}%");
                });
            });
        }

        // 2. Filtro por Data Inicial
        if ($request->filled('data_inicio')) {
            $query->whereDate('data_hora_saida', '>=', $request->input('data_inicio'));
        }

        // 3. Filtro por Data Fim
        if ($request->filled('data_fim')) {
            $query->whereDate('data_hora_saida', '<=', $request->input('data_fim'));
        }

        $historico = $query->orderBy('data_hora_chegada', 'desc')->paginate(10);

        return view('portal_motorista.historico', compact('historico'));
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
