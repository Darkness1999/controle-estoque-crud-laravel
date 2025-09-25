<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class ProdutoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome' => fake()->words(3, true),
            'descricao' => fake()->sentence(),
            'codigo_barras' => fake()->unique()->ean13(),
        ];
    }
}