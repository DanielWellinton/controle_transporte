@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900">Editar Usuário</h1>
        <a href="{{ route('users.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-800">Voltar</a>
    </div>

    <form action="{{ route('users.update', $user) }}" method="POST" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Nome</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('name') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">E-mail</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('email') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Data de Nascimento</label>
                <input type="date" name="data_nascimento" value="{{ old('data_nascimento', optional($user->data_nascimento)->format('Y-m-d')) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Telefone</label>
                <input type="text" name="telefone" value="{{ old('telefone', $user->telefone) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Nova Senha (opcional)</label>
                <input type="password" name="password" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Deixe em branco para não alterar">
                @error('password') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Confirmar Nova Senha</label>
                <input type="password" name="password_confirmation" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>

        <hr class="border-slate-100 my-2">

        <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase mb-2">Papéis / Permissões</label>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($papeis as $papel)
                    <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition">
                        <input type="checkbox" name="papeis[]" value="{{ $papel->id }}" {{ in_array($papel->id, old('papeis', $papeisUsuario)) ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm font-medium text-slate-800">{{ $papel->descricao }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end pt-3">
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-md shadow-indigo-600/20 transition">
                Atualizar Usuário
            </button>
        </div>
    </form>
</div>
@endsection