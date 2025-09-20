<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Valores para o Atributo: <span class="text-indigo-500">{{ $atributo->nome }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4 flex justify-between items-center">
                        <a href="{{ route('atributos.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900"> &larr; Voltar para a Lista de Atributos</a>
                        <a href="{{ route('valores.create', $atributo->id) }}" class="px-4 py-2 bg-indigo-600 rounded-md font-semibold text-xs text-white uppercase">Adicionar Valor</a>
                    </div>

                    @if(session('sucesso'))
                        <x-alert :message="session('sucesso')" />
                    @endif

                    <table class="min-w-full">
                        <thead class="border-b">
                            <tr>
                                <th class="px-6 py-3 text-left">Valor (Ex: Azul, P, 110v)</th>
                                <th class="px-6 py-3 text-left">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($valores as $valor)
                            <tr class="border-b">
                                <td class="px-6 py-4">{{ $valor->valor }}</td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('valores.destroy', $valor->id) }}" onsubmit="return confirm('Tem certeza?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Apagar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="px-6 py-4 text-center">Nenhum valor cadastrado para este atributo.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>