<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVeiculoRequest;
use App\Http\Requests\UpdateVeiculoRequest;
use Illuminate\Http\Request;
use App\Models\Veiculo;

class VeiculoController extends Controller
{
    public function index()
    {
        $veiculos = Veiculo::all();
        return view('veiculos.index', compact('veiculos'));
    }

    public function create()
    {
        return view('veiculos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'numero_passageiros' => 'required|integer|min:1',
            'placa' => 'required|string|max:10|unique:veiculos,placa',
        ]);

        $validated['ativo'] = $request->has('ativo');

        Veiculo::create($validated);

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

    public function update(Request $request, Veiculo $veiculo)
    {
        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'numero_passageiros' => 'required|integer|min:1',
            'placa' => 'required|string|max:10|unique:veiculos,placa,' . $veiculo->id,
        ]);

        $validated['ativo'] = $request->has('ativo');

        $veiculo->update($validated);

        return redirect()->route('veiculos.index')
            ->with('success', 'Veículo atualizado com sucesso.');
    }

    public function destroy(Veiculo $veiculo)
    {
        $veiculo->delete();

        return redirect()->route('veiculos.index')
            ->with('success', 'Veículo removido com sucesso.');
    }
}
