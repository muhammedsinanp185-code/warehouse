<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Shift;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function managerIndex()
    {
        $totalProducts = Product::count();
        $inventoryValue = Product::selectRaw('SUM(quantity * price) as total')->value('total') ?? 0;
        
        $lowStockItems = Product::whereColumn('quantity', '<=', 'min_stock_level')->get();
        $lowStockCount = $lowStockItems->count();
        
        // --- ZOHO STYLE DATA ---
        
        // Sales Activity
        // To Be Packed = Confirmed Sales Orders
        $toBePacked = \App\Models\SalesOrder::where('status', 'confirmed')->count();
        // To Be Shipped = Packed Shipments
        $toBeShipped = \App\Models\Shipment::where('status', 'packed')->count();
        // To Be Delivered = Shipped Shipments
        $toBeDelivered = \App\Models\Shipment::where('status', 'shipped')->count();
        // To Be Invoiced = Delivered Sales Orders (Mock logic)
        $toBeInvoiced = \App\Models\SalesOrder::where('status', 'delivered')->count();

        // Inventory Summary
        $quantityInHand = Product::sum('quantity') ?? 0;
        // Quantity to be received = Sum of quantities in Pending Purchase Orders
        $quantityToBeReceived = \App\Models\PurchaseOrder::where('status', 'pending')
                                ->join('purchase_order_items', 'purchase_orders.id', '=', 'purchase_order_items.purchase_order_id')
                                ->sum('purchase_order_items.quantity') ?? 0;

        // Purchase Order stats
        $poRange = request()->query('po_range', 'month');
        $poQuery = \App\Models\PurchaseOrder::query();
        $now = Carbon::now();
        if ($poRange === 'day') {
            $poQuery->whereDate('created_at', $now->toDateString());
        } elseif ($poRange === 'week') {
            $poQuery->whereBetween('created_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]);
        } elseif ($poRange === 'month') {
            $poQuery->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
        } elseif ($poRange === 'year') {
            $poQuery->whereYear('created_at', $now->year);
        }
        $poAmountOrdered = $poQuery->sum('total_amount') ?? 0;

        // Top Selling Items (Mocked by highest out movements)
        $topSelling = Product::withCount(['stockMovements as out_count' => function ($query) {
            $query->where('type', 'out');
        }])->orderByDesc('out_count')->take(2)->get();
        
        // Active items vs Unconfirmed
        // In our DB, we don't have "unconfirmed" status for products, so we fetch actual total and assume 0 or handle logic if needed.
        $activeItems = $totalProducts;
        $unconfirmedItems = 0; 
        
        $salesOrdersTable = (object)[
            'draft' => \App\Models\SalesOrder::where('status', 'draft')->count(),
            'confirmed' => \App\Models\SalesOrder::where('status', 'confirmed')->count(),
            'packed' => \App\Models\Shipment::where('status', 'packed')->count(),
            'shipped' => \App\Models\Shipment::where('status', 'shipped')->count()
        ];

        // Active shifts for the sidebar/footer if needed
        $activeShifts = Shift::with('user')->whereNull('ended_at')->orderBy('started_at', 'desc')->get();
        
        $recentMovements = StockMovement::with(['product', 'user', 'document'])
                            ->latest()
                            ->limit(5)
                            ->get();
                            
        return view('manager.dashboard', compact(
            'totalProducts', 'inventoryValue', 'lowStockCount', 'lowStockItems',
            'toBePacked', 'toBeShipped', 'toBeDelivered', 'toBeInvoiced',
            'quantityInHand', 'quantityToBeReceived',
            'activeItems', 'unconfirmedItems',
            'topSelling', 'poAmountOrdered', 'poRange', 'salesOrdersTable',
            'recentMovements', 'activeShifts'
        ));
    }
    public function chartData(Request $request)
    {
        $range = $request->query('range', 'week');
        $compare = $request->query('compare', '0') === '1';
        $startInput = $request->query('start');
        $endInput = $request->query('end');

        $now = Carbon::now();
        $startDate = $now->copy()->startOfDay();
        $endDate = $now->copy()->endOfDay();
        $grouping = 'day';
        
        if ($range === 'day') {
            $startDate = $now->copy()->startOfDay();
            $grouping = 'hour';
        } elseif ($range === 'week') {
            $startDate = $now->copy()->subDays(6)->startOfDay();
            $grouping = 'day';
        } elseif ($range === 'month') {
            $startDate = $now->copy()->subDays(29)->startOfDay();
            $grouping = 'day';
        } elseif ($range === '3months') {
            $startDate = $now->copy()->subMonths(3)->startOfDay();
            $grouping = 'week';
        } elseif ($range === '6months') {
            $startDate = $now->copy()->subMonths(6)->startOfDay();
            $grouping = 'month';
        } elseif ($range === 'custom' && $startInput && $endInput) {
            $startDate = Carbon::parse($startInput)->startOfDay();
            $endDate = Carbon::parse($endInput)->endOfDay();
            $daysDiff = $startDate->diffInDays($endDate);
            if ($daysDiff <= 1) {
                $grouping = 'hour';
            } elseif ($daysDiff <= 31) {
                $grouping = 'day';
            } elseif ($daysDiff <= 90) {
                $grouping = 'week';
            } else {
                $grouping = 'month';
            }
        }

        $mainData = $this->getAggregatedData($startDate, $endDate, $grouping);

        $response = [
            'labels' => $mainData['labels'],
            'received' => $mainData['received'],
            'dispatched' => $mainData['dispatched'],
            'received_amount' => $mainData['received_amount'],
            'dispatched_amount' => $mainData['dispatched_amount'],
            'received_exact' => $mainData['received_exact'],
            'dispatched_exact' => $mainData['dispatched_exact']
        ];

        if ($compare) {
            $diffSeconds = $startDate->diffInSeconds($endDate);
            $compareEndDate = $startDate->copy()->subSecond();
            $compareStartDate = $compareEndDate->copy()->subSeconds($diffSeconds);

            $compareData = $this->getAggregatedData($compareStartDate, $compareEndDate, $grouping);
            
            $response['compare_received'] = array_pad(array_slice($compareData['received'], 0, count($mainData['labels'])), count($mainData['labels']), 0);
            $response['compare_dispatched'] = array_pad(array_slice($compareData['dispatched'], 0, count($mainData['labels'])), count($mainData['labels']), 0);
        }

        return response()->json($response);
    }

    private function getAggregatedData($startDate, $endDate, $grouping)
    {
        $movements = StockMovement::with('product')->whereBetween('created_at', [$startDate, $endDate])->get();
        
        $labels = [];
        $receivedData = [];
        $dispatchedData = [];
        $receivedAmountData = [];
        $dispatchedAmountData = [];
        $receivedExact = [];
        $dispatchedExact = [];

        $inTypes = ['in', 'purchase_receipt', 'receive'];
        $outTypes = ['out', 'sales_shipment', 'dispatch'];

        if ($grouping === 'hour') {
            $hours = $startDate->diffInHours($endDate) ?: 23;
            for ($i = 0; $i <= $hours; $i++) {
                $time = $startDate->copy()->addHours($i);
                $labels[] = $time->format('g A');
                
                $m = $movements->filter(function($item) use ($time) {
                    return Carbon::parse($item->created_at)->format('Y-m-d H') === $time->format('Y-m-d H');
                });
                $in = $m->whereIn('type', $inTypes);
                $out = $m->whereIn('type', $outTypes);
                
                $receivedData[] = (int) $in->sum('quantity');
                $dispatchedData[] = (int) $out->sum('quantity');
                
                $receivedAmountData[] = (float) $in->sum(function($item) { return $item->quantity * ($item->product->cost_price ?? $item->product->price ?? 0); });
                $dispatchedAmountData[] = (float) $out->sum(function($item) { return $item->quantity * ($item->product->price ?? 0); });

                $rTimes = $in->map(function($i) { return Carbon::parse($i->created_at)->format('g:i A'); })->unique()->values()->toArray();
                $dTimes = $out->map(function($i) { return Carbon::parse($i->created_at)->format('g:i A'); })->unique()->values()->toArray();
                $receivedExact[] = empty($rTimes) ? null : 'at ' . implode(', ', $rTimes);
                $dispatchedExact[] = empty($dTimes) ? null : 'at ' . implode(', ', $dTimes);
            }
        } elseif ($grouping === 'day') {
            $days = $startDate->diffInDays($endDate);
            for ($i = 0; $i <= $days; $i++) {
                $date = $startDate->copy()->addDays($i);
                $labels[] = $date->format('M d');
                
                $m = $movements->filter(function($item) use ($date) {
                    return Carbon::parse($item->created_at)->format('Y-m-d') === $date->format('Y-m-d');
                });
                $in = $m->whereIn('type', $inTypes);
                $out = $m->whereIn('type', $outTypes);

                $receivedData[] = (int) $in->sum('quantity');
                $dispatchedData[] = (int) $out->sum('quantity');
                $receivedAmountData[] = (float) $in->sum(function($item) { return $item->quantity * ($item->product->cost_price ?? $item->product->price ?? 0); });
                $dispatchedAmountData[] = (float) $out->sum(function($item) { return $item->quantity * ($item->product->price ?? 0); });
                
                $receivedExact[] = null;
                $dispatchedExact[] = null;
            }
        } elseif ($grouping === 'week') {
            $weeks = $startDate->diffInWeeks($endDate);
            for ($i = 0; $i <= $weeks; $i++) {
                $startOfWeek = $startDate->copy()->addWeeks($i);
                $endOfWeek = $startOfWeek->copy()->addDays(6);
                if ($endOfWeek > $endDate) $endOfWeek = $endDate->copy();
                
                $labels[] = $startOfWeek->format('M d') . ' - ' . $endOfWeek->format('d');
                
                $m = $movements->filter(function($item) use ($startOfWeek, $endOfWeek) {
                    $cDate = Carbon::parse($item->created_at);
                    return $cDate >= $startOfWeek && $cDate <= $endOfWeek->copy()->endOfDay();
                });
                $in = $m->whereIn('type', $inTypes);
                $out = $m->whereIn('type', $outTypes);

                $receivedData[] = (int) $in->sum('quantity');
                $dispatchedData[] = (int) $out->sum('quantity');
                $receivedAmountData[] = (float) $in->sum(function($item) { return $item->quantity * ($item->product->cost_price ?? $item->product->price ?? 0); });
                $dispatchedAmountData[] = (float) $out->sum(function($item) { return $item->quantity * ($item->product->price ?? 0); });
                
                $receivedExact[] = null;
                $dispatchedExact[] = null;
            }
        } elseif ($grouping === 'month') {
            $months = $startDate->diffInMonths($endDate);
            for ($i = 0; $i <= $months; $i++) {
                $monthDate = $startDate->copy()->addMonths($i);
                $labels[] = $monthDate->format('M Y');
                
                $m = $movements->filter(function($item) use ($monthDate) {
                    return Carbon::parse($item->created_at)->format('Y-m') === $monthDate->format('Y-m');
                });
                $in = $m->whereIn('type', $inTypes);
                $out = $m->whereIn('type', $outTypes);

                $receivedData[] = (int) $in->sum('quantity');
                $dispatchedData[] = (int) $out->sum('quantity');
                $receivedAmountData[] = (float) $in->sum(function($item) { return $item->quantity * ($item->product->cost_price ?? $item->product->price ?? 0); });
                $dispatchedAmountData[] = (float) $out->sum(function($item) { return $item->quantity * ($item->product->price ?? 0); });

                $receivedExact[] = null;
                $dispatchedExact[] = null;
            }
        }

        return [
            'labels' => $labels,
            'received' => $receivedData,
            'dispatched' => $dispatchedData,
            'received_amount' => $receivedAmountData,
            'dispatched_amount' => $dispatchedAmountData,
            'received_exact' => $receivedExact,
            'dispatched_exact' => $dispatchedExact
        ];
    }
}
