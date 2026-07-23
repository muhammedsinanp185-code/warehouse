@extends('layouts.manager')

@section('page_title', 'LOW STOCK ALERTS')
@section('back_link', route('manager.reports.index'))

@section('content')
<div style="max-width: 1100px; margin: 0 auto; width: 100%;">
    
    @if($lowStockItems->total() > 0)
    <div style="display: flex; gap: 1rem; margin-bottom: 2rem; align-items: stretch;">
        <div style="flex: 1; display: flex; align-items: center; justify-content: space-between; padding: 1.5rem 2rem; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 12px;">
            <div>
                <h2 style="margin: 0 0 0.5rem 0; color: #ef4444; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 24px; height: 24px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Attention Required
                </h2>
                <p style="margin: 0; color: var(--text-color); opacity: 0.8; font-size: 0.95rem;">The following items are at or below their minimum stock levels.</p>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 3rem; font-weight: 800; color: #ef4444; line-height: 1;">{{ $lowStockItems->total() }}</div>
                <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-top: 0.25rem;">Items</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filters Section -->
    <div class="filters-bar" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; align-items: center; justify-content: flex-end; flex-wrap: wrap;">
        <form method="GET" action="{{ route('manager.low-stock') }}" style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;" id="filterForm">
            
            <div style="position: relative; display: flex; align-items: center; border: 1px solid var(--glass-border-20); border-radius: 8px; background-color: var(--glass-bg-03);">
                <input type="text" name="search" id="searchInput" placeholder="Search SKU, Name..." value="{{ request('search') }}" class="form-input" style="width: 250px; margin: 0; padding: 0.6rem 0.6rem 0.6rem 2.2rem; font-size: 0.9rem; background: transparent; border: none;">
                <svg style="position: absolute; left: 10px; color: var(--text-muted); width: 18px; height: 18px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                @if(request('search'))
                    <button type="button" onclick="window.location='{{ route('manager.low-stock') }}'" style="position: absolute; right: 8px; background: none; border: none; color: var(--text-muted); cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                @endif
            </div>

            <select name="category" class="form-input" style="margin: 0; padding: 0.6rem; width: 140px; background-color: var(--glass-bg-03); border: 1px solid var(--glass-border-20); border-radius: 8px; color: var(--text-color); font-size: 0.9rem;" onchange="this.form.submit()">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <select name="sort" class="form-input" style="margin: 0; padding: 0.6rem; width: 160px; background-color: var(--glass-bg-03); border: 1px solid var(--glass-border-20); border-radius: 8px; color: var(--text-color); font-size: 0.9rem;" onchange="this.form.submit()">
                <option value="qty_asc" {{ request('sort') == 'qty_asc' ? 'selected' : '' }}>Qty: Lowest First</option>
                <option value="qty_desc" {{ request('sort') == 'qty_desc' ? 'selected' : '' }}>Qty: Highest First</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z-A</option>
            </select>
        </form>
    </div>

    @if($lowStockItems->count() > 0)
    <form method="POST" action="{{ route('manager.purchase-orders.bulk-store') }}" id="bulkOrderForm">
        @csrf
        <style>
            .btn-bulk-order {
                padding: 0.6rem 1.2rem;
                background: #3b82f6;
                color: #fff;
                border: none;
                border-radius: 8px;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                transition: all 0.2s;
            }
            .btn-bulk-order:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }
            .btn-bulk-order:not(:disabled) {
                opacity: 1;
                cursor: pointer;
            }
            .btn-bulk-order:not(:disabled):hover {
                background: #2563eb;
            }
        </style>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <div style="color: var(--text-muted); font-size: 0.9rem;">
                <span id="selectedCount">0</span> items selected
            </div>
            <button type="submit" id="orderSelectedBtn" class="btn-bulk-order" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
                Order Selected Items
            </button>
        </div>

        <div class="table-container">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;"></th>
                        <th style="text-align: left;">Product Name</th>
                        <th style="text-align: left;">SKU</th>
                        <th style="text-align: center;">Current Stock</th>
                        <th style="text-align: center;">Min Stock Level</th>
                        <th style="text-align: center;">Order Qty</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockItems as $item)
                    @php 
                        $hasPending = $item->hasPendingOrders(); 
                        $recommendedQty = max($item->min_stock_level - $item->quantity, 0) + 50;
                    @endphp
                    <tr>
                        <td style="text-align: center;">
                            <input type="checkbox" name="product_ids[]" value="{{ $item->id }}" class="item-checkbox" onclick="updateSelectedCount()" {{ $hasPending ? 'disabled' : '' }} style="width: 16px; height: 16px; cursor: pointer;">
                        </td>
                        <td>
                            <a href="{{ route('manager.purchase-orders.create', ['product_id' => $item->id, 'qty' => $recommendedQty]) }}" style="font-weight: 600; color: #3b82f6; text-decoration: none;" title="Click to create purchase order for this item">
                                {{ $item->name }}
                            </a>
                            @if($hasPending)
                            <span style="margin-left: 0.5rem; padding: 0.2rem 0.5rem; background: rgba(59, 130, 246, 0.2); color: #3b82f6; border-radius: 4px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase;">Ordered</span>
                            @endif
                        </td>
                        <td style="color: var(--text-muted); font-family: monospace;">{{ $item->sku }}</td>
                        <td style="text-align: center;"><span style="color: #ef4444; font-weight: 600;">{{ $item->quantity }}</span></td>
                        <td style="text-align: center;">{{ $item->min_stock_level }}</td>
                        <td style="text-align: center;">
                            <input type="number" name="quantities[{{ $item->id }}]" value="{{ $recommendedQty }}" min="1" style="width: 70px; padding: 0.3rem; border: 1px solid var(--glass-border-20); border-radius: 4px; background: var(--glass-bg-03); color: var(--text-primary); text-align: center;" {{ $hasPending ? 'disabled' : '' }}>
                        </td>
                        <td style="text-align: center;">
                            <span style="padding: 0.3rem 0.6rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3);">Critical</span>
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('manager.purchase-orders.create', ['product_id' => $item->id, 'qty' => $recommendedQty]) }}" class="btn-primary" style="padding: 0.35rem 0.75rem; font-size: 0.8rem; border-radius: 6px; text-decoration: none; display: inline-block;">
                                Create PO
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>
    @else
    <div style="text-align: center; padding: 4rem 1rem; color: var(--text-muted); background: var(--glass-bg-03); border-radius: 12px; border: 1px dashed var(--glass-border-20);">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 64px; height: 64px; margin: 0 auto 1.5rem auto; opacity: 0.8; color: #10b981;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <h3 style="margin: 0 0 0.5rem 0; font-size: 1.5rem; color: var(--text-primary); font-weight: 600;">All Stock Levels Healthy</h3>
        <p style="margin: 0; font-size: 1.1rem;">Great job! You have no items currently requiring a restock.</p>
    </div>
    @endif

    @if($lowStockItems->hasPages())
    <div style="margin-top: 1.5rem;">
        {{ $lowStockItems->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>
@endsection

@section('extra_scripts')
<script>
    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        const count = checkedBoxes.length;
        const btn = document.getElementById('orderSelectedBtn');
        const countSpan = document.getElementById('selectedCount');
        
        countSpan.textContent = count;
        
        if (btn) {
            btn.disabled = (count === 0);
        }
    }
</script>
@endsection
