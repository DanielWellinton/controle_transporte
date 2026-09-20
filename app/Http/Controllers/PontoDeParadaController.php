<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePontoDeParadaRequest;
use App\Http\Requests\UpdatePontoDeParadaRequest;
use App\Models\PontoDeParada;
use Illuminate\Http\Request;

class PontoDeParadaController extends Controller
{
    public function index(Request $request)
    {
        $query = PontoDeParada::query();

        // Filtro por Descrição
        if ($request->filled('search')) {
            $query->where('descricao', 'like', '%' . $request->input('search') . '%');
        }

        // Filtro por Status
        if ($request->filled('status')) {
            $query->where('ativo', $request->input('status'));
        }

        $pontoDeParada = $query->paginate(10);

        return view('ponto_de_paradas.index', compact('pontoDeParada'));
    }

    public function create()
    {
        return view('ponto_de_paradas.create');
    }

    public function store(StorePontoDeParadaRequest $request)
    {
        PontoDeParada::create($request->validated());

        return redirect()->route('ponto_de_paradas.index')
            ->with('success', 'Ponto de parada cadastrado com sucesso.');
    }

    public function show(PontoDeParada $pontoDeParada)
    {
        return view('ponto_de_paradas.show', ['pontoDeParada' => $pontoDeParada]);
    }

    public function edit(PontoDeParada $pontoDeParada)
    {
        return view('ponto_de_paradas.edit', ['pontoDeParada' => $pontoDeParada]);
    }

    public function update(UpdatePontoDeParadaRequest $request, PontoDeParada $pontoDeParada)
    {
        $pontoDeParada->update($request->validated());

        return redirect()->route('ponto_de_paradas.index')
            ->with('success', 'Ponto de parada atualizado com sucesso.');
    }

    public function destroy(PontoDeParada $pontoDeParada)
    {
        $pontoDeParada->delete();

        return redirect()->route('ponto_de_paradas.index')
            ->with('success', 'Ponto de parada removido com sucesso.');
    }

    /**
     * Retorna os pontos de parada para o componente de Autocomplete via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autocomplete(Request $request)
    {
        $search = trim($request->get('q', ''));

        // Retorna array vazio caso a busca esteja zerada
        if (empty($search)) {
            return response()->json([]);
        }

        $pontos = PontoDeParada::query()
            ->where('descricao', 'LIKE', "%{$search}%")
            ->orderBy('descricao', 'asc')
            ->limit(10)
            ->get(['id', 'descricao']);

        return response()->json($pontos);
    }
}
