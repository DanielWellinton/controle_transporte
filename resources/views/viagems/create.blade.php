@extends('layouts.admin')

@section('title', isset($viagem) ? 'Duplicar Viagem' : 'Agendar Viagem')
@section('header_title', isset($viagem) ? 'Duplicar Viagem Existente' : 'Agendar Nova Viagem')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Card de Cabeçalho / Ações -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('viagems.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
                    &larr; Voltar para Viagens
                </a>
            </div>
            <h2 class="text-base font-bold text-slate-800 mt-1">
                {{ isset($viagem) ? 'Duplicar Viagem' : 'Agendar Nova Viagem' }}
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                {{ isset($viagem) ? 'Ajuste os dados conforme necessário para criar a nova viagem com base na selecionada.' : 'Preencha os dados operacionais para cadastrar o manifesto da viagem.' }}
            </p>
        </div>
    </div>

    <!-- Formulário Principal -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('viagems.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Seleção da Rota -->
            <div>
                <label for="rota_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                    Rota <span class="text-rose-500">*</span>
                </label>
                <select id="rota_id" name="rota_id" required
                    class="w-full text-xs font-medium text-slate-800 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-3 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition @error('rota_id') border-rose-500 @enderror">
                    <option value="">-- Selecione uma Rota --</option>
                    @foreach($rotas as $rota)
                        <option value="{{ $rota->id }}" {{ old('rota_id', $viagem->rota_id ?? '') == $rota->id ? 'selected' : '' }}>
                            {{ $rota->descricao }}
                        </option>
                    @endforeach
                </select>
                @error('rota_id')
                    <p class="text-xs font-medium text-rose-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid: Motorista e Veículo -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Motorista -->
                <div>
                    <label for="motorista_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                        Motorista <span class="text-rose-500">*</span>
                    </label>
                    <select id="motorista_id" name="motorista_id" required
                        class="w-full text-xs font-medium text-slate-800 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-3 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition @error('motorista_id') border-rose-500 @enderror">
                        <option value="">-- Selecione um Motorista --</option>
                        @foreach($motoristas as $motorista)
                            <option value="{{ $motorista->id }}" {{ old('motorista_id', $viagem->motorista_id ?? '') == $motorista->id ? 'selected' : '' }}>
                                {{ $motorista->usuario->name ?? $motorista->nome ?? 'Motorista #'.$motorista->id }}
                            </option>
                        @endforeach
                    </select>
                    @error('motorista_id')
                        <p class="text-xs font-medium text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Veículo -->
                <div>
                    <label for="veiculo_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                        Veículo <span class="text-rose-500">*</span>
                    </label>
                    <select id="veiculo_id" name="veiculo_id" required
                        class="w-full text-xs font-medium text-slate-800 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-3 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition @error('veiculo_id') border-rose-500 @enderror">
                        <option value="">-- Selecione um Veículo --</option>
                        @foreach($veiculos as $veiculo)
                            <option value="{{ $veiculo->id }}" {{ old('veiculo_id', $viagem->veiculo_id ?? '') == $veiculo->id ? 'selected' : '' }}>
                                {{ $veiculo->descricao ?? $veiculo->descricao }} ({{ $veiculo->placa }})
                            </option>
                        @endforeach
                    </select>
                    @error('veiculo_id')
                        <p class="text-xs font-medium text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Grid: Saída e Chegada -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Data/Hora Saída -->
                <div>
                    <label for="data_hora_saida" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                        Data e Hora de Saída <span class="text-rose-500">*</span>
                    </label>
                    @php
                        $valSaida = old('data_hora_saida');
                        if (!$valSaida && isset($viagem->data_hora_saida)) {
                            $valSaida = $viagem->data_hora_saida instanceof \Carbon\Carbon
                                ? $viagem->data_hora_saida->format('Y-m-d\TH:i')
                                : \Carbon\Carbon::parse($viagem->data_hora_saida)->format('Y-m-d\TH:i');
                        }
                    @endphp
                    <input type="datetime-local" id="data_hora_saida" name="data_hora_saida" 
                        value="{{ $valSaida }}" required
                        class="w-full text-xs font-medium text-slate-800 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-3 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition @error('data_hora_saida') border-rose-500 @enderror" />
                    @error('data_hora_saida')
                        <p class="text-xs font-medium text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data/Hora Chegada -->
                <div>
                    <label for="data_hora_chegada" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                        Data e Hora de Chegada (Prevista)
                    </label>
                    @php
                        $valChegada = old('data_hora_chegada');
                        if (!$valChegada && isset($viagem->data_hora_chegada)) {
                            $valChegada = $viagem->data_hora_chegada instanceof \Carbon\Carbon
                                ? $viagem->data_hora_chegada->format('Y-m-d\TH:i')
                                : \Carbon\Carbon::parse($viagem->data_hora_chegada)->format('Y-m-d\TH:i');
                        }
                    @endphp
                    <input type="datetime-local" id="data_hora_chegada" name="data_hora_chegada" 
                        value="{{ $valChegada }}"
                        class="w-full text-xs font-medium text-slate-800 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-3 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition @error('data_hora_chegada') border-rose-500 @enderror" />
                    @error('data_hora_chegada')
                        <p class="text-xs font-medium text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status Ativo/Inativo -->
            <div class="pt-2">
                <label class="inline-flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" id="ativo" name="ativo" value="1" 
                        {{ old('ativo', $viagem->ativo ?? true) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/20 focus:ring-2 transition cursor-pointer">
                    <span class="text-xs font-medium text-slate-700 group-hover:text-slate-900 transition">
                        Viagem Ativa (Disponível para embarque / check-in)
                    </span>
                </label>
            </div>

            <!-- Botões de Ação -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('viagems.index') }}" 
                    class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition">
                    Cancelar
                </a>
                <button type="submit" 
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition shadow-md shadow-indigo-500/20">
                    Salvar Viagem
                </button>
            </div>
        </form>
    </div>

</div>
@endsection