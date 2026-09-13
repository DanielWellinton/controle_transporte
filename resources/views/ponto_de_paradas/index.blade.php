@extends('layouts.admin')

@section('title', 'Pontos de Parada')
@section('header_title', 'Pontos de Parada')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Card de Ações Superiores -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-slate-800">Gerenciamento de Pontos de Parada</h2>
            <p class="text-xs text-slate-500">Visualize, edite ou cadastre novos pontos para as rotas do sistema.</p>
        </div>
        <a href="{{ route('ponto_de_paradas.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition shadow-md shadow-indigo-500/20 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Novo Ponto</span>
        </a>
    </div>

    <!-- Tabela de Pontos -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Descrição</th>
                        <th class="py-3.5 px-6 text-center">Latitude</th>
                        <th class="py-3.5 px-6 text-center">Longitude</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                        <th class="py-3.5 px-6 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse ($pontoDeParada as $ponto)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-semibold text-slate-800">
                                {{ $ponto->descricao }}
                            </td>
                            <td class="py-4 px-6 text-center font-mono text-slate-600">
                                {{ $ponto->latitude }}
                            </td>
                            <td class="py-4 px-6 text-center font-mono text-slate-600">
                                {{ $ponto->longitude }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $ponto->ativo ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $ponto->ativo ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    {{ $ponto->ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('ponto_de_paradas.show', $ponto) }}" class="px-3 py-1.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 font-semibold transition">
                                        Ver
                                    </a>
                                    <a href="{{ route('ponto_de_paradas.edit', $ponto) }}" class="px-3 py-1.5 rounded-xl bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-semibold transition">
                                        Editar
                                    </a>
                                    <form action="{{ route('ponto_de_paradas.destroy', $ponto) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja remover este ponto de parada?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 font-semibold transition cursor-pointer">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-700">Nenhum ponto cadastrado</p>
                                <p class="text-xs text-slate-400 mt-1">Cadastre novos pontos de parada para vinculá-los às rotas.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection