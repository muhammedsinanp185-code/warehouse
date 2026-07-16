@extends('layouts.user')

@section('page_title', 'MY ACTIVITY LOGS')

@section('content')
            <div class="table-container">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="text-align: center;vertical-align: bottom;">Date / Time</th>
                            <th rowspan="2" style="text-align: center;vertical-align: bottom;">Product</th>
                            <th rowspan="2" style="text-align: center; vertical-align: bottom;">Source / Destination</th>
                            <th colspan="3" style="text-align: center; border-bottom: 2px solid var(--glass-border-10); padding-bottom: 0.5rem;">Ledger Details</th>
                        </tr>
                        <tr>
                            <th style="text-align: center; font-size: 0.8rem; color: #10b981; padding-top: 0.5rem;">Received</th>
                            <th style="text-align: center; font-size: 0.8rem; color: #ef4444; padding-top: 0.5rem;">Dispatched</th>
                            <th style="text-align: center; font-size: 0.8rem; color: var(--text-color); padding-top: 0.5rem;">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                        <tr>
                            <td style="color: var(--text-muted); font-size: 0.85rem;">{{ $movement->created_at->format('M d, Y h:i A') }}</td>
                            <td style="font-weight: 500;">
                                <a href="{{ route('user.products.show', $movement->product->id) }}" style="color: var(--text-primary); text-decoration: none; transition: color 0.2s ease;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='var(--text-primary)'">
                                    {{ $movement->product->name }}
                                </a>
                                <div style="font-size: 0.75rem; color: var(--text-muted); font-family: monospace; margin-top: 2px;">{{ $movement->product->sku }}</div>
                            </td>
                            <td style="text-align: center; color: var(--text-muted); font-size: 0.9rem;">
                                {{ $movement->reference_party ?: '-' }}
                            </td>
                            <td style="text-align: center; font-weight: 600; color: #10b981;">
                                {{ $movement->type == 'in' ? '+' . $movement->quantity : '-' }}
                            </td>
                            <td style="text-align: center; font-weight: 600; color: #ef4444;">
                                {{ $movement->type == 'out' ? '-' . $movement->quantity : '-' }}
                            </td>
                            <td style="text-align: center; font-weight: 700; color: var(--text-color);">
                                {{ $movement->balance_after }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">You have not recorded any stock movements yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1rem;">
                {{ $movements->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
@endsection
