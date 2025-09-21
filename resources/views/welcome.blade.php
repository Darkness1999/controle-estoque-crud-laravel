<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Controle de Estoque</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeIn 0.9s ease-out forwards; }
    </style>
</head>
<body class="antialiased">

    <div class="relative flex items-center justify-center min-h-screen bg-gradient-to-br from-indigo-700 via-purple-700 to-indigo-900">
        
        <!-- brilho de fundo -->
        <div class="absolute inset-0 bg-black/40"></div>

        <!-- Conteúdo -->
        <div class="relative text-center text-white fade-in px-6 max-w-3xl">
            
            <!-- Ícone -->
            <div class="mx-auto mb-10 w-24 h-24 flex items-center justify-center rounded-full bg-white/10 backdrop-blur-sm shadow-xl text-5xl">
                📦
            </div>

            <!-- Nome + Slogan -->
            <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight drop-shadow-lg">
                Controle de Estoque
            </h1>
            <p class="mt-4 text-xl md:text-2xl text-indigo-100 font-light">
                SISTEMA COMPLETO E MODERNO PARA GESTÃO DE PRODUTOS
            </p>

            <!-- Ações -->
            @if (Route::has('login'))
                <div class="mt-10 flex flex-col sm:flex-row gap-6 justify-center">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="px-8 py-4 rounded-lg bg-white text-indigo-800 text-lg font-semibold shadow-lg hover:bg-gray-100 transition">
                           Ir para o Painel
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-8 py-4 rounded-lg bg-white text-indigo-800 text-lg font-semibold shadow-lg hover:bg-gray-100 transition">
                           Entrar
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-8 py-4 rounded-lg bg-indigo-500 text-white text-lg font-semibold shadow-lg hover:bg-indigo-400 transition">
                               Registrar
                            </a>
                        @endif
                    @endauth
                </div>
            @endif

            <!-- Rodapé -->
            <footer class="mt-16 text-sm text-indigo-200">
                Desenvolvido com Laravel v{{ Illuminate\Foundation\Application::VERSION }}
                (PHP v{{ PHP_VERSION }})
            </footer>
        </div>
    </div>

</body>
</html>
