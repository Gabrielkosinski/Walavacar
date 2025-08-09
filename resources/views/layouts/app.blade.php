<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- 📱 PWA Manifest -->
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#2563eb">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="WaLavacar">

        <!-- 🎨 WaLavacar - Sistema Visual Único e Otimizado -->
        <link rel="stylesheet" href="{{ asset('css/waluvacar-visual.css') }}">
        
        <!-- 📱 Mobile Images - Responsividade -->
        <link rel="stylesheet" href="{{ asset('css/mobile-images.css') }}">
        
        <!-- Scripts do Laravel/Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- 🎨 UI Libraries Essenciais -->
        <!-- SweetAlert2 - Alertas bonitos -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <!-- 📊 Chart.js - Gráficos interativos -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
        
        <!-- Font Awesome - Ícones para modais -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        
        <!-- Iconify - Ícones vetoriais -->
        <script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>

        <!-- 🔐 CSRF Token Manager -->
        <script src="{{ asset('js/csrf-manager.js') }}"></script>
    </head>
    <body class="font-sans antialiased mobile-safe">
        <div class="min-h-screen bg-gray-900 mobile-safe">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-gray-900 shadow-lg border-b border-red-600">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- 🚀 Scripts essenciais carregados após o conteúdo -->
        <script defer src="{{ asset('js/csrf-manager.js') }}"></script>
    </body>
</html>
