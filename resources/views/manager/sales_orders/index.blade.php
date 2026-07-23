@extends('layouts.manager')

@section('page_title', 'SALES ORDERS')

@section('content')
<div style="max-width: 1100px; margin: 0 auto; width: 100%;">
    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--text-color);">All Sales Orders</h2>
            <p style="margin: 0.25rem 0 0 0; color: var(--text-muted); font-size: 0.875rem;">Manage customer orders, draft quotes, and order statuses.</p>
        </div>
        <a href="{{ route('manager.sales_orders.create') }}" class="btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.65rem 1.25rem; font-weight: 600; font-size: 0.9rem; border-radius: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            New Sales Order
        </a>
    </div>

    <!-- Sales Orders Table -->
    <div class="table-container">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th style="text-align: left;">SO Number</th>
                    <th style="text-align: left;">Customer</th>
                    <th style="text-align: center;">Order Date</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: right;">Amount</th>
                    <th style="text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salesOrders as $so)
                    <tr>
                        <td style="font-family: monospace; font-weight: 700; color: var(--text-color);">{{ $so->so_number }}</td>
                        <td style="font-weight: 600; color: var(--text-color);">{{ $so->customer->name ?? 'Unknown' }}</td>
                        <td style="text-align: center; color: var(--text-muted);">{{ $so->order_date ? $so->order_date->format('M d, Y') : '-' }}</td>
                        <td style="text-align: center;">
                            @if($so->status == 'draft')
                                <span style="padding: 0.3rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: rgba(107, 114, 128, 0.2); color: #9ca3af; border: 1px solid rgba(107, 114, 128, 0.3);">Draft</span>
                            @elseif($so->status == 'confirmed')
                                <span style="padding: 0.3rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: rgba(59, 130, 246, 0.2); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.3);">Confirmed</span>
                            @elseif($so->status == 'shipped')
                                <span style="padding: 0.3rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: rgba(234, 179, 8, 0.2); color: #eab308; border: 1px solid rgba(234, 179, 8, 0.3);">Shipped</span>
                            @elseif($so->status == 'delivered')
                                <span style="padding: 0.3rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);">Delivered</span>
                            @endif
                        </td>
                        <td style="text-align: right; font-weight: 700; color: var(--text-color);">${{ number_format($so->total_amount, 2) }}</td>
                        <td style="text-align: center;">
                            <a href="{{ route('manager.sales_orders.show', $so) }}" style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.4rem 0.8rem; background: var(--glass-bg-10); border: 1px solid var(--glass-border-20); border-radius: 6px; color: #3b82f6; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: background 0.2s;" onmouseover="this.style.background='var(--glass-bg-20)'" onmouseout="this.style.background='var(--glass-bg-10)'">
                                View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 4rem 1rem;">
                            No sales orders found. Click "+ New Sales Order" to create one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
