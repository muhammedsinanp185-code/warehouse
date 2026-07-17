@extends('layouts.manager')

@section('page_title', 'MOVEMENT HISTORY')
@section('back_link', route('manager.products.show', $product->id))

@section('content')
<div style="max-width: 1100px; margin: 0 auto; width: 100%;">
    
    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; background: var(--glass-bg-05); border: 1px solid var(--glass-border-10); border-radius: 16px; padding: 2rem; backdrop-filter: blur(10px);">
        <div>
            <h1 style="margin: 0; font-size: 2.2rem; color: var(--text-primary); font-weight: 700;">{{ $product->name }}</h1>
            <div style="margin: 0.5rem 0 0 0; color: var(--text-muted); font-size: 1rem; display: flex; align-items: center; gap: 0.5rem; font-family: monospace;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                </svg>
                SKU: {{ $product->sku }}
            </div>
        </div>
        <div style="text-align: right; background: rgba(0,0,0,0.2); padding: 1rem 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
            <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">Current Stock</div>
            <div style="font-size: 2.5rem; font-weight: 700; color: {{ $product->quantity <= $product->min_stock_level ? '#ef4444' : '#10b981' }}; line-height: 1;">
                {{ number_format($product->quantity) }}
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-bar" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; align-items: center; justify-content: flex-end; flex-wrap: wrap;">
        <form method="GET" action="{{ route('manager.products.history', $product->id) }}" style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;" id="historyFilterForm">
            
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

    <!-- Movement History -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
        
        <div style="text-align: right; background: rgba(0,0,0,0.2); padding: 1rem 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
            <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">Total Records</div>
            <div style="font-size: 2.5rem; font-weight: 700; color: var(--text-primary); line-height: 1;">
                {{ number_format($movements->total()) }}
            </div>
        </div>
    </div>

    <!-- Movement History Full -->
    <div style="background: var(--glass-bg-05); border: 1px solid var(--glass-border-10); border-radius: 16px; padding: 2rem; backdrop-filter: blur(10px);">
        <h3 style="font-size: 1.2rem; color: var(--text-primary); margin-top: 0; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px; color: #3b82f6;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Full Movement History
        </h3>
        
        @if($movements->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--glass-border-20);">
                        <th style="padding: 1rem 0; font-size: 0.85rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Date / Time</th>
                        <th style="padding: 1rem 1rem; font-size: 0.85rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Type</th>
                        <th style="padding: 1rem 1rem; font-size: 0.85rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Reference Party</th>
                        <th style="padding: 1rem 1rem; font-size: 0.85rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; text-align: right;">Quantity</th>
                        <th style="padding: 1rem 0 1rem 1rem; font-size: 0.85rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; text-align: right;">Authorized By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movements as $movement)
                    <tr style="border-bottom: 1px solid var(--glass-border-10);">
                        <td style="padding: 1rem 0;">
                            <div style="font-weight: 500; color: var(--text-primary);">{{ $movement->created_at->format('M d, Y') }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">{{ $movement->created_at->format('h:i A') }}</div>
                        </td>
                        <td style="padding: 1rem 1rem;">
                            @if($movement->type == 'in')
                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; color: #10b981; font-weight: 600; background: rgba(16,185,129,0.1); padding: 0.3rem 0.6rem; border-radius: 6px; font-size: 0.8rem; letter-spacing: 0.5px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    RECEIVED
                                </span>
                            @else
                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; color: #ef4444; font-weight: 600; background: rgba(239,68,68,0.1); padding: 0.3rem 0.6rem; border-radius: 6px; font-size: 0.8rem; letter-spacing: 0.5px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" /></svg>
                                    DISPATCHED
                                </span>
                            @endif
                        </td>
                        <td style="padding: 1rem 1rem; color: var(--text-muted); font-size: 0.9rem;">
                            {{ $movement->reference_party ?: '-' }}
                        </td>
                        <td style="padding: 1rem 1rem; text-align: right; font-weight: 700; font-family: monospace; font-size: 1.15rem; color: {{ $movement->type == 'in' ? '#10b981' : '#ef4444' }};">
                            {{ $movement->type == 'in' ? '+' : '-' }}{{ number_format($movement->quantity) }}
                        </td>
                        <td style="padding: 1rem 0 1rem 1rem; text-align: right; color: var(--text-primary); font-weight: 500;">
                            {{ $movement->user ? $movement->user->name : 'System' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div style="margin-top: 2rem;">
            {{ $movements->links('vendor.pagination.custom') }}
        </div>
        
        @else
        <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted); background: var(--glass-bg-03); border-radius: 8px; border: 1px dashed var(--glass-border-20);">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 48px; height: 48px; margin: 0 auto 1rem auto; opacity: 0.5;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <p style="margin: 0;">No movement history found for this product.</p>
        </div>
        @endif
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
