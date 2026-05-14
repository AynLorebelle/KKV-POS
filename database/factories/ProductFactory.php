<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $skincareTypes = ['Cleanser', 'Toner', 'Serum', 'Moisturizer', 'Sunscreen', 'Face Mask', 'Eye Cream', 'Exfoliator', 'Essence', 'Lip Balm'];
        $brands = ['Glow', 'Radiance', 'Pure', 'Luminous', 'Hydra', 'Vital', 'Silk', 'Aura'];
        
        $name = $this->faker->randomElement($brands) . ' ' . $this->faker->randomElement($skincareTypes);

        return [
            'barcode' => $this->faker->unique()->ean13(),
            'name' => $name,
            'price' => $this->faker->randomFloat(2, 50, 2000),
        ];
    }
}
