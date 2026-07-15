<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('inventory:check-low-stock')]
#[Description('Checks for low stock products and sends an alert to managers')]
class CheckLowStock extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lowStockProducts = \App\Models\Product::whereColumn('quantity', '<=', 'min_stock_level')->get();

        if ($lowStockProducts->isEmpty()) {
            $this->info('No low stock items found.');
            return;
        }

        $managers = \App\Models\User::where('role', 'manager')->get();
        
        \Illuminate\Support\Facades\Notification::send($managers, new \App\Notifications\LowStockAlert($lowStockProducts));

        $this->info('Alert sent to managers for ' . $lowStockProducts->count() . ' low stock items.');
    }
}
