<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Controle de Estoque - Verificar E-mail</title>

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

        <!-- Caixa de Verificação -->
        <div class="relative w-full max-w-md px-8 py-10 bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl fade-in text-white">

            <!-- Ícone -->
            <div class="mx-auto mb-8 w-20 h-20 flex items-center justify-center rounded-full bg-white/10 text-indigo-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.5 9.75V6.375c0-.621-.504-1.125-1.125-1.125h-6.75c-.621 0-1.125.504-1.125 1.125V9.75m9 0v7.875c0 .621-.504 1.125-1.125 1.125h-6.75c-.621 0-1.125-.504-1.125-1.125V9.75m9 0h-9" />
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-center">Verifique seu E-mail</h1>
            <p class="mt-3 text-center text-indigo-100 text-sm">
                Obrigado por se registrar! Antes de começar, confirme seu endereço de e-mail clicando no link que enviamos para você.
                <br>Se não recebeu, podemos enviar novamente.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="mt-6 mb-4 text-center font-medium text-green-300 text-sm">
                    Um novo link de verificação foi enviado para o e-mail informado.
                </div>
            @endif

            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <!-- Reenviar -->
                <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit"
                        class="w-full sm:w-auto px-6 py-3 rounded-lg bg-indigo-500 text-white font-semibold shadow-lg hover:bg-indigo-400 transition">
                        Reenviar E-mail de Verificação
                    </button>
                </form>

                <!-- Sair -->
                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit"
                        class="w-full sm:w-auto px-6 py-3 rounded-lg bg-white/20 text-white font-semibold shadow-md hover:bg-white/30 transition">
                        Sair
                    </button>
                </form>
            </div>

            <!-- Rodapé -->
            <footer class="mt-8 text-center text-xs text-indigo-200">
                Desenvolvido com Laravel v{{ Illuminate\Foundation\Application::VERSION }}
                (PHP v{{ PHP_VERSION }})
            </footer>
        </div>
    </div>

</body>
</html>
