<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockMovement;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function managerIndex()
    {
        $totalProducts = Product::count();
        
        // Calculate total value: sum of (quantity * price) for all products
        $inventoryValue = Product::selectRaw('SUM(quantity * price) as total')->value('total');
        $inventoryValue = $inventoryValue ? $inventoryValue : 0;
        
        // Count products where quantity is less than or equal to their min_stock_level
        $lowStockCount = Product::whereColumn('quantity', '<=', 'min_stock_level')->count();
        
        // Count today's stock movements
        $todayActivity = StockMovement::whereDate('created_at', Carbon::today())->count();
        
        // Get the latest 5 movements with the related product and user
        $recentMovements = StockMovement::with(['product', 'user'])
                            ->latest()
                            ->limit(5)
                            ->get();
                            
        // Get all products to populate the modals dropdown
        $allProducts = Product::orderBy('name')->get();
                            
        return view('manager.dashboard', compact(
            'totalProducts', 
            'inventoryValue', 
            'lowStockCount', 
            'todayActivity', 
            'recentMovements',
            'allProducts'
        ));
    }
}
