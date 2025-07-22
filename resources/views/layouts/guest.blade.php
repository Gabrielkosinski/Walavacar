<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WaLavacar') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="{{ asset('js/app.js') }}"></script>
        <!-- WaLavacar Theme -->
        <link rel="stylesheet" href="{{ asset('css/wa-theme.css') }}">
        
        <style>
            :root {
                --wa-red-primary: #C53030;
                --wa-red-secondary: #E53E3E;
                --wa-red-dark: #9B2C2C;
                --wa-black-primary: #1A1A1A;
                --wa-black-secondary: #2D2D2D;
                --wa-accent-gold: #D69E2E;
            }
            
            .login-bg {
                background-image: linear-gradient(135deg, 
                    rgba(26, 26, 26, 0.45), 
                    rgba(45, 45, 45, 0.35), 
                    rgba(197, 48, 48, 0.10)), 
                  url('/images/Logo-mae.jpg');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                min-height: 100vh;
            }
            
            /* Para dispositivos móveis */
            @media (max-width: 768px) {
                .login-bg {
                    background-attachment: scroll;
                    background-size: cover;
                }
            }
            
            .login-card {
                backdrop-filter: blur(12px);
                background: rgba(26, 26, 26, 0.85);
                border: 2px solid rgba(197, 48, 48, 0.4);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3),
                            0 0 0 1px rgba(197, 48, 48, 0.2),
                            0 0 20px rgba(197, 48, 48, 0.3);
                color: white;
            }
            
            .login-card:hover {
                border-color: rgba(197, 48, 48, 0.5);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5),
                            0 0 30px rgba(197, 48, 48, 0.3);
            }
            
            /* Logo com cores WaLavacar */
            .wa-logo {
                background: linear-gradient(135deg, var(--wa-red-primary), var(--wa-red-dark));
                box-shadow: 0 8px 20px rgba(197, 48, 48, 0.4);
            }
            
            /* Inputs com tema escuro */
            .login-card input {
                background: rgba(45, 45, 45, 0.8) !important;
                border: 1px solid rgba(197, 48, 48, 0.3) !important;
                color: white !important;
            }
            
            .login-card input:focus {
                border-color: var(--wa-red-primary) !important;
                box-shadow: 0 0 0 3px rgba(197, 48, 48, 0.2) !important;
            }
            
            .login-card label {
                color: #E2E8F0 !important;
                font-weight: 500;
            }
            
            /* Botão com cores WaLavacar */
            .wa-button {
                background: linear-gradient(135deg, var(--wa-red-primary), var(--wa-red-dark)) !important;
                border: none !important;
                box-shadow: 0 4px 15px rgba(197, 48, 48, 0.4) !important;
                transition: all 0.3s ease !important;
            }
            
            .wa-button:hover {
                background: linear-gradient(135deg, var(--wa-red-dark), #7C2D12) !important;
                box-shadow: 0 6px 20px rgba(197, 48, 48, 0.6) !important;
                transform: translateY(-1px) !important;
            }
            
            /* Links */
            .login-card a {
                color: var(--wa-accent-gold) !important;
            }
            
            .login-card a:hover {
                color: var(--wa-red-secondary) !important;
            }
            
            /* Animação suave para o card */
            .login-card {
                animation: fadeInUp 0.8s ease-out;
            }
            
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 login-bg">
            <!-- Logo/Brand -->
            <div class="mb-8">
                <a href="/" class="flex items-center space-x-4">
                    <div class="w-14 h-14 wa-logo rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                    </div>
                    <div class="text-white">
                        <h1 class="text-3xl font-bold tracking-tight">WaLavacar</h1>
                        <p class="text-sm text-yellow-300 opacity-90 font-medium">Painel de Gestão Corporativo</p>
                    </div>
                </a>
            </div>

            <!-- Login Form Card -->
            <div class="w-full sm:max-w-md px-8 py-8 login-card shadow-2xl sm:rounded-2xl">
                <div class="mb-6 text-center">
                    <div class="flex items-center justify-center mb-3">
                        <div class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center mr-2">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-white">Acesso Restrito</h2>
                    </div>
                    <p class="text-sm text-gray-300 mb-1">Sistema exclusivo para funcionários autorizados</p>
                    <p class="text-xs text-yellow-400 font-medium">⚠️ Apenas pessoal corporativo</p>
                </div>
                
                {{ $slot }}
            </div>
            
            <!-- Footer -->
            <div class="mt-8 text-center">
                <div class="bg-black/20 backdrop-blur-sm rounded-lg px-4 py-2 border border-red-600/30">
                    <p class="text-sm text-white/90 mb-1">
                        © {{ date('Y') }} <span class="text-red-400 font-semibold">WaLavacar</span> - Sistema de Gestão Profissional
                    </p>
                    <p class="text-xs text-gray-300">
                        🕒 Suporte técnico: Segunda à Sexta, 8h às 18h
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
