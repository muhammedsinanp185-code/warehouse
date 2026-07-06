<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockMovement;

class UserDashboardController extends Controller
{
    public function index()
    {
        // Low stock alerts
        $lowStockProducts = Product::whereColumn('quantity', '<=', 'min_stock_level')
            ->orderBy('quantity', 'asc')
            ->get();
        
        // Recent activity
        $recentMovements = StockMovement::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $allProducts = Product::orderBy('name')->get();

        return view('user.dashboard', compact('lowStockProducts', 'recentMovements', 'allProducts'));
    }
}
