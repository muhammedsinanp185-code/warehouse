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

use Barryvdh\DomPDF\Facade\Pdf;

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

    public function create()
    {
        $vendors = \App\Models\Vendor::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('manager.purchase_orders.create', compact('vendors', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'expected_date' => 'nullable|date',
            'reference_number' => 'nullable|string|max:120',
            'payment_terms' => 'nullable|string|max:120',
            'delivery_method' => 'nullable|string|max:120',
            'vendor_notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.tax' => 'nullable|numeric|min:0',
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Generate PO Number
            $latestPo = PurchaseOrder::latest('id')->first();
            $nextId = $latestPo ? $latestPo->id + 1 : 1;
            $poNumber = 'PO-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

            $po = PurchaseOrder::create([
                'po_number' => $poNumber,
                'vendor_id' => $request->vendor_id,
                'reference_number' => $request->reference_number,
                'created_by' => Auth::id(),
                'status' => 'pending',
                'expected_date' => $request->expected_date,
                'payment_terms' => $request->payment_terms,
                'delivery_method' => $request->delivery_method,
                'vendor_notes' => $request->vendor_notes,
                'terms_conditions' => $request->terms_conditions,
                'total_amount' => 0 // calculated below
            ]);

            $grandTotal = 0;

            foreach ($request->items as $item) {
                $qty = $item['quantity'];
                $price = $item['unit_price'];
                $discount = $item['discount'] ?? 0;
                $taxPct = $item['tax'] ?? 0;

                $subtotal = ($qty * $price) - $discount;
                $taxAmount = $subtotal * ($taxPct / 100);
                $lineTotal = $subtotal + $taxAmount;

                $grandTotal += $lineTotal;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'discount' => $discount,
                    'tax' => $taxPct,
                    'total' => $lineTotal
                ]);
            }

            $po->update(['total_amount' => $grandTotal]);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('manager.purchase-orders.show', $po->id)->with('success', 'Purchase Order created successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Error creating Purchase Order: ' . $e->getMessage())->withInput();
        }
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
    public function downloadPdf($id)
    {
        $purchaseOrder = PurchaseOrder::with(['items.product', 'creator', 'receiver'])->findOrFail($id);
        
        $pdf = Pdf::loadView('pdf.purchase_order', compact('purchaseOrder'));
        
        return $pdf->download($purchaseOrder->po_number . '_Purchase_Order.pdf');
    }

    public function downloadPickList($id)
    {
        $purchaseOrder = PurchaseOrder::with(['items.product', 'creator'])->findOrFail($id);
        
        $pdf = Pdf::loadView('pdf.pick_list', compact('purchaseOrder'));
        
        return $pdf->download($purchaseOrder->po_number . '_Pick_List.pdf');
    }
}
