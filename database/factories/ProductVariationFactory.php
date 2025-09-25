<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class ProductVariationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sku' => fake()->unique()->bothify('??-####??'),
            'preco_venda' => fake()->randomFloat(2, 10, 500),
            'preco_custo' => fake()->randomFloat(2, 5, 250),
            'estoque_minimo' => fake()->numberBetween(5, 20),
        ];
    }
}