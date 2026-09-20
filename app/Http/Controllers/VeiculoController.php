<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVeiculoRequest;
use App\Http\Requests\UpdateVeiculoRequest;
use Illuminate\Http\Request;
use App\Models\Veiculo;

class VeiculoController extends Controller
{
    public function index(Request $request)
    {
        $query = Veiculo::query();

        // Filtro por Descrição ou Placa
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('descricao', 'like', "%{$search}%")
                    ->orWhere('placa', 'like', "%{$search}%");
            });
        }

        // Filtro por Status
        if ($request->filled('status')) {
            $query->where('ativo', $request->input('status'));
        }

        $veiculos = $query->paginate(10);

        return view('veiculos.index', compact('veiculos'));
    }

    public function create()
    {
        return view('veiculos.create');
    }

    public function store(StoreVeiculoRequest $request)
    {
        Veiculo::create($request->validated());

        return redirect()->route('veiculos.index')
            ->with('success', 'Veículo cadastrado com sucesso.');
    }

    public function show(Veiculo $veiculo)
    {
        return view('veiculos.show', compact('veiculo'));
    }

    public function edit(Veiculo $veiculo)
    {
        return view('veiculos.edit', compact('veiculo'));
    }

    public function update(UpdateVeiculoRequest $request, Veiculo $veiculo)
    {
        $veiculo->update($request->validated());

        return redirect()->route('veiculos.index')
            ->with('success', 'Veículo atualizado com sucesso.');
    }

    public function destroy(Veiculo $veiculo)
    {
        $veiculo->delete();

        return redirect()->route('veiculos.index')
            ->with('success', 'Veículo removido com sucesso.');
    }

    public function autocomplete(Request $request)
    {
        $term = $request->query('q');
        $currentVeiculoId = $request->query('current_id');

        if (!$term) {
            return response()->json([]);
        }

        $veiculos = Veiculo::query()
            ->where(function ($q) use ($currentVeiculoId) {
                $q->where('ativo', true);
                if ($currentVeiculoId) {
                    $q->orWhere('id', $currentVeiculoId);
                }
            })
            ->where(function ($q) use ($term) {
                $q->where('descricao', 'like', "%{$term}%")
                    ->orWhere('placa', 'like', "%{$term}%");
            })
            ->limit(8)
            ->get(['id', 'descricao', 'placa']);

        return response()->json($veiculos);
    }
}
