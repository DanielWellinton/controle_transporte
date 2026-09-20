@extends('layouts.admin')

@section('title', 'Meu Histórico de Viagens')
@section('header_title', 'Meu Histórico de Viagens')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Card de Cabeçalho / Botão para voltar -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-slate-800">Histórico de Viagens Encerradas</h2>
            <p class="text-xs text-slate-500 mt-1">Consulte o registro das viagens que você realizou ou esteve inscrito.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition shadow-md shadow-indigo-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Voltar às Viagens Ativas</span>
            </a>
        </div>
    </div>

    <!-- Barra de Filtros -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('passageiros.historico') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            
            <!-- Campo Busca (Rota, Motorista ou Veículo) -->
            <div class="sm:col-span-12 lg:col-span-6">
                <label for="search" class="block text-xs font-semibold text-slate-600 mb-1">Buscar</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Buscar por rota, motorista ou veículo..." 
                           class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition text-slate-800 placeholder-slate-400">
                </div>
            </div>

            <!-- Filtro por Data Inicial -->
            <div class="sm:col-span-6 lg:col-span-2">
                <label for="data_inicio" class="block text-xs font-semibold text-slate-600 mb-1">Data Início</label>
                <input type="date" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}" 
                       class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition text-slate-800">
            </div>

            <!-- Filtro por Data Fim -->
            <div class="sm:col-span-6 lg:col-span-2">
                <label for="data_fim" class="block text-xs font-semibold text-slate-600 mb-1">Data Fim</label>
                <input type="date" name="data_fim" id="data_fim" value="{{ request('data_fim') }}" 
                       class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition text-slate-800">
            </div>

            <!-- Botões de Ação do Filtro -->
            <div class="sm:col-span-12 lg:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full py-2 px-3 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-xl shadow-sm transition flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span>Filtrar</span>
                </button>
                
                @if(request()->hasAny(['search', 'data_inicio', 'data_fim']))
                    <a href="{{ route('passageiros.historico') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs rounded-xl transition flex items-center justify-center">
                        Limpar
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Lista de Viagens Encerradas -->
    @if(!isset($historico) || $historico->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center">
            <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-sm font-bold text-slate-800">
                @if(request()->hasAny(['search', 'data_inicio', 'data_fim']))
                    Nenhuma viagem encontrada
                @else
                    Nenhum histórico disponível
                @endif
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                @if(request()->hasAny(['search', 'data_inicio', 'data_fim']))
                    Tente ajustar os filtros aplicados.
                @else
                    Você não possui viagens encerradas registradas até o momento.
                @endif
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($historico as $viagemFinalizada)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between overflow-hidden opacity-95 hover:opacity-100 transition">
                    <div class="p-6">
                        <!-- Badges de Status -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                                Encerrada
                            </span>
                            @if($viagemFinalizada->pivot?->data_hora_saida)
                                <span class="text-[10px] bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                                    Embarque Confirmado
                                </span>
                            @endif
                        </div>

                        <!-- Título da Rota -->
                        <h4 class="text-base font-bold text-slate-800 mb-1">
                            {{ $viagemFinalizada->rota->descricao ?? 'Rota não informada' }}
                        </h4>

                        <!-- Datas de Saída e Chegada -->
                        <div class="space-y-1 mb-4 text-xs font-semibold text-slate-500">
                            <p>Saída: <span class="text-slate-700">{{ $viagemFinalizada->data_hora_saida ? \Carbon\Carbon::parse($viagemFinalizada->data_hora_saida)->format('d/m/Y H:i') : '-' }}</span></p>
                            <p>Chegada: <span class="text-slate-700">{{ $viagemFinalizada->data_hora_chegada ? \Carbon\Carbon::parse($viagemFinalizada->data_hora_chegada)->format('d/m/Y H:i') : '-' }}</span></p>
                        </div>

                        <!-- Detalhes do Transporte -->
                        <div class="space-y-2.5 border-t border-slate-100 pt-4 text-xs text-slate-600">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Motorista: <strong class="text-slate-800">{{ $viagemFinalizada->motorista->usuario->name ?? 'A definir' }}</strong></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                                <span>Veículo: <strong class="text-slate-800">{{ $viagemFinalizada->veiculo->descricao ?? 'N/A' }} ({{ $viagemFinalizada->veiculo->placa ?? '-' }})</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        @if(method_exists($historico, 'hasPages') && $historico->hasPages())
            <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm mt-6">
                {{ $historico->withQueryString()->links() }}
            </div>
        @endif
    @endif

</div>
@endsection