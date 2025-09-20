<?php

namespace App\Http\Controllers;

use App\Models\MovimentacaoEstoque;
use App\Models\ProductVariation;
use App\Models\Fornecedor;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MovimentacaoEstoqueController extends Controller
{
    public function index()
    {
        $variations = ProductVariation::with('produto', 'attributeValues.atributo')->get();
        $movimentacoes = MovimentacaoEstoque::with('productVariation.produto', 'user', 'fornecedor', 'cliente')->latest()->take(10)->get();
        
        $fornecedores = Fornecedor::orderBy('nome')->get();
        $clientes = Cliente::orderBy('nome')->get();

        return view('estoque.index', compact('variations', 'movimentacoes', 'fornecedores', 'clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_variation_id' => 'required|exists:product_variations,id',
            'tipo' => 'required|in:entrada,saida',
            'quantidade' => 'required|integer|min:1',
            'motivo' => 'nullable|string|max:255',
            'fornecedor_id' => 'required_if:tipo,entrada|exists:fornecedores,id',
            'cliente_id' => 'required_if:tipo,saida|exists:clientes,id',
        ]);

        $variation = ProductVariation::findOrFail($request->product_variation_id);

        if ($request->tipo === 'saida' && $request->quantidade > $variation->estoque_atual) {
            return back()->withErrors(['quantidade' => 'A quantidade de saída não pode ser maior que o estoque atual.']);
        }

        DB::transaction(function () use ($request, $variation) {
            MovimentacaoEstoque::create([
                'product_variation_id' => $request->product_variation_id,
                'user_id' => Auth::id(),
                'tipo' => $request->tipo,
                'quantidade' => $request->quantidade,
                'motivo' => $request->motivo,
                'fornecedor_id' => $request->tipo === 'entrada' ? $request->fornecedor_id : null,
                'cliente_id' => $request->tipo === 'saida' ? $request->cliente_id : null,
            ]);

            if ($request->tipo === 'entrada') {
                $variation->increment('estoque_atual', $request->quantidade);
            } else {
                $variation->decrement('estoque_atual', $request->quantidade);
            }
        });

        return redirect()->route('estoque.movimentar')->with('sucesso', 'Movimentação de estoque registrada com sucesso!');
    }
}