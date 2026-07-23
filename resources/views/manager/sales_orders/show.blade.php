@extends('layouts.manager')

@section('page_title', 'SALES ORDER DETAILS')

@section('content')
<div class="show-page-wrapper">
    <!-- Top Action Bar -->
    <div class="show-top-bar">
        <a href="{{ route('manager.sales_orders.index') }}" style="color: #3b82f6; text-decoration: none; font-size: 0.9rem; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem;">
            ← Back to Sales Orders
        </a>
        <div style="display: flex; gap: 0.5rem;">
            <button style="padding: 0.5rem 1rem; background: var(--glass-bg-10); border: 1px solid var(--glass-border-20); color: var(--text-color); border-radius: 6px; cursor: pointer; font-size: 0.85rem; font-weight: 500; transition: background 0.2s;" onmouseover="this.style.background='var(--glass-bg-20)'" onmouseout="this.style.background='var(--glass-bg-10)'">Edit</button>
            <button style="padding: 0.5rem 1rem; background: var(--glass-bg-10); border: 1px solid var(--glass-border-20); color: var(--text-color); border-radius: 6px; cursor: pointer; font-size: 0.85rem; font-weight: 500; transition: background 0.2s;" onmouseover="this.style.background='var(--glass-bg-20)'" onmouseout="this.style.background='var(--glass-bg-10)'">Download PDF</button>
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
                    <h2 style="font-size: 1.85rem; font-weight: 800; color: var(--text-color); text-transform: uppercase; letter-spacing: 1px; margin: 0 0 0.25rem 0;">Sales Order</h2>
                    <p style="color: var(--text-muted); font-family: monospace; font-size: 1rem; margin: 0;">{{ $salesOrder->so_number }}</p>
                </div>
                <div style="text-align: right;">
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-color); margin: 0 0 0.25rem 0;">Your Company Name</h3>
                    <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0; line-height: 1.4;">123 Business Rd.<br>Business City, BC 12345</p>
                </div>
            </div>

            <!-- Details Row (Customer Info & Order Info) -->
            <div class="invoice-details-row">
                <!-- Customer Details -->
                <div style="flex: 1;">
                    <p style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--text-muted); letter-spacing: 0.5px; margin: 0 0 0.5rem 0;">Customer Details</p>
                    <p style="font-size: 1.1rem; font-weight: 700; color: var(--text-color); margin: 0 0 0.25rem 0;">{{ $salesOrder->customer->name ?? 'Unknown Customer' }}</p>
                    @if($salesOrder->customer)
                        <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0 0 0.15rem 0;">{{ $salesOrder->customer->email }}</p>
                        <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">{{ $salesOrder->customer->phone }}</p>
                    @endif
                </div>
                
                <!-- Metadata Table -->
                <div style="flex: 1; display: flex; justify-content: flex-end;">
                    <table style="font-size: 0.85rem; border-collapse: collapse;">
                        <tbody>
                            <tr>
                                <td style="padding: 0.2rem 1rem 0.2rem 0; color: var(--text-muted); text-align: right;">Order Date:</td>
                                <td style="padding: 0.2rem 0; color: var(--text-color); font-weight: 600; text-align: right;">{{ $salesOrder->order_date ? $salesOrder->order_date->format('d M Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.2rem 1rem 0.2rem 0; color: var(--text-muted); text-align: right;">Expected Shipment:</td>
                                <td style="padding: 0.2rem 0; color: var(--text-color); font-weight: 600; text-align: right;">{{ $salesOrder->expected_shipment_date ? \Carbon\Carbon::parse($salesOrder->expected_shipment_date)->format('d M Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.2rem 1rem 0.2rem 0; color: var(--text-muted); text-align: right;">Reference#:</td>
                                <td style="padding: 0.2rem 0; color: var(--text-color); font-weight: 600; text-align: right;">{{ $salesOrder->reference_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.2rem 1rem 0.2rem 0; color: var(--text-muted); text-align: right;">Payment Terms:</td>
                                <td style="padding: 0.2rem 0; color: var(--text-color); font-weight: 600; text-align: right;">{{ $salesOrder->payment_terms ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.2rem 1rem 0.2rem 0; color: var(--text-muted); text-align: right;">Delivery Method:</td>
                                <td style="padding: 0.2rem 0; color: var(--text-color); font-weight: 600; text-align: right;">{{ $salesOrder->delivery_method ?? '-' }}</td>
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
                    @foreach($salesOrder->items as $item)
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
                    @if($salesOrder->customer_notes)
                        <div style="margin-bottom: 1.25rem;">
                            <h4 style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 0.35rem 0;">Customer Notes</h4>
                            <p style="font-size: 0.875rem; color: var(--text-color); margin: 0; white-space: pre-wrap; line-height: 1.4;">{{ $salesOrder->customer_notes }}</p>
                        </div>
                    @endif
                    @if($salesOrder->terms_conditions)
                        <div>
                            <h4 style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 0.35rem 0;">Terms & Conditions</h4>
                            <p style="font-size: 0.875rem; color: var(--text-color); margin: 0; white-space: pre-wrap; line-height: 1.4;">{{ $salesOrder->terms_conditions }}</p>
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
                        <span style="font-size: 1.5rem; font-weight: 800; color: #3b82f6;">${{ number_format($salesOrder->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions & Workflow -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Status Card -->
            <div class="action-sidebar-card" style="border-left: 4px solid 
                @if($salesOrder->status == 'draft') #6b7280
                @elseif($salesOrder->status == 'confirmed') #3b82f6
                @elseif($salesOrder->status == 'shipped') #eab308
                @elseif($salesOrder->status == 'delivered') #10b981
                @endif;">
                
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                    <div style="width: 10px; height: 10px; border-radius: 50%; 
                        @if($salesOrder->status == 'draft') background: #6b7280;
                        @elseif($salesOrder->status == 'confirmed') background: #3b82f6;
                        @elseif($salesOrder->status == 'shipped') background: #eab308;
                        @elseif($salesOrder->status == 'delivered') background: #10b981;
                        @endif"></div>
                    <h3 style="margin: 0; color: var(--text-color); font-size: 1.1rem; text-transform: capitalize; font-weight: 700;">{{ $salesOrder->status }}</h3>
                </div>
                
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.25rem; line-height: 1.4;">
                    @if($salesOrder->status == 'draft')
                        This order is a draft. Confirm it to lock it in and begin fulfillment.
                    @elseif($salesOrder->status == 'confirmed')
                        Order confirmed. Ready to be packed into a shipment.
                    @elseif($salesOrder->status == 'shipped')
                        Order has been shipped and is on its way.
                    @elseif($salesOrder->status == 'delivered')
                        Order has been successfully delivered.
                    @endif
                </p>
                
                @if($salesOrder->status == 'draft')
                    <form action="{{ route('manager.sales_orders.confirm', $salesOrder) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary" style="width: 100%; padding: 0.75rem; text-align: center; border-radius: 8px; font-weight: 600;">Confirm Order</button>
                    </form>
                @elseif($salesOrder->status == 'confirmed')
                    <form action="{{ route('manager.shipments.store', $salesOrder) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary" style="width: 100%; padding: 0.75rem; text-align: center; border-radius: 8px; font-weight: 600;">Create Shipment</button>
                    </form>
                @endif
            </div>

            <!-- Related Shipments Card -->
            @if($salesOrder->shipments->count() > 0)
                <div class="action-sidebar-card" style="padding: 0; overflow: hidden;">
                    <div style="padding: 1rem; border-bottom: 1px solid var(--glass-border-10); background: var(--glass-bg-05); display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="margin: 0; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Shipments</h3>
                        <span style="background: rgba(59, 130, 246, 0.2); color: #3b82f6; font-size: 0.75rem; padding: 0.15rem 0.5rem; border-radius: 9999px; font-weight: 700;">{{ $salesOrder->shipments->count() }}</span>
                    </div>
                    <div style="padding: 1rem; display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach($salesOrder->shipments as $shipment)
                            <div style="background: var(--glass-bg-05); border: 1px solid var(--glass-border-10); border-radius: 8px; padding: 0.75rem; display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <p style="margin: 0; font-size: 0.85rem; font-weight: 600; color: var(--text-color);">{{ $shipment->tracking_number ?? 'Awaiting Tracking#' }}</p>
                                    <p style="margin: 0.2rem 0 0 0; font-size: 0.75rem; color: var(--text-muted);">{{ $shipment->created_at->format('d M, Y') }}</p>
                                </div>
                                <div>
                                    @if($shipment->status == 'packed')
                                        <span style="padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; background: rgba(59, 130, 246, 0.2); color: #3b82f6;">Packed</span>
                                    @elseif($shipment->status == 'shipped')
                                        <span style="padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; background: rgba(234, 179, 8, 0.2); color: #eab308;">Shipped</span>
                                    @elseif($shipment->status == 'delivered')
                                        <span style="padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; background: rgba(16, 185, 129, 0.2); color: #10b981;">Delivered</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
