@extends('layouts.admin')

@section('title', 'Motoristas')
@section('header_title', 'Gestão de Motoristas')

@section('content')
<div class="space-y-6">

    <!-- Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Equipe de Motoristas</h2>
            <p class="text-xs text-slate-500">Gerencie as credenciais, CNHs e disponibilidades.</p>
        </div>
        <a href="{{ route('motoristas.create') }}" 
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-semibold text-xs shadow-md shadow-indigo-500/20 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Novo Motorista
        </a>
    </div>

    <!-- 1. VISÃO MOBILE (Cards dinâmicos para telas abaixo de SM) -->
    <div class="grid grid-cols-1 gap-4 sm:hidden">
        @forelse ($motoristas as $motorista)
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600 text-sm">
                            {{ substr($motorista->usuario->name ?? 'M', 0, 2) }}
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 text-sm">{{ $motorista->usuario->name ?? 'N/A' }}</p>
                            <p class="text-xs text-slate-500">CNH: {{ $motorista->cnh }}</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full uppercase tracking-wider {{ $motorista->ativo ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                        {{ $motorista->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>

                <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Validade CNH:</span>
                    <span class="font-medium text-slate-700">{{ \Carbon\Carbon::parse($motorista->data_validade_cnh)->format('d/m/Y') }}</span>
                </div>

                <!-- Botões de Ação Mobile -->
                <div class="pt-2 flex items-center justify-end gap-2">
                    <a href="{{ route('motoristas.show', $motorista) }}" class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition text-xs font-semibold">
                        Ver detalhes
                    </a>
                    <a href="{{ route('motoristas.edit', $motorista) }}" class="p-2 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition text-xs font-semibold">
                        Editar
                    </a>
                    <form action="{{ route('motoristas.destroy', $motorista) }}" method="POST" onsubmit="return confirm('Deseja excluir este registro?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition text-xs font-semibold">
                            Excluir
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white p-8 rounded-2xl border border-slate-200/80 text-center text-slate-500 text-xs">
                Nenhum motorista cadastrado.
            </div>
        @endforelse
    </div>

    <!-- 2. VISÃO DESKTOP (Tabela fluida para SM ou superior) -->
    <div class="hidden sm:block bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200/80">
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Motorista</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Documento CNH</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Validade CNH</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($motoristas as $motorista)
                        <tr class="hover:bg-slate-50/60 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 font-bold flex items-center justify-center text-xs group-hover:bg-indigo-100 group-hover:text-indigo-600 transition">
                                        {{ substr($motorista->usuario->name ?? 'M', 0, 2) }}
                                    </div>
                                    <span class="font-semibold text-slate-800">{{ $motorista->usuario->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-600 font-mono text-xs">
                                {{ $motorista->cnh }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-600 text-xs">
                                {{ \Carbon\Carbon::parse($motorista->data_validade_cnh)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium {{ $motorista->ativo ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $motorista->ativo ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    {{ $motorista->ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('motoristas.show', $motorista) }}" title="Visualizar" class="p-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('motoristas.edit', $motorista) }}" title="Editar" class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('motoristas.destroy', $motorista) }}" method="POST" class="inline-block" onsubmit="return confirm('Excluir este motorista?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Excluir" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs">
                                <svg class="w-10 h-10 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                Nenhum motorista encontrado na base de dados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection