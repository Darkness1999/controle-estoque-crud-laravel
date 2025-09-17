<?php

namespace App\Http\Controllers;

use App\Models\Atributo;
use App\Models\ValorAtributo;
use Illuminate\Http\Request;

class ValorAtributoController extends Controller
{
    public function index(Atributo $atributo)
    {
        // Usando o relacionamento para buscar apenas os filhos do pai
        $valores = $atributo->valorAtributos()->latest()->get();
        return view('valores.index', compact('atributo', 'valores'));
    }

    public function create(Atributo $atributo)
    {
        return view('valores.create', compact('atributo'));
    }

    public function store(Request $request, Atributo $atributo)
    {
        $request->validate(['valor' => 'required|string|max:255']);

        // Usando o relacionamento para criar o filho (preenche 'atributo_id' automaticamente)
        $atributo->valorAtributos()->create($request->all());

        return redirect()->route('valores.index', $atributo->id)->with('sucesso', 'Valor adicionado com sucesso!');
    }

    public function destroy(ValorAtributo $valorAtributo)
    {
        // Guarda o ID do pai antes de apagar o filho
        $atributo_id = $valorAtributo->atributo_id;
        $valorAtributo->delete();
        
        return redirect()->route('valores.index', $atributo_id)->with('sucesso', 'Valor apagado com sucesso!');
    }
}