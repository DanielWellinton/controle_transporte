@extends('layouts.admin')

@section('title', 'Painel do Motorista')
@section('header_title', 'Painel do Motorista')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <!-- Mensagens de Feedback -->
    @if (session('success'))
        <div class="p-4 rounded-xl text-sm font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 rounded-xl text-sm font-semibold text-rose-800 bg-rose-50 border border-rose-200">
            {{ session('error') }}
        </div>
    @endif

    <!-- Card de Cabeçalho com Acesso ao Histórico -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-slate-800">Sua Escala de Viagens</h2>
            <p class="text-xs text-slate-500 mt-1">Gerencie e acompanhe as rotas atribuídas ao seu veículo.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('portal_motorista.historico') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-xl transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Ver Histórico de Viagens</span>
            </a>
        </div>
    </div>

    <!-- SEÇÃO: VIAGENS ATIVAS / AGENDADAS -->
    <div class="space-y-4">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Viagens Ativas / Agendadas</h3>
        </div>

        @if($viagens->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Nenhuma viagem agendada</h3>
                <p class="text-xs text-slate-500 mt-1">Você não possui rotas escaladas para execução no momento.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($viagens as $viagem)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between overflow-hidden">
                        <div class="p-6">
                            <!-- Badges de Status -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Ativa
                                </span>
                                <span class="text-xs font-bold text-slate-500">
                                    {{ $viagem->passageiros->count() }} / {{ $viagem->veiculo->numero_passageiros ?? 0 }} passageiros
                                </span>
                            </div>

                            <!-- Título da Rota -->
                            <h4 class="text-base font-bold text-slate-800 mb-1">
                                {{ $viagem->rota->descricao ?? $viagem->rota->nome ?? 'Rota não informada' }}
                            </h4>

                            <!-- Horário de Saída -->
                            <p class="text-xs font-semibold text-slate-500 mb-4">
                                Saída: <span class="text-slate-700">{{ $viagem->data_hora_saida ? \Carbon\Carbon::parse($viagem->data_hora_saida)->format('d/m/Y H:i') : '-' }}</span>
                            </p>

                            <!-- Detalhes do Veículo -->
                            <div class="space-y-2.5 border-t border-slate-100 pt-4 text-xs text-slate-600">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                    <span>Veículo: <strong class="text-slate-800">{{ $viagem->veiculo->descricao ?? 'N/A' }} ({{ $viagem->veiculo->placa ?? '-' }})</strong></span>
                                </div>
                            </div>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="p-6 pt-0 space-y-2.5 mt-auto">
                            <a href="{{ route('portal_motorista.show', $viagem->id) }}"
                                class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition shadow-md shadow-indigo-500/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 43a8 8 0 100-16 8 8 0 000 16zM12 11a9 9 0 100-18 9 9 0 000 18z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>Lista de Passageiros</span>
                            </a>

                            <div class="grid grid-cols-2 gap-2">
                                <form action="{{ route('portal_motorista.iniciar', $viagem->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex justify-center items-center gap-1 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl transition">
                                        <span>Iniciar</span>
                                    </button>
                                </form>

                                <form action="{{ route('portal_motorista.finalizar', $viagem->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex justify-center items-center gap-1 px-3 py-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs rounded-xl transition">
                                        <span>Finalizar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection