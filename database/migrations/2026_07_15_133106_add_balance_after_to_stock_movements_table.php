<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->integer('balance_after')->default(0)->after('quantity');
        });

        // Backfill script
        $products = \Illuminate\Support\Facades\DB::table('products')->get();
        
        foreach ($products as $product) {
            $movements = \Illuminate\Support\Facades\DB::table('stock_movements')
                ->where('product_id', $product->id)
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->get();
                
            $currentBalance = $product->quantity;
            
            foreach ($movements as $movement) {
                \Illuminate\Support\Facades\DB::table('stock_movements')
                    ->where('id', $movement->id)
                    ->update(['balance_after' => $currentBalance]);
                
                if ($movement->type === 'in') {
                    $currentBalance -= $movement->quantity;
                } else {
                    $currentBalance += $movement->quantity;
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropColumn('balance_after');
        });
    }
};
