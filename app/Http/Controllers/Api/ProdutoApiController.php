<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Http\Resources\ProdutoResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProdutoApiController extends Controller
{
    /**
     * Retorna uma lista paginada de produtos.
     */
    public function index()
    {
        $produtos = Produto::with('categoria', 'marca')->paginate(15);
        return ProdutoResource::collection($produtos);
    }

    /**
     * Cria um novo produto base.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'marca_id' => 'required|exists:marcas,id',
            'fornecedor_id' => 'nullable|exists:fornecedores,id',
            'codigo_barras' => 'nullable|string|max:255|unique:produtos',
        ]);

        $produto = Produto::create($validatedData);

        // Retorna o recurso criado com status 201 (Created)
        return (new ProdutoResource($produto))->response()->setStatusCode(201);
    }

    /**
     * Retorna os detalhes de um produto específico.
     */
    public function show(Produto $produto)
    {
        $produto->load('categoria', 'marca', 'fornecedor', 'variations.attributeValues.atributo');
        return new ProdutoResource($produto);
    }

    /**
     * Atualiza um produto existente.
     */
    public function update(Request $request, Produto $produto)
    {
        $validatedData = $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'descricao' => 'nullable|string',
            'categoria_id' => 'sometimes|required|exists:categorias,id',
            'marca_id' => 'sometimes|required|exists:marcas,id',
            'fornecedor_id' => 'nullable|exists:fornecedores,id',
            'codigo_barras' => ['nullable', 'string', 'max:255', Rule::unique('produtos')->ignore($produto->id)],
        ]);

        $produto->update($validatedData);

        return new ProdutoResource($produto);
    }

    /**
     * Apaga (soft delete) um produto.
     */
    public function destroy(Produto $produto)
    {
        $produto->delete(); // Isto irá executar o Soft Delete que implementámos

        // Retorna uma resposta vazia com status 204 (No Content)
        return response()->json(null, 204);
    }
}