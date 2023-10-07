<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductImage;
use App\Models\Shop;
use App\Models\DiscountCode;
use App\Models\DiscountCodeHasProduct;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $codeShop= ['ADMIN', 'MANHKHANH', 'ABC'];
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $type = ['%', '$'];

        foreach (range(1, 200) as $index) {
            $discount_code_id = DiscountCode::where('applies_to_all_products', 0)->pluck('id')->random();

            DiscountCodeHasProduct::create([
                'product_id' => rand(1, 200),
                'discount_code_id' => $discount_code_id,
            ]);
        }
    }
}