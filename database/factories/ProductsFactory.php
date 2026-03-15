<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'sku' => $this->faker->unique()->bothify('SKU-####'),
            'barcode' => $this->faker->unique()->ean13(),
            'category_id' => Category::all()->random()->id, // Ambil ID kategori random yang sudah ada
            'supplier_id' => Supplier::all()->random()->id, // Ambil ID supplier random yang sudah ada
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(10000, 500000),
            'stock' => $this->faker->numberBetween(1, 100),
            'image' => null, // Biarkan null untuk seeder awal
        ];
    }
}