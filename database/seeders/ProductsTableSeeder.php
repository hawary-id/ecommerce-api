<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        Product::query()->delete();

        Product::create([
            'name' => 'Laptop XYZ',
            'price' => 5000000,
        ]);

        Product::create([
            'name' => 'Smartphone ABC',
            'price' => 3000000,
        ]);
    }
}
