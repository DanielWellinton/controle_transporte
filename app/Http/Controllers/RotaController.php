<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRotaRequest;
use App\Http\Requests\UpdateRotaRequest;
use Illuminate\Http\Request;
use App\Models\Rota;
use App\Models\PontoDeParada;

class RotaController extends Controller
{
    public function index(Request $request)
    {
        $query = Rota::query();

        // Filtro por Descrição
        if ($request->filled('search')) {
            $query->where('descricao', 'like', '%' . $request->input('search') . '%');
        }

        // Filtro por Status
        if ($request->filled('status')) {
            $query->where('ativo', $request->input('status'));
        }

        $rotas = $query->paginate(10);

        return view('rotas.index', compact('rotas'));
    }

    public function create()
    {
        return view('rotas.create');
    }

    public function store(StoreRotaRequest $request)
    {
        $rota = Rota::create($request->validated());

        return redirect()->route('rotas.edit', $rota)
            ->with('success', 'Rota cadastrada com sucesso! Você já pode vincular os pontos de parada.');
    }

    public function show(Rota $rota)
    {
        $rota->load('pontosDeParada');

        return view('rotas.show', compact('rota'));
    }

    public function edit(Rota $rota)
    {
        $rota->load('pontosDeParada');

        $pontosDisponiveis = PontoDeParada::where('ativo', true)
            ->whereNotIn('id', $rota->pontosDeParada->pluck('id'))
            ->get();

        return view('rotas.edit', compact('rota', 'pontosDisponiveis'));
    }

    public function update(UpdateRotaRequest $request, Rota $rota)
    {
        $rota->update($request->validated());

        return redirect()->route('rotas.edit', $rota)
            ->with('success', 'Dados da rota atualizados com sucesso.');
    }

    public function destroy(Rota $rota)
    {
        $rota->delete();

        return redirect()->route('rotas.index')
            ->with('success', 'Rota removida com sucesso.');
    }

    // --- Métodos da Pivot (Gerenciados no Edit) ---

    public function vincularPonto(Request $request, Rota $rota)
    {
        $validated = $request->validate([
            'ponto_de_parada_id' => 'required|exists:ponto_de_paradas,id',
        ]);

        $proximaOrdem = ($rota->pontosDeParada()->max('rota_ponto_de_paradas.ordem') ?? 0) + 1;

        $rota->pontosDeParada()->attach($validated['ponto_de_parada_id'], [
            'ordem' => $proximaOrdem,
            'ativo' => true,
        ]);

        return redirect()->route('rotas.edit', $rota)
            ->with('success', 'Ponto de parada vinculado com sucesso.');
    }

    public function desvincularPonto(Rota $rota, PontoDeParada $pontoDeParada)
    {
        $rota->pontosDeParada()->detach($pontoDeParada->id);

        return redirect()->route('rotas.edit', $rota)
            ->with('success', 'Ponto de parada removido da rota.');
    }

    public function atualizarPontos(Request $request, Rota $rota)
    {
        $request->validate([
            'pontos' => 'required|array',
            'pontos.*.ordem' => 'required|integer|min:1',
        ]);

        foreach ($request->pontos as $pontoId => $dados) {
            $rota->pontosDeParada()->updateExistingPivot($pontoId, [
                'ordem' => $dados['ordem'],
                'ativo' => isset($dados['ativo']),
            ]);
        }

        return redirect()->route('rotas.edit', $rota)
            ->with('success', 'Ordem e status dos pontos atualizados com sucesso.');
    }

    public function autocomplete(Request $request)
    {
        $term = $request->query('q');
        $currentRotaId = $request->query('current_id');
    
        if (!$term) {
            return response()->json([]);
        }
    
        $rotas = Rota::query()
            ->where(function ($q) use ($currentRotaId) {
                $q->where('ativo', true);
                if ($currentRotaId) {
                    $q->orWhere('id', $currentRotaId);
                }
            })
            ->where('descricao', 'like', "%{$term}%")
            ->limit(8)
            ->get(['id', 'descricao']);
    
        return response()->json($rotas);
    }
}
