@extends('layouts.manager')

@section('page_title', 'DASHBOARD')

@section('content')
<style>
    .zoho-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .zoho-card {
        background: var(--glass-bg-05);
        border: 1px solid var(--glass-border-10);
        border-radius: 12px;
        backdrop-filter: blur(10px);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
    }

    .zoho-card-header {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--glass-border-10);
    }

    .activity-boxes {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }

    .activity-box {
        text-align: center;
        padding: 1rem;
        background: var(--glass-bg-03);
        border: 1px solid var(--glass-border-10);
        border-radius: 8px;
        transition: transform 0.2s, background 0.2s;
        text-decoration: none;
        display: block;
        cursor: pointer;
    }
    .activity-box:hover {
        background: var(--glass-bg-10);
        transform: translateY(-2px);
    }

    .activity-box h2 {
        font-size: 2rem;
        font-weight: 300;
        color: #3b82f6;
        margin: 0 0 0.5rem 0;
    }
    .activity-box .label {
        font-size: 0.75rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px dashed var(--glass-border-20);
    }
    .summary-row:last-child {
        border-bottom: none;
    }
    .summary-label {
        font-size: 0.9rem;
        color: var(--text-color);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .summary-value {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--glass-border-10);
    }
    .detail-item:last-child {
        border-bottom: none;
    }

    .po-qty {
        font-size: 3rem;
        font-weight: 300;
        color: #3b82f6;
        text-align: center;
        margin-top: 1rem;
    }
    
    .product-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        background: var(--glass-bg-10);
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<!-- Row 1: Sales Activity (Full Width) -->
<div style="display: grid; grid-template-columns: 1fr; margin-bottom: 1.5rem;">
    <div class="zoho-card">
        <div class="zoho-card-header">Sales Activity</div>
        <div class="activity-boxes">
            <a href="{{ route('manager.sales_orders.index') }}" class="activity-box">
                <h2>{{ $toBePacked }}</h2>
                <div class="label">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    TO BE PACKED
                </div>
            </a>
            <a href="{{ route('manager.shipments.index') }}" class="activity-box">
                <h2 style="color: #ef4444;">{{ $toBeShipped }}</h2>
                <div class="label">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" /></svg>
                    TO BE SHIPPED
                </div>
            </a>
            <a href="{{ route('manager.shipments.index') }}" class="activity-box">
                <h2 style="color: #10b981;">{{ $toBeDelivered }}</h2>
                <div class="label">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                    TO BE DELIVERED
                </div>
            </a>
            <a href="{{ route('manager.sales_orders.index') }}" class="activity-box">
                <h2>{{ $toBeInvoiced }}</h2>
                <div class="label">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    TO BE INVOICED
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Row 2: Inventory Summary & Product Details -->
<div class="zoho-grid">
    <!-- Inventory Summary -->
    <div class="zoho-card">
        <div class="zoho-card-header">Inventory Summary</div>
        <a href="{{ route('manager.products.index') }}" class="summary-row" style="text-decoration: none; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='var(--glass-bg-05)'" onmouseout="this.style.background='transparent'">
            <span class="summary-label">Quantity In Hand</span>
            <span class="summary-value">{{ number_format($quantityInHand) }}</span>
        </a>
        <a href="{{ route('manager.purchase-orders.index') }}" class="summary-row" style="text-decoration: none; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='var(--glass-bg-05)'" onmouseout="this.style.background='transparent'">
            <span class="summary-label">Quantity To Be Received</span>
            <span class="summary-value">{{ number_format($quantityToBeReceived) }}</span>
        </a>
    </div>

    <!-- Product Details -->
    <div class="zoho-card">
        <div class="zoho-card-header">Product Details</div>
        <div class="details-grid">
            <div>
                <a href="{{ route('manager.low-stock') }}" class="detail-item" style="text-decoration: none; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='var(--glass-bg-05)'" onmouseout="this.style.background='transparent'">
                    <span style="color: #ef4444;">Low Stock Items</span>
                    <span style="color: #ef4444; font-weight: bold;">{{ $lowStockCount }}</span>
                </a>
                <a href="{{ route('categories.index') }}" class="detail-item" style="text-decoration: none; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='var(--glass-bg-05)'" onmouseout="this.style.background='transparent'">
                    <span style="color: var(--text-muted);">All Item Group</span>
                    <span style="font-weight: bold;">0</span>
                </a>
                <a href="{{ route('manager.products.index') }}" class="detail-item" style="text-decoration: none; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='var(--glass-bg-05)'" onmouseout="this.style.background='transparent'">
                    <span style="color: var(--text-muted);">All Items</span>
                    <span style="font-weight: bold;">{{ $totalProducts }}</span>
                </a>
                <a href="{{ route('manager.products.index') }}" class="detail-item" style="text-decoration: none; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='var(--glass-bg-05)'" onmouseout="this.style.background='transparent'">
                    <span style="color: #ef4444;">Unconfirmed Items ⓘ</span>
                    <span style="color: #ef4444; font-weight: bold;">{{ $unconfirmedItems }}</span>
                </a>
            </div>
            <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; background: var(--glass-bg-03); border-radius: 8px;">
                <div style="font-size: 2.5rem; font-weight: 300; color: #10b981;">{{ $totalProducts > 0 ? round((($totalProducts - $lowStockCount) / $totalProducts) * 100) : 0 }}%</div>
                <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Active Items</div>
            </div>
        </div>
    </div>
</div>

<!-- Row 3: Stock Movements (Chart) & Top Selling Items -->
<div class="zoho-grid" style="grid-template-columns: 2fr 1fr;">
    <!-- Stock Movements Chart -->
    <div class="zoho-card">
        <div class="zoho-card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span>Stock Movements Over Time</span>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <select id="chartMetric" onchange="loadChartData()" style="background: var(--glass-bg-10); border: 1px solid var(--glass-border-20); color: var(--text-color); border-radius: 6px; padding: 0.3rem 0.6rem; font-size: 0.8rem; outline: none; font-weight: 500;">
                    <option value="qty">Units (Quantity)</option>
                    <option value="amount">Dollar Value ($)</option>
                </select>
                <select id="chartRange" onchange="loadChartData()" style="background: var(--glass-bg-10); border: 1px solid var(--glass-border-20); color: var(--text-color); border-radius: 6px; padding: 0.3rem 0.6rem; font-size: 0.8rem; outline: none; font-weight: 500;">
                    <option value="day">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="6months">Last 6 Months</option>
                </select>
            </div>
        </div>
        <div style="position: relative; height: 250px; width: 100%;">
            <canvas id="movementsChart"></canvas>
        </div>
    </div>

    <!-- Top Selling Items -->
    <div class="zoho-card">
        <div class="zoho-card-header" style="display: flex; justify-content: space-between;">
            Top Selling Items
            <span style="font-size: 0.8rem; font-weight: normal; color: var(--text-muted); cursor: pointer;">This Month ▼</span>
        </div>
        <div style="display: flex; justify-content: space-around; align-items: center; height: 100%;">
            @forelse($topSelling as $item)
            <div style="text-align: center;">
                <div class="product-img" style="margin: 0 auto 0.5rem auto;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 30px; height: 30px; color: var(--text-muted);"><path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                </div>
                <div style="font-size: 0.85rem; color: var(--text-muted); max-width: 120px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item->name }}</div>
                <div style="font-size: 1.25rem; font-weight: 600;">{{ $item->out_count }} <span style="font-size: 0.8rem; font-weight: normal; color: var(--text-muted);">pcs</span></div>
            </div>
            @empty
            <div style="color: var(--text-muted); font-size: 0.9rem;">No sales data available yet.</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Row 4: Purchase Order & Sales Order Pipelines -->
<div class="zoho-grid">
    <!-- Purchase Order -->
    <div class="zoho-card">
        <div class="zoho-card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <a href="{{ route('manager.purchase-orders.index') }}" style="text-decoration: none; color: inherit;">Purchase Order</a>
            <form method="GET" action="{{ route('manager.dashboard') }}" style="margin: 0;">
                <!-- Preserve existing query parameters -->
                @if(request('range')) <input type="hidden" name="range" value="{{ request('range') }}"> @endif
                @if(request('compare')) <input type="hidden" name="compare" value="{{ request('compare') }}"> @endif
                
                <select name="po_range" onchange="this.form.submit()" style="background: var(--glass-bg-10); border: 1px solid var(--glass-border-20); color: var(--text-color); border-radius: 6px; padding: 0.2rem 0.5rem; font-size: 0.8rem; outline: none;">
                    <option value="day" {{ $poRange == 'day' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ $poRange == 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ $poRange == 'month' ? 'selected' : '' }}>This Month</option>
                    <option value="year" {{ $poRange == 'year' ? 'selected' : '' }}>This Year</option>
                </select>
            </form>
        </div>
        <a href="{{ route('manager.purchase-orders.index') }}" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; text-decoration: none; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
            <div style="font-size: 1rem; color: var(--text-muted);">Amount Ordered ($)</div>
            <div class="po-qty">{{ number_format($poAmountOrdered, 2) }}</div>
        </a>
    </div>

    <!-- Sales Order Pipeline -->
    <div class="zoho-card">
        <div class="zoho-card-header">Sales Order Pipeline</div>
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid var(--glass-border-10);">
                    <th style="padding: 0.75rem 0; font-size: 0.85rem; color: var(--text-muted); font-weight: normal;">Channel</th>
                    <th style="padding: 0.75rem 0; font-size: 0.85rem; color: var(--text-muted); font-weight: normal; text-align: center;">Draft</th>
                    <th style="padding: 0.75rem 0; font-size: 0.85rem; color: var(--text-muted); font-weight: normal; text-align: center;">Confirmed</th>
                    <th style="padding: 0.75rem 0; font-size: 0.85rem; color: var(--text-muted); font-weight: normal; text-align: center;">Packed</th>
                    <th style="padding: 0.75rem 0; font-size: 0.85rem; color: var(--text-muted); font-weight: normal; text-align: center;">Shipped</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 1rem 0; font-size: 0.9rem; color: var(--text-primary);">Direct sales</td>
                    <td style="padding: 1rem 0; font-size: 0.9rem; text-align: center;"><a href="{{ route('manager.sales_orders.index') }}" style="color: #3b82f6; text-decoration: none; font-weight: 600;">{{ $salesOrdersTable ? $salesOrdersTable->draft : 0 }}</a></td>
                    <td style="padding: 1rem 0; font-size: 0.9rem; text-align: center;"><a href="{{ route('manager.sales_orders.index') }}" style="color: #3b82f6; text-decoration: none; font-weight: 600;">{{ $salesOrdersTable ? $salesOrdersTable->confirmed : 0 }}</a></td>
                    <td style="padding: 1rem 0; font-size: 0.9rem; text-align: center;"><a href="{{ route('manager.shipments.index') }}" style="color: #3b82f6; text-decoration: none; font-weight: 600;">{{ $salesOrdersTable ? $salesOrdersTable->packed : 0 }}</a></td>
                    <td style="padding: 1rem 0; font-size: 0.9rem; text-align: center;"><a href="{{ route('manager.shipments.index') }}" style="color: #3b82f6; text-decoration: none; font-weight: 600;">{{ $salesOrdersTable ? $salesOrdersTable->shipped : 0 }}</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Row 5: Detailed Metrics -->
<div class="zoho-grid" style="grid-template-columns: 2fr 1fr;">
    <!-- Recent Activity Table -->
    <div class="zoho-card">
        <div class="zoho-card-header" style="display: flex; justify-content: space-between;">
            Recent Stock Movements
            <a href="{{ route('manager.inventory') }}" style="font-size: 0.8rem; color: #3b82f6; text-decoration: none;">View All →</a>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--glass-border-10);">
                        <th style="padding: 0.75rem 1rem 0.75rem 0; font-size: 0.85rem; color: var(--text-muted); font-weight: normal;">Item</th>
                        <th style="padding: 0.75rem 1rem; font-size: 0.85rem; color: var(--text-muted); font-weight: normal;">Type</th>
                        <th style="padding: 0.75rem 1rem; font-size: 0.85rem; color: var(--text-muted); font-weight: normal; text-align: right;">Qty</th>
                        <th style="padding: 0.75rem 0 0.75rem 1rem; font-size: 0.85rem; color: var(--text-muted); font-weight: normal;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentMovements as $movement)
                    <tr style="border-bottom: 1px solid var(--glass-border-05);">
                        <td style="padding: 0.75rem 1rem 0.75rem 0; font-size: 0.9rem; font-weight: 500;">
                            {{ $movement->product->name ?? 'Unknown' }}
                            <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">{{ $movement->reference }}</div>
                        </td>
                        <td style="padding: 0.75rem 1rem;">
                            @if($movement->type === 'in')
                                <span style="background: rgba(16, 185, 129, 0.15); color: #10b981; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">IN</span>
                            @elseif($movement->type === 'out')
                                <span style="background: rgba(239, 68, 68, 0.15); color: #ef4444; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">OUT</span>
                            @else
                                <span style="background: rgba(245, 158, 11, 0.15); color: #f59e0b; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">ADJUST</span>
                            @endif
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: right; font-weight: 600; color: var(--text-primary);">{{ $movement->quantity }}</td>
                        <td style="padding: 0.75rem 0 0.75rem 1rem; font-size: 0.85rem; color: var(--text-muted);">{{ $movement->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 1rem; color: var(--text-muted); font-size: 0.9rem;">No recent movements.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Critical Alerts -->
    <div class="zoho-card">
        <div class="zoho-card-header">Critical Stock Alerts</div>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @forelse($lowStockItems->take(4) as $item)
                @php $reorderQty = max($item->min_stock_level - $item->quantity, 0) + 50; @endphp
                <a href="{{ route('manager.purchase-orders.create', ['product_id' => $item->id, 'qty' => $reorderQty]) }}" style="text-decoration: none; display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: rgba(239, 68, 68, 0.05); border-left: 3px solid #ef4444; border-radius: 6px; transition: background 0.2s;" onmouseover="this.style.background='rgba(239, 68, 68, 0.12)'" onmouseout="this.style.background='rgba(239, 68, 68, 0.05)'" title="Click to order restock for {{ $item->name }}">
                    <div>
                        <div style="font-weight: 600; font-size: 0.9rem; color: var(--text-primary);">{{ $item->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Min Level: {{ $item->min_stock_level }}</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 1.2rem; font-weight: bold; color: #ef4444;">{{ $item->quantity }}</div>
                        <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase;">In Stock</div>
                    </div>
                </a>
            @empty
                <div style="text-align: center; padding: 2rem 0; color: #10b981;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 48px; height: 48px; margin: 0 auto 0.5rem auto; opacity: 0.5;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <div>All stock levels healthy!</div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let movementsChart = null;

    function loadChartData() {
        const range = document.getElementById('chartRange').value;
        const metric = document.getElementById('chartMetric') ? document.getElementById('chartMetric').value : 'qty';

        fetch(`/manager/api/chart-data?range=${range}`)
            .then(res => res.json())
            .then(data => {
                const ctx = document.getElementById('movementsChart');
                if (!ctx) return;
                
                if (movementsChart) {
                    movementsChart.destroy();
                }

                const isAmount = (metric === 'amount');
                const receivedDataset = isAmount ? data.received_amount : data.received;
                const dispatchedDataset = isAmount ? data.dispatched_amount : data.dispatched;

                movementsChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [
                            {
                                label: isAmount ? 'Received Value ($)' : 'Stock In (Units)',
                                data: receivedDataset,
                                borderColor: 'rgba(16, 185, 129, 1)',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointRadius: 3,
                                pointBackgroundColor: 'rgba(16, 185, 129, 1)'
                            },
                            {
                                label: isAmount ? 'Dispatched Value ($)' : 'Stock Out (Units)',
                                data: dispatchedDataset,
                                borderColor: 'rgba(239, 68, 68, 1)',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointRadius: 3,
                                pointBackgroundColor: 'rgba(239, 68, 68, 1)'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(255,255,255,0.05)' },
                                ticks: { 
                                    color: 'rgba(255,255,255,0.6)',
                                    callback: function(value) {
                                        return isAmount ? '$' + value.toLocaleString() : value;
                                    }
                                }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { color: 'rgba(255,255,255,0.6)' }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: { 
                                    color: 'rgba(255,255,255,0.7)',
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    boxWidth: 6,
                                    boxHeight: 6
                                }
                            },
                            tooltip: {
                                usePointStyle: true,
                                callbacks: {
                                    label: function(context) {
                                        const index = context.dataIndex;
                                        const datasetIndex = context.datasetIndex;
                                        if (datasetIndex === 0) {
                                            const qty = data.received[index] || 0;
                                            const amt = (data.received_amount[index] || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                            return ` Stock In: ${qty} units ($${amt})`;
                                        } else {
                                            const qty = data.dispatched[index] || 0;
                                            const amt = (data.dispatched_amount[index] || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                            return ` Stock Out: ${qty} units ($${amt})`;
                                        }
                                    }
                                }
                            }
                        }
                    }
                });
            });
    }

    document.addEventListener('DOMContentLoaded', loadChartData);
</script>

@endsection