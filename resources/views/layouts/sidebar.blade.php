<div 
    :class="{
        'translate-x-0': sidebarOpen, 
        '-translate-x-full': !sidebarOpen
    }"
    class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 shadow-xl z-30 
           transform md:translate-x-0 transition-transform duration-300 ease-in-out
           border-r border-gray-200 dark:border-gray-700"
>

    {{-- ===== LOGO E TÍTULO ===== --}}
    <div class="flex items-center justify-center h-20 border-b border-gray-200 dark:border-gray-700 px-4">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 hover:opacity-90 transition">
            
            {{-- Ícone genérico de estoque/caixa --}}
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M3 7.5l9-4.5 9 4.5-9 4.5-9-4.5zM3 7.5v9l9 4.5 9-4.5v-9" />
                </svg>
            </div>

            {{-- Nome do Sistema --}}
            <span class="text-xl font-bold uppercase text-gray-800 dark:text-gray-200 tracking-tight">
                Controle de Estoque
            </span>
        </a>
    </div>

    {{-- ===== MENU ===== --}}
    <nav class="mt-4 px-4 space-y-2 overflow-y-auto h-[calc(100vh-5rem)] 
                scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">

        {{-- PRINCIPAL --}}
        <h3 class="px-3 text-xs uppercase font-semibold tracking-wider text-gray-500 dark:text-gray-400">
            Principal
        </h3>
        <x-side-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            Dashboard
        </x-side-nav-link>
        <x-side-nav-link :href="route('vendas.dashboard')" :active="request()->routeIs('vendas.dashboard')">
            Dashboard de Vendas
        </x-side-nav-link>

        {{-- OPERAÇÕES --}}
        <h3 class="px-3 mt-4 text-xs uppercase font-semibold tracking-wider text-gray-500 dark:text-gray-400">
            Operações
        </h3>
        <x-side-nav-link :href="route('estoque.movimentar')" :active="request()->routeIs('estoque.movimentar')">
            Movimentar Estoque
        </x-side-nav-link>
        <x-side-nav-link :href="route('relatorios.movimentacoes')" :active="request()->routeIs('relatorios.movimentacoes')">
            Relatórios
        </x-side-nav-link>

        {{-- CADASTROS --}}
        <h3 class="px-3 mt-4 text-xs uppercase font-semibold tracking-wider text-gray-500 dark:text-gray-400">
            Cadastros
        </h3>
        <x-side-nav-link :href="route('produtos.index')" :active="request()->routeIs('produtos.*')">
            Produtos
        </x-side-nav-link>
        <x-side-nav-link :href="route('categorias.index')" :active="request()->routeIs('categorias.*')">
            Categorias
        </x-side-nav-link>
        <x-side-nav-link :href="route('marcas.index')" :active="request()->routeIs('marcas.*')">
            Marcas
        </x-side-nav-link>
        <x-side-nav-link :href="route('atributos.index')" :active="request()->routeIs('atributos.*', 'valores.*')">
            Atributos
        </x-side-nav-link>
        <x-side-nav-link :href="route('fornecedores.index')" :active="request()->routeIs('fornecedores.*')">
            Fornecedores
        </x-side-nav-link>
        <x-side-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')">
            Clientes
        </x-side-nav-link>

        {{-- ADMINISTRAÇÃO --}}
        @can('access-admin-area')
            <h3 class="px-3 mt-4 text-xs uppercase font-semibold tracking-wider text-gray-500 dark:text-gray-400">
                Administração
            </h3>
            <x-side-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                Usuários
            </x-side-nav-link>
        @endcan
    </nav>
</div>
