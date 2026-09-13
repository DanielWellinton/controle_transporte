@extends('layouts.admin')

@section('title', 'Detalhes do Motorista')
@section('header_title', 'Detalhes do Motorista')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Card Principal de Visualização -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Header do Card -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-sm border border-indigo-200">
                    {{ substr($motorista->usuario->name ?? 'M', 0, 2) }}
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">{{ $motorista->usuario->name ?? 'N/A' }}</h3>
                    <p class="text-xs text-slate-500">Informações detalhadas do cadastro</p>
                </div>
            </div>
            <a href="{{ route('motoristas.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
                &larr; Voltar para a lista
            </a>
        </div>

        <!-- Grid de Informações -->
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                
                <!-- Nome -->
                <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nome Completo</p>
                    <p class="text-sm font-semibold text-slate-800">{{ $motorista->usuario->name ?? 'N/A' }}</p>
                </div>

                <!-- E-mail -->
                <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">E-mail</p>
                    <p class="text-sm font-semibold text-slate-800 truncate">{{ $motorista->usuario->email ?? 'N/A' }}</p>
                </div>

                <!-- CNH -->
                <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Número da CNH</p>
                    <p class="text-sm font-semibold text-slate-800 font-mono">{{ $motorista->cnh }}</p>
                </div>

                <!-- Validade CNH -->
                <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Validade da CNH</p>
                    <p class="text-sm font-semibold text-slate-800">
                        {{ \Carbon\Carbon::parse($motorista->data_validade_cnh)->format('d/m/Y') }}
                    </p>
                </div>

            </div>

            <!-- Status -->
            <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Status da Conta</p>
                    <p class="text-xs text-slate-500">Indica se o motorista está apto para atribuição de viagens</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium {{ $motorista->ativo ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $motorista->ativo ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                    {{ $motorista->ativo ? 'Ativo' : 'Inativo' }}
                </span>
            </div>

            <!-- Footer com Ações -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('motoristas.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs transition">
                    Voltar
                </a>
                <a href="{{ route('motoristas.edit', $motorista) }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition shadow-md shadow-indigo-500/20">
                    Editar Motorista
                </a>
            </div>

        </div>
    </div>

</div>
@endsection