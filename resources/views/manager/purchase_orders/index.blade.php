@extends('layouts.manager')

@section('page_title', 'PURCHASE ORDERS')

@section('content')
<div style="max-width: 1100px; margin: 0 auto; width: 100%;">
    
    <!-- Filters Section -->
    <div class="filters-bar" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; align-items: center; justify-content: space-between; flex-wrap: wrap;">
        <a href="{{ route('manager.purchase-orders.create') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.2rem; background: #3b82f6; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 0.9rem; transition: background 0.2s;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            New Purchase Order
        </a>

        <form method="GET" action="{{ route('manager.purchase-orders.index') }}" style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;" id="filterForm">
            
            <div style="position: relative; display: flex; align-items: center; border: 1px solid var(--glass-border-20); border-radius: 8px; background-color: var(--glass-bg-03);">
                <input type="text" name="search" placeholder="Search PO Number..." value="{{ request('search') }}" class="form-input" style="width: 200px; margin: 0; padding: 0.6rem 0.6rem 0.6rem 2.2rem; font-size: 0.9rem; background: transparent; border: none;">
                <svg style="position: absolute; left: 10px; color: var(--text-muted); width: 18px; height: 18px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                @if(request('search'))
                    <button type="button" onclick="window.location='{{ route('manager.purchase-orders.index') }}'" style="position: absolute; right: 8px; background: none; border: none; color: var(--text-muted); cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                @endif
            </div>

            <select name="status" class="form-input" style="margin: 0; padding: 0.6rem; width: 140px; background-color: var(--glass-bg-03); border: 1px solid var(--glass-border-20); border-radius: 8px; color: var(--text-color); font-size: 0.9rem;" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <select name="sort" class="form-input" style="margin: 0; padding: 0.6rem; width: 160px; background-color: var(--glass-bg-03); border: 1px solid var(--glass-border-20); border-radius: 8px; color: var(--text-color); font-size: 0.9rem;" onchange="this.form.submit()">
                <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Date: Newest First</option>
                <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Date: Oldest First</option>
            </select>
        </form>
    </div>

    @if($purchaseOrders->count() > 0)
    <div class="table-container">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th style="text-align: left;">PO Number</th>
                    <th style="text-align: center;">Date Created</th>
                    <th style="text-align: center;">Created By</th>
                    <th style="text-align: center;">Items</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchaseOrders as $po)
                <tr>
                    <td style="font-family: monospace; font-weight: 700; color: var(--text-primary);">
                        {{ $po->po_number }}
                    </td>
                    <td style="text-align: center; color: var(--text-muted); font-size: 0.9rem;">
                        {{ $po->created_at->format('M d, Y') }}
                    </td>
                    <td style="text-align: center; color: var(--text-muted);">
                        {{ $po->creator->name }}
                    </td>
                    <td style="text-align: center; font-weight: 600;">
                        {{ $po->items_count }}
                    </td>
                    <td style="text-align: center;">
                        @if($po->status == 'pending')
                            <span style="padding: 0.3rem 0.6rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: rgba(245, 158, 11, 0.2); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3);">Pending</span>
                        @elseif($po->status == 'received')
                            <span style="padding: 0.3rem 0.6rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);">Received</span>
                        @else
                            <span style="padding: 0.3rem 0.6rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: rgba(107, 114, 128, 0.2); color: #9ca3af; border: 1px solid rgba(107, 114, 128, 0.3);">Cancelled</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route('manager.purchase-orders.show', $po->id) }}" style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.4rem 0.8rem; background: var(--glass-bg-10); border: 1px solid var(--glass-border-20); border-radius: 6px; color: var(--text-primary); text-decoration: none; font-size: 0.8rem; transition: background 0.2s;" onmouseover="this.style.background='var(--glass-bg-20)'" onmouseout="this.style.background='var(--glass-bg-10)'">
                            View Details
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="text-align: center; padding: 4rem 1rem; color: var(--text-muted); background: var(--glass-bg-03); border-radius: 12px; border: 1px dashed var(--glass-border-20);">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 64px; height: 64px; margin: 0 auto 1.5rem auto; opacity: 0.5;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
        </svg>
        <h3 style="margin: 0 0 0.5rem 0; font-size: 1.5rem; color: var(--text-primary); font-weight: 600;">No Purchase Orders</h3>
        <p style="margin: 0; font-size: 1.1rem;">You haven't created any purchase orders yet. Go to Low Stock Alerts to bulk order items.</p>
        <a href="{{ route('manager.low-stock') }}" style="display: inline-block; margin-top: 1.5rem; padding: 0.8rem 1.5rem; background: #3b82f6; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600;">View Low Stock Alerts</a>
    </div>
    @endif

    @if($purchaseOrders->hasPages())
    <div style="margin-top: 1.5rem;">
        {{ $purchaseOrders->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>
@endsection
