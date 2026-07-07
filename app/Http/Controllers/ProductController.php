<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status == 'low') {
                $query->whereColumn('quantity', '<=', 'min_stock_level');
            } elseif ($request->status == 'healthy') {
                $query->whereColumn('quantity', '>', 'min_stock_level');
            }
        }

        $products = $query->with(['category', 'brand'])->orderBy('name')->paginate(15);
        $allProducts = Product::orderBy('name')->get(); 
        $categories = \App\Models\Category::orderBy('name')->get();
        $brands = \App\Models\Brand::orderBy('name')->get();
        
        return view('manager.products', compact('products', 'allProducts', 'categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id'
        ]);

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
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id'
        ]);

        $product->update($validated);

        return back()->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted successfully!');
    }
}
