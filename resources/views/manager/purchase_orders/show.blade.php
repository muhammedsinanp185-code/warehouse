@extends('layouts.manager')

@section('page_title', 'PURCHASE ORDER DETAILS')

@section('content')
<div class="show-page-wrapper">
    <!-- Top Action Bar -->
    <div class="show-top-bar">
        <a href="{{ route('manager.purchase-orders.index') }}" style="color: #3b82f6; text-decoration: none; font-size: 0.9rem; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem;">
            ← Back to Purchase Orders
        </a>
        <div style="display: flex; gap: 0.5rem;">
            <button style="padding: 0.5rem 1rem; background: var(--glass-bg-10); border: 1px solid var(--glass-border-20); color: var(--text-color); border-radius: 6px; cursor: pointer; font-size: 0.85rem; font-weight: 500; transition: background 0.2s;" onmouseover="this.style.background='var(--glass-bg-20)'" onmouseout="this.style.background='var(--glass-bg-10)'">Edit</button>
            <a href="{{ route('manager.purchase-orders.pdf', $purchaseOrder->id) }}" style="padding: 0.5rem 1rem; background: var(--glass-bg-10); border: 1px solid var(--glass-border-20); color: var(--text-color); border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 500; transition: background 0.2s;" onmouseover="this.style.background='var(--glass-bg-20)'" onmouseout="this.style.background='var(--glass-bg-10)'">Download PDF</a>
            <button style="padding: 0.5rem 1rem; background: var(--glass-bg-10); border: 1px solid var(--glass-border-20); color: var(--text-color); border-radius: 6px; cursor: pointer; font-size: 0.85rem; font-weight: 500; transition: background 0.2s;" onmouseover="this.style.background='var(--glass-bg-20)'" onmouseout="this.style.background='var(--glass-bg-10)'">Send Email</button>
        </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="show-layout-grid">
        <!-- Main Invoice Area -->
        <div class="invoice-card" id="printableArea">
            <!-- Header Row -->
            <div class="invoice-header-row">
                <div>
                    <h2 style="font-size: 1.85rem; font-weight: 800; color: var(--text-color); text-transform: uppercase; letter-spacing: 1px; margin: 0 0 0.25rem 0;">Purchase Order</h2>
                    <p style="color: var(--text-muted); font-family: monospace; font-size: 1rem; margin: 0;">{{ $purchaseOrder->po_number }}</p>
                </div>
                <div style="text-align: right;">
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-color); margin: 0 0 0.25rem 0;">Your Company Name</h3>
                    <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0; line-height: 1.4;">123 Business Rd.<br>Business City, BC 12345</p>
                </div>
            </div>

            <!-- Details Row (Vendor Info & Order Info) -->
            <div class="invoice-details-row">
                <!-- Vendor Details -->
                <div style="flex: 1;">
                    <p style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); letter-spacing: 0.5px; margin: 0 0 0.5rem 0;">Vendor Details</p>
                    <p style="font-size: 1.1rem; font-weight: 700; color: var(--text-color); margin: 0 0 0.25rem 0;">{{ $purchaseOrder->vendor_id ? \App\Models\Vendor::find($purchaseOrder->vendor_id)->name : 'Unknown Vendor' }}</p>
                    @if($purchaseOrder->vendor_id)
                        @php $vendor = \App\Models\Vendor::find($purchaseOrder->vendor_id); @endphp
                        <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0 0 0.15rem 0;">{{ $vendor->email }}</p>
                        <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">{{ $vendor->phone }}</p>
                    @endif
                </div>
                
                <!-- Metadata Table -->
                <div style="flex: 1; display: flex; justify-content: flex-end;">
                    <table style="font-size: 0.85rem; border-collapse: collapse;">
                        <tbody>
                            <tr>
                                <td style="padding: 0.2rem 1rem 0.2rem 0; color: var(--text-muted); text-align: right;">Order Date:</td>
                                <td style="padding: 0.2rem 0; color: var(--text-color); font-weight: 600; text-align: right;">{{ $purchaseOrder->created_at ? $purchaseOrder->created_at->format('d M Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.2rem 1rem 0.2rem 0; color: var(--text-muted); text-align: right;">Expected Date:</td>
                                <td style="padding: 0.2rem 0; color: var(--text-color); font-weight: 600; text-align: right;">{{ $purchaseOrder->expected_date ? \Carbon\Carbon::parse($purchaseOrder->expected_date)->format('d M Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.2rem 1rem 0.2rem 0; color: var(--text-muted); text-align: right;">Reference#:</td>
                                <td style="padding: 0.2rem 0; color: var(--text-color); font-weight: 600; text-align: right;">{{ $purchaseOrder->reference_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.2rem 1rem 0.2rem 0; color: var(--text-muted); text-align: right;">Payment Terms:</td>
                                <td style="padding: 0.2rem 0; color: var(--text-color); font-weight: 600; text-align: right;">{{ $purchaseOrder->payment_terms ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.2rem 1rem 0.2rem 0; color: var(--text-muted); text-align: right;">Delivery Method:</td>
                                <td style="padding: 0.2rem 0; color: var(--text-color); font-weight: 600; text-align: right;">{{ $purchaseOrder->delivery_method ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Items Table -->
            <table class="invoice-table-custom">
                <thead>
                    <tr>
                        <th style="text-align: left;">Item & Description</th>
                        <th style="text-align: center; width: 10%;">Qty</th>
                        <th style="text-align: right; width: 18%;">Rate</th>
                        <th style="text-align: right; width: 15%;">Discount</th>
                        <th style="text-align: right; width: 12%;">Tax (%)</th>
                        <th style="text-align: right; width: 18%;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subTotal = 0;
                        $totalDiscount = 0;
                        $totalTax = 0;
                    @endphp
                    @foreach($purchaseOrder->items as $item)
                        @php
                            $lineSubtotal = $item->quantity * $item->unit_price;
                            $discountVal = $item->discount ?? 0;
                            $afterDiscount = $lineSubtotal - $discountVal;
                            $taxPct = $item->tax ?? 0;
                            $taxVal = $afterDiscount * ($taxPct / 100);
                            
                            $subTotal += $lineSubtotal;
                            $totalDiscount += $discountVal;
                            $totalTax += $taxVal;
                        @endphp
                        <tr>
                            <td>
                                <div style="color: var(--text-color); font-weight: 600;">{{ $item->product->name ?? 'Deleted Product' }}</div>
                                <div style="color: var(--text-muted); font-size: 0.75rem; margin-top: 0.15rem;">SKU: {{ $item->product->sku ?? '-' }}</div>
                            </td>
                            <td style="text-align: center; color: var(--text-muted);">{{ $item->quantity }}</td>
                            <td style="text-align: right; color: var(--text-muted);">${{ number_format($item->unit_price, 2) }}</td>
                            <td style="text-align: right; color: var(--text-muted);">{{ $discountVal > 0 ? '$'.number_format($discountVal, 2) : '-' }}</td>
                            <td style="text-align: right; color: var(--text-muted);">{{ $taxPct > 0 ? $taxPct.'%' : '-' }}</td>
                            <td style="text-align: right; color: var(--text-color); font-weight: 700;">${{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Footer Grid (Notes & Totals Box) -->
            <div class="invoice-footer-grid">
                <div style="flex: 1.2;">
                    @if($purchaseOrder->vendor_notes)
                        <div style="margin-bottom: 1.25rem;">
                            <h4 style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 0.35rem 0;">Vendor Notes</h4>
                            <p style="font-size: 0.875rem; color: var(--text-color); margin: 0; white-space: pre-wrap; line-height: 1.4;">{{ $purchaseOrder->vendor_notes }}</p>
                        </div>
                    @endif
                    @if($purchaseOrder->terms_conditions)
                        <div>
                            <h4 style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 0.35rem 0;">Terms & Conditions</h4>
                            <p style="font-size: 0.875rem; color: var(--text-color); margin: 0; white-space: pre-wrap; line-height: 1.4;">{{ $purchaseOrder->terms_conditions }}</p>
                        </div>
                    @endif
                </div>
                
                <div style="flex: 0.9; background: var(--glass-bg-05); padding: 1.25rem; border-radius: 10px; border: 1px solid var(--glass-border-20);">
                    <div style="display: flex; justify-content: space-between; color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.5rem;">
                        <span>Sub Total</span>
                        <span style="color: var(--text-color); font-weight: 600;">${{ number_format($subTotal, 2) }}</span>
                    </div>
                    @if($totalDiscount > 0)
                    <div style="display: flex; justify-content: space-between; color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.5rem;">
                        <span>Discount</span>
                        <span style="color: #ef4444; font-weight: 600;">-${{ number_format($totalDiscount, 2) }}</span>
                    </div>
                    @endif
                    @if($totalTax > 0)
                    <div style="display: flex; justify-content: space-between; color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.5rem;">
                        <span>Tax</span>
                        <span style="color: var(--text-color); font-weight: 600;">${{ number_format($totalTax, 2) }}</span>
                    </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; border-top: 1px solid var(--glass-border-20); padding-top: 0.75rem; margin-top: 0.5rem;">
                        <span style="font-size: 1rem; font-weight: 700; color: var(--text-color);">Total</span>
                        <span style="font-size: 1.5rem; font-weight: 800; color: #3b82f6;">${{ number_format($purchaseOrder->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions & Workflow -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Status Card -->
            <div class="action-sidebar-card" style="border-left: 4px solid 
                @if($purchaseOrder->status == 'pending') #eab308
                @elseif($purchaseOrder->status == 'received') #10b981
                @elseif($purchaseOrder->status == 'cancelled') #6b7280
                @endif;">
                
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                    <div style="width: 10px; height: 10px; border-radius: 50%; 
                        @if($purchaseOrder->status == 'pending') background: #eab308;
                        @elseif($purchaseOrder->status == 'received') background: #10b981;
                        @elseif($purchaseOrder->status == 'cancelled') background: #6b7280;
                        @endif"></div>
                    <h3 style="margin: 0; color: var(--text-color); font-size: 1.1rem; text-transform: capitalize; font-weight: 700;">{{ $purchaseOrder->status }}</h3>
                </div>
                
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.25rem; line-height: 1.4;">
                    @if($purchaseOrder->status == 'pending')
                        This order is currently pending receipt of items. Click the button below once the items arrive.
                    @elseif($purchaseOrder->status == 'received')
                        Order has been fully received and inventory is updated.
                    @elseif($purchaseOrder->status == 'cancelled')
                        Order was cancelled.
                    @endif
                </p>
                
                @if($purchaseOrder->status == 'pending')
                    <form method="POST" action="{{ route('manager.purchase-orders.receive', $purchaseOrder->id) }}" onsubmit="return confirm('Are you sure you want to mark this entire order as received? This will permanently update product inventory levels.');">
                        @csrf
                        <button type="submit" class="btn-success" style="width: 100%; padding: 0.75rem; text-align: center; border-radius: 8px; font-weight: 600;">Mark as Received</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
