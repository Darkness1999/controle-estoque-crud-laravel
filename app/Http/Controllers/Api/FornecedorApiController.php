<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FornecedorResource;
use App\Models\Fornecedor;
use Illuminate\Http\Request;

class FornecedorApiController extends Controller
{
    public function index()
    {
        return FornecedorResource::collection(Fornecedor::orderBy('nome')->get());
    }
}