<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Transporte Escolar') }} - Gestão e Controle de Viagens</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Header / Navbar Pública -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Logo / Identidade do Sistema -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-md shadow-indigo-500/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <div>
                    <span class="text-base font-extrabold text-slate-900 tracking-tight block">TranspEscolar</span>
                    <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest block -mt-1">Gestão Municipal</span>
                </div>
            </div>

            <!-- Botões de Ação do Topo -->
            <div class="flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('viagems.index') }}" 
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition shadow-md shadow-indigo-500/20">
                            Acessar Painel
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                            class="px-4 py-2.5 text-slate-700 hover:text-indigo-600 font-semibold text-xs transition">
                            Entrar
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition shadow-md shadow-indigo-500/20">
                                Cadastrar-se
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="relative overflow-hidden py-16 sm:py-24 bg-gradient-to-b from-slate-100/70 to-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center max-w-3xl mx-auto space-y-6">
                    <span class="px-3.5 py-1.5 text-xs font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200/60 rounded-full inline-block">
                        Plataforma Oficial de Transporte
                    </span>
                    
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Transporte Escolar Digital, Seguro e Organizado
                    </h1>
                    
                    <p class="text-base sm:text-lg text-slate-600 leading-relaxed">
                        Consulte rotas, selecione seus pontos de embarque e desembarque, e garanta seu acesso ao transporte utilizando a validação por QR Code.
                    </p>

                    <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ route('login') }}" 
                            class="w-full sm:w-auto px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition shadow-lg shadow-indigo-500/30 text-center">
                            Acessar Minha Conta
                        </a>
                        <a href="#como-funciona" 
                            class="w-full sm:w-auto px-8 py-3.5 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 transition text-center">
                            Como Funciona
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Recursos e Serviços Prestados -->
        <section id="servicos" class="py-16 bg-white border-y border-slate-200/60">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <h2 class="text-2xl font-bold text-slate-900">Serviços e Benefícios</h2>
                    <p class="text-xs text-slate-500 mt-2">Tecnologia desenvolvida para oferecer transparência e segurança para alunos e gestores.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Serviço 1 -->
                    <div class="p-8 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4 hover:border-indigo-200 transition">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Mapeamento de Rotas</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Acompanhe os itinerários cadastrados, com detalhes dos horários previstos de saída e chegada de cada veículo.
                        </p>
                    </div>

                    <!-- Serviço 2 -->
                    <div class="p-8 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4 hover:border-indigo-200 transition">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Pontos de Parada Fixos</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            O passageiro seleciona exatamente onde irá embarcar e desembarcar, otimizando o trajeto do motorista.
                        </p>
                    </div>

                    <!-- Serviço 3 -->
                    <div class="p-8 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4 hover:border-indigo-200 transition">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Embarque com QR Code</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Confirmação rápida e digital na entrada do ônibus através do leitor de QR Code do aplicativo do motorista/fiscal.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Passo a Passo (Como Funciona) -->
        <section id="como-funciona" class="py-16 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <h2 class="text-2xl font-bold text-slate-900">Como funciona para o estudante?</h2>
                    <p class="text-xs text-slate-500 mt-2">Siga estes 3 passos simples para garantir seu lugar na viagem.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Passo 1 -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative">
                        <span class="text-3xl font-black text-indigo-100 absolute top-4 right-4">01</span>
                        <h4 class="text-sm font-bold text-slate-900 mb-2">Cadastre-se no Sistema</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Crie sua conta informando seus dados pessoais para ter acesso à lista de viagens ativas.
                        </p>
                    </div>

                    <!-- Passo 2 -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative">
                        <span class="text-3xl font-black text-indigo-100 absolute top-4 right-4">02</span>
                        <h4 class="text-sm font-bold text-slate-900 mb-2">Escolha a Viagem e os Pontos</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Selecione a viagem desejada e defina quais serão seus pontos de subida e descida.
                        </p>
                    </div>

                    <!-- Passo 3 -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative">
                        <span class="text-3xl font-black text-indigo-100 absolute top-4 right-4">03</span>
                        <h4 class="text-sm font-bold text-slate-900 mb-2">Apresente o QR Code</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Na hora do embarque, mostre o código QR gerado na tela do seu celular para validar sua presença.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Rodapé -->
    <footer class="bg-white border-t border-slate-200 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-slate-500">
                &copy; {{ date('Y') }} Sistema de Transporte Escolar. Todos os direitos reservados.
            </p>
            <div class="flex items-center gap-4 text-xs font-semibold text-slate-500">
                <a href="{{ route('login') }}" class="hover:text-indigo-600 transition">Entrar</a>
                <span>&bull;</span>
                <a href="#" class="hover:text-indigo-600 transition">Termos de Uso</a>
            </div>
        </div>
    </footer>

</body>
</html>