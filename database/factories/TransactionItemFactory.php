<?php

namespace Database\Factories;

use App\Models\TransactionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TransactionItem>
 */
class TransactionItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = \App\Models\Product::inRandomOrder()->first() ?? \App\Models\Product::factory()->create();
        $qty = $this->faker->numberBetween(1, 5);
        return [
            'transaction_id' => \App\Models\Transaction::factory(),
            'product_id' => $product->id,
            'qty' => $qty,
            'price' => $product->price,
            'amount' => $product->price * $qty,
        ];
    }
}
