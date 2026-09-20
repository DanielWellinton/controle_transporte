@extends('layouts.admin')

@section('title', 'Lista de Embarque - Viagem #' . $viagem->id)
@section('header_title', 'Lista de Embarque')

@section('content')
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
            <h2 class="text-lg font-bold text-slate-800">{{ $viagem->rota->descricao ?? 'Rota não informada' }}</h2>
            
            <div class="flex flex-wrap items-center gap-x-6 gap-y-1 mt-2 text-xs font-medium text-slate-500">
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-400">Veículo:</span>
                    <strong class="text-slate-700 font-semibold">{{ $viagem->veiculo->placa ?? '-' }}</strong>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-400">Saída:</span>
                    <strong class="text-slate-700 font-semibold">{{ optional($viagem->data_hora_saida)->format('d/m/Y H:i') ?? \Carbon\Carbon::parse($viagem->data_hora_saida)->format('d/m/Y H:i') }}</strong>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-400">Lotação:</span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                        {{ $viagem->passageiros->count() }} Passageiros
                    </span>
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

    <!-- Tabela de Passageiros Confirmados -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800">Passageiros Confirmados</h3>
            <span class="text-xs bg-slate-100 text-slate-600 font-bold px-2.5 py-1 rounded-full">
                Total: {{ $viagem->passageiros->count() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="p-4">Nome</th>
                        <th class="p-4">Telefone</th>
                        <th class="p-4">Ponto de Subida</th>
                        <th class="p-4">Ponto de Descida</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($viagem->passageiros as $passageiro)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 font-bold text-slate-800">{{ $passageiro->name }}</td>
                            <td class="p-4 text-slate-500">{{ $passageiro->telefone ?? 'Não informado' }}</td>
                            <td class="p-4">
                                {{ \App\Models\PontoDeParada::find($passageiro->pivot->ponto_de_parada_saida_id)?->descricao ?? 'Padrão' }}
                            </td>
                            <td class="p-4">
                                {{ \App\Models\PontoDeParada::find($passageiro->pivot->ponto_de_parada_chegada_id)?->descricao ?? 'Padrão' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400">
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