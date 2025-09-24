<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MarcaResource;
use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaApiController extends Controller
{
    public function index()
    {
        return MarcaResource::collection(Marca::all());
    }
}