@extends('layouts.user')

@section('page_title', 'WORKSPACE')

@section('content')
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        
        <!-- Shift Tracking -->
        @if($currentShift)
            <div class="stat-card" style="display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; border: 1px solid rgba(59, 130, 246, 0.4); background: rgba(59, 130, 246, 0.05);">
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; width: 64px; height: 64px; margin-bottom: 1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="32" height="32"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                </div>
                <h3 style="color: var(--text-color); font-size: 1.2rem; margin-bottom: 0.5rem;">Shift in Progress</h3>
                <p style="color: #3b82f6; font-size: 0.9rem; margin-bottom: 1rem; font-weight: bold;">Started {{ $currentShift->started_at->format('g:i A') }}</p>
                <form action="{{ route('user.shift.end') }}" method="POST">
                    @csrf
                    <button type="submit" class="auth-button" style="padding: 0.5rem 1.5rem; background: var(--glass-bg); color: var(--text-color); border: 1px solid var(--border-color);">End Shift</button>
                </form>
            </div>
        @else
            <div class="stat-card" style="display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; border: 1px solid var(--glass-bg-05);">
                <div class="stat-icon" style="background: var(--glass-bg); color: var(--text-muted); width: 64px; height: 64px; margin-bottom: 1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="32" height="32"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                </div>
                <h3 style="color: var(--text-color); font-size: 1.2rem; margin-bottom: 0.5rem;">Not Clocked In</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">Start your shift to begin</p>
                <form action="{{ route('user.shift.start') }}" method="POST">
                    @csrf
                    <button type="submit" class="auth-button" style="padding: 0.5rem 1.5rem; background: #3b82f6; color: white; border: none;">Start Shift</button>
                </form>
            </div>
        @endif

        <!-- Quick Action: Receive -->
        <div class="stat-card" style="display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; cursor: pointer; transition: transform 0.2s; border: 1px solid rgba(16, 185, 129, 0.2);" onclick="openModal('receiveStockModal')" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981; width: 64px; height: 64px; margin-bottom: 1rem;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="32" height="32"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
            </div>
            <h3 style="color: var(--text-color); font-size: 1.2rem; margin-bottom: 0.5rem;">Receive Stock</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Log incoming inventory</p>
        </div>

        <!-- Quick Action: Dispatch -->
        <div class="stat-card" style="display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; cursor: pointer; transition: transform 0.2s; border: 1px solid rgba(239, 68, 68, 0.2);" onclick="openModal('dispatchStockModal')" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
            <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; width: 64px; height: 64px; margin-bottom: 1rem;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="32" height="32"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" /></svg>
            </div>
            <h3 style="color: var(--text-color); font-size: 1.2rem; margin-bottom: 0.5rem;">Dispatch Stock</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Log outgoing inventory</p>
        </div>

        <!-- Low Stock Alerts -->
        <div class="stat-card" style="display: block; grid-column: 1 / -1;">
            <h2 style="color: var(--text-color); margin-bottom: 1rem; font-size: 1.1rem; display: flex; align-items: center;">
                <span style="display: inline-block; width: 10px; height: 10px; background: #ef4444; border-radius: 50%; margin-right: 0.5rem; box-shadow: 0 0 8px #ef4444;"></span>
                Low Stock Alerts
            </h2>
            <div class="table-container" style="box-shadow: none; border: 1px solid var(--glass-bg-05); margin-bottom: 0; max-height: 250px; overflow-y: auto;">
                <table class="dashboard-table">
                    <tbody>
                        @forelse($lowStockProducts as $product)
                        <tr>
                            <td style="font-weight: 500;">{{ $product->name }} <br><span style="font-size: 0.8rem; color: var(--text-muted); font-weight: normal;">{{ $product->sku }}</span></td>
                            <td style="text-align: right; color: #ef4444; font-weight: bold;">{{ $product->quantity }} left</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" style="text-align: center; color: var(--text-muted); padding: 2rem;">No low stock alerts! All items are adequately stocked.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="stat-card" style="display: block;">
        <h2 style="color: var(--text-color); margin-bottom: 1rem; font-size: 1.1rem;">My Recent Activity</h2>
        <div class="table-container" style="box-shadow: none; border: 1px solid var(--glass-bg-05); margin-bottom: 0;">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="vertical-align: bottom;">Time</th>
                        <th rowspan="2" style="vertical-align: bottom;">Product</th>
                        <th rowspan="2" style="text-align: center; vertical-align: bottom;">Source / Destination</th>
                        <th colspan="3" style="text-align: center; border-bottom: 2px solid var(--glass-border-10); padding-bottom: 0.5rem;">Ledger Details</th>
                        <th rowspan="2" style="text-align: right; vertical-align: bottom;">Options</th>
                    </tr>
                    <tr>
                        <th style="text-align: center; font-size: 0.8rem; color: #10b981; padding-top: 0.5rem;">Received</th>
                        <th style="text-align: center; font-size: 0.8rem; color: #ef4444; padding-top: 0.5rem;">Dispatched</th>
                        <th style="text-align: center; font-size: 0.8rem; color: var(--text-color); padding-top: 0.5rem;">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentMovements as $movement)
                    <tr>
                        <td style="color: var(--text-muted);">{{ $movement->created_at->diffForHumans() }}</td>
                        <td style="font-weight: 500;">{{ $movement->product->name }}</td>
                        <td style="text-align: center; color: var(--text-muted); font-size: 0.85rem;">{{ $movement->reference_party ?: '-' }}</td>
                        <td style="text-align: center; font-weight: 600; color: #10b981;">
                            {{ $movement->type == 'in' ? '+' . $movement->quantity : '-' }}
                        </td>
                        <td style="text-align: center; font-weight: 600; color: #ef4444;">
                            {{ $movement->type == 'out' ? '-' . $movement->quantity : '-' }}
                        </td>
                        <td style="text-align: center; font-weight: 700; color: var(--text-color);">
                            {{ $movement->balance_after }}
                        </td>
                        <td style="text-align: right;">
                            <form action="{{ route('user.inventory.destroy', $movement->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to undo this? It will reverse the quantity on the product.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; text-decoration: underline; font-size: 0.9rem;">Undo</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">You have no recent activity.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
