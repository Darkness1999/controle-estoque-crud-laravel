<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gerenciamento de Atributos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4 flex justify-between items-center">
                        <p class="font-xl">Atributos Cadastrados (Cor, Tamanho, etc.)</p>
                        <a href="{{ route('atributos.create') }}" class="px-4 py-2 bg-indigo-600 rounded-md font-semibold text-xs text-white uppercase">Adicionar Atributo</a>
                    </div>
                    @if (session('sucesso'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">{{ session('sucesso') }}</div>
                    @endif
                    <table class="min-w-full">
                        <thead class="border-b">
                            <tr>
                                <th class="px-6 py-3 text-left">Nome</th>
                                <th class="px-6 py-3 text-left">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($atributos as $atributo)
                            <tr class="border-b">
                                <td class="px-6 py-4">{{ $atributo->nome }}</td>
                                <td class="px-6 py-4 flex space-x-4">
                                    <a href="{{ route('valores.index', $atributo->id) }}" class="text-blue-600 hover:text-blue-900 font-semibold">Gerenciar Valores</a>
                                    <a href="{{ route('atributos.edit', $atributo->id) }}" class="text-yellow-600 hover:text-yellow-900">Editar</a>
                                    <form method="POST" action="{{ route('atributos.destroy', $atributo->id) }}" onsubmit="return confirm('Tem certeza?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Apagar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="px-6 py-4 text-center">Nenhum atributo cadastrado.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>