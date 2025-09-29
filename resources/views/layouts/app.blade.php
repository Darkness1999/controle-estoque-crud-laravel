<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Controle de Estoque') }}</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-900">

    <div class="min-h-screen flex" x-data="{ sidebarOpen: true }">

        {{-- ===== SIDEBAR ===== --}}
        @include('layouts.sidebar')

        {{-- ===== CONTEÚDO PRINCIPAL ===== --}}
        <div class="flex-1 md:ml-64 transition-all duration-300">

            {{-- ===== TOPO ===== --}}
            <header class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-30">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">

                    {{-- Botão para abrir sidebar no mobile --}}
                    <button @click="sidebarOpen = !sidebarOpen"
                            class="md:hidden text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    {{-- Título da página (slot $header) --}}
                    <div class="flex-1 px-2">
                        @isset($header)
                            <h1 class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                                {{ $header }}
                            </h1>
                        @endisset
                    </div>

                    {{-- Menu do usuário --}}
                    <div class="hidden sm:flex items-center space-x-4">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 rounded-md bg-gray-100 dark:bg-gray-700 
                                               text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 
                                               focus:outline-none transition">
                                    <span>{{ Auth::user()->name }}</span>
                                    <svg class="ml-1 h-4 w-4 fill-current" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 
                                              111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    Perfil
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        Sair
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </header>

            {{-- ===== CONTEÚDO DINÂMICO ===== --}}
            <main class="py-6 px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </main>

            {{-- ===== RODAPÉ ===== --}}
            <footer class="border-t border-gray-200 dark:border-gray-700 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                Desenvolvido com Laravel v{{ Illuminate\Foundation\Application::VERSION }}
                (PHP v{{ PHP_VERSION }})
            </footer>

        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
