@extends('layouts.admin')

@section('title', 'Cadastrar Veículo')
@section('header_title', 'Cadastrar Veículo')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Card Topo / Cabeçalho -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('veiculos.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
                    &larr; Voltar para Veículos
                </a>
                <h2 class="text-base font-bold text-slate-800">
                    Cadastrar Novo Veículo
                </h2>
            </div>
            <p class="text-xs text-slate-500 mt-1">Preencha as informações para registrar um novo veículo na frota.</p>
        </div>
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

    <!-- Formulário de Cadastro -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('veiculos.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Descrição -->
            <div>
                <label for="descricao" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Descrição</label>
                <input id="descricao" type="text" name="descricao" value="{{ old('descricao') }}" required placeholder="Ex: Ônibus Executivo - Volare V8"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm px-3 py-2.5">
                @error('descricao')
                    <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Placa -->
                <div>
                    <label for="placa" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Placa</label>
                    <input id="placa" type="text" name="placa" value="{{ old('placa') }}" required uppercase placeholder="ABC1D23"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 font-mono font-bold text-sm uppercase focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm px-3 py-2.5">
                    @error('placa')
                        <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Número de Passageiros -->
                <div>
                    <label for="numero_passageiros" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Número de Passageiros</label>
                    <input id="numero_passageiros" type="number" name="numero_passageiros" value="{{ old('numero_passageiros') }}" required min="1" placeholder="Ex: 44"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm px-3 py-2.5">
                    @error('numero_passageiros')
                        <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Ativo -->
            <div class="pt-2">
                <label for="ativo" class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input id="ativo" type="checkbox" name="ativo" value="1" {{ old('ativo', true) ? 'checked' : '' }}
                        class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer w-4 h-4">
                    <span class="text-xs font-bold text-slate-700">Veículo Ativo</span>
                </label>
            </div>

            <!-- Botões de Ação -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('veiculos.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition shadow-md shadow-indigo-500/20 flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Salvar Veículo</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection