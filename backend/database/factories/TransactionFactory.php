<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = \App\Models\Transaction::class;
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
            'payment_reference'=>$this->faker->unique()->lexify('TXN???'),
            'status'=>'successful'
        ];
    }
}
