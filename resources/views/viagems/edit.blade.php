@extends('layouts.admin')

@section('title', 'Editar Viagem')
@section('header_title', 'Editar Viagem')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Card Topo / Cabeçalho -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('viagems.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
                    &larr; Voltar para Viagens
                </a>
                <h2 class="text-base font-bold text-slate-800">
                    Editar Viagem #{{ $viagem->id }}
                </h2>
            </div>
            <p class="text-xs text-slate-500 mt-1">Atualize a rota, alocação de motorista, veículo e horários previstos.</p>
        </div>

        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $viagem->ativo ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }} self-start sm:self-auto">
            <span class="w-1.5 h-1.5 rounded-full {{ $viagem->ativo ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
            {{ $viagem->ativo ? 'Viagem Ativa' : 'Viagem Inativa' }}
        </span>
    </div>

    <!-- Erros de Validação Geral -->
    @if ($errors->any())
        <div class="p-4 rounded-xl text-xs font-semibold text-rose-800 bg-rose-50 border border-rose-200 space-y-1">
            <p class="font-bold text-sm">Atenção:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulário de Edição -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('viagems.update', $viagem) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Rota -->
            <div>
                <label for="rota_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Rota</label>
                <select id="rota_id" name="rota_id" required
                    class="w-full rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm px-3 py-2.5">
                    <option value="">Selecione uma rota</option>
                    @foreach($rotas as $rota)
                        <option value="{{ $rota->id }}" {{ old('rota_id', $viagem->rota_id) == $rota->id ? 'selected' : '' }}>
                            {{ $rota->descricao }}
                        </option>
                    @endforeach
                </select>
                @error('rota_id')
                    <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Motorista -->
                <div>
                    <label for="motorista_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Motorista</label>
                    <select id="motorista_id" name="motorista_id" required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm px-3 py-2.5">
                        <option value="">Selecione o motorista</option>
                        @foreach($motoristas as $motorista)
                            <option value="{{ $motorista->id }}" {{ old('motorista_id', $viagem->motorista_id) == $motorista->id ? 'selected' : '' }}>
                                {{ $motorista->usuario->name ?? $motorista->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('motorista_id')
                        <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Veículo -->
                <div>
                    <label for="veiculo_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Veículo</label>
                    <select id="veiculo_id" name="veiculo_id" required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm px-3 py-2.5">
                        <option value="">Selecione o veículo</option>
                        @foreach($veiculos as $veiculo)
                            <option value="{{ $veiculo->id }}" {{ old('veiculo_id', $viagem->veiculo_id) == $veiculo->id ? 'selected' : '' }}>
                                {{ $veiculo->descricao ?? $veiculo->modelo }} ({{ $veiculo->placa }})
                            </option>
                        @endforeach
                    </select>
                    @error('veiculo_id')
                        <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Data / Hora de Saída -->
                <div>
                    <label for="data_hora_saida" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Data / Hora de Saída</label>
                    <input id="data_hora_saida" type="datetime-local" name="data_hora_saida"
                        value="{{ old('data_hora_saida', $viagem->data_hora_saida ? $viagem->data_hora_saida->format('Y-m-d\TH:i') : '') }}" required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm px-3 py-2.5">
                    @error('data_hora_saida')
                        <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data / Hora de Chegada -->
                <div>
                    <label for="data_hora_chegada" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Data / Hora de Chegada</label>
                    <input id="data_hora_chegada" type="datetime-local" name="data_hora_chegada"
                        value="{{ old('data_hora_chegada', $viagem->data_hora_chegada ? $viagem->data_hora_chegada->format('Y-m-d\TH:i') : '') }}"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm px-3 py-2.5">
                    @error('data_hora_chegada')
                        <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Ativo -->
            <div class="pt-2">
                <label for="ativo" class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input id="ativo" type="checkbox" name="ativo" value="1" {{ old('ativo', $viagem->ativo) ? 'checked' : '' }}
                        class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer w-4 h-4">
                    <span class="text-xs font-bold text-slate-700">Viagem Ativa</span>
                </label>
            </div>

            <!-- Botões de Ação -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('viagems.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition shadow-md shadow-indigo-500/20 flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Atualizar Viagem</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection