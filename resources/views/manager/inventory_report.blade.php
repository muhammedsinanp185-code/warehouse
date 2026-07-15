@extends('layouts.manager')

@section('page_title', 'INVENTORY REPORT')
@section('back_link', route('manager.reports.index'))

@section('content')
<div class="content-panel">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 1.5rem; gap: 1rem;">
        <div>
            <h2 style="margin: 0; color: var(--text-primary);">Inventory Value Breakdown</h2>
            <p style="color: var(--text-muted); margin-top: 0.25rem;">A complete report of all products and their current total value in stock.</p>
        </div>
        
        <form method="GET" action="{{ route('manager.inventory-report') }}" style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;" id="reportSearchForm">
            <div style="position: relative; display: flex; align-items: center; border: 1px solid var(--glass-border-20); border-radius: 8px; background-color: var(--glass-bg-03);">
                <svg style="position: absolute; left: 10px; color: var(--text-muted); width: 18px; height: 18px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" id="reportSearchInput" placeholder="Search product or SKU..." class="form-input" style="width: 250px; margin: 0; padding: 0.6rem 0.6rem 0.6rem 2.2rem; font-size: 0.9rem; background: transparent; border: none;" value="{{ request('search') }}">
            </div>
            <select name="status" class="form-input" style="margin: 0; padding: 0.6rem; width: 140px; background-color: var(--glass-bg-03); border: 1px solid var(--glass-border-20); border-radius: 8px; color: var(--text-color); font-size: 0.9rem;" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="healthy" {{ request('status') == 'healthy' ? 'selected' : '' }}>Healthy Stock</option>
                <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Low Stock</option>
            </select>
            <select name="sort" class="form-input" style="margin: 0; padding: 0.6rem; width: 160px; background-color: var(--glass-bg-03); border: 1px solid var(--glass-border-20); border-radius: 8px; color: var(--text-color); font-size: 0.9rem;" onchange="this.form.submit()">
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z-A</option>
                <option value="qty_desc" {{ request('sort') == 'qty_desc' ? 'selected' : '' }}>Qty: High-Low</option>
                <option value="qty_asc" {{ request('sort') == 'qty_asc' ? 'selected' : '' }}>Qty: Low-High</option>
                <option value="val_desc" {{ request('sort') == 'val_desc' ? 'selected' : '' }}>Value: High-Low</option>
                <option value="val_asc" {{ request('sort') == 'val_asc' ? 'selected' : '' }}>Value: Low-High</option>
            </select>
        </form>
    </div>

    <div class="table-container">
        <table class="dashboard-table" style="width: 100%;">
            <thead style="position: sticky; top: 0; background: var(--bg-card); z-index: 10;">
                <tr>
                    <th>Product Name</th>
                    <th style="text-align: right;">Price</th>
                    <th style="text-align: center;">Quantity</th>
                    <th style="text-align: right;">Total Value</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $calculatedTotal = 0;
                @endphp
                @forelse($allProducts as $product)
                    @php
                        $itemTotal = $product->price * $product->quantity;
                        $calculatedTotal += $itemTotal;
                    @endphp
                    <tr>
                        <td style="font-weight: 500;">{{ $product->name }}</td>
                        <td style="text-align: right;">₹{{ number_format($product->price, 2) }}</td>
                        <td style="text-align: center;">
                            <div style="display: inline-flex; align-items: center; justify-content: flex-start; width: 3.5rem; text-align: left;">
                                @if($product->quantity <= $product->min_stock_level)
                                    <span class="status-circle status-red" title="Low Stock"></span>
                                @else
                                    <span class="status-circle status-green" title="Healthy"></span>
                                @endif
                                <span style="margin-left: 0.75rem; font-weight: 500;">{{ $product->quantity }}</span>
                            </div>
                        </td>
                        <td style="text-align: right; font-weight: bold;">₹{{ number_format($itemTotal, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 2rem;">No products found in inventory.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot style="background: var(--bg-card); border-top: 2px solid var(--border-color); font-weight: bold; font-size: 1.1rem;">
                <tr>
                    <td colspan="3" style="text-align: right; padding: 1.5rem 1rem;">Total Inventory Value:</td>
                    <td style="text-align: right; padding: 1.5rem 1rem; color: #10b981; font-size: 1.25rem;">₹{{ number_format($calculatedTotal, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

@section('extra_scripts')
<script>
    let reportTimeout = null;
    const reportSearchInput = document.getElementById('reportSearchInput');
    
    if (reportSearchInput) {
        reportSearchInput.addEventListener('input', function() {
            clearTimeout(reportTimeout);
            reportTimeout = setTimeout(function() {
                document.getElementById('reportSearchForm').submit();
            }, 600);
        });
    }
</script>
@endsection
