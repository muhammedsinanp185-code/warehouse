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
        
        // Calculate total value: sum of (quantity * price) for all products
        $inventoryValue = Product::selectRaw('SUM(quantity * price) as total')->value('total');
        $inventoryValue = $inventoryValue ? $inventoryValue : 0;
        
        // Get products where quantity is less than or equal to their min_stock_level
        $lowStockItems = Product::whereColumn('quantity', '<=', 'min_stock_level')->orderBy('quantity')->get();
        $lowStockCount = $lowStockItems->count();
        
        // Count today's stock movements
        $todayActivity = StockMovement::whereDate('created_at', Carbon::today())->count();
        
        // Get the latest 5 movements with the related product and user
        $recentMovements = StockMovement::with(['product', 'user'])
                            ->latest()
                            ->limit(5)
                            ->get();
                            
        // Get all products to populate the modals dropdown
        $allProducts = Product::orderBy('name')->get();

        // Get active shifts (employees currently clocked in)
        $activeShifts = Shift::with('user')->whereNull('ended_at')->orderBy('started_at', 'desc')->get();
                            
        return view('manager.dashboard', compact(
            'totalProducts', 
            'inventoryValue', 
            'lowStockCount', 
            'lowStockItems',
            'todayActivity', 
            'recentMovements',
            'allProducts',
            'activeShifts'
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
        $movements = StockMovement::whereBetween('created_at', [$startDate, $endDate])->get();
        
        $labels = [];
        $receivedData = [];
        $dispatchedData = [];
        $receivedExact = [];
        $dispatchedExact = [];

        if ($grouping === 'hour') {
            $hours = $startDate->diffInHours($endDate) ?: 23;
            for ($i = 0; $i <= $hours; $i++) {
                $time = $startDate->copy()->addHours($i);
                $labels[] = $time->format('g A');
                
                $m = $movements->filter(function($item) use ($time) {
                    return $item->created_at->format('Y-m-d H') === $time->format('Y-m-d H');
                });
                $in = $m->where('type', 'in');
                $out = $m->where('type', 'out');
                
                $receivedData[] = (int) $in->sum('quantity');
                $dispatchedData[] = (int) $out->sum('quantity');
                
                $rTimes = $in->map(function($i) { return $i->created_at->format('g:i A'); })->unique()->values()->toArray();
                $dTimes = $out->map(function($i) { return $i->created_at->format('g:i A'); })->unique()->values()->toArray();
                $receivedExact[] = empty($rTimes) ? null : 'at ' . implode(', ', $rTimes);
                $dispatchedExact[] = empty($dTimes) ? null : 'at ' . implode(', ', $dTimes);
            }
        } elseif ($grouping === 'day') {
            $days = $startDate->diffInDays($endDate);
            for ($i = 0; $i <= $days; $i++) {
                $date = $startDate->copy()->addDays($i);
                $labels[] = $date->format('M d');
                
                $m = $movements->filter(function($item) use ($date) {
                    return $item->created_at->format('Y-m-d') === $date->format('Y-m-d');
                });
                
                $receivedData[] = (int) $m->where('type', 'in')->sum('quantity');
                $dispatchedData[] = (int) $m->where('type', 'out')->sum('quantity');
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
                    return $item->created_at >= $startOfWeek && $item->created_at <= $endOfWeek->copy()->endOfDay();
                });
                
                $receivedData[] = (int) $m->where('type', 'in')->sum('quantity');
                $dispatchedData[] = (int) $m->where('type', 'out')->sum('quantity');
                $receivedExact[] = null;
                $dispatchedExact[] = null;
            }
        } elseif ($grouping === 'month') {
            $months = $startDate->diffInMonths($endDate);
            for ($i = 0; $i <= $months; $i++) {
                $monthDate = $startDate->copy()->addMonths($i);
                $labels[] = $monthDate->format('M Y');
                
                $m = $movements->filter(function($item) use ($monthDate) {
                    return $item->created_at->format('Y-m') === $monthDate->format('Y-m');
                });
                
                $receivedData[] = (int) $m->where('type', 'in')->sum('quantity');
                $dispatchedData[] = (int) $m->where('type', 'out')->sum('quantity');
                $receivedExact[] = null;
                $dispatchedExact[] = null;
            }
        }

        return [
            'labels' => $labels,
            'received' => $receivedData,
            'dispatched' => $dispatchedData,
            'received_exact' => $receivedExact,
            'dispatched_exact' => $dispatchedExact
        ];
    }
}
