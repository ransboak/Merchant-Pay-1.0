<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Settlement>
 */
class SettlementFactory extends Factory
{
    protected $model = \App\Models\Settlement::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'merchant_id'=>null, // assign explicitly
            'amount'=>$this->faker->randomFloat(2, 10, 1000),
            'settlement_date'=>$this->faker->dateTimeThisMonth,
            'reference'=>$this->faker->unique()->lexify('SET-???')
        ];
    }
}
