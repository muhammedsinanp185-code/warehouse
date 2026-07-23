<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SalesOrderController extends Controller
{
    public function index()
    {
        $salesOrders = SalesOrder::with(['customer'])->latest()->get();
        return view('manager.sales_orders.index', compact('salesOrders'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('manager.sales_orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'expected_shipment_date' => 'nullable|date',
            'reference_number' => 'nullable|string|max:120',
            'payment_terms' => 'nullable|string|max:120',
            'delivery_method' => 'nullable|string|max:120',
            'customer_notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.tax' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Generate SO Number
            $latestSo = SalesOrder::latest('id')->first();
            $nextId = $latestSo ? $latestSo->id + 1 : 1;
            $soNumber = 'SO-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

            $salesOrder = SalesOrder::create([
                'customer_id' => $request->customer_id,
                'so_number' => $soNumber,
                'reference_number' => $request->reference_number,
                'status' => 'draft',
                'order_date' => $request->order_date,
                'expected_shipment_date' => $request->expected_shipment_date,
                'payment_terms' => $request->payment_terms,
                'delivery_method' => $request->delivery_method,
                'customer_notes' => $request->customer_notes,
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

                SalesOrderItem::create([
                    'sales_order_id' => $salesOrder->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'discount' => $discount,
                    'tax' => $taxPct,
                    'total' => $lineTotal
                ]);
            }

            $salesOrder->update(['total_amount' => $grandTotal]);

            DB::commit();
            return redirect()->route('manager.sales_orders.show', $salesOrder)->with('success', 'Sales Order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating Sales Order: ' . $e->getMessage())->withInput();
        }
    }

    public function show(SalesOrder $salesOrder)
    {
        $salesOrder->load(['customer', 'items.product', 'shipments']);
        return view('manager.sales_orders.show', compact('salesOrder'));
    }

    public function confirm(SalesOrder $salesOrder)
    {
        if ($salesOrder->status !== 'draft') {
            return back()->with('error', 'Only draft orders can be confirmed.');
        }

        $salesOrder->update(['status' => 'confirmed']);
        return back()->with('success', 'Sales Order confirmed. It is now ready for packing.');
    }
}
