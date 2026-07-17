<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

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
            case 'name_asc':
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $perPage = $request->input('per_page', 15);
        $products = $query->with(['category', 'brand'])->paginate($perPage)->withQueryString();
        
        $allProducts = Product::orderBy('name')->get(); 
        $categories = \App\Models\Category::orderBy('name')->get();
        $brands = \App\Models\Brand::orderBy('name')->get();
        
        return view('manager.products', compact('products', 'allProducts', 'categories', 'brands'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'stockMovements.user']);
        
        // Let's sort the stock movements by newest first and limit to 4
        $movements = $product->stockMovements()->with('user')->orderBy('created_at', 'desc')->take(4)->get();
        
        return view('manager.products_show', compact('product', 'movements'));
    }

    public function history(Request $request, Product $product)
    {
        $query = $product->stockMovements()->with('user');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
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
        
        return view('manager.products_history', compact('product', 'movements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'category_name' => 'nullable|string|max:255',
            'brand_name' => 'nullable|string|max:255'
        ]);

        if (!empty($request->category_name)) {
            $category = \App\Models\Category::firstOrCreate(['name' => $request->category_name]);
            $validated['category_id'] = $category->id;
        }

        if (!empty($request->brand_name)) {
            $brand = \App\Models\Brand::firstOrCreate(['name' => $request->brand_name]);
            $validated['brand_id'] = $brand->id;
        }
        
        unset($validated['category_name'], $validated['brand_name']);

        Product::create($validated);

        return back()->with('success', 'Product added successfully!');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'category_name' => 'nullable|string|max:255',
            'brand_name' => 'nullable|string|max:255'
        ]);

        if (!empty($request->category_name)) {
            $category = \App\Models\Category::firstOrCreate(['name' => $request->category_name]);
            $validated['category_id'] = $category->id;
        } else {
            $validated['category_id'] = null;
        }

        if (!empty($request->brand_name)) {
            $brand = \App\Models\Brand::firstOrCreate(['name' => $request->brand_name]);
            $validated['brand_id'] = $brand->id;
        } else {
            $validated['brand_id'] = null;
        }
        
        unset($validated['category_name'], $validated['brand_name']);

        $product->update($validated);

        return back()->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted successfully!');
    }
}
