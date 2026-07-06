@extends('layouts.manager')

@section('page_title', 'DASHBOARD')

@section('content')
            <div class="stat-grid">
                <!-- Stat Card 1 -->
                <a href="{{ route('manager.products.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="stat-card">
                        <div class="stat-icon icon-blue">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                        </div>
                        <div class="stat-details">
                            <h4>Total Products</h4>
                            <h2>{{ number_format($totalProducts) }}</h2>
                        </div>
                    </div>
                </a>

                <!-- Stat Card 2 -->
                <div class="stat-card">
                    <div class="stat-icon icon-green">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </div>
                    <div class="stat-details">
                        <h4>Inventory Value</h4>
                        <h2>${{ number_format($inventoryValue, 2) }}</h2>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="stat-card">
                    <div class="stat-icon icon-red">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z" /></svg>
                    </div>
                    <div class="stat-details">
                        <h4>Low Stock Items</h4>
                        <h2>{{ number_format($lowStockCount) }}</h2>
                    </div>
                </div>

                <!-- Stat Card 4 -->
                <div class="stat-card">
                    <div class="stat-icon icon-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="28" height="28"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
                    </div>
                    <div class="stat-details">
                        <h4>Today's Activity</h4>
                        <h2>{{ number_format($todayActivity) }}</h2>
                    </div>
                </div>
            </div>



            <!-- Recent Activity Table -->
            <h3 class="dashboard-section-title">Recent Stock Movements</h3>
            <div class="table-container">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Action</th>
                            <th>Quantity</th>
                            <th>User</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentMovements as $movement)
                        <tr>
                            <td>{{ $movement->product->name }}</td>
                            <td>
                                @if($movement->type == 'in')
                                    <span class="badge badge-in">Stock In</span>
                                @else
                                    <span class="badge badge-out">Stock Out</span>
                                @endif
                            </td>
                            <td>
                                {{ $movement->type == 'in' ? '+' : '-' }} {{ $movement->quantity }}
                            </td>
                            <td>{{ $movement->user->name }}</td>
                            <td>{{ $movement->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                No recent stock movements found. Start adding inventory!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
@endsection

@section('extra_modals')
    <!-- The Receive and Dispatch modals are handled in the master layout since they are used everywhere -->
@endsection