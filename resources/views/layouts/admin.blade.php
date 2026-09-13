<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Painel Administrativo') - FleetManager</title>
    
    <!-- Fonts: Inter para tipografia limpa -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="h-full font-sans antialiased text-slate-900 selection:bg-indigo-500 selection:text-white" 
      x-data="{ sidebarOpen: false, profileMenuOpen: false }">

    <!-- Back-drop Escuro para Mobile -->
    <div x-cloak 
         x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"></div>

    <div class="min-h-full flex flex-col lg:flex-row">
        
        <!-- Sidebar Navigation -->
        @include('layouts.partials.admin-sidebar')

        <!-- Container Principal -->
        <div class="flex-1 flex flex-col min-w-0 min-h-screen">
            
            <!-- Navbar Header Topo -->
            @include('layouts.partials.admin-navbar')

            <!-- Área de Conteúdo da Página -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-6">
                
                <!-- Feedback Toast - Sucesso -->
                @if (session('success'))
                    <div x-data="{ show: true }" 
                         x-show="show" 
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="flex items-center justify-between p-4 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-900 shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="flex p-2 bg-emerald-100 rounded-lg text-emerald-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 p-1 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif

                <!-- Feedback Toast - Erro -->
                @if (session('error'))
                    <div x-data="{ show: true }" 
                         x-show="show" 
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="flex items-center justify-between p-4 rounded-xl bg-rose-50 border border-rose-200/80 text-rose-900 shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="flex p-2 bg-rose-100 rounded-lg text-rose-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </span>
                            <span class="text-sm font-medium">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-rose-500 hover:text-rose-700 p-1 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Rodapé Clean -->
            <footer class="mt-auto border-t border-slate-200/70 bg-white py-4 px-6 text-center lg:text-left text-xs text-slate-500">
                <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
                    <span>&copy; {{ date('Y') }} FleetManager SaaS. Todos os direitos reservados.</span>
                    <span class="text-slate-400">v2.4.0</span>
                </div>
            </footer>
        </div>

    </div>

    @stack('scripts')
</body>
</html>