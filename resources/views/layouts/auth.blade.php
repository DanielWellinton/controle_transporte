<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Sistema'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts e Estilos (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800">
    <div class="min-h-screen flex flex-col items-center justify-center p-6 sm:p-0">
        
        <!-- Logotipo ou Nome do Sistema Opcional no topo do card -->
        <div class="mb-6 text-center">
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Seu Sistema</h1>
            <p class="text-xs text-slate-500 mt-1">Gestão Inteligente</p>
        </div>

        <!-- Conteúdo da Autenticação (Card) -->
        <div class="w-full sm:max-w-md bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
            @yield('content')
        </div>

        <!-- Rodapé discreto opcional -->
        <div class="mt-6 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} — Todos os direitos reservados.
        </div>
    </div>
</body>
</html>