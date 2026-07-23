@extends('layouts.manager')

@section('page_title', 'SALES BY CUSTOMER REPORT')

@section('content')
<div style="max-width: 1100px; margin: 0 auto; width: 100%;">
    <!-- Top Action & Filter Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <a href="{{ route('manager.reports.index') }}" style="color: #3b82f6; text-decoration: none; font-size: 0.9rem; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem;">
                ← Back to Reports Center
            </a>
            <h2 style="margin: 0.5rem 0 0 0; font-size: 1.5rem; font-weight: 700; color: var(--text-color);">Sales by Customer</h2>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <button onclick="window.print()" class="btn-primary" style="padding: 0.55rem 1.1rem; font-size: 0.85rem; font-weight: 600; border-radius: 6px;">
                Export PDF / Print
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <form method="GET" action="{{ route('manager.reports.sales-by-customer') }}" class="table-container" style="padding: 1rem 1.25rem; margin-bottom: 1.5rem; min-height: auto;">
        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <span style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Filters:</span>
            <div>
                <label style="font-size: 0.8rem; color: var(--text-muted);">From Date</label>
                <input type="date" name="from_date" value="{{ $startDate }}" style="background: var(--glass-bg-10); border: 1px solid var(--glass-border-20); color: var(--text-color); border-radius: 6px; padding: 0.35rem 0.6rem; font-size: 0.85rem; outline: none;">
            </div>
            <div>
                <label style="font-size: 0.8rem; color: var(--text-muted);">To Date</label>
                <input type="date" name="to_date" value="{{ $endDate }}" style="background: var(--glass-bg-10); border: 1px solid var(--glass-border-20); color: var(--text-color); border-radius: 6px; padding: 0.35rem 0.6rem; font-size: 0.85rem; outline: none;">
            </div>
            <button type="submit" class="btn-primary" style="padding: 0.45rem 1rem; font-size: 0.85rem; font-weight: 600; border-radius: 6px; margin-top: 1rem;">Run Report</button>
        </div>
    </form>

    <!-- Report Table Card -->
    <div class="table-container">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th style="text-align: left;">Customer Name</th>
                    <th style="text-align: center;">Sales Order Count</th>
                    <th style="text-align: right;">Total Sales</th>
                    <th style="text-align: right;">Sales With Tax</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalCount = 0;
                    $totalSalesAmt = 0;
                @endphp
                @forelse($customers as $customer)
                    @php
                        $count = $customer->invoice_count ?? 0;
                        $sales = $customer->total_sales ?? 0;
                        $salesWithTax = $sales * 1.05; // 5% estimated tax
                        $totalCount += $count;
                        $totalSalesAmt += $sales;
                    @endphp
                    <tr>
                        <td style="font-weight: 600; color: #3b82f6;">{{ $customer->name }}</td>
                        <td style="text-align: center; color: var(--text-color); font-weight: 600;">{{ $count }}</td>
                        <td style="text-align: right; color: var(--text-color); font-weight: 600;">${{ number_format($sales, 2) }}</td>
                        <td style="text-align: right; color: var(--text-color); font-weight: 700;">${{ number_format($salesWithTax, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 3rem 1rem;">
                            No sales data found for the selected date range.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="border-top: 2px solid var(--glass-border-20); background: var(--glass-bg-10);">
                    <td style="font-weight: 800; font-size: 1rem; color: var(--text-color);">Total</td>
                    <td style="text-align: center; font-weight: 800; font-size: 1rem; color: var(--text-color);">{{ $totalCount }}</td>
                    <td style="text-align: right; font-weight: 800; font-size: 1.1rem; color: var(--text-color);">${{ number_format($totalSalesAmt, 2) }}</td>
                    <td style="text-align: right; font-weight: 800; font-size: 1.1rem; color: #3b82f6;">${{ number_format($totalSalesAmt * 1.05, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
