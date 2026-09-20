<?php

namespace App\Http\Controllers;

use App\Models\Papel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('papeis')->latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $papeis = Papel::where('ativo', true)->get();

        return view('users.create', compact('papeis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'data_nascimento' => 'nullable|date',
            'telefone' => 'nullable|string|max:20',
            'papeis' => 'array',
            'papeis.*' => 'exists:papels,id',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'data_nascimento' => $validated['data_nascimento'] ?? null,
                'telefone' => $validated['telefone'] ?? null,
            ]);

            if (!empty($validated['papeis'])) {
                $syncData = [];
                foreach ($validated['papeis'] as $papelId) {
                    $syncData[$papelId] = [
                        'data_hora_inicio' => now(),
                        'ativo' => true,
                    ];
                }
                $user->papeis()->attach($syncData);
            }
        });

        return redirect()->route('users.index')->with('success', 'Usuário cadastrado com sucesso!');
    }

    public function edit(User $user)
    {
        $papeis = Papel::where('ativo', true)->get();
        $papeisUsuario = $user->papeis()
            ->wherePivot('ativo', true)
            ->pluck('papels.id')
            ->toArray();

        return view('users.edit', compact('user', 'papeis', 'papeisUsuario'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'data_nascimento' => 'nullable|date',
            'telefone' => 'nullable|string|max:20',
            'papeis' => 'array',
            'papeis.*' => 'exists:papels,id',
        ]);

        DB::transaction(function () use ($validated, $user) {
            $dataToUpdate = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'data_nascimento' => $validated['data_nascimento'] ?? null,
                'telefone' => $validated['telefone'] ?? null,
            ];

            if (!empty($validated['password'])) {
                $dataToUpdate['password'] = Hash::make($validated['password']);
            }

            $user->update($dataToUpdate);

            // Sincronização mantendo o histórico na pivot
            $papeisAtuaisIds = $user->papeis()->wherePivot('ativo', true)->pluck('papels.id')->toArray();
            $novosPapeisIds = $validated['papeis'] ?? [];

            // Desativa papéis desmarcados
            $removerIds = array_diff($papeisAtuaisIds, $novosPapeisIds);
            foreach ($removerIds as $papelId) {
                $user->papeis()->updateExistingPivot($papelId, [
                    'ativo' => false,
                    'data_hora_fim' => now(),
                ]);
            }

            // Adiciona ou reativa novos papéis
            foreach ($novosPapeisIds as $papelId) {
                if (!in_array($papelId, $papeisAtuaisIds)) {
                    $user->papeis()->attach($papelId, [
                        'data_hora_inicio' => now(),
                        'data_hora_fim' => null,
                        'ativo' => true,
                    ]);
                }
            }
        });

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Você não pode excluir sua própria conta.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuário removido com sucesso!');
    }
}
