<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Controle de Estoque - Registro</title>

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

        <!-- Caixa de Registro -->
        <div class="relative w-full max-w-md px-8 py-10 bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl fade-in text-white">

            <!-- Ícone -->
            <div class="mx-auto mb-8 w-20 h-20 flex items-center justify-center rounded-full bg-white/10 text-indigo-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5l9-4.5 9 4.5M3 7.5l9 4.5 9-4.5M3 7.5v9l9 4.5 9-4.5v-9" />
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-center">Crie sua Conta</h1>
            <p class="mt-2 text-center text-indigo-100 text-sm">Cadastre-se para utilizar o sistema</p>

            <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <x-input-label for="name" :value="__('Nome')" class="text-indigo-100" />
                    <x-text-input id="name"
                        class="block mt-1 w-full rounded-md border-gray-300 focus:border-indigo-400 focus:ring focus:ring-indigo-300 text-gray-900"
                        type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-300" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-indigo-100" />
                    <x-text-input id="email"
                        class="block mt-1 w-full rounded-md border-gray-300 focus:border-indigo-400 focus:ring focus:ring-indigo-300 text-gray-900"
                        type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300" />
                </div>

                <div>
                    <x-input-label for="password" :value="__('Senha')" class="text-indigo-100" />
                    <x-text-input id="password"
                        class="block mt-1 w-full rounded-md border-gray-300 focus:border-indigo-400 focus:ring focus:ring-indigo-300 text-gray-900"
                        type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" class="text-indigo-100" />
                    <x-text-input id="password_confirmation"
                        class="block mt-1 w-full rounded-md border-gray-300 focus:border-indigo-400 focus:ring focus:ring-indigo-300 text-gray-900"
                        type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-300" />
                </div>

                <div>
                    <button type="submit"
                        class="w-full mt-4 px-6 py-3 rounded-lg bg-indigo-500 text-white text-lg font-semibold shadow-lg hover:bg-indigo-400 transition">
                        {{ __('Registrar') }}
                    </button>
                </div>

                <div class="text-center mt-4">
                    <p class="text-sm text-indigo-100">
                        Já possui conta?
                        <a class="underline font-semibold text-white hover:text-indigo-200"
                           href="{{ route('login') }}">
                            Entrar
                        </a>
                    </p>
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
