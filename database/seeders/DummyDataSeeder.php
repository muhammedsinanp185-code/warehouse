<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first user in the database to assign movements to
        $user = User::first();
        
        if (!$user) {
            $user = User::create([
                'name' => 'System Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'manager',
            ]);
        }

        // Clear existing data just in case it's run multiple times
        Product::truncate();
        StockMovement::truncate();

        // Create 3 Products
        $p1 = Product::create([
            'name' => 'Dell XPS 15 Laptop',
            'sku' => 'LAP-DELL-XPS',
            'price' => 1500.00,
            'quantity' => 45,
            'min_stock_level' => 10,
        ]);

        $p2 = Product::create([
            'name' => 'Logitech MX Master 3',
            'sku' => 'MSE-LOG-MX3',
            'price' => 99.99,
            'quantity' => 120,
            'min_stock_level' => 20,
        ]);

        $p3 = Product::create([
            'name' => 'Keychron K2 Keyboard',
            'sku' => 'KBD-KEY-K2',
            'price' => 79.50,
            'quantity' => 5, // Intentionally Low Stock
            'min_stock_level' => 15,
        ]);

        // Create Stock Movements
        StockMovement::create([
            'product_id' => $p1->id,
            'user_id' => $user->id,
            'type' => 'in',
            'quantity' => 50,
            'created_at' => Carbon::now()->subDays(1),
        ]);

        StockMovement::create([
            'product_id' => $p1->id,
            'user_id' => $user->id,
            'type' => 'out',
            'quantity' => 5,
            'created_at' => Carbon::now()->subHours(5),
        ]);

        StockMovement::create([
            'product_id' => $p3->id,
            'user_id' => $user->id,
            'type' => 'out',
            'quantity' => 20,
            'created_at' => Carbon::now()->subHours(2),
        ]);
        
        StockMovement::create([
            'product_id' => $p2->id,
            'user_id' => $user->id,
            'type' => 'in',
            'quantity' => 120,
            'created_at' => Carbon::now()->subMinutes(15),
        ]);
    }
}
