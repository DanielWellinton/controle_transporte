@extends('layouts.admin')

@section('title', 'Lista de Embarque - Viagem #' . $viagem->id)
@section('header_title', 'Lista de Embarque')

@section('content')
@php
    // Cálculo dos totais de embarque
    $totalAgendados = $viagem->passageiros->count();
    $totalEmbarcados = $viagem->passageiros->filter(fn($p) => !is_null($p->pivot->data_hora_saida))->count();
    $diferenca = $totalAgendados - $totalEmbarcados;
@endphp

<div class="max-w-7xl mx-auto space-y-6">

    <!-- Card Principal: Detalhes da Viagem e Ações -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ route('portal_motorista.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 transition mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Voltar para o painel</span>
            </a>
            <h2 class="text-lg font-bold text-slate-800">{{ $viagem->rota->descricao ?? $viagem->rota->nome ?? 'Rota não informada' }}</h2>
            
            <div class="flex flex-wrap items-center gap-x-6 gap-y-1 mt-2 text-xs font-medium text-slate-500">
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-400">Veículo:</span>
                    <strong class="text-slate-700 font-semibold">{{ $viagem->veiculo->placa ?? '-' }}</strong>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-400">Saída da Viagem:</span>
                    <strong class="text-slate-700 font-semibold">
                        @if($viagem->data_hora_saida)
                            {{ $viagem->data_hora_saida instanceof \Carbon\Carbon ? $viagem->data_hora_saida->format('d/m/Y H:i') : \Carbon\Carbon::parse($viagem->data_hora_saida)->format('d/m/Y H:i') }}
                        @else
                            <span class="text-slate-400 font-normal italic">Não definida</span>
                        @endif
                    </strong>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('portal_motorista.imprimir', $viagem->id) }}" target="_blank"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Imprimir Lista</span>
            </a>
        </div>
    </div>

    <!-- Cards de Resumo e Comparativo de Embarque -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Agendados</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalAgendados }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Embarcaram</p>
                <p class="text-2xl font-bold text-emerald-700 mt-1">{{ $totalEmbarcados }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Pendentes / Ausentes</p>
                <p class="text-2xl font-bold text-amber-700 mt-1">{{ $diferenca }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Tabela de Passageiros Confirmados -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800">Passageiros Confirmados</h3>
            <span class="text-xs bg-emerald-100 text-emerald-800 font-bold px-2.5 py-1 rounded-full">
                Embarcados: {{ $totalEmbarcados }} / {{ $totalAgendados }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="p-4">Passageiro</th>
                        <th class="p-4">Subida (Embarque)</th>
                        <th class="p-4">Hora Subida</th>
                        <th class="p-4">Descida (Desembarque)</th>
                        <th class="p-4">Hora Descida</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($viagem->passageiros as $passageiro)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4">
                                <div class="font-bold text-slate-800">{{ $passageiro->name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $passageiro->telefone ?? 'Sem telefone' }}</div>
                            </td>
                            
                            <!-- Ponto de Embarque -->
                            <td class="p-4 font-medium text-slate-700">
                                {{ \App\Models\PontoDeParada::find($passageiro->pivot->ponto_de_parada_saida_id)?->descricao ?? 'Padrão' }}
                            </td>
                            
                            <!-- Hora de Embarque -->
                            <td class="p-4">
                                @if($passageiro->pivot->data_hora_saida)
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($passageiro->pivot->data_hora_saida)->format('H:i') }}
                                        <span class="text-[10px] opacity-75">({{ \Carbon\Carbon::parse($passageiro->pivot->data_hora_saida)->format('d/m') }})</span>
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded-lg text-[11px] font-medium bg-slate-100 text-slate-400 border border-slate-200">
                                        Não embarcou
                                    </span>
                                @endif
                            </td>

                            <!-- Ponto de Desembarque -->
                            <td class="p-4 font-medium text-slate-700">
                                {{ \App\Models\PontoDeParada::find($passageiro->pivot->ponto_de_parada_chegada_id)?->descricao ?? 'Padrão' }}
                            </td>

                            <!-- Hora de Desembarque -->
                            <td class="p-4">
                                @if($passageiro->pivot->data_hora_chegada)
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 inline-flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($passageiro->pivot->data_hora_chegada)->format('H:i') }}
                                        <span class="text-[10px] opacity-75">({{ \Carbon\Carbon::parse($passageiro->pivot->data_hora_chegada)->format('d/m') }})</span>
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">Pendente</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">
                                Nenhum passageiro agendado para esta viagem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection