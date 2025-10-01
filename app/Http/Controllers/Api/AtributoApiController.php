<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Atributo;
use Illuminate\Http\Request;

class AtributoApiController extends Controller
{
    public function index() { return Atributo::with('valores')->get(); }
    public function store(Request $request) {
        $data = $request->validate(['nome' => 'required|string|unique:atributos,nome']);
        return Atributo::create($data);
    }
    public function show(Atributo $atributo) { return $atributo->load('valores'); }
    public function update(Request $request, Atributo $atributo) {
        $data = $request->validate(['nome' => 'required|string|unique:atributos,nome,'.$atributo->id]);
        $atributo->update($data);
        return $atributo;
    }
    public function destroy(Atributo $atributo) {
        $atributo->delete();
        return response()->noContent();
    }
}