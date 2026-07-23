@extends('layouts.manager')

@section('page_title', 'VENDORS (SUPPLIERS)')

@section('content')

@if(session('success'))
    <div class="alert" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 500;">
        {{ session('success') }}
    </div>
@endif

<!-- Header / Actions -->
<div class="table-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <div>
        <h2 class="dashboard-section-title" style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--text-color);">Manage Vendors</h2>
        <p style="margin: 0.25rem 0 0 0; color: var(--text-muted); font-size: 0.875rem;">View, edit, or add new supplier profiles to your database.</p>
    </div>
    <button class="btn btn-primary" onclick="document.getElementById('addVendorModal').style.display='flex'" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.65rem 1.25rem; font-weight: 600; font-size: 0.9rem; border-radius: 8px;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Add Vendor
    </button>
</div>

<!-- Vendors Table -->
<div class="table-container">
    <table class="dashboard-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Tax ID</th>
                <th>Address</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vendors as $vendor)
            <tr>
                <td style="font-weight: 600; color: var(--text-color);">{{ $vendor->name }}</td>
                <td style="color: var(--text-muted);">{{ $vendor->email ?? '-' }}</td>
                <td style="color: var(--text-muted);">{{ $vendor->phone ?? '-' }}</td>
                <td style="color: var(--text-muted);">{{ $vendor->tax_id ?? '-' }}</td>
                <td style="color: var(--text-muted);">{{ Str::limit($vendor->address ?? '-', 40) }}</td>
                <td style="text-align: right;">
                    <button class="btn btn-secondary" onclick="openEditVendorModal({{ json_encode($vendor) }})" style="padding: 0.35rem 0.75rem; font-size: 0.85rem; margin-right: 0.4rem; border-radius: 6px;">Edit</button>
                    <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this vendor?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.75rem; font-size: 0.85rem; background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 6px;">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 3rem;">No vendors found. Add your first supplier!</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Add Vendor Modal -->
<div id="addVendorModal" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(8px); z-index: 1000; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="modal-content" style="background: rgba(15, 23, 42, 0.95); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 16px; padding: 2rem; width: 620px; max-width: 100%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7); backdrop-filter: blur(16px);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem;">
            <div>
                <h3 style="margin: 0; color: #fff; font-size: 1.25rem; font-weight: 700;">Add New Vendor</h3>
                <p style="margin: 0.25rem 0 0 0; color: var(--text-muted); font-size: 0.85rem;">Enter company info and contact numbers for your supplier.</p>
            </div>
            <button type="button" onclick="document.getElementById('addVendorModal').style.display='none'" style="background: none; border: none; color: var(--text-muted); font-size: 1.5rem; cursor: pointer; padding: 0.25rem 0.5rem; line-height: 1;">&times;</button>
        </div>

        <form action="{{ route('vendors.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
            @csrf
            
            <div class="form-group-custom">
                <label class="form-label-custom">Company / Vendor Name <span style="color: #ef4444;">*</span></label>
                <input type="text" name="name" class="form-control-custom" placeholder="e.g. Apex Global Supply Co." required>
            </div>

            <div class="form-grid-2" style="gap: 1rem;">
                <div class="form-group-custom">
                    <label class="form-label-custom">Email Address</label>
                    <input type="email" name="email" class="form-control-custom" placeholder="vendor@example.com">
                </div>
                <div class="form-group-custom">
                    <label class="form-label-custom">Phone Number (India)</label>
                    <div style="display: flex; align-items: center;">
                        <span style="background: var(--glass-bg-10); border: 1px solid var(--glass-border-20); border-right: none; border-radius: 8px 0 0 8px; padding: 0.6rem 0.75rem; color: var(--text-color); font-weight: 600; font-size: 0.9rem; user-select: none;">+91</span>
                        <input type="text" name="phone" id="add_vendor_phone" inputmode="numeric" maxlength="10" class="form-control-custom" placeholder="9876543210" style="border-radius: 0 8px 8px 0;" oninput="validateIndianPhone(this, 'add_vendor_phone_error')" onblur="validateIndianPhone(this, 'add_vendor_phone_error')">
                    </div>
                    <div id="add_vendor_phone_error" style="display: none; color: #ef4444; font-size: 0.78rem; font-weight: 600; margin-top: 0.35rem;"></div>
                </div>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Tax ID (GST / VAT)</label>
                <input type="text" name="tax_id" class="form-control-custom" placeholder="e.g. GSTIN98741256">
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Street / Billing Address</label>
                <textarea name="address" class="form-control-custom" rows="3" placeholder="Full street address..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1.25rem;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addVendorModal').style.display='none'" style="padding: 0.6rem 1.25rem; border-radius: 8px;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1.75rem; border-radius: 8px; font-weight: 600;">Save Vendor</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Vendor Modal -->
<div id="editVendorModal" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(8px); z-index: 1000; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="modal-content" style="background: rgba(15, 23, 42, 0.95); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 16px; padding: 2rem; width: 620px; max-width: 100%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7); backdrop-filter: blur(16px);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem;">
            <div>
                <h3 style="margin: 0; color: #fff; font-size: 1.25rem; font-weight: 700;">Edit Vendor</h3>
                <p style="margin: 0.25rem 0 0 0; color: var(--text-muted); font-size: 0.85rem;">Update profile details for this supplier.</p>
            </div>
            <button type="button" onclick="document.getElementById('editVendorModal').style.display='none'" style="background: none; border: none; color: var(--text-muted); font-size: 1.5rem; cursor: pointer; padding: 0.25rem 0.5rem; line-height: 1;">&times;</button>
        </div>

        <form id="editVendorForm" method="POST" style="display: flex; flex-direction: column; gap: 1.25rem;">
            @csrf
            @method('PUT')

            <div class="form-group-custom">
                <label class="form-label-custom">Company / Vendor Name <span style="color: #ef4444;">*</span></label>
                <input type="text" name="name" id="edit_vendor_name" class="form-control-custom" required>
            </div>

            <div class="form-grid-2" style="gap: 1rem;">
                <div class="form-group-custom">
                    <label class="form-label-custom">Email Address</label>
                    <input type="email" name="email" id="edit_vendor_email" class="form-control-custom">
                </div>
                <div class="form-group-custom">
                    <label class="form-label-custom">Phone Number (India)</label>
                    <div style="display: flex; align-items: center;">
                        <span style="background: var(--glass-bg-10); border: 1px solid var(--glass-border-20); border-right: none; border-radius: 8px 0 0 8px; padding: 0.6rem 0.75rem; color: var(--text-color); font-weight: 600; font-size: 0.9rem; user-select: none;">+91</span>
                        <input type="text" name="phone" id="edit_vendor_phone" inputmode="numeric" maxlength="10" class="form-control-custom" placeholder="9876543210" style="border-radius: 0 8px 8px 0;" oninput="validateIndianPhone(this, 'edit_vendor_phone_error')" onblur="validateIndianPhone(this, 'edit_vendor_phone_error')">
                    </div>
                    <div id="edit_vendor_phone_error" style="display: none; color: #ef4444; font-size: 0.78rem; font-weight: 600; margin-top: 0.35rem;"></div>
                </div>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Tax ID (GST / VAT)</label>
                <input type="text" name="tax_id" id="edit_vendor_tax_id" class="form-control-custom">
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Street / Billing Address</label>
                <textarea name="address" id="edit_vendor_address" class="form-control-custom" rows="3"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1.25rem;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('editVendorModal').style.display='none'" style="padding: 0.6rem 1.25rem; border-radius: 8px;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1.75rem; border-radius: 8px; font-weight: 600;">Update Vendor</button>
            </div>
        </form>
    </div>
</div>

<script>
    function validateIndianPhone(input, errorMsgId) {
        input.value = input.value.replace(/[^0-9]/g, '');
        const val = input.value;
        const errorEl = document.getElementById(errorMsgId);
        
        if (!errorEl) return true;
        
        if (val.length > 0) {
            const firstDigit = val.charAt(0);
            if (!['6', '7', '8', '9'].includes(firstDigit)) {
                errorEl.textContent = '⚠️ Must start with 6, 7, 8, or 9';
                errorEl.style.display = 'block';
                input.style.borderColor = '#ef4444';
                return false;
            }
            
            if (val.length < 10) {
                errorEl.textContent = '⚠️ Must be 10 digits (' + val.length + '/10 entered)';
                errorEl.style.display = 'block';
                input.style.borderColor = '#ef4444';
                return false;
            }
        }
        
        errorEl.style.display = 'none';
        input.style.borderColor = 'var(--glass-border-20)';
        return true;
    }

    function openEditVendorModal(vendor) {
        document.getElementById('edit_vendor_name').value = vendor.name;
        document.getElementById('edit_vendor_email').value = vendor.email || '';
        let cleanPhone = (vendor.phone || '').replace(/^\+?91\s?/, '').replace(/[^0-9]/g, '');
        document.getElementById('edit_vendor_phone').value = cleanPhone;
        validateIndianPhone(document.getElementById('edit_vendor_phone'), 'edit_vendor_phone_error');
        document.getElementById('edit_vendor_tax_id').value = vendor.tax_id || '';
        document.getElementById('edit_vendor_address').value = vendor.address || '';
        document.getElementById('editVendorForm').action = '/vendors/' + vendor.id;
        document.getElementById('editVendorModal').style.display = 'flex';
    }
</script>@endsection
