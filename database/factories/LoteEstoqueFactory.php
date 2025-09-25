<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class LoteEstoqueFactory extends Factory
{
    public function definition(): array
    {
        return [
            'lote' => 'LOTE-' . fake()->unique()->numberBetween(1000, 9999),
            'data_validade' => fake()->dateTimeBetween('+1 month', '+2 years'),
            'quantidade_atual' => fake()->numberBetween(50, 500),
        ];
    }
}