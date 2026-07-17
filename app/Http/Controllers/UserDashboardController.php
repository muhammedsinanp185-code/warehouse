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

        // 1. Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // 2. Category Filter
        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        // 3. Brand Filter
        if ($request->filled('brand')) {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('name', $request->brand);
            });
        }

        // 4. Status Filter
        if ($request->filled('status')) {
            if ($request->status == 'in_stock') {
                $query->whereColumn('quantity', '>', 'min_stock_level');
            } elseif ($request->status == 'low_stock') {
                $query->whereColumn('quantity', '<=', 'min_stock_level')->where('quantity', '>', 0);
            } elseif ($request->status == 'out_of_stock') {
                $query->where('quantity', 0);
            }
        }

        // 5. Sorting
        $sort = $request->query('sort', 'name_asc');
        switch ($sort) {
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'qty_desc':
                $query->orderBy('quantity', 'desc');
                break;
            case 'qty_asc':
                $query->orderBy('quantity', 'asc');
                break;
            case 'name_asc':
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $perPage = $request->input('per_page', 15);
        $products = $query->paginate($perPage)->withQueryString();
        
        $categories = \App\Models\Category::orderBy('name')->get();
        $brands = \App\Models\Brand::orderBy('name')->get();
        
        return view('user.products', compact('products', 'categories', 'brands'));
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
        $query = StockMovement::with(['product'])
            ->where('user_id', Auth::id());

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('product', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('sku', 'like', "%{$search}%");
                })
                ->orWhere('reference_party', 'like', "%{$search}%");
            });
        }

        // Type Filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Date Range Filter
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

        // Sorting
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
            
        return view('user.activity', compact('movements'));
    }
}
