@extends('layouts.manager')

@section('page_title', 'SALES BY ITEM REPORT')

@section('content')
<div style="max-width: 1100px; margin: 0 auto; width: 100%;">
    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <a href="{{ route('manager.reports.index') }}" style="color: #3b82f6; text-decoration: none; font-size: 0.9rem; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem;">
                ← Back to Reports Center
            </a>
            <h2 style="margin: 0.5rem 0 0 0; font-size: 1.5rem; font-weight: 700; color: var(--text-color);">Sales by Item</h2>
        </div>
        <div>
            <button onclick="window.print()" class="btn-primary" style="padding: 0.55rem 1.1rem; font-size: 0.85rem; font-weight: 600; border-radius: 6px;">
                Export PDF / Print
            </button>
        </div>
    </div>

    <!-- Report Table Card -->
    <div class="table-container">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th style="text-align: left;">Item Name</th>
                    <th style="text-align: left;">SKU</th>
                    <th style="text-align: center;">Quantity Sold</th>
                    <th style="text-align: right;">Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalQty = 0;
                    $totalRev = 0;
                @endphp
                @forelse($items as $item)
                    @php
                        $totalQty += $item->total_qty;
                        $totalRev += $item->total_revenue;
                    @endphp
                    <tr>
                        <td style="font-weight: 600; color: var(--text-color);">{{ $item->product->name ?? 'Unknown Product' }}</td>
                        <td style="font-family: monospace; color: var(--text-muted);">{{ $item->product->sku ?? '-' }}</td>
                        <td style="text-align: center; color: var(--text-color); font-weight: 600;">{{ $item->total_qty }} pcs</td>
                        <td style="text-align: right; color: var(--text-color); font-weight: 700;">${{ number_format($item->total_revenue, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 3rem 1rem;">
                            No item sales records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="border-top: 2px solid var(--glass-border-20); background: var(--glass-bg-10);">
                    <td colspan="2" style="font-weight: 800; font-size: 1rem; color: var(--text-color);">Total</td>
                    <td style="text-align: center; font-weight: 800; font-size: 1rem; color: var(--text-color);">{{ $totalQty }} pcs</td>
                    <td style="text-align: right; font-weight: 800; font-size: 1.1rem; color: #3b82f6;">${{ number_format($totalRev, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
