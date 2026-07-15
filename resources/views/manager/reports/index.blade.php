@extends('layouts.manager')

@section('page_title', 'Reports Center')

@section('content')
<div class="content-header">
    <div style="flex: 1;">
        <h2 style="font-size: 1.25rem; font-weight: 500; color: var(--text-color); margin-bottom: 0.5rem;">Warehouse Analytics</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem;">View comprehensive reports on your inventory value, stock velocity, and reorder levels.</p>
    </div>
</div>

<div class="stat-grid" style="grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));">

    <!-- 1. Inventory Valuation Report -->
    <a href="{{ route('manager.inventory-report') }}" class="stat-card" style="text-decoration: none; display: block; transition: transform 0.2s ease, background 0.2s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
        <div class="stat-header">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 style="font-size: 1.1rem; color: var(--text-color); font-weight: 600; margin: 0;">Inventory Valuation</h3>
        </div>
        <div style="margin-top: 1rem; color: var(--text-muted); font-size: 0.9rem; line-height: 1.5;">
            A complete breakdown of everything currently sitting in the warehouse and exactly how much it is worth financially. Groupable by category and brand.
        </div>
    </a>

    <!-- 3. Reorder / Low Stock Report -->
    <a href="{{ route('manager.low-stock') }}" class="stat-card" style="text-decoration: none; display: block; transition: transform 0.2s ease, background 0.2s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
        <div class="stat-header">
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 style="font-size: 1.1rem; color: var(--text-color); font-weight: 600; margin: 0;">Reorder / Low Stock</h3>
        </div>
        <div style="margin-top: 1rem; color: var(--text-muted); font-size: 0.9rem; line-height: 1.5;">
            A focused list of only the products that have fallen below their minimum stock level, indicating they need to be reordered immediately.
        </div>
    </a>

</div>
@endsection
