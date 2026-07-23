@extends('layouts.manager')

@section('page_title', 'NEW PURCHASE ORDER')

@section('content')
<div class="order-form-wrapper">
    <!-- Header Card -->
    <div class="form-header-card">
        <div>
            <div class="form-header-title">New Purchase Order</div>
            <div class="form-header-subtitle">Order stock from vendors and track incoming inventory.</div>
        </div>
        <a href="{{ route('manager.purchase-orders.index') }}" style="color: var(--text-muted); font-size: 1.5rem; text-decoration: none; padding: 0.25rem 0.75rem; border-radius: 6px; transition: background 0.2s;" onmouseover="this.style.background='var(--glass-bg-10)'" onmouseout="this.style.background='transparent'">&times;</a>
    </div>

    <form action="{{ route('manager.purchase-orders.store') }}" method="POST" id="poForm">
        @csrf
        
        <!-- General Information Card -->
        <div class="form-section-card">
            <div class="form-section-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px; color: #3b82f6;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-1-4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                </svg>
                Vendor & Order Information
            </div>
            
            <div class="form-grid-2">
                <!-- Left Column -->
                <div>
                    <div class="form-group-custom">
                        <label class="form-label-custom">Vendor Name <span style="color: #ef4444;">*</span></label>
                        <select name="vendor_id" required class="form-control-custom">
                            <option value="" disabled selected>Select a Vendor</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" style="background: #0f172a; color: #fff;">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Reference Number</label>
                        <input type="text" name="reference_number" placeholder="e.g. PO-REF-1002" class="form-control-custom">
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Payment Terms</label>
                        <select name="payment_terms" class="form-control-custom">
                            <option value="Due on Receipt" style="background: #0f172a; color: #fff;">Due on Receipt</option>
                            <option value="Net 15" style="background: #0f172a; color: #fff;">Net 15</option>
                            <option value="Net 30" style="background: #0f172a; color: #fff;">Net 30</option>
                            <option value="Net 45" style="background: #0f172a; color: #fff;">Net 45</option>
                            <option value="Net 60" style="background: #0f172a; color: #fff;">Net 60</option>
                        </select>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div class="form-group-custom">
                        <label class="form-label-custom">Expected Delivery Date</label>
                        <input type="date" name="expected_date" class="form-control-custom" style="color-scheme: dark;">
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Delivery Method</label>
                        <input type="text" name="delivery_method" placeholder="e.g. Freight, Air Cargo" class="form-control-custom">
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table Card -->
        <div class="form-section-card">
            <div class="form-section-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px; color: #3b82f6;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                </svg>
                Item Details
            </div>
            
            <div class="form-table-wrapper">
                <table class="form-table-custom" id="itemsTable">
                    <thead>
                        <tr>
                            <th style="min-w: 220px; width: 35%;">Item Details</th>
                            <th style="width: 10%; text-align: center;">Qty</th>
                            <th style="width: 15%; text-align: center;">Cost Rate ($)</th>
                            <th style="width: 12%; text-align: center;">Discount ($)</th>
                            <th style="width: 10%; text-align: center;">Tax (%)</th>
                            <th style="width: 15%; text-align: right; padding-right: 1rem;">Amount ($)</th>
                            <th style="width: 3%; text-align: center;"></th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        @php
                            $initialProdId = request('product_id');
                            $initialProduct = $initialProdId ? \App\Models\Product::find($initialProdId) : null;
                            $initialRate = $initialProduct ? ($initialProduct->cost_price ?? $initialProduct->price ?? 0) : '';
                            $initialQty = request('qty') ? request('qty') : 1;
                            $initialAmount = ($initialRate && $initialQty) ? ($initialRate * $initialQty) : '0.00';
                        @endphp
                        <tr class="item-row">
                            <td style="padding: 0.75rem;">
                                <select name="items[0][product_id]" required class="product-select form-control-custom">
                                    <option value="" disabled {{ !$initialProdId ? 'selected' : '' }}>Select an item</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->cost_price ?? $product->price ?? 0 }}" style="background: #0f172a; color: #fff;" {{ ($initialProdId == $product->id) ? 'selected' : '' }}>{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="padding: 0.75rem;">
                                <input type="number" name="items[0][quantity]" required min="1" value="{{ $initialQty }}" class="qty-input form-control-custom" style="text-align: center;">
                            </td>
                            <td style="padding: 0.75rem;">
                                <input type="number" name="items[0][unit_price]" required step="0.01" min="0" value="{{ $initialRate }}" placeholder="0.00" class="rate-input form-control-custom" style="text-align: center;">
                            </td>
                            <td style="padding: 0.75rem;">
                                <input type="number" name="items[0][discount]" step="0.01" min="0" value="0" class="discount-input form-control-custom" style="text-align: center;">
                            </td>
                            <td style="padding: 0.75rem;">
                                <input type="number" name="items[0][tax]" step="0.01" min="0" value="0" class="tax-input form-control-custom" style="text-align: center;">
                            </td>
                            <td style="padding: 0.75rem 1rem; text-align: right; font-weight: 700; color: var(--text-color);">
                                <span class="amount-text">{{ is_numeric($initialAmount) ? number_format($initialAmount, 2) : '0.00' }}</span>
                            </td>
                            <td style="padding: 0.75rem; text-align: center;">
                                <button type="button" class="remove-row" style="background: none; border: none; color: #ef4444; font-size: 1.25rem; cursor: pointer; padding: 0.25rem 0.5rem;" title="Remove Row">&times;</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1rem;">
                <button type="button" id="addRowBtn" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.2rem; background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.3); color: #3b82f6; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='rgba(59, 130, 246, 0.25)'" onmouseout="this.style.background='rgba(59, 130, 246, 0.15)'">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Line Item
                </button>
            </div>
        </div>

        <!-- Notes & Calculation Summary Card -->
        <div class="form-summary-layout">
            <!-- Notes Column -->
            <div class="form-section-card" style="margin-bottom: 0;">
                <div class="form-group-custom" style="margin-bottom: 1.25rem;">
                    <label class="form-label-custom">Vendor Notes</label>
                    <textarea name="vendor_notes" rows="3" class="form-control-custom" placeholder="Notes to send to the vendor..."></textarea>
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom">Terms & Conditions</label>
                    <textarea name="terms_conditions" rows="3" class="form-control-custom" placeholder="Enter terms and conditions..."></textarea>
                </div>
            </div>

            <!-- Calculation Box Column -->
            <div class="summary-box">
                <div class="form-label-custom" style="font-size: 0.85rem; border-bottom: 1px solid var(--glass-border-20); padding-bottom: 0.5rem;">Order Summary</div>
                
                <div class="summary-row">
                    <span>Sub Total</span>
                    <span id="subTotal" style="color: var(--text-color); font-weight: 600;">0.00</span>
                </div>
                <div class="summary-row">
                    <span>Discount Total</span>
                    <span id="totalDiscount" style="color: #ef4444; font-weight: 600;">0.00</span>
                </div>
                <div class="summary-row">
                    <span>Tax Total</span>
                    <span id="totalTax" style="color: var(--text-color); font-weight: 600;">0.00</span>
                </div>
                <div class="summary-total-row">
                    <span style="font-size: 1rem;">Grand Total ($)</span>
                    <span id="grandTotal" style="color: #3b82f6; font-size: 1.5rem;">0.00</span>
                </div>
            </div>
        </div>

        <!-- Sticky Footer Buttons -->
        <div class="sticky-action-bar">
            <a href="{{ route('manager.purchase-orders.index') }}" style="padding: 0.65rem 1.5rem; border-radius: 8px; border: 1px solid var(--glass-border-20); color: var(--text-muted); text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: background 0.2s;" onmouseover="this.style.background='var(--glass-bg-10)'" onmouseout="this.style.background='transparent'">
                Cancel
            </a>
            <button type="submit" class="btn-primary" style="padding: 0.65rem 2rem; font-size: 0.9rem; font-weight: 600; border-radius: 8px;">
                Save & Confirm PO
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const itemsBody = document.getElementById('itemsBody');
        const addRowBtn = document.getElementById('addRowBtn');
        let rowIndex = 1;

        function calculateTotals() {
            let subTotal = 0;
            let totalDiscount = 0;
            let totalTax = 0;
            let grandTotal = 0;

            document.querySelectorAll('.item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const rate = parseFloat(row.querySelector('.rate-input').value) || 0;
                const discount = parseFloat(row.querySelector('.discount-input').value) || 0;
                const taxPct = parseFloat(row.querySelector('.tax-input').value) || 0;
                
                const itemSubtotal = (qty * rate);
                const itemAfterDiscount = itemSubtotal - discount;
                const taxAmount = itemAfterDiscount * (taxPct / 100);
                const amount = itemAfterDiscount + taxAmount;
                
                row.querySelector('.amount-text').textContent = amount.toFixed(2);
                
                subTotal += itemSubtotal;
                totalDiscount += discount;
                totalTax += taxAmount;
                grandTotal += amount;
            });

            document.getElementById('subTotal').textContent = subTotal.toFixed(2);
            document.getElementById('totalDiscount').textContent = '-' + totalDiscount.toFixed(2);
            document.getElementById('totalTax').textContent = totalTax.toFixed(2);
            document.getElementById('grandTotal').textContent = grandTotal.toFixed(2);
        }

        function attachListeners(row) {
            row.querySelector('.product-select').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.getAttribute('data-price') || 0;
                row.querySelector('.rate-input').value = price;
                calculateTotals();
            });

            row.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', calculateTotals);
            });
            
            row.querySelector('.remove-row').addEventListener('click', function() {
                if (document.querySelectorAll('.item-row').length > 1) {
                    row.remove();
                    calculateTotals();
                } else {
                    alert('You must have at least one item.');
                }
            });
        }

        attachListeners(document.querySelector('.item-row'));
        calculateTotals();

        addRowBtn.addEventListener('click', function() {
            const newRow = document.querySelector('.item-row').cloneNode(true);
            
            newRow.querySelector('.product-select').name = `items[${rowIndex}][product_id]`;
            newRow.querySelector('.product-select').value = "";
            newRow.querySelector('.qty-input').name = `items[${rowIndex}][quantity]`;
            newRow.querySelector('.qty-input').value = "1";
            newRow.querySelector('.rate-input').name = `items[${rowIndex}][unit_price]`;
            newRow.querySelector('.rate-input').value = "";
            newRow.querySelector('.discount-input').name = `items[${rowIndex}][discount]`;
            newRow.querySelector('.discount-input').value = "0";
            newRow.querySelector('.tax-input').name = `items[${rowIndex}][tax]`;
            newRow.querySelector('.tax-input').value = "0";
            newRow.querySelector('.amount-text').textContent = "0.00";
            
            attachListeners(newRow);
            itemsBody.appendChild(newRow);
            rowIndex++;
        });
    });
</script>
@endsection
