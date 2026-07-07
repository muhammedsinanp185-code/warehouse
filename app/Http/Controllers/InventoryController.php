<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function lowStock(Request $request)
    {
        $lowStockItems = Product::whereColumn('quantity', '<=', 'min_stock_level')
                            ->orderBy('quantity', 'asc')
                            ->paginate(15);
                            
        return view('manager.low_stock', compact('lowStockItems'));
    }

    public function receive(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reference_party' => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->increment('quantity', $request->quantity);

        StockMovement::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'type' => 'in',
            'quantity' => $request->quantity,
            'reference_party' => $request->reference_party,
        ]);

        return back()->with('success', "Received {$request->quantity} units of {$product->name}.");
    }

    public function dispatch(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reference_party' => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->quantity < $request->quantity) {
            return back()->with('error', "Cannot dispatch {$request->quantity} units. Only {$product->quantity} in stock.");
        }

        $product->decrement('quantity', $request->quantity);

        StockMovement::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'type' => 'out',
            'quantity' => $request->quantity,
            'reference_party' => $request->reference_party,
        ]);

        return back()->with('success', "Dispatched {$request->quantity} units of {$product->name}.");
    }

    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'user']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $movements = $query->latest()->paginate(15);
        $allProducts = Product::orderBy('name')->get(); 
        
        $totalReceived = StockMovement::where('type', 'in')->sum('quantity');
        $totalDispatched = StockMovement::where('type', 'out')->sum('quantity');
        $totalMovements = StockMovement::count();

        return view('manager.inventory', compact('movements', 'allProducts', 'totalReceived', 'totalDispatched', 'totalMovements'));
    }

    public function destroy(StockMovement $movement)
    {
        $product = $movement->product;
        
        if ($movement->type == 'in') {
            $product->decrement('quantity', $movement->quantity);
        } else {
            $product->increment('quantity', $movement->quantity);
        }

        $movement->delete();

        return back()->with('success', 'Movement deleted and inventory reverted successfully!');
    }
}
