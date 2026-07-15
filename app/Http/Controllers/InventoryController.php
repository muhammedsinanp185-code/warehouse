<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function lowStock(Request $request)
    {
        $lowStockItems = Product::whereColumn('quantity', '<=', 'min_stock_level')
                            ->orderBy('quantity', 'asc')
                            ->paginate(15);
                            
        return view('manager.low_stock', compact('lowStockItems'));
    }

    public function report(Request $request)
    {
        $query = Product::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            if ($request->status == 'low') {
                $query->whereColumn('quantity', '<=', 'min_stock_level');
            } elseif ($request->status == 'healthy') {
                $query->whereColumn('quantity', '>', 'min_stock_level');
            }
        }

        // Sort
        $sort = $request->query('sort', 'name_asc');
        switch ($sort) {
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'qty_desc':
                $query->orderBy('quantity', 'desc');
                break;
            case 'qty_asc':
                $query->orderBy('quantity', 'asc');
                break;
            case 'val_desc':
                $query->orderByRaw('(price * quantity) DESC');
                break;
            case 'val_asc':
                $query->orderByRaw('(price * quantity) ASC');
                break;
            case 'name_asc':
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $allProducts = $query->get();
        return view('manager.inventory_report', compact('allProducts'));
    }

    public function receive(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reference_party' => 'nullable|string|max:255',
            'transaction_date' => 'nullable|date',
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->increment('quantity', $request->quantity);

        $movement = new StockMovement([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'type' => 'in',
            'quantity' => $request->quantity,
            'reference_party' => $request->reference_party,
            'balance_after' => $product->quantity,
        ]);

        if ($request->filled('transaction_date')) {
            $movement->created_at = $request->transaction_date;
            $movement->updated_at = $request->transaction_date;
        }
        $movement->save();

        return back()->with('success', "Received {$request->quantity} units of {$product->name}.");
    }

    public function dispatch(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reference_party' => 'nullable|string|max:255',
            'transaction_date' => 'nullable|date',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->quantity < $request->quantity) {
            return back()->with('error', "Cannot dispatch {$request->quantity} units. Only {$product->quantity} in stock.");
        }

        $product->decrement('quantity', $request->quantity);

        $movement = new StockMovement([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'type' => 'out',
            'quantity' => $request->quantity,
            'reference_party' => $request->reference_party,
            'balance_after' => $product->quantity,
        ]);

        if ($request->filled('transaction_date')) {
            $movement->created_at = $request->transaction_date;
            $movement->updated_at = $request->transaction_date;
        }
        $movement->save();

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
            $query->where(function($q) use ($search) {
                $q->whereHas('product', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('sku', 'like', "%{$search}%");
                })
                ->orWhereHas('user', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })
                ->orWhere('reference_party', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', \Carbon\Carbon::today());
                    break;
                case '7days':
                    $query->where('created_at', '>=', \Carbon\Carbon::now()->subDays(7));
                    break;
                case '30days':
                    $query->where('created_at', '>=', \Carbon\Carbon::now()->subDays(30));
                    break;
                case 'custom':
                    if ($request->filled('exact_date')) {
                        $query->whereDate('created_at', $request->exact_date);
                    }
                    break;
            }
        }

        $sort = $request->query('sort', 'date_desc');
        switch ($sort) {
            case 'date_asc':
                $query->oldest();
                break;
            case 'qty_desc':
                $query->orderBy('quantity', 'desc');
                break;
            case 'qty_asc':
                $query->orderBy('quantity', 'asc');
                break;
            case 'date_desc':
            default:
                $query->latest();
                break;
        }

        $movements = $query->paginate(15)->withQueryString();
        $allProducts = Product::orderBy('name')->get(); 
        
        $totalReceived = StockMovement::where('type', 'in')->sum('quantity');
        $totalDispatched = StockMovement::where('type', 'out')->sum('quantity');
        $totalMovements = StockMovement::count();

        return view('manager.inventory', compact('movements', 'allProducts', 'totalReceived', 'totalDispatched', 'totalMovements'));
    }

    public function destroy(StockMovement $movement)
    {
        // Users can only delete their own movements. Managers can delete any.
        if (Auth::user()->role !== 'manager' && $movement->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        DB::transaction(function () use ($movement) {
            $product = $movement->product;
            
            if ($movement->type == 'in') {
                $product->decrement('quantity', $movement->quantity);
            } else {
                $product->increment('quantity', $movement->quantity);
            }

            $movement->delete();
            
            $this->recalculateBalancesForProduct($product);
        });

        return back()->with('success', 'Movement deleted and inventory reverted successfully!');
    }

    private function recalculateBalancesForProduct(Product $product)
    {
        $movements = StockMovement::where('product_id', $product->id)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->get();
            
        $currentBalance = $product->quantity;
        
        foreach ($movements as $mov) {
            DB::table('stock_movements')
                ->where('id', $mov->id)
                ->update(['balance_after' => $currentBalance]);
                
            if ($mov->type == 'in') {
                $currentBalance -= $mov->quantity;
            } else {
                $currentBalance += $mov->quantity;
            }
        }
    }
}
