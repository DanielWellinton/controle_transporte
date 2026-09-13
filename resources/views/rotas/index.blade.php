@extends('layouts.admin')

@section('title', 'Gestão de Rotas')
@section('header_title', 'Rotas')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Card Topo / Cabeçalho -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-slate-800">
                Gestão de Rotas
            </h2>
            <p class="text-xs text-slate-500 mt-1">Gerencie as rotas e percursos cadastrados no sistema.</p>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-auto">
            <a href="{{ route('rotas.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition shadow-md shadow-indigo-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Nova Rota</span>
            </a>
        </div>
    </div>

    <!-- Mensagem de Sucesso -->
    @if(session('success'))
        <div class="flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs font-semibold">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- TABELA DE ROTAS -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th scope="col" class="px-6 py-4">Descrição</th>
                        <th scope="col" class="px-6 py-4 text-center">Status</th>
                        <th scope="col" class="px-6 py-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @isset($rotas)
                        @forelse ($rotas as $rota)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-6 py-4 font-semibold text-slate-800">
                                    {{ $rota->descricao }}
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $rota->ativo ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $rota->ativo ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                        {{ $rota->ativo ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('rotas.show', $rota) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition">
                                            Ver
                                        </a>
                                        <a href="{{ route('rotas.edit', $rota) }}" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold rounded-lg transition">
                                            Editar
                                        </a>
                                        <form action="{{ route('rotas.destroy', $rota) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja remover esta rota?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold rounded-lg transition cursor-pointer">
                                                Excluir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-slate-400 font-medium">
                                    Nenhuma rota cadastrada no sistema.
                                </td>
                            </tr>
                        @endforelse
                    @else
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-slate-400 font-medium">
                                Nenhum registro encontrado.
                            </td>
                        </tr>
                    @endisset
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection