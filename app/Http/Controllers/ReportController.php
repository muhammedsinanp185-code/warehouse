<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\PurchaseOrder;
use App\Models\Shipment;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('manager.reports.index');
    }

    public function salesByCustomer(Request $request)
    {
        $startDate = $request->query('from_date');
        $endDate = $request->query('to_date');

        $query = Customer::withCount(['salesOrders as invoice_count' => function($q) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }
        }])
        ->withSum(['salesOrders as total_sales' => function($q) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }
        }], 'total_amount');

        $customers = $query->get();

        return view('manager.reports.sales_by_customer', compact('customers', 'startDate', 'endDate'));
    }

    public function salesByItem(Request $request)
    {
        $items = SalesOrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total) as total_revenue'))
            ->with('product')
            ->groupBy('product_id')
            ->get();

        return view('manager.reports.sales_by_item', compact('items'));
    }

    public function purchasesByVendor(Request $request)
    {
        $vendors = Vendor::withCount('purchaseOrders')
            ->withSum('purchaseOrders as total_purchases', 'total_amount')
            ->get();

        return view('manager.reports.purchases_by_vendor', compact('vendors'));
    }

    public function orderFulfillment(Request $request)
    {
        $shipments = Shipment::with(['salesOrder.customer', 'salesOrder.items.product'])
            ->latest()
            ->get();

        return view('manager.reports.order_fulfillment', compact('shipments'));
    }

    public function packingHistory(Request $request)
    {
        $shipments = Shipment::with(['salesOrder.customer'])->latest()->get();

        return view('manager.reports.packing_history', compact('shipments'));
    }
}
