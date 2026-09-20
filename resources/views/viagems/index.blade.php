@extends('layouts.admin')

@section('title', 'Viagens')
@section('header_title', 'Gerenciamento de Viagens')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Card Topo / Cabeçalho da Seção -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-slate-800">
                Lista de Viagens
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Acompanhe e gerencie a programação de viagens, motoristas e veículos alocados.</p>
        </div>

        <a href="{{ route('viagems.create') }}"
            class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition shadow-md shadow-indigo-500/20 flex items-center justify-center gap-2 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Nova Viagem</span>
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

    <!-- Tabela de Viagens -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if($viagens->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th scope="col" class="px-6 py-4">Rota</th>
                        <th scope="col" class="px-6 py-4">Motorista / Veículo</th>
                        <th scope="col" class="px-6 py-4 text-center">Saída</th>
                        <th scope="col" class="px-6 py-4 text-center">Chegada</th>
                        <th scope="col" class="px-6 py-4 text-center">Status</th>
                        <th scope="col" class="px-6 py-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @foreach ($viagens as $viagem)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $viagem->rota->descricao ?? 'Sem Rota' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800">
                                    {{ $viagem->motorista->usuario->name ?? 'N/A' }}
                                </div>
                                @if($viagem->veiculo)
                                    <div class="text-[11px] text-slate-400 font-medium">
                                        {{ $viagem->veiculo->descricao }} <span class="font-mono font-bold text-slate-500 uppercase">({{ $viagem->veiculo->placa }})</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-slate-600 font-medium">
                                {{ $viagem->data_hora_saida ? $viagem->data_hora_saida->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center text-slate-600 font-medium">
                                {{ $viagem->data_hora_chegada ? $viagem->data_hora_chegada->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($viagem->ativo)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Ativa
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Inativa
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-medium">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('viagems.show', $viagem) }}"
                                        class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition"
                                        title="Ver detalhes">
                                        Ver
                                    </a>

                                    <a href="{{ route('viagems.duplicate', $viagem) }}"
                                        class="px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold rounded-lg transition inline-flex items-center gap-1"
                                        title="Duplicar esta viagem">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        <span>Duplicar</span>
                                    </a>

                                    <a href="{{ route('viagems.edit', $viagem) }}"
                                        class="px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold rounded-lg transition"
                                        title="Editar">
                                        Editar
                                    </a>

                                    <form action="{{ route('viagems.destroy', $viagem) }}" method="POST" class="inline-block" onsubmit="return confirm('Deseja excluir esta viagem?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold rounded-lg transition cursor-pointer" title="Excluir">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(method_exists($viagens, 'hasPages') && $viagens->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $viagens->links() }}
            </div>
        @endif
        @else
        <div class="p-12 text-center text-slate-400 font-medium text-xs">
            Nenhuma viagem cadastrada no sistema.
        </div>
        @endif
    </div>

</div>
@endsection