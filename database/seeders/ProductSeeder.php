<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            ['name' => 'Logitech MX Master 3', 'sku' => 'MOU-LOG-MX3', 'price' => 99.99, 'quantity' => 20, 'min_stock_level' => 5],
            ['name' => 'Dell UltraSharp 27', 'sku' => 'MON-DEL-U27', 'price' => 350.00, 'quantity' => 12, 'min_stock_level' => 4],
            ['name' => 'Apple Magic Keyboard', 'sku' => 'KBD-APP-MK', 'price' => 129.00, 'quantity' => 8, 'min_stock_level' => 10],
            ['name' => 'Samsung 970 EVO 1TB', 'sku' => 'SSD-SAM-970', 'price' => 85.00, 'quantity' => 30, 'min_stock_level' => 10],
            ['name' => 'Sony WH-1000XM4', 'sku' => 'AUD-SON-XM4', 'price' => 298.00, 'quantity' => 15, 'min_stock_level' => 5],
            ['name' => 'Corsair Vengeance 32GB', 'sku' => 'RAM-COR-32G', 'price' => 110.00, 'quantity' => 25, 'min_stock_level' => 10],
            ['name' => 'Intel Core i7-12700K', 'sku' => 'CPU-INT-I712', 'price' => 320.00, 'quantity' => 8, 'min_stock_level' => 5],
            ['name' => 'AMD Ryzen 5 5600X', 'sku' => 'CPU-AMD-R556', 'price' => 240.00, 'quantity' => 4, 'min_stock_level' => 5],
            ['name' => 'NVIDIA RTX 3060 Ti', 'sku' => 'GPU-NVI-3060T', 'price' => 450.00, 'quantity' => 2, 'min_stock_level' => 3],
            ['name' => 'Razer DeathAdder V2', 'sku' => 'MOU-RAZ-DA2', 'price' => 45.00, 'quantity' => 18, 'min_stock_level' => 5],
            ['name' => 'LG 34" Ultrawide', 'sku' => 'MON-LG-34UW', 'price' => 400.00, 'quantity' => 6, 'min_stock_level' => 3],
            ['name' => 'HyperX Cloud II', 'sku' => 'AUD-HYP-CL2', 'price' => 70.00, 'quantity' => 22, 'min_stock_level' => 8],
            ['name' => 'Seagate Barracuda 2TB', 'sku' => 'HDD-SEA-2TB', 'price' => 55.00, 'quantity' => 40, 'min_stock_level' => 15],
            ['name' => 'Asus ROG Strix B550', 'sku' => 'MOB-ASU-B550', 'price' => 180.00, 'quantity' => 10, 'min_stock_level' => 4],
            ['name' => 'NZXT H510 Case', 'sku' => 'CAS-NZX-H510', 'price' => 75.00, 'quantity' => 5, 'min_stock_level' => 3],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
