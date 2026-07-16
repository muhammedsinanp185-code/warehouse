<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        // Low stock alerts
        $lowStockProducts = Product::whereColumn('quantity', '<=', 'min_stock_level')
            ->orderBy('quantity', 'asc')
            ->get();
        
        // Personal recent activity (Undo allowed for these)
        $recentMovements = StockMovement::with(['product'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $allProducts = Product::orderBy('name')->get();

        // Check if currently on shift
        $currentShift = Auth::user()->shifts()->whereNull('ended_at')->first();

        return view('user.dashboard', compact('lowStockProducts', 'recentMovements', 'allProducts', 'currentShift'));
    }

    public function startShift(Request $request)
    {
        $user = Auth::user();

        if ($user->shifts()->whereNull('ended_at')->exists()) {
            return back()->with('error', 'You are already on an active shift.');
        }

        $user->shifts()->create([
            'started_at' => now(),
        ]);

        return back()->with('success', 'Shift started successfully. Have a great day!');
    }

    public function endShift(Request $request)
    {
        $user = Auth::user();
        $currentShift = $user->shifts()->whereNull('ended_at')->first();

        if (!$currentShift) {
            return back()->with('error', 'You are not currently on a shift.');
        }

        $currentShift->update([
            'ended_at' => now(),
        ]);

        return back()->with('success', 'Shift ended successfully. Great job!');
    }

    public function products(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('name')->paginate(15);
        return view('user.products', compact('products'));
    }

    public function productShow(Product $product)
    {
        $product->load(['category', 'brand']);
        
        // Show up to 10 most recent movements for context
        $movements = $product->stockMovements()->with('user')->orderBy('created_at', 'desc')->take(10)->get();
        
        return view('user.products_show', compact('product', 'movements'));
    }

    public function activity(Request $request)
    {
        $movements = StockMovement::with(['product'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('user.activity', compact('movements'));
    }
}
