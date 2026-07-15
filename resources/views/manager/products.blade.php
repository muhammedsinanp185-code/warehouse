@extends('layouts.manager')

@section('page_title', 'PRODUCTS CATALOG')

@section('content')
            <div class="filters-bar" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; align-items: center; justify-content: flex-end; flex-wrap: wrap;">
                
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <div style="position: relative; display: flex; align-items: center; border: 1px solid var(--glass-border-20); border-radius: 8px; background-color: var(--glass-bg-03);">
                        <input type="text" id="searchInput" placeholder="Search SKU, Name, Category..." class="form-input" style="width: 250px; margin: 0; padding: 0.6rem 2rem; font-size: 0.9rem; background: transparent; border: none;">
                        <button type="button" onclick="clearSearch()" id="clearSearchBtn" style="position: absolute; right: 8px; background: none; border: none; color: var(--text-muted); cursor: pointer; display: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <div id="activeFilterAlert" style="display: none; background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-color);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18" height="18" style="color: #3b82f6;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" /></svg>
                    <span>Showing filtered results for: <strong id="activeFilterText"></strong></span>
                </div>
                <button type="button" onclick="clearSearch()" style="background: #3b82f6; color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 4px; cursor: pointer; font-size: 0.8rem; display: flex; align-items: center; gap: 0.3rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" /></svg>
                    Back to All Products
                </button>
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
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td style="font-family: monospace; color: var(--text-muted);">{{ $product->sku }}</td>
                            <td style="font-weight: 500;">
                                <a href="{{ route('manager.products.show', $product->id) }}" style="color: var(--text-primary); text-decoration: none; transition: color 0.2s ease;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='var(--text-primary)'">
                                    {{ $product->name }}
                                </a>
                            </td>
                            <td style="text-align: center;">
                                @if($product->category)
                                    <a href="javascript:void(0)" onclick="setSearch('{{ $product->category->name }}')" style="color: #3b82f6; text-decoration: none; font-size: 0.85rem;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">{{ $product->category->name }}</a>
                                @else
                                    <span style="color: var(--text-muted);">-</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($product->brand)
                                    <a href="javascript:void(0)" onclick="setSearch('{{ $product->brand->name }}')" style="color: #3b82f6; text-decoration: none; font-size: 0.85rem;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">{{ $product->brand->name }}</a>
                                @else
                                    <span style="color: var(--text-muted);">-</span>
                                @endif
                            </td>
                            <td>₹{{ number_format($product->price, 2) }}</td>
                            <td style="text-align: right; border-bottom: none;">
                                <div style="display: inline-flex; gap: 0.25rem;">
                                    <button type="button" class="action-icon" style="color: #3b82f6; padding: 4px;" onclick="openEditModal({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ addslashes($product->sku) }}', {{ $product->price }}, {{ $product->min_stock_level }}, '{{ $product->category ? addslashes($product->category->name) : '' }}', '{{ $product->brand ? addslashes($product->brand->name) : '' }}')" title="Edit">
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
            <div style="margin-top: 1rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="font-size: 0.85rem; color: var(--text-muted);">Rows per page:</span>
                    <select id="perPageSelect" class="form-input" style="margin: 0; padding: 0.3rem 1.5rem 0.3rem 0.5rem; width: auto; background-color: var(--glass-bg-03); border: 1px solid var(--glass-border-20); border-radius: 6px; color: var(--text-color); font-size: 0.85rem;">
                        <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        <option value="custom" {{ request('per_page') && !in_array(request('per_page'), [15,25,50,100]) ? 'selected' : '' }}>Custom...</option>
                    </select>
                    <div id="customPerPageContainer" style="display: {{ request('per_page') && !in_array(request('per_page'), [15,25,50,100]) ? 'flex' : 'none' }}; align-items: center; gap: 0.5rem;">
                        <input type="number" id="customPerPageInput" class="form-input" style="margin: 0; padding: 0.3rem 0.5rem; width: 70px; font-size: 0.85rem; background-color: var(--glass-bg-03); border: 1px solid var(--glass-border-20); border-radius: 6px;" min="1" value="{{ request('per_page') && !in_array(request('per_page'), [15,25,50,100]) ? request('per_page') : '' }}" placeholder="Qty">
                        <button type="button" id="customPerPageBtn" style="background: #3b82f6; color: white; border: none; padding: 0.3rem 0.6rem; border-radius: 4px; cursor: pointer; font-size: 0.8rem;">Go</button>
                    </div>
                </div>
                <div>
                    {{ $products->appends(request()->query())->links('vendor.pagination.custom') }}
                </div>
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
                <div style="margin-bottom: 1.5rem;">
                    <label style="font-size: 0.75rem; color: var(--text-muted); display: block; text-transform: uppercase; letter-spacing: 0.5px;">Product Name</label>
                    <div class="form-group" style="margin-bottom: 0;"><input type="text" name="name" id="edit_name" class="form-input" placeholder="Product Name" required></div>
                </div>
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="font-size: 0.75rem; color: var(--text-muted); display: block; text-transform: uppercase; letter-spacing: 0.5px;">SKU (Stock Keeping Unit)</label>
                    <div class="form-group" style="margin-bottom: 0;"><input type="text" name="sku" id="edit_sku" class="form-input" placeholder="SKU" required></div>
                </div>
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="font-size: 0.75rem; color: var(--text-muted); display: block; text-transform: uppercase; letter-spacing: 0.5px;">Category (Optional)</label>
                    <div class="form-group" style="margin-bottom: 0;">
                        <input type="text" name="category_name" id="edit_category_name" class="form-input" placeholder="Category" list="edit_category_list">
                        <datalist id="edit_category_list">
                            @foreach(\App\Models\Category::orderBy('name')->get() as $cat)
                                <option value="{{ $cat->name }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="font-size: 0.75rem; color: var(--text-muted); display: block; text-transform: uppercase; letter-spacing: 0.5px;">Brand (Optional)</label>
                    <div class="form-group" style="margin-bottom: 0;">
                        <input type="text" name="brand_name" id="edit_brand_name" class="form-input" placeholder="Brand" list="edit_brand_list">
                        <datalist id="edit_brand_list">
                            @foreach(\App\Models\Brand::orderBy('name')->get() as $brand)
                                <option value="{{ $brand->name }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="font-size: 0.75rem; color: var(--text-muted); display: block; text-transform: uppercase; letter-spacing: 0.5px;">Price (₹)</label>
                    <div class="form-group" style="margin-bottom: 0;"><input type="number" step="0.01" name="price" id="edit_price" class="form-input" placeholder="Price (₹)" required></div>
                </div>
                
                <div style="margin-bottom: 2rem;">
                    <label style="font-size: 0.75rem; color: var(--text-muted); display: block; text-transform: uppercase; letter-spacing: 0.5px;">Low Stock Alert Threshold</label>
                    <div class="form-group" style="margin-bottom: 0;"><input type="number" name="min_stock_level" id="edit_min_stock" class="form-input" placeholder="Low Stock Threshold" required></div>
                </div>
                
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
    function openEditModal(id, name, sku, price, minStock, categoryName, brandName) {
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_sku').value = sku;
        document.getElementById('edit_price').value = price;
        document.getElementById('edit_min_stock').value = minStock;
        
        document.getElementById('edit_category_name').value = categoryName || '';
        document.getElementById('edit_brand_name').value = brandName || '';
        
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
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    const activeFilterAlert = document.getElementById('activeFilterAlert');
    const activeFilterText = document.getElementById('activeFilterText');
    
    function setSearch(term) {
        searchInput.value = term;
        filterTable();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    function clearSearch() {
        searchInput.value = '';
        filterTable();
    }
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const rows = document.querySelectorAll('.dashboard-table tbody tr');
        
        if (searchTerm.length > 0) {
            clearSearchBtn.style.display = 'block';
            activeFilterAlert.style.display = 'flex';
            let filterDesc = [];
            if (searchTerm) filterDesc.push('"' + searchInput.value + '"');
            activeFilterText.innerText = filterDesc.join(' + ');
        } else {
            clearSearchBtn.style.display = 'none';
            activeFilterAlert.style.display = 'none';
        }

        rows.forEach(row => {
            // Skip the "empty" message row if it exists
            if(row.cells.length < 5) return; 

            const sku = row.cells[0].innerText.toLowerCase();
            const name = row.cells[1].innerText.toLowerCase();
            const category = row.cells[2].innerText.toLowerCase();
            const brand = row.cells[3].innerText.toLowerCase();
            
            const matchesSearch = sku.includes(searchTerm) || name.includes(searchTerm) || category.includes(searchTerm) || brand.includes(searchTerm);

            if (matchesSearch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    
    const perPageSelect = document.getElementById('perPageSelect');
    const customPerPageContainer = document.getElementById('customPerPageContainer');
    const customPerPageInput = document.getElementById('customPerPageInput');
    const customPerPageBtn = document.getElementById('customPerPageBtn');

    perPageSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customPerPageContainer.style.display = 'flex';
            customPerPageInput.focus();
        } else {
            customPerPageContainer.style.display = 'none';
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', this.value);
            window.location.href = url.href;
        }
    });

    function applyCustomPerPage() {
        if (customPerPageInput.value > 0) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', customPerPageInput.value);
            window.location.href = url.href;
        }
    }

    customPerPageBtn.addEventListener('click', applyCustomPerPage);
    customPerPageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            applyCustomPerPage();
        }
    });
</script>
@endsection
