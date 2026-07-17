<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['creator', 'receiver'])
            ->withCount('items');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('po_number', 'like', "%{$search}%");
        }
        
        $sort = $request->query('sort', 'date_desc');
        switch ($sort) {
            case 'date_asc':
                $query->oldest();
                break;
            case 'date_desc':
            default:
                $query->latest();
                break;
        }

        $purchaseOrders = $query->paginate(15)->withQueryString();
        
        return view('manager.purchase_orders.index', compact('purchaseOrders'));
    }

    public function show($id)
    {
        $purchaseOrder = PurchaseOrder::with(['items.product', 'creator', 'receiver'])->findOrFail($id);
        return view('manager.purchase_orders.show', compact('purchaseOrder'));
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'numeric|min:1'
        ]);

        $products = Product::whereIn('id', $request->product_ids)->get();

        if ($products->isEmpty()) {
            return back()->with('error', 'No valid products selected.');
        }

        // Generate PO Number
        $poNumber = 'PO-' . strtoupper(Str::random(8)) . '-' . date('Ymd');

        $po = PurchaseOrder::create([
            'po_number' => $poNumber,
            'created_by' => Auth::id(),
            'status' => 'pending',
        ]);

        foreach ($products as $product) {
            $orderQty = $request->quantities[$product->id] ?? (max($product->min_stock_level - $product->quantity, 0) + 50);
            
            PurchaseOrderItem::create([
                'purchase_order_id' => $po->id,
                'product_id' => $product->id,
                'quantity' => $orderQty,
            ]);
        }

        return redirect()->route('manager.purchase-orders.show', $po->id)
            ->with('success', 'Purchase Order ' . $poNumber . ' created successfully for ' . $products->count() . ' items.');
    }

    public function receive($id)
    {
        $po = PurchaseOrder::with('items.product')->findOrFail($id);

        if ($po->status !== 'pending') {
            return back()->with('error', 'This purchase order has already been processed.');
        }

        foreach ($po->items as $item) {
            // Update product quantity
            $item->product->increment('quantity', $item->quantity);

            // Record stock movement
            StockMovement::create([
                'product_id' => $item->product_id,
                'user_id' => Auth::id(),
                'type' => 'in',
                'quantity' => $item->quantity,
                'reference_party' => 'Supplier via ' . $po->po_number,
                'balance_after' => $item->product->quantity,
            ]);
        }

        $po->update([
            'status' => 'received',
            'received_at' => Carbon::now(),
            'received_by' => Auth::id(),
        ]);

        return back()->with('success', 'Purchase Order received successfully. Inventory has been updated.');
    }
}
