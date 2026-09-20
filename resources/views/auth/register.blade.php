@extends('layouts.auth')

@section('title', 'Criar Conta')

@section('content')
<div class="text-center mb-6">
    <h2 class="text-base font-bold text-slate-800">Criar Nova Conta</h2>
    <p class="text-xs text-slate-500 mt-1">Preencha os dados abaixo para se cadastrar.</p>
</div>

<form method="POST" action="{{ route('register') }}" class="space-y-4">
    @csrf

    <!-- Name -->
    <div>
        <label for="name" class="block text-xs font-semibold text-slate-600 mb-1">Nome Completo</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
               class="w-full py-2.5 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition text-slate-800"
               placeholder="Seu nome completo">
        @error('name')
            <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- Email Address -->
    <div>
        <label for="email" class="block text-xs font-semibold text-slate-600 mb-1">E-mail</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
               class="w-full py-2.5 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition text-slate-800"
               placeholder="seu.email@exemplo.com">
        @error('email')
            <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- Data de Nascimento -->
    <div>
        <label for="data_nascimento" class="block text-xs font-semibold text-slate-600 mb-1">Data de Nascimento</label>
        <input id="data_nascimento" type="date" name="data_nascimento" value="{{ old('data_nascimento') }}" required
               class="w-full py-2.5 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition text-slate-800">
        @error('data_nascimento')
            <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- Telefone -->
    <div>
        <label for="telefone" class="block text-xs font-semibold text-slate-600 mb-1">Telefone</label>
        <input id="telefone" type="text" name="telefone" value="{{ old('telefone') }}" required autocomplete="tel" placeholder="(00) 00000-0000"
               class="w-full py-2.5 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition text-slate-800">
        @error('telefone')
            <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- Password -->
    <div>
        <label for="password" class="block text-xs font-semibold text-slate-600 mb-1">Senha</label>
        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••"
               class="w-full py-2.5 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition text-slate-800">
        @error('password')
            <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div>
        <label for="password_confirmation" class="block text-xs font-semibold text-slate-600 mb-1">Confirmar Senha</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••"
               class="w-full py-2.5 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition text-slate-800">
        @error('password_confirmation')
            <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- Botão de Ação -->
    <div class="pt-2">
        <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition shadow-md shadow-indigo-500/20 flex items-center justify-center">
            <span>Registrar</span>
        </button>
    </div>

    <div class="text-center pt-3 border-t border-slate-100 mt-4">
        <p class="text-xs text-slate-500">
            Já possui cadastro? 
            <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 underline">Fazer login</a>
        </p>
    </div>
</form>
@endsection