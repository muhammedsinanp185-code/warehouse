@extends('layouts.manager')

@section('page_title', 'PRODUCTS CATALOG')

@section('content')
            <div class="filters-bar" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; align-items: center; justify-content: flex-end; flex-wrap: wrap;">
                
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <input type="text" id="searchInput" placeholder="Search SKU or Name..." class="form-input" style="width: 250px; margin: 0; padding: 0.6rem; font-size: 0.9rem;">
                    <select id="statusSelect" class="form-input" style="margin: 0; padding: 0.6rem; width: 140px; background: transparent; color: var(--text-color); font-size: 0.9rem;">
                        <option value="" style="color: black;">All Status</option>
                        <option value="healthy" style="color: black;">Healthy</option>
                        <option value="low" style="color: black;">Low Stock</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 0.5rem; font-size: 0.75rem; color: var(--text-muted);">
                <span class="status-circle status-green" style="width: 8px; height: 8px; margin-right: 4px;"></span> Healthy
                <span class="status-circle status-red" style="width: 8px; height: 8px; margin-left: 12px; margin-right: 4px;"></span> Low Stock
            </div>
            <div class="table-container">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Name</th>
                            <th style="text-align: center;">Category</th>
                            <th style="text-align: center;">Brand</th>
                            <th>Price</th>
                            <th style="text-align: center;">Stock</th>
                            <th style="text-align: center;">Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td style="font-family: monospace; color: var(--text-muted);">{{ $product->sku }}</td>
                            <td style="font-weight: 500;">{{ $product->name }}</td>
                            <td style="text-align: center;">{{ $product->category ? $product->category->name : '-' }}</td>
                            <td style="text-align: center;">{{ $product->brand ? $product->brand->name : '-' }}</td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td style="text-align: center;">
                                {{ $product->quantity }}
                            </td>
                            <td style="text-align: center;">
                                @if($product->quantity <= $product->min_stock_level)
                                    <span class="status-circle status-red" title="Low Stock"></span>
                                @else
                                    <span class="status-circle status-green" title="Healthy"></span>
                                @endif
                            </td>
                            <td style="text-align: right; border-bottom: none;">
                                <div style="display: inline-flex; gap: 0.25rem;">
                                    <button type="button" class="action-icon" style="color: #3b82f6; padding: 4px;" onclick="openEditModal({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ addslashes($product->sku) }}', {{ $product->price }}, {{ $product->min_stock_level }}, '{{ $product->category_id }}', '{{ $product->brand_id }}')" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
                                    </button>
                                    <button type="button" class="action-icon" style="color: #ef4444; padding: 4px;" onclick="openDeleteModal({{ $product->id }}, '{{ addslashes($product->name) }}')" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">No products found in the catalog.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div style="margin-top: 1rem;">
                {{ $products->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
@endsection

@section('extra_modals')
    <!-- Edit Product Modal -->
    <div class="modal-overlay" id="editProductModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Product</h2>
                <button class="modal-close" onclick="closeModal('editProductModal')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form method="POST" id="editProductForm">
                @csrf
                @method('PUT')
                <div class="form-group"><input type="text" name="name" id="edit_name" class="form-input" placeholder="Product Name" required></div>
                <div class="form-group"><input type="text" name="sku" id="edit_sku" class="form-input" placeholder="SKU" required></div>
                <div class="form-group">
                    <select name="category_id" id="edit_category_id" class="form-input" style="color: black;">
                        <option value="">No Category</option>
                        @foreach(\App\Models\Category::orderBy('name')->get() as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <select name="brand_id" id="edit_brand_id" class="form-input" style="color: black;">
                        <option value="">No Brand</option>
                        @foreach(\App\Models\Brand::orderBy('name')->get() as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group"><input type="number" step="0.01" name="price" id="edit_price" class="form-input" placeholder="Price" required></div>
                <div class="form-group" style="margin-bottom: 2rem;"><input type="number" name="min_stock_level" id="edit_min_stock" class="form-input" placeholder="Low Stock Threshold" required></div>
                <button type="submit" class="auth-button btn-dispatch">Update Product</button>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteProductModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Delete Product</h2>
                <button class="modal-close" onclick="closeModal('deleteProductModal')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <p style="color: var(--text-color); margin-bottom: 2rem;">Are you sure you want to delete <strong id="delete_product_name"></strong>? This will permanently erase the product and its entire stock movement history.</p>
            <form method="POST" id="deleteProductForm">
                @csrf
                @method('DELETE')
                <button type="submit" class="auth-button" style="background: #ef4444; color: white; border: none;">Yes, Delete Product</button>
            </form>
        </div>
    </div>
@endsection

@section('extra_scripts')
<script>
    function openEditModal(id, name, sku, price, minStock, categoryId, brandId) {
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_sku').value = sku;
        document.getElementById('edit_price').value = price;
        document.getElementById('edit_min_stock').value = minStock;
        document.getElementById('edit_category_id').value = categoryId || '';
        document.getElementById('edit_brand_id').value = brandId || '';
        
        document.getElementById('editProductForm').action = '/products/' + id;
        openModal('editProductModal');
    }

    function openDeleteModal(id, name) {
        document.getElementById('delete_product_name').innerText = name;
        document.getElementById('deleteProductForm').action = '/products/' + id;
        openModal('deleteProductModal');
    }

    // --- Live Search & Filter Logic ---
    const searchInput = document.getElementById('searchInput');
    const statusSelect = document.getElementById('statusSelect');
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusTerm = statusSelect.value;
        const rows = document.querySelectorAll('.dashboard-table tbody tr');

        rows.forEach(row => {
            // Skip the "empty" message row if it exists
            if(row.cells.length < 6) return; 

            const sku = row.cells[0].innerText.toLowerCase();
            const name = row.cells[1].innerText.toLowerCase();
            
            const isLowStock = row.cells[4].querySelector('.status-red') !== null;
            const isHealthy = row.cells[4].querySelector('.status-green') !== null;
            
            let currentStatus = '';
            if (isLowStock) currentStatus = 'low';
            if (isHealthy) currentStatus = 'healthy';

            const matchesSearch = sku.includes(searchTerm) || name.includes(searchTerm);
            const matchesStatus = statusTerm === '' || statusTerm === currentStatus;

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    statusSelect.addEventListener('change', filterTable);
</script>
@endsection
