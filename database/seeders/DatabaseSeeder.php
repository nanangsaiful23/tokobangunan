<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::create([
            'name' => 'Pemilik Toko',
            'email' => 'owner@tokobangunan.test',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        User::create([
            'name' => 'Kasir Toko',
            'email' => 'kasir@tokobangunan.test',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        $semen = Category::create(['name' => 'Semen & Bahan Bangunan']);
        $cat = Category::create(['name' => 'Cat & Finishing']);
        $listrik = Category::create(['name' => 'Listrik & Perkakas']);

        $products = [
            ['sku' => 'SMN-001', 'name' => 'Semen Gresik 50kg', 'category_id' => $semen->id, 'unit' => 'sak', 'cost_price' => 55000, 'sell_price' => 65000, 'reorder_threshold' => 20, 'stock' => 100],
            ['sku' => 'BTA-001', 'name' => 'Bata Merah', 'category_id' => $semen->id, 'unit' => 'pcs', 'cost_price' => 700, 'sell_price' => 1000, 'reorder_threshold' => 500, 'stock' => 2000],
            ['sku' => 'CAT-001', 'name' => 'Cat Tembok Dulux 5kg', 'category_id' => $cat->id, 'unit' => 'kaleng', 'cost_price' => 120000, 'sell_price' => 145000, 'reorder_threshold' => 10, 'stock' => 5],
            ['sku' => 'KBL-001', 'name' => 'Kabel NYM 2x1.5mm (roll)', 'category_id' => $listrik->id, 'unit' => 'roll', 'cost_price' => 350000, 'sell_price' => 410000, 'reorder_threshold' => 5, 'stock' => 12],
        ];

        foreach ($products as $data) {
            $stock = $data['stock'];
            unset($data['stock']);

            $product = Product::create($data + ['current_stock' => 0]);

            StockMovement::record(
                product: $product,
                type: 'in',
                quantity: $stock,
                reason: 'initial',
                note: 'Stok awal (seeder)',
                createdBy: $owner->id,
            );
        }
    }
}
