<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Controle de Estoque - Recuperar Senha</title>

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

        <!-- Caixa de recuperação -->
        <div class="relative w-full max-w-md px-8 py-10 bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl fade-in text-white">

            <!-- Ícone -->
            <div class="mx-auto mb-8 w-20 h-20 flex items-center justify-center rounded-full bg-white/10 text-indigo-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9 9 9 0 009-9z" />
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-center">Esqueceu sua Senha?</h1>
            <p class="mt-3 text-center text-indigo-100 text-sm">
                Sem problemas! Informe seu e-mail e enviaremos um link para redefinir sua senha.
            </p>

            <!-- Status da sessão -->
            @if (session('status'))
                <div class="mt-6 mb-4 text-center font-medium text-green-300 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Formulário -->
            <form method="POST" action="{{ route('password.email') }}" class="mt-6">
                @csrf

                <!-- Campo de e-mail -->
                <div>
                    <label for="email" class="block text-sm font-medium mb-2">E-mail</label>
                    <input id="email"
                           class="block w-full px-4 py-3 rounded-lg border border-transparent focus:ring-2 focus:ring-indigo-400 text-gray-800"
                           type="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="mt-2 text-sm text-red-300">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Botão -->
                <div class="mt-6">
                    <button type="submit"
                        class="w-full px-6 py-3 rounded-lg bg-indigo-500 text-white font-semibold shadow-lg hover:bg-indigo-400 transition">
                        Enviar Link de Redefinição
                    </button>
                </div>
            </form>

            <!-- Rodapé -->
            <footer class="mt-8 text-center text-xs text-indigo-200">
                Desenvolvido com Laravel v{{ Illuminate\Foundation\Application::VERSION }}
                (PHP v{{ PHP_VERSION }})
            </footer>
        </div>
    </div>

</body>
</html>
