<?php

namespace App\Http\Controllers;

use App\Models\Atributo;
use Illuminate\Http\Request;

class AtributoController extends Controller
{
    public function index()
    {
        $atributos = Atributo::latest()->paginate(10);
        return view('atributos.index', compact('atributos'));
    }

    public function create()
    {
        return view('atributos.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nome' => 'required|string|max:255|unique:atributos']);
        Atributo::create($request->all());
        return redirect()->route('atributos.index')->with('sucesso', 'Atributo criado com sucesso!');
    }

    public function edit(Atributo $atributo)
    {
        return view('atributos.edit', compact('atributo'));
    }

    public function update(Request $request, Atributo $atributo)
    {
        $request->validate(['nome' => 'required|string|max:255|unique:atributos,nome,' . $atributo->id]);
        $atributo->update($request->all());
        return redirect()->route('atributos.index')->with('sucesso', 'Atributo atualizado com sucesso!');
    }

    public function destroy(Atributo $atributo)
    {
        $atributo->delete();
        return redirect()->route('atributos.index')->with('sucesso', 'Atributo apagado com sucesso!');
    }
}