<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Atributo;
use App\Models\ValorAtributo;
use Illuminate\Http\Request;

class ValorAtributoApiController extends Controller
{
    public function index(Atributo $atributo) { return $atributo->valores; }
    public function store(Request $request, Atributo $atributo) {
        $data = $request->validate(['valor' => 'required|string']);
        return $atributo->valores()->create($data);
    }
    public function update(Request $request, ValorAtributo $valore) { // Laravel usa o singular do model
        $data = $request->validate(['valor' => 'required|string']);
        $valore->update($data);
        return $valore;
    }
    public function destroy(ValorAtributo $valore) {
        $valore->delete();
        return response()->noContent();
    }
}