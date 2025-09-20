<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Fornecedor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if(session('sucesso'))
                        <x-alert :message="session('sucesso')" />
                    @endif

                    <form method="POST" action="{{ route('fornecedores.update', $fornecedor->id) }}">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="nome" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nome</label>
                            <input id="nome" name="nome" type="text" value="{{ old('nome', $fornecedor->nome) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" required autofocus>
                        </div>

                        <div class="mt-4">
                            <label for="cnpj" class="block font-medium text-sm text-gray-700 dark:text-gray-300">CNPJ</label>
                            <input id="cnpj" name="cnpj" type="text" value="{{ old('cnpj', $fornecedor->cnpj) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                        </div>

                        <div class="mt-4">
                            <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $fornecedor->email) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                        </div>

                        <div class="mt-4">
                            <label for="telefone" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Telefone</label>
                            <input id="telefone" name="telefone" type="text" value="{{ old('telefone', $fornecedor->telefone) }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                        </div>

                        <div class="mt-4">
                            <label for="endereco" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Endereço</label>
                            <textarea id="endereco" name="endereco" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">{{ old('endereco', $fornecedor->endereco) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                                Atualizar
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>