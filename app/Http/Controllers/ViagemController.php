<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreViagemRequest;
use App\Http\Requests\UpdateViagemRequest;
use Illuminate\Http\Request;
use App\Models\Motorista;
use App\Models\Rota;
use App\Models\Veiculo;
use App\Models\Viagem;

class ViagemController extends Controller
{
    public function index()
    {
        $viagens = Viagem::with(['rota', 'motorista', 'veiculo'])
            ->latest('data_hora_saida')
            ->paginate(10);

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
}
