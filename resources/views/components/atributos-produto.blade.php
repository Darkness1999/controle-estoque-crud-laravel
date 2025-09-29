@props(['produto', 'todosAtributos'])

<div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <h3 class="text-lg font-semibold mb-2">Atributos do Produto</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Selecione os atributos que este produto utiliza (ex: Cor, Tamanho). Isto irá determinar quais opções estarão disponíveis para criar variações.</p>
        <form method="POST" action="{{ route('produtos.attributes.sync', $produto->id) }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach ($todosAtributos as $atributo)
                    <label class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <input type="checkbox" name="attributes[]" value="{{ $atributo->id }}" 
                               @if($produto->attributes->contains($atributo)) checked @endif
                               class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span>{{ $atributo->nome }}</span>
                    </label>
                @endforeach
            </div>
            <div class="flex items-center justify-end mt-6 border-t dark:border-gray-700 pt-4">
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold text-sm transition">Salvar Atributos</button>
            </div>
        </form>
    </div>
</div>