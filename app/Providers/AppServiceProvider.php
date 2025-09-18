<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Define um novo "Gate" (regra de acesso) chamado 'access-admin-area'
        Gate::define('access-admin-area', function (User $user) {
            // A regra é simples: retorna verdadeiro se a função do utilizador for 'admin'
            return $user->role === 'admin';
        });
    }
}
