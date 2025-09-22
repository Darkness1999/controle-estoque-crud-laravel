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

    <div class="relative flex items-center justify-center min-h-screen bg-gradient-to-br from-indigo-700 via-indigo-800 to-purple-900">
        
        <!-- Overlay para contraste -->
        <div class="absolute inset-0 bg-black/40"></div>

        <!-- Conteúdo central -->
        <div class="relative text-center text-white fade-in px-6 max-w-3xl">
            
            <!-- Ícone SVG -->
            <div class="mx-auto mb-10 w-28 h-28 flex items-center justify-center rounded-full bg-white/10 backdrop-blur-sm shadow-2xl text-indigo-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5l9-4.5 9 4.5M3 7.5l9 4.5 9-4.5M3 7.5v9l9 4.5 9-4.5v-9" />
                </svg>
            </div>

            <!-- Nome + Subtítulo -->
            <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight drop-shadow-lg">
                Controle de Estoque
            </h1>
            <p class="mt-4 text-xl md:text-2xl text-indigo-100 font-light">
                Gestão de estoque inteligente.
            </p>

            <!-- Botões de Ação -->
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
            <footer class="mt-16 text-sm text-indigo-20">
                Desenvolvido com Laravel v{{ Illuminate\Foundation\Application::VERSION }}
                (PHP v{{ PHP_VERSION }})
            </footer>
        </div>
    </div>

</body>
</html>
