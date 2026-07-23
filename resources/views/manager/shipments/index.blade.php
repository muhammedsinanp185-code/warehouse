@extends('layouts.manager')

@section('page_title', 'SHIPMENTS')

@section('content')
<div style="max-width: 1100px; margin: 0 auto; width: 100%;">
    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--text-color);">All Shipments</h2>
            <p style="margin: 0.25rem 0 0 0; color: var(--text-muted); font-size: 0.875rem;">Track order packages, shipments, and delivery statuses.</p>
        </div>
    </div>

    <!-- Shipments Table -->
    <div class="table-container">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th style="text-align: left;">Tracking #</th>
                    <th style="text-align: left;">Sales Order</th>
                    <th style="text-align: left;">Customer</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Date Packed</th>
                    <th style="text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shipments as $shipment)
                    <tr>
                        <td style="font-family: monospace; font-weight: 700; color: #3b82f6;">{{ $shipment->tracking_number }}</td>
                        <td>
                            <a href="{{ route('manager.sales_orders.show', $shipment->salesOrder) }}" style="color: var(--text-color); font-weight: 600; text-decoration: none;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='var(--text-color)'">
                                {{ $shipment->salesOrder->so_number }}
                            </a>
                        </td>
                        <td style="color: var(--text-color); font-weight: 500;">{{ $shipment->salesOrder->customer->name ?? 'Unknown' }}</td>
                        <td style="text-align: center;">
                            @if($shipment->status == 'packed')
                                <span style="padding: 0.3rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: rgba(59, 130, 246, 0.2); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.3);">Packed</span>
                            @elseif($shipment->status == 'shipped')
                                <span style="padding: 0.3rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: rgba(234, 179, 8, 0.2); color: #eab308; border: 1px solid rgba(234, 179, 8, 0.3);">Shipped</span>
                            @elseif($shipment->status == 'delivered')
                                <span style="padding: 0.3rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);">Delivered</span>
                            @endif
                        </td>
                        <td style="text-align: center; color: var(--text-muted); font-size: 0.9rem;">{{ $shipment->created_at->format('M d, Y H:i') }}</td>
                        <td style="text-align: center;">
                            @if($shipment->status == 'packed')
                                <form action="{{ route('manager.shipments.ship', $shipment) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="btn-warning" style="padding: 0.45rem 1rem; font-size: 0.85rem; font-weight: 600; border-radius: 6px;">
                                        Mark Shipped
                                    </button>
                                </form>
                            @elseif($shipment->status == 'shipped')
                                <form action="{{ route('manager.shipments.deliver', $shipment) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="btn-success" style="padding: 0.45rem 1rem; font-size: 0.85rem; font-weight: 600; border-radius: 6px;">
                                        Mark Delivered
                                    </button>
                                </form>
                            @else
                                <span style="color: #10b981; font-weight: 600; font-size: 0.85rem;">Completed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 4rem 1rem;">
                            No shipments created yet. Confirm a Sales Order to begin packing.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
