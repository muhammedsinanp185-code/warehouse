@extends('layouts.manager')

@section('page_title', 'LOW STOCK REPORT')
@section('back_link', route('manager.reports.index'))

@section('content')
<div class="dashboard-card" style="padding: 2rem; background: var(--glass-bg-03); border: 1px solid var(--glass-border-10); border-radius: 12px; backdrop-filter: blur(10px);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h3 class="dashboard-section-title" style="margin-bottom: 0;">Items Requiring Restock</h3>
        <a href="{{ route('manager.dashboard') }}" style="padding: 0.6rem 1.2rem; background: var(--glass-bg-10); color: var(--text-color); text-decoration: none; border-radius: 8px; border: 1px solid var(--glass-border-10); font-size: 0.9rem; transition: background 0.3s;" onmouseover="this.style.background='var(--glass-bg-10)'" onmouseout="this.style.background='var(--glass-bg-05)'">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px; display: inline-block; vertical-align: text-bottom; margin-right: 4px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    @if($lowStockItems->count() > 0)
    <div class="table-container">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th style="text-align: left;">Product Name</th>
                    <th style="text-align: left;">SKU</th>
                    <th style="text-align: center;">Current Stock</th>
                    <th style="text-align: center;">Min Stock Level</th>
                    <th style="text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStockItems as $item)
                <tr>
                    <td>
                        <strong style="font-weight: 500;">{{ $item->name }}</strong>
                    </td>
                    <td style="color: var(--text-muted); font-family: monospace;">{{ $item->sku }}</td>
                    <td style="text-align: center;"><span style="color: #ef4444; font-weight: 600;">{{ $item->quantity }}</span></td>
                    <td style="text-align: center;">{{ $item->min_stock_level }}</td>
                    <td style="text-align: center;">
                        <span style="padding: 0.3rem 0.6rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3);">Critical</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
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
        {{ $lowStockItems->links() }}
    </div>
    @endif
</div>
@endsection
