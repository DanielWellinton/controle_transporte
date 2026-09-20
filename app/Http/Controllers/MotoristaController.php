<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMotoristaRequest;
use App\Http\Requests\UpdateMotoristaRequest;
use App\Models\Motorista;
use App\Models\User;
use Illuminate\Http\Request;

class MotoristaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Motorista::with(['usuario']);
    
        // Filtro por Nome (via relacionamento com Usuario) ou por CNH
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('cnh', 'like', "%{$search}%")
                  ->orWhereHas('usuario', function($qUser) use ($search) {
                      $qUser->where('name', 'like', "%{$search}%");
                  });
            });
        }
    
        // Filtro por Status (Ativo / Inativo)
        if ($request->filled('status')) {
            $query->where('ativo', $request->input('status'));
        }
    
        $motoristas = $query->paginate(10);
    
        return view('motoristas.index', compact('motoristas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $usuarios = User::whereHas('papeis', function ($query) {
            $query->where('descricao', 'Motorista')
                  ->where('papels.ativo', true)
                  ->where('usuario_papels.ativo', true)
                  ->where(function ($q) {
                      $q->whereNull('usuario_papels.data_hora_fim')
                        ->orWhere('usuario_papels.data_hora_fim', '>', now());
                  });
        })
        ->with(['motorista', 'papeis'])
        ->get();
        return view('motoristas.create', compact('usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMotoristaRequest $request)
    {
        Motorista::create($request->validated());
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
        $motorista->update($request->validated());
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
