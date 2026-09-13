<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePontoDeParadaRequest;
use App\Http\Requests\UpdatePontoDeParadaRequest;
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
}
