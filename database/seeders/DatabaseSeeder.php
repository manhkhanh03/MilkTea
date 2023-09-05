<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductImage;
use App\Models\Shop;
use App\Models\ProductSizeFlavor;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $shippingAddress = ['127.0.0.1',
        '127.0.0.2',
        '127.0.0.3',
        '127.0.0.4',
        '127.0.0.5',
        '127.0.0.6',
        '127.0.0.7'];

        $status = ['awaiting approval', 'violation', 'confirmed', 'hide'];

        foreach (range(1, 400) as $index) {
            ProductSizeFlavor::create([
                'product_id' => rand(1, 200),
                'size_id' => rand(1, 3),
                'flavor_id' => rand(1, 20),
                'price' => rand(1.00, 10.00),
            ]);
        }
    }
}
