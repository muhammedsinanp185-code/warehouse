@extends('layouts.user')

@section('page_title', 'PRODUCT DIRECTORY')

@section('content')
            <div class="filters-bar" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; align-items: center; justify-content: flex-end; flex-wrap: wrap;">
                
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <div style="position: relative; display: flex; align-items: center; border: 1px solid var(--glass-border-20); border-radius: 8px; background-color: var(--glass-bg-03);">
                        <form action="{{ route('user.products.index') }}" method="GET" id="searchForm" style="margin: 0; display: flex; align-items: center;">
                            <input type="text" name="search" id="searchInput" placeholder="Search SKU, Name..." value="{{ request('search') }}" class="form-input" style="width: 250px; margin: 0; padding: 0.6rem 2rem; font-size: 0.9rem; background: transparent; border: none;">
                            @if(request('search'))
                                <button type="button" onclick="window.location='{{ route('user.products.index') }}'" style="position: absolute; right: 8px; background: none; border: none; color: var(--text-muted); cursor: pointer;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Name</th>
                            <th style="text-align: center;">Category</th>
                            <th style="text-align: center;">Brand</th>
                            <th style="text-align: center;">Current Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td style="font-family: monospace; color: var(--text-muted);">{{ $product->sku }}</td>
                            <td style="font-weight: 500;">
                                <a href="{{ route('user.products.show', $product->id) }}" style="color: var(--text-primary); text-decoration: none; transition: color 0.2s ease;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='var(--text-primary)'">
                                    {{ $product->name }}
                                </a>
                            </td>
                            <td style="text-align: center;">
                                @if($product->category)
                                    <span style="font-size: 0.85rem; color: var(--text-muted);">{{ $product->category->name }}</span>
                                @else
                                    <span style="color: var(--text-muted);">-</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($product->brand)
                                    <span style="font-size: 0.85rem; color: var(--text-muted);">{{ $product->brand->name }}</span>
                                @else
                                    <span style="color: var(--text-muted);">-</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <span style="font-weight: 600; color: {{ $product->quantity <= $product->min_stock_level ? '#ef4444' : '#10b981' }};">
                                    {{ number_format($product->quantity) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-muted);">No products found in the catalog.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div style="margin-top: 1.5rem;">
                {{ $products->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
@endsection
