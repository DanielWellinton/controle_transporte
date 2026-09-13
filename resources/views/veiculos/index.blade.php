@extends('layouts.admin')

@section('title', 'Veículos')
@section('header_title', 'Gerenciamento de Veículos')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Card Topo / Cabeçalho da Seção -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-slate-800">
                Lista de Veículos
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Gerencie os veículos cadastrados na frota, capacidades e disponibilidades.</p>
        </div>

        <a href="{{ route('veiculos.create') }}"
            class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition shadow-md shadow-indigo-500/20 flex items-center justify-center gap-2 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Novo Veículo</span>
        </a>
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

    <!-- Tabela de Veículos -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th scope="col" class="px-6 py-4">Descrição</th>
                        <th scope="col" class="px-6 py-4">Placa</th>
                        <th scope="col" class="px-6 py-4 text-center">Passageiros</th>
                        <th scope="col" class="px-6 py-4 text-center">Status</th>
                        <th scope="col" class="px-6 py-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse ($veiculos as $veiculo)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-slate-800">
                                {{ $veiculo->descricao }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-mono font-bold text-slate-600 uppercase">
                                {{ $veiculo->placa }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-slate-600 font-medium">
                                {{ $veiculo->numero_passageiros }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($veiculo->ativo)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Ativo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Inativo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('veiculos.show', $veiculo) }}"
                                        class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition">
                                        Ver
                                    </a>
                                    <a href="{{ route('veiculos.edit', $veiculo) }}"
                                        class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold rounded-lg transition">
                                        Editar
                                    </a>
                                    <form action="{{ route('veiculos.destroy', $veiculo) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja remover este veículo?');">
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
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium">
                                Nenhum veículo cadastrado no sistema.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($veiculos, 'hasPages') && $veiculos->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $veiculos->links() }}
            </div>
        @endif
    </div>

</div>
@endsection