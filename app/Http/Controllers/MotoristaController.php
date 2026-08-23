<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMotoristaRequest;
use App\Http\Requests\UpdateMotoristaRequest;
use App\Models\Motorista;
use App\Models\User;

class MotoristaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $motoristas = Motorista::with('usuario')->get();

        return view('motoristas.index', compact('motoristas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $usuarios = User::all();
        return view('motoristas.create', compact('usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMotoristaRequest $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:users,id|unique:motoristas,usuario_id',
            'cnh' => 'required|string|max:20|unique:motoristas,cnh',
            'data_validade_cnh' => 'required|date',
            'ativo' => 'boolean',
        ]);
        $validated['ativo'] = $request->has('ativo');
        Motorista::create($validated);

        return redirect()->route('motoristas.index')->with('success', 'Motorista cadastrado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Motorista $motorista)
    {
        return view('motoristas.show', compact('motorista'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Motorista $motorista)
    {
        $usuarios = User::all();
        return view('motoristas.edit', compact('motorista', 'usuarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMotoristaRequest $request, Motorista $motorista)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:users,id|unique:motoristas,usuario_id,' . $motorista->id,
            'cnh' => 'required|string|max:20|unique:motoristas,cnh,' . $motorista->id,
            'data_validade_cnh' => 'required|date',
            'ativo' => 'boolean',
        ]);
        $validated['ativo'] = $request->has('ativo');
        $motorista->update($validated);

        return redirect()->route('motoristas.index')->with('success', 'Motorista atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Motorista $motorista)
    {
        $motorista->delete();
        return redirect()->route('motoristas.index')->with('success', 'Motorista removido com sucesso.');
    }
}
