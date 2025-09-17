<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Mostra a lista de todos os utilizadores.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Atualiza a função (role) de um utilizador específico.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,operador', // Garante que a função seja uma das válidas
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->route('users.index')->with('sucesso', "Função do utilizador {$user->name} atualizada com sucesso!");
    }
}