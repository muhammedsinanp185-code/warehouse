<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\SalesOrder;
use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with(['salesOrder.customer'])->latest()->get();
        return view('manager.shipments.index', compact('shipments'));
    }

    public function store(Request $request, SalesOrder $salesOrder)
    {
        if ($salesOrder->status !== 'confirmed') {
            return back()->with('error', 'Only confirmed orders can be packed.');
        }

        try {
            DB::beginTransaction();

            $shipment = Shipment::create([
                'sales_order_id' => $salesOrder->id,
                'status' => 'packed',
                'tracking_number' => 'TRK-' . strtoupper(Str::random(8))
            ]);

            DB::commit();
            return back()->with('success', 'Order packed successfully. Shipment created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create shipment: ' . $e->getMessage());
        }
    }

    public function ship(Shipment $shipment)
    {
        if ($shipment->status !== 'packed') {
            return back()->with('error', 'Only packed shipments can be shipped.');
        }

        try {
            DB::beginTransaction();

            // Deduct inventory for each item in the sales order
            $salesOrder = $shipment->salesOrder;
            $items = $salesOrder->items;

            foreach ($items as $item) {
                $product = $item->product;
                
                // Check stock
                if ($product->quantity < $item->quantity) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                $balanceBefore = $product->quantity;
                $balanceAfter = $balanceBefore - $item->quantity;

                // Update product quantity
                $product->decrement('quantity', $item->quantity);

                // Create stock movement
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'type' => 'out',
                    'quantity' => $item->quantity,
                    'reference' => 'SO Dispatch: ' . $salesOrder->so_number,
                    'reason' => 'Sales Order Fulfillment',
                    'balance_after' => $balanceAfter
                ]);
            }

            $shipment->update(['status' => 'shipped']);
            
            // Also update sales order status
            $salesOrder->update(['status' => 'shipped']);

            DB::commit();
            return back()->with('success', 'Shipment dispatched! Inventory has been deducted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to dispatch shipment: ' . $e->getMessage());
        }
    }

    public function deliver(Shipment $shipment)
    {
        if ($shipment->status !== 'shipped') {
            return back()->with('error', 'Only shipped shipments can be delivered.');
        }

        $shipment->update(['status' => 'delivered']);
        $shipment->salesOrder->update(['status' => 'delivered']);

        return back()->with('success', 'Shipment marked as delivered.');
    }
}
