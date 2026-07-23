@extends('layouts.manager')

@section('page_title', 'PACKING HISTORY REPORT')

@section('content')
<div style="max-width: 1100px; margin: 0 auto; width: 100%;">
    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <a href="{{ route('manager.reports.index') }}" style="color: #3b82f6; text-decoration: none; font-size: 0.9rem; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem;">
                ← Back to Reports Center
            </a>
            <h2 style="margin: 0.5rem 0 0 0; font-size: 1.5rem; font-weight: 700; color: var(--text-color);">Packing History</h2>
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
                    <th style="text-align: left;">Packing Slip #</th>
                    <th style="text-align: left;">Sales Order</th>
                    <th style="text-align: left;">Customer</th>
                    <th style="text-align: center;">Packed Date</th>
                    <th style="text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shipments as $shipment)
                    <tr>
                        <td style="font-family: monospace; font-weight: 700; color: var(--text-color);">PK-{{ str_pad($shipment->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td style="font-weight: 600; color: #3b82f6;">{{ $shipment->salesOrder->so_number ?? '-' }}</td>
                        <td style="color: var(--text-color);">{{ $shipment->salesOrder->customer->name ?? 'Unknown' }}</td>
                        <td style="text-align: center; color: var(--text-muted); font-size: 0.85rem;">{{ $shipment->created_at->format('M d, Y H:i') }}</td>
                        <td style="text-align: center;">
                            <span style="padding: 0.25rem 0.6rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: rgba(59, 130, 246, 0.2); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.3);">
                                {{ ucfirst($shipment->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 3rem 1rem;">
                            No packing records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
