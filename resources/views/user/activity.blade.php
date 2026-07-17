@extends('layouts.user')

@section('page_title', 'MY ACTIVITY')
@section('back_link', route('user.dashboard'))

@section('content')
<div style="max-width: 1100px; margin: 0 auto; width: 100%;">
    
    <!-- Filters Section -->
    <div class="filters-bar" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; align-items: center; justify-content: flex-end; flex-wrap: wrap;">
        <form method="GET" action="{{ route('user.activity') }}" style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;" id="filterForm">
            
            <div style="position: relative; display: flex; align-items: center; border: 1px solid var(--glass-border-20); border-radius: 8px; background-color: var(--glass-bg-03);">
                <input type="text" name="search" id="searchInput" placeholder="Search SKU, Name, Ref..." value="{{ request('search') }}" class="form-input" style="width: 250px; margin: 0; padding: 0.6rem 0.6rem 0.6rem 2.2rem; font-size: 0.9rem; background: transparent; border: none;">
                <svg style="position: absolute; left: 10px; color: var(--text-muted); width: 18px; height: 18px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                @if(request('search'))
                    <button type="button" onclick="window.location='{{ route('user.activity') }}'" style="position: absolute; right: 8px; background: none; border: none; color: var(--text-muted); cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                @endif
            </div>

            <select name="type" class="form-input" style="margin: 0; padding: 0.6rem; width: 140px; background-color: var(--glass-bg-03); border: 1px solid var(--glass-border-20); border-radius: 8px; color: var(--text-color); font-size: 0.9rem;" onchange="this.form.submit()">
                <option value="">All Types</option>
                <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stock In</option>
                <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stock Out</option>
            </select>

            <select name="date_range" id="historyDateSelect" class="form-input" style="margin: 0; padding: 0.6rem; width: 140px; background-color: var(--glass-bg-03); border: 1px solid var(--glass-border-20); border-radius: 8px; color: var(--text-color); font-size: 0.9rem;" onchange="handleDateSelect(this)">
                <option value="">All Time</option>
                <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                <option value="7days" {{ request('date_range') == '7days' ? 'selected' : '' }}>Last 7 Days</option>
                <option value="30days" {{ request('date_range') == '30days' ? 'selected' : '' }}>Last 30 Days</option>
                <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>Specific Date...</option>
            </select>
            <input type="date" name="exact_date" id="exactDateInput" class="form-input" style="margin: 0; padding: 0.5rem; width: auto; background-color: var(--glass-bg-03); border: 1px solid var(--glass-border-20); border-radius: 8px; color: var(--text-color); font-size: 0.9rem; display: {{ request('date_range') == 'custom' ? 'block' : 'none' }};" value="{{ request('exact_date') }}" onchange="this.form.submit()">

            <select name="sort" class="form-input" style="margin: 0; padding: 0.6rem; width: 160px; background-color: var(--glass-bg-03); border: 1px solid var(--glass-border-20); border-radius: 8px; color: var(--text-color); font-size: 0.9rem;" onchange="this.form.submit()">
                <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Date: Newest First</option>
                <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Date: Oldest First</option>
                <option value="qty_desc" {{ request('sort') == 'qty_desc' ? 'selected' : '' }}>Qty: Highest First</option>
                <option value="qty_asc" {{ request('sort') == 'qty_asc' ? 'selected' : '' }}>Qty: Lowest First</option>
            </select>
        </form>
    </div>

            <div class="table-container">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="text-align: center;vertical-align: bottom;">Date / Time</th>
                            <th rowspan="2" style="text-align: center;vertical-align: bottom;">Product</th>
                            <th rowspan="2" style="text-align: center; vertical-align: bottom;">Source / Destination</th>
                            <th colspan="3" style="text-align: center; border-bottom: 2px solid var(--glass-border-10); padding-bottom: 0.5rem;">Ledger Details</th>
                        </tr>
                        <tr>
                            <th style="text-align: center; font-size: 0.8rem; color: #10b981; padding-top: 0.5rem;">Received</th>
                            <th style="text-align: center; font-size: 0.8rem; color: #ef4444; padding-top: 0.5rem;">Dispatched</th>
                            <th style="text-align: center; font-size: 0.8rem; color: var(--text-color); padding-top: 0.5rem;">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                        <tr>
                            <td style="color: var(--text-muted); font-size: 0.85rem;">{{ $movement->created_at->format('M d, Y h:i A') }}</td>
                            <td style="font-weight: 500;">
                                <a href="{{ route('user.products.show', $movement->product->id) }}" style="color: var(--text-primary); text-decoration: none; transition: color 0.2s ease;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='var(--text-primary)'">
                                    {{ $movement->product->name }}
                                </a>
                                <div style="font-size: 0.75rem; color: var(--text-muted); font-family: monospace; margin-top: 2px;">{{ $movement->product->sku }}</div>
                            </td>
                            <td style="text-align: center; color: var(--text-muted); font-size: 0.9rem;">
                                {{ $movement->reference_party ?: '-' }}
                            </td>
                            <td style="text-align: center; font-weight: 600; color: #10b981;">
                                {{ $movement->type == 'in' ? '+' . $movement->quantity : '-' }}
                            </td>
                            <td style="text-align: center; font-weight: 600; color: #ef4444;">
                                {{ $movement->type == 'out' ? '-' . $movement->quantity : '-' }}
                            </td>
                            <td style="text-align: center; font-weight: 700; color: var(--text-color);">
                                {{ $movement->balance_after }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">You have not recorded any stock movements yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1rem;">
                {{ $movements->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
</div>
@endsection

@section('extra_js')
<script>
    function handleDateSelect(select) {
        const exactDateInput = document.getElementById('exactDateInput');
        if (select.value === 'custom') {
            exactDateInput.style.display = 'block';
            if (!exactDateInput.value) {
                exactDateInput.focus();
            }
        } else {
            exactDateInput.style.display = 'none';
            select.form.submit();
        }
    }
</script>
@endsection
