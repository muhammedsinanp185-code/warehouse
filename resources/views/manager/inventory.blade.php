@extends('layouts.manager')

@section('page_title', 'INVENTORY AUDIT LOG')

@section('content')
            <div class="stat-grid">
                <!-- Stat Card 1 -->
                <div class="stat-card">
                    <div class="stat-icon icon-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" /></svg>
                    </div>
                    <div class="stat-details">
                        <h4>Total Movements</h4>
                        <h2>{{ number_format($totalMovements) }}</h2>
                    </div>
                </div>

                <!-- Stat Card 2 -->
                <div class="stat-card">
                    <div class="stat-icon icon-green">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                    </div>
                    <div class="stat-details">
                        <h4>Total Received</h4>
                        <h2>{{ number_format($totalReceived) }}</h2>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="stat-card">
                    <div class="stat-icon icon-red">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" /></svg>
                    </div>
                    <div class="stat-details">
                        <h4>Total Dispatched</h4>
                        <h2>{{ number_format($totalDispatched) }}</h2>
                    </div>
                </div>
            </div>

            <div class="filters-bar" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                <div class="quick-actions" style="margin-bottom: 0;">
                    <button type="button" class="btn-action btn-receive" onclick="openModal('receiveStockModal')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                        Receive Stock
                    </button>
                    <button type="button" class="btn-action btn-dispatch" onclick="openModal('dispatchStockModal')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" /></svg>
                        Dispatch Stock
                    </button>
                    <button type="button" class="btn-action btn-add" onclick="openModal('addProductModal')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Product
                    </button>
                </div>
                
                <form method="GET" action="{{ route('manager.inventory') }}" style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;" id="auditSearchForm">
                    <div style="position: relative; display: flex; align-items: center; border: 1px solid var(--glass-border-20); border-radius: 8px; background-color: var(--glass-bg-03);">
                        <svg style="position: absolute; left: 10px; color: var(--text-muted); width: 18px; height: 18px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" id="auditSearchInput" placeholder="Search products, users, refs..." class="form-input" style="width: 250px; margin: 0; padding: 0.6rem 0.6rem 0.6rem 2.2rem; font-size: 0.9rem; background: transparent;" value="{{ request('search') }}">
                    </div>
                    <select name="type" id="auditTypeSelect" class="form-input" style="margin: 0; padding: 0.6rem; width: 140px; background-color: var(--glass-bg-03); border: 1px solid var(--glass-border-20); border-radius: 8px; color: var(--text-color); font-size: 0.9rem;" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stock In</option>
                        <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stock Out</option>
                    </select>
                    <select name="date_range" id="auditDateSelect" class="form-input" style="margin: 0; padding: 0.6rem; width: 140px; background-color: var(--glass-bg-03); border: 1px solid var(--glass-border-20); border-radius: 8px; color: var(--text-color); font-size: 0.9rem;" onchange="handleDateSelect(this)">
                        <option value="">All Time</option>
                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="7days" {{ request('date_range') == '7days' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="30days" {{ request('date_range') == '30days' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>Specific Date...</option>
                    </select>
                    <input type="date" name="exact_date" id="exactDateInput" class="form-input" style="margin: 0; padding: 0.5rem; width: auto; background-color: var(--glass-bg-03); border: 1px solid var(--glass-border-20); border-radius: 8px; color: var(--text-color); font-size: 0.9rem; display: {{ request('date_range') == 'custom' ? 'block' : 'none' }};" value="{{ request('exact_date') }}" onchange="this.form.submit()">
                    <select name="sort" id="auditSortSelect" class="form-input" style="margin: 0; padding: 0.6rem; width: 160px; background-color: var(--glass-bg-03); border: 1px solid var(--glass-border-20); border-radius: 8px; color: var(--text-color); font-size: 0.9rem;" onchange="this.form.submit()">
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
                            <th rowspan="2" style="text-align: center; vertical-align: bottom;">Authorized By</th>
                            <th rowspan="2" style="text-align: right; vertical-align: bottom;">Action</th>
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
                                <a href="{{ route('manager.products.show', $movement->product->id) }}" style="color: var(--text-primary); text-decoration: none; transition: color 0.2s ease;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='var(--text-primary)'">
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
                            <td style="text-align: center;">{{ $movement->user->name }}</td>
                            <td style="text-align: right;">
                                <button type="button" class="action-icon" style="color: #ef4444;" onclick="openDeleteLogModal({{ $movement->id }})" title="Delete Record & Revert Stock">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 2rem; color: var(--text-muted);">No stock movements recorded yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1rem;">
                {{ $movements->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
@endsection

@section('extra_modals')
    <!-- Delete Log Confirmation Modal -->
    <div class="modal-overlay" id="deleteLogModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Delete Audit Log</h2>
                <button class="modal-close" onclick="closeModal('deleteLogModal')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <p style="color: var(--text-color); margin-bottom: 2rem;">
                Are you sure you want to delete this log entry? <br>
                <strong style="color: #ef4444;">Warning:</strong> This will permanently delete the log and mathematically revert the product's quantity back to its previous state.
            </p>
            <form method="POST" id="deleteLogForm">
                @csrf
                @method('DELETE')
                <button type="submit" class="auth-button" style="background: #ef4444; color: white; border: none;">Yes, Delete & Revert Stock</button>
            </form>
        </div>
    </div>
@endsection

@section('extra_scripts')
<script>
    function openDeleteLogModal(id) {
        document.getElementById('deleteLogForm').action = '/inventory/' + id;
        openModal('deleteLogModal');
    }

    let timeout = null;
    const auditSearchInput = document.getElementById('auditSearchInput');
    
    if (auditSearchInput) {
        auditSearchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                document.getElementById('auditSearchForm').submit();
            }, 600);
        });
    }

    function handleDateSelect(select) {
        if (select.value === 'custom') {
            document.getElementById('exactDateInput').style.display = 'block';
            document.getElementById('exactDateInput').focus();
        } else {
            document.getElementById('exactDateInput').style.display = 'none';
            document.getElementById('exactDateInput').value = '';
            select.form.submit();
        }
    }
</script>
@endsection
