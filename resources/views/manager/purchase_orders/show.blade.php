@extends('layouts.manager')

@section('page_title', 'PURCHASE ORDER DETAILS')
@section('back_link', route('manager.purchase-orders.index'))

@section('content')
<div style="max-width: 1100px; margin: 0 auto; width: 100%;">
    
    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; background: var(--glass-bg-05); border: 1px solid var(--glass-border-10); border-radius: 16px; padding: 2rem; backdrop-filter: blur(10px);">
        <div>
            <h1 style="margin: 0 0 0.5rem 0; font-size: 2.2rem; color: var(--text-primary); font-weight: 700;">{{ $purchaseOrder->po_number }}</h1>
            <div style="display: flex; gap: 1.5rem; color: var(--text-muted); font-size: 0.95rem;">
                <div style="display: flex; align-items: center; gap: 0.4rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                    Created: {{ $purchaseOrder->created_at->format('M d, Y H:i') }}
                </div>
                <div style="display: flex; align-items: center; gap: 0.4rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                    By: {{ $purchaseOrder->creator->name }}
                </div>
            </div>
        </div>
        <div style="text-align: right;">
            <div style="margin-bottom: 1rem;">
                @if($purchaseOrder->status == 'pending')
                    <span style="padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.9rem; font-weight: 700; background: rgba(245, 158, 11, 0.2); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3);">PENDING RECEIPT</span>
                @elseif($purchaseOrder->status == 'received')
                    <span style="padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.9rem; font-weight: 700; background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);">RECEIVED</span>
                @else
                    <span style="padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.9rem; font-weight: 700; background: rgba(107, 114, 128, 0.2); color: #9ca3af; border: 1px solid rgba(107, 114, 128, 0.3);">CANCELLED</span>
                @endif
            </div>
            
            @if($purchaseOrder->status == 'pending')
            <form method="POST" action="{{ route('manager.purchase-orders.receive', $purchaseOrder->id) }}" onsubmit="return confirm('Are you sure you want to mark this entire order as received? This will permanently update product inventory levels.');">
                @csrf
                <button type="submit" style="padding: 0.8rem 1.5rem; background: #10b981; color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.95rem; transition: background 0.2s;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 18px; height: 18px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    Mark as Received
                </button>
            </form>
            @endif
            
            @if($purchaseOrder->status == 'received')
            <div style="color: var(--text-muted); font-size: 0.85rem; margin-top: 0.5rem;">
                Received on: {{ $purchaseOrder->received_at->format('M d, Y H:i') }}<br>
                By: {{ $purchaseOrder->receiver->name ?? 'Unknown' }}
            </div>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 2rem; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 2rem; font-weight: 500;">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-container">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th style="text-align: left;">Product</th>
                    <th style="text-align: left;">SKU</th>
                    <th style="text-align: center;">Order Qty</th>
                    <th style="text-align: center;">Current Stock</th>
                    <th style="text-align: center;">Min Level</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchaseOrder->items as $item)
                <tr>
                    <td>
                        <a href="{{ route('manager.products.show', $item->product->id) }}" style="font-weight: 500; color: var(--text-primary); text-decoration: none;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='var(--text-primary)'">
                            {{ $item->product->name }}
                        </a>
                    </td>
                    <td style="color: var(--text-muted); font-family: monospace;">{{ $item->product->sku }}</td>
                    <td style="text-align: center;">
                        <span style="font-weight: 700; color: #3b82f6; font-size: 1.1rem;">+{{ $item->quantity }}</span>
                    </td>
                    <td style="text-align: center; color: var(--text-muted);">{{ $item->product->quantity }}</td>
                    <td style="text-align: center; color: var(--text-muted);">{{ $item->product->min_stock_level }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
