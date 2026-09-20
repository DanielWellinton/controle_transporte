@extends('layouts.auth')

@section('title', 'Entrar no Sistema')

@section('content')
<div class="text-center mb-6">
    <h2 class="text-base font-bold text-slate-800">Acessar o Sistema</h2>
    <p class="text-xs text-slate-500 mt-1">Informe suas credenciais para continuar.</p>
</div>

<!-- Session Status -->
@if (session('status'))
    <div class="mb-4 font-medium text-xs text-emerald-600 bg-emerald-50 border border-emerald-200 p-3 rounded-xl">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('login') }}" class="space-y-4">
    @csrf

    <!-- Email Address -->
    <div>
        <label for="email" class="block text-xs font-semibold text-slate-600 mb-1">E-mail</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
               class="w-full py-2.5 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition text-slate-800 placeholder-slate-400"
               placeholder="seu.email@exemplo.com">
        @error('email')
            <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- Password -->
    <div>
        <div class="flex items-center justify-between mb-1">
            <label for="password" class="block text-xs font-semibold text-slate-600">Senha</label>
            @if (Route::has('password.request'))
                <a class="text-[11px] font-medium text-indigo-600 hover:text-indigo-700 underline" href="{{ route('password.request') }}">
                    Esqueceu a senha?
                </a>
            @endif
        </div>
        <input id="password" type="password" name="password" required autocomplete="current-password"
               class="w-full py-2.5 px-3 text-xs rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition text-slate-800"
               placeholder="••••••••">
        @error('password')
            <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- Remember Me -->
    <div class="flex items-center">
        <label for="remember_me" class="inline-flex items-center cursor-pointer">
            <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-4 h-4">
            <span class="ms-2 text-xs text-slate-600 font-medium">Lembrar-me</span>
        </label>
    </div>

    <!-- Botão de Ação -->
    <div>
        <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition shadow-md shadow-indigo-500/20 flex items-center justify-center gap-2">
            <span>Entrar</span>
        </button>
    </div>

    @if (Route::has('register'))
        <div class="text-center pt-3 border-t border-slate-100 mt-4">
            <p class="text-xs text-slate-500">
                Não tem uma conta? 
                <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 underline">Cadastre-se</a>
            </p>
        </div>
    @endif
</form>
@endsection