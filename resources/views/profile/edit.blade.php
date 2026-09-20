@extends('layouts.admin')

@section('title', 'Meu Perfil')
@section('header_title', 'Configurações da Conta')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    
    <!-- Grid Responsivo Profissional: 1 coluna no mobile/tablet, 12 colunas no desktop -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Coluna Esquerda: Informações Pessoais e Senha (Ocupa 7 colunas no Desktop) -->
        <div class="lg:col-span-7 space-y-8">
            
            <!-- Card: Informações do Perfil -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm">
                <div class="w-full">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Card: Atualizar Senha -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm">
                <div class="w-full">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

        </div>

        <!-- Coluna Direita: Segurança, Exclusão e Dicas (Ocupa 5 colunas no Desktop) -->
        <div class="lg:col-span-5 space-y-8">
            
            <!-- Card Informativo / Boas Práticas -->
            <div class="bg-gradient-to-br from-indigo-50/70 to-blue-50/30 border border-indigo-100 p-6 sm:p-8 rounded-2xl">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold shadow-md shadow-indigo-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Segurança da Conta</h3>
                        <p class="text-xs text-slate-500">Mantenha seus dados sempre atualizados.</p>
                    </div>
                </div>
                <p class="text-xs leading-relaxed text-slate-600">
                    Seu e-mail é utilizado para notificações importantes do sistema e recuperação de acesso. Certifique-se de utilizar uma senha forte contendo letras, números e caracteres especiais.
                </p>
            </div>

            <!-- Card: Zona de Perigo (Deletar Conta) -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-rose-200/80 shadow-sm">
                <div class="w-full">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>

    </div>

</div>
@endsection