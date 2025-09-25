<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class FornecedorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome' => fake()->unique()->company(),
            'cnpj' => fake()->unique()->numerify('##.###.###/0001-##'),
            'email' => fake()->unique()->companyEmail(),
            'telefone' => fake()->phoneNumber(),
            'endereco' => fake()->address(),
        ];
    }
}