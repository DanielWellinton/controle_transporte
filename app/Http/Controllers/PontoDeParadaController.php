<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePontoDeParadaRequest;
use App\Http\Requests\UpdatePontoDeParadaRequest;
use Illuminate\Http\Request;
use App\Models\PontoDeParada;

class PontoDeParadaController extends Controller
{
    public function index()
    {
        $pontoDeParada = PontoDeParada::all();

        return view('ponto_de_paradas.index', compact('pontoDeParada'));
    }

    public function create()
    {
        return view('ponto_de_paradas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $validated['ativo'] = $request->has('ativo');

        PontoDeParada::create($validated);

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

    public function update(Request $request, PontoDeParada $pontoDeParada)
    {
        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $validated['ativo'] = $request->has('ativo');

        $pontoDeParada->update($validated);

        return redirect()->route('ponto_de_paradas.index')
            ->with('success', 'Ponto de parada atualizado com sucesso.');
    }

    public function destroy(PontoDeParada $pontoDeParada)
    {
        $pontoDeParada->delete();

        return redirect()->route('ponto_de_paradas.index')
            ->with('success', 'Ponto de parada removido com sucesso.');
    }
}
