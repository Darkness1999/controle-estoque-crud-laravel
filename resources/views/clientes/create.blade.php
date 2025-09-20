<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Adicionar Novo Cliente') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('clientes.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="nome" class="block font-medium text-sm">Nome</label>
                            <input id="nome" name="nome" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700" required autofocus>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="cpf_cnpj" class="block font-medium text-sm">CPF/CNPJ</label>
                                <input id="cpf_cnpj" name="cpf_cnpj" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700">
                            </div>
                            <div>
                                <label for="email" class="block font-medium text-sm">Email</label>
                                <input id="email" name="email" type="email" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700">
                            </div>
                        </div>
                        <div>
                            <label for="telefone" class="block font-medium text-sm">Telefone</label>
                            <input id="telefone" name="telefone" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700">
                        </div>
                        <div>
                            <label for="endereco" class="block font-medium text-sm">Endereço</label>
                            <textarea id="endereco" name="endereco" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700"></textarea>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 rounded-md font-semibold text-xs text-white uppercase">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>