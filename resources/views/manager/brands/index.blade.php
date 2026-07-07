@extends('layouts.manager')

@section('page_title', 'BRANDS')

@section('content')
            <div class="filters-bar" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; align-items: center; justify-content: flex-end; flex-wrap: wrap;">
                <button onclick="openModal('addBrandModal')" class="form-btn" style="width: auto; padding: 0.6rem 1.2rem; font-size: 0.9rem; border-radius: 6px;">+ Add Brand</button>
            </div>

            <div class="table-container">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Brand Name</th>
                            <th style="text-align: center;">Total Products</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($brands as $brand)
                        <tr>
                            <td style="font-weight: 500;">{{ $brand->name }}</td>
                            <td style="text-align: center;">{{ $brand->products_count }}</td>
                            <td style="text-align: right; border-bottom: none;">
                                <div style="display: inline-flex; gap: 0.25rem;">
                                    <button type="button" class="action-icon" style="color: #3b82f6; padding: 4px;" onclick="openEditBrandModal({{ $brand->id }}, '{{ addslashes($brand->name) }}')" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
                                    </button>
                                    <form action="{{ route('brands.destroy', $brand) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this brand?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-icon" style="color: #ef4444; padding: 4px;" title="Delete" style="background:none; border:none; padding:0; cursor:pointer;">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 2rem; color: var(--text-muted);">No brands found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Add Brand Modal -->
            <div class="modal-overlay" id="addBrandModal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Add Brand</h2>
                        <button type="button" class="modal-close" onclick="closeModal('addBrandModal')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <form action="{{ route('brands.store') }}" method="POST">
                        @csrf
                        <div class="form-group"><input type="text" name="name" class="form-input" placeholder="Brand Name" required></div>
                        <button type="submit" class="form-btn" style="margin-top: 1rem;">Save Brand</button>
                    </form>
                </div>
            </div>

            <!-- Edit Brand Modal -->
            <div class="modal-overlay" id="editBrandModal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Edit Brand</h2>
                        <button type="button" class="modal-close" onclick="closeModal('editBrandModal')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <form method="POST" id="editBrandForm">
                        @csrf
                        @method('PUT')
                        <div class="form-group"><input type="text" name="name" id="edit_brand_name" class="form-input" placeholder="Brand Name" required></div>
                        <button type="submit" class="form-btn" style="margin-top: 1rem;">Update Brand</button>
                    </form>
                </div>
            </div>

            <script>
                function openModal(id) {
                    document.getElementById(id).classList.add('active');
                }
                function closeModal(id) {
                    document.getElementById(id).classList.remove('active');
                }
                function openEditBrandModal(id, name) {
                    const form = document.getElementById('editBrandForm');
                    form.action = `/brands/${id}`;
                    document.getElementById('edit_brand_name').value = name;
                    openModal('editBrandModal');
                }
            </script>
@endsection
