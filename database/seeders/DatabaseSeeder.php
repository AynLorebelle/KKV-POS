<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create demo account
        User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create random products
        \App\Models\Product::factory(10)->create();

        // Create random transactions with items
        \App\Models\Transaction::factory(10)->create()->each(function ($transaction) {
            \App\Models\TransactionItem::factory(rand(1, 5))->create([
                'transaction_id' => $transaction->id,
            ]);
        });
    }
}
