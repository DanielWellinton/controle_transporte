@extends('layouts.admin')

@section('title', 'Cadastrar Motorista')
@section('header_title', 'Cadastrar Motorista')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Card Principal do Form -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Header do Form -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-800">Novo Cadastro</h3>
                <p class="text-xs text-slate-500">Preencha as informações do motorista e vincule a um usuário do sistema.</p>
            </div>
            <a href="{{ route('motoristas.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
                &larr; Voltar para a lista
            </a>
        </div>

        <form action="{{ route('motoristas.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <!-- Usuário -->
            <div>
                <x-input-label for="usuario_id" :value="__('Usuário do Sistema')" class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" />
                <div class="relative">
                    <select id="usuario_id" name="usuario_id" class="w-full rounded-xl border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm">
                        <option value="">Selecione um usuário...</option>
                        @foreach ($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" {{ old('usuario_id') == $usuario->id ? 'selected' : '' }}>
                                {{ $usuario->name }} ({{ $usuario->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <x-input-error :messages="$errors->get('usuario_id')" class="mt-1.5 text-xs text-rose-600" />
            </div>

            <!-- Grid com CNH e Validade (Responsivo: 1 col no mobile, 2 no PC) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                
                <!-- CNH -->
                <div>
                    <x-input-label for="cnh" :value="__('Número da CNH')" class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" />
                    <x-text-input 
                        id="cnh" 
                        class="w-full rounded-xl border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm font-mono" 
                        type="text" 
                        name="cnh" 
                        :value="old('cnh')" 
                        required 
                    />
                    <x-input-error :messages="$errors->get('cnh')" class="mt-1.5 text-xs text-rose-600" />
                </div>

                <!-- Validade CNH -->
                <div>
                    <x-input-label for="data_validade_cnh" :value="__('Data de Validade da CNH')" class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" />
                    <x-text-input 
                        id="data_validade_cnh" 
                        class="w-full rounded-xl border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm" 
                        type="date" 
                        name="data_validade_cnh" 
                        :value="old('data_validade_cnh')" 
                        required 
                    />
                    <x-input-error :messages="$errors->get('data_validade_cnh')" class="mt-1.5 text-xs text-rose-600" />
                </div>

            </div>

            <!-- Toggle Ativo / Inativo -->
            <div class="pt-2">
                <label for="ativo" class="inline-flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-200/80 cursor-pointer hover:bg-slate-100/80 transition w-full sm:w-auto">
                    <input 
                        id="ativo" 
                        type="checkbox" 
                        class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 shadow-sm" 
                        name="ativo" 
                        value="1" 
                        {{ old('ativo', true) ? 'checked' : '' }}
                    >
                    <span class="text-xs font-semibold text-slate-700">{{ __('Motorista Ativo no Sistema') }}</span>
                </label>
            </div>

            <!-- Footer do Formulário com Botões de Ação -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('motoristas.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs transition">
                    Cancelar
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition shadow-md shadow-indigo-500/20">
                    {{ __('Salvar Motorista') }}
                </button>
            </div>

        </form>
    </div>

</div>
@endsection