<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $total = $this->faker->randomFloat(2, 50, 2000);
        $cashTendered = ceil($total / 100) * 100;
        if ($cashTendered < $total) $cashTendered += 100;

        $vatable = $total / 1.12;
        $vat = $total - $vatable;

        return [
            'invoice_no' => 'INV-' . $this->faker->unique()->numerify('######'),
            'cashier_name' => 'Demo Cashier',
            'total_amount' => $total,
            'cash_tendered' => $cashTendered,
            'change' => $cashTendered - $total,
            'vatable_sales' => round($vatable, 2),
            'vat_amount' => round($vat, 2),
            'vat_exempt' => 0,
            'zero_rated' => 0,
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
