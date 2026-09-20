@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Cabeçalho -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Gestão de Usuários</h1>
            <p class="text-sm text-slate-500 mt-1">Gerencie os usuários e os papéis atribuídos no sistema.</p>
        </div>
        <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-md shadow-indigo-600/20 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Novo Usuário</span>
        </a>
    </div>

    <!-- Barra de Filtros -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            
            <!-- Campo Busca (Nome ou E-mail) -->
            <div class="sm:col-span-5">
                <label for="search" class="block text-xs font-semibold text-slate-600 mb-1">Buscar por Nome / E-mail</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Digite o nome ou e-mail..." 
                           class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition text-slate-800 placeholder-slate-400">
                </div>
            </div>

            <!-- Filtro por Papel / Função -->
            <div class="sm:col-span-4">
                <label for="papel_id" class="block text-xs font-semibold text-slate-600 mb-1">Papel / Função</label>
                <select name="papel_id" id="papel_id" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition text-slate-800">
                    <option value="">Todos os Papéis</option>
                    @foreach($papeis as $papel)
                        <option value="{{ $papel->id }}" {{ request('papel_id') == $papel->id ? 'selected' : '' }}>
                            {{ $papel->descricao ?? $papel->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Botões de Ação do Filtro -->
            <div class="sm:col-span-3 flex items-center gap-2">
                <button type="submit" class="w-full py-2 px-3 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-xl shadow-sm transition flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Filtrar</span>
                </button>
                
                @if(request()->hasAny(['search', 'papel_id']))
                    <a href="{{ route('users.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs rounded-xl transition flex items-center justify-center">
                        Limpar
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Tabela de Usuários -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/50 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-4">Nome</th>
                        <th class="py-3 px-4">E-mail</th>
                        <th class="py-3 px-4">Telefone</th>
                        <th class="py-3 px-4">Papéis Ativos</th>
                        <th class="py-3 px-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-4 font-semibold text-slate-900">{{ $user->name }}</td>
                            <td class="py-3.5 px-4 text-slate-600">{{ $user->email }}</td>
                            <td class="py-3.5 px-4 text-slate-600">{{ $user->telefone ?? '-' }}</td>
                            <td class="py-3.5 px-4">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->papeis->where('pivot.ativo', true) as $papel)
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200/60">
                                            {{ $papel->descricao }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-slate-400 font-italic">Sem papéis</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('users.edit', $user) }}" class="p-1.5 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-slate-100 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Deseja realmente remover este usuário?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-slate-100 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                @if(request()->hasAny(['search', 'papel_id']))
                                    Nenhum usuário encontrado com os filtros aplicados.
                                @else
                                    Nenhum usuário cadastrado.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação com preservação de querystring -->
        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection