<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class ClienteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'cpf_cnpj' => fake()->unique()->numerify('###.###.###-##'),
            'email' => fake()->unique()->safeEmail(),
            'telefone' => fake()->phoneNumber(),
            'endereco' => fake()->address(),
        ];
    }
}