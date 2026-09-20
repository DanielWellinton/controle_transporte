<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreViagemRequest;
use App\Http\Requests\UpdateViagemRequest;
use Illuminate\Http\Request;
use App\Models\Motorista;
use App\Models\Rota;
use App\Models\Veiculo;
use App\Models\Viagem;
use Carbon\Carbon;

class ViagemController extends Controller
{
    public function index(Request $request)
    {
        $query = Viagem::with(['rota', 'motorista.usuario', 'veiculo']);

        // Busca Textual Geral
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

        // Filtro por Data Inicial (a partir do início do dia 00:00:00)
        if ($request->filled('data_inicio')) {
            $query->whereDate('data_hora_saida', '>=', $request->input('data_inicio'));
        }

        // Filtro por Data Fim (até o final do dia 23:59:59)
        if ($request->filled('data_fim')) {
            $query->whereDate('data_hora_saida', '<=', $request->input('data_fim'));
        }

        // Filtro por Status
        if ($request->filled('status')) {
            $query->where('ativo', $request->input('status'));
        }

        $viagens = $query->orderBy('data_hora_saida', 'desc')->paginate(10);

        return view('viagems.index', compact('viagens'));
    }

    public function create()
    {
        $rotas = Rota::where('ativo', true)->get();
        $motoristas = Motorista::where('ativo', true)->get();
        $veiculos = Veiculo::where('ativo', true)->get();

        return view('viagems.create', compact('rotas', 'motoristas', 'veiculos'));
    }

    public function store(StoreViagemRequest $request)
    {
        Viagem::create($request->validated());

        return redirect()->route('viagems.index')
            ->with('success', 'Viagem agendada com sucesso!');
    }

    public function show(Viagem $viagem)
    {
        $viagem->load([
            'rota.pontosDeParada',
            'motorista',
            'veiculo',
            'passageiros'
        ]);

        return view('viagems.show', compact('viagem'));
    }

    public function edit(Viagem $viagem)
    {
        $rotas = Rota::where('ativo', true)->orWhere('id', $viagem->rota_id)->get();
        $motoristas = Motorista::where('ativo', true)->orWhere('id', $viagem->motorista_id)->get();
        $veiculos = Veiculo::where('ativo', true)->orWhere('id', $viagem->veiculo_id)->get();

        return view('viagems.edit', compact('viagem', 'rotas', 'motoristas', 'veiculos'));
    }

    public function update(UpdateViagemRequest $request, Viagem $viagem)
    {
        $viagem->update($request->validated());

        return redirect()->route('viagems.index')
            ->with('success', 'Viagem atualizada com sucesso!');
    }

    public function destroy(Viagem $viagem)
    {
        $viagem->delete();

        return redirect()->route('viagems.index')
            ->with('success', 'Viagem excluída com sucesso!');
    }

    /**
     * Abre o formulário de criação pré-preenchido com os dados de uma viagem existente.
     */
    public function duplicate(Viagem $viagem)
    {
        $viagemDuplicada = $viagem->replicate();

        $viagemDuplicada->data_hora_saida = null;
        $viagemDuplicada->data_hora_chegada = null;

        $rotas = Rota::where('ativo', true)->orWhere('id', $viagem->rota_id)->get();
        $motoristas = Motorista::where('ativo', true)->orWhere('id', $viagem->motorista_id)->get();
        $veiculos = Veiculo::where('ativo', true)->orWhere('id', $viagem->veiculo_id)->get();

        return view('viagems.create', [
            'viagem' => $viagemDuplicada,
            'rotas' => $rotas,
            'motoristas' => $motoristas,
            'veiculos' => $veiculos,
        ]);
    }
}
