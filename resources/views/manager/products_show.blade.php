@extends('layouts.manager')

@section('page_title', 'PRODUCT PROFILE')
@section('back_link', str_contains(url()->previous(), '/history') ? route('manager.products.index') : url()->previous())

@section('content')
<div style="max-width: 1100px; margin: 0 auto; width: 100%;">
    
    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; background: var(--glass-bg-05); border: 1px solid var(--glass-border-10); border-radius: 16px; padding: 2rem; backdrop-filter: blur(10px);">
        <div>
            <h1 style="margin: 0; font-size: 2.2rem; color: var(--text-primary); font-weight: 700;">{{ $product->name }}</h1>
            <div style="margin: 0.5rem 0 0 0; color: var(--text-muted); font-size: 1rem; display: flex; align-items: center; gap: 0.5rem; font-family: monospace;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                </svg>
                SKU: {{ $product->sku }}
            </div>
        </div>
        
        <div style="text-align: right; background: rgba(0,0,0,0.2); padding: 1rem 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
            <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">Current Stock</div>
            <div style="font-size: 2.5rem; font-weight: 700; color: {{ $product->quantity <= $product->min_stock_level ? '#ef4444' : '#10b981' }}; line-height: 1;">
                {{ number_format($product->quantity) }}
            </div>
            @if($product->quantity <= $product->min_stock_level)
                <div style="color: #ef4444; font-size: 0.75rem; margin-top: 0.5rem; font-weight: 600; background: rgba(239,68,68,0.1); padding: 0.2rem 0.5rem; border-radius: 4px; display: inline-block;">LOW STOCK ALERT</div>
            @endif
            <div style="margin-top: 1rem;">
                <button type="button" onclick="document.getElementById('adjustModal').style.display='block'" style="background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.2); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; transition: all 0.2s;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                    </svg>
                    Adjust Stock
                </button>
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        
        <!-- Unit Price -->
        <div style="background: var(--glass-bg-03); border: 1px solid var(--glass-border-10); border-radius: 12px; padding: 1.5rem; display: flex; flex-direction: column; justify-content: center;">
            <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Unit Price</div>
            <div style="font-size: 1.8rem; font-weight: 600; color: var(--text-primary);">
                ₹{{ number_format($product->price, 2) }}
            </div>
        </div>

        <!-- Total Value -->
        <div style="background: var(--glass-bg-03); border: 1px solid var(--glass-border-10); border-radius: 12px; padding: 1.5rem; display: flex; flex-direction: column; justify-content: center;">
            <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Total Value</div>
            <div style="font-size: 1.8rem; font-weight: 600; color: #3b82f6;">
                ₹{{ number_format($product->price * $product->quantity, 2) }}
            </div>
        </div>

        <!-- Category -->
        <div style="background: var(--glass-bg-03); border: 1px solid var(--glass-border-10); border-radius: 12px; padding: 1.5rem; display: flex; flex-direction: column; justify-content: center;">
            <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Category</div>
            <div style="font-size: 1.25rem; font-weight: 500; color: var(--text-primary);">
                {{ $product->category ? $product->category->name : 'Uncategorized' }}
            </div>
        </div>

        <!-- Brand -->
        <div style="background: var(--glass-bg-03); border: 1px solid var(--glass-border-10); border-radius: 12px; padding: 1.5rem; display: flex; flex-direction: column; justify-content: center;">
            <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Brand</div>
            <div style="font-size: 1.25rem; font-weight: 500; color: var(--text-primary);">
                {{ $product->brand ? $product->brand->name : 'No Brand' }}
            </div>
        </div>

    </div>

    <!-- Movement History -->
    <div style="background: var(--glass-bg-05); border: 1px solid var(--glass-border-10); border-radius: 16px; padding: 2rem; backdrop-filter: blur(10px);">
        <h3 style="font-size: 1.2rem; color: var(--text-primary); margin-top: 0; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px; color: #3b82f6;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Movement History
        </h3>
        
        @if($movements->count() > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--glass-border-20);">
                        <th style="padding: 1rem 0; font-size: 0.85rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Date / Time</th>
                        <th style="padding: 1rem 1rem; font-size: 0.85rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Type</th>
                        <th style="padding: 1rem 1rem; font-size: 0.85rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; text-align: right;">Quantity</th>
                        <th style="padding: 1rem 0 1rem 1rem; font-size: 0.85rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; text-align: right;">Authorized By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movements as $movement)
                    <tr style="border-bottom: 1px solid var(--glass-border-10);">
                        <td style="padding: 1rem 0;">
                            <div style="font-weight: 500; color: var(--text-primary);">{{ $movement->created_at->format('M d, Y') }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">{{ $movement->created_at->format('h:i A') }}</div>
                        </td>
                        <td style="padding: 1rem 1rem;">
                            @if($movement->type == 'in')
                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; color: #10b981; font-weight: 600; background: rgba(16,185,129,0.1); padding: 0.3rem 0.6rem; border-radius: 6px; font-size: 0.8rem; letter-spacing: 0.5px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    RECEIVED
                                </span>
                            @else
                                <span style="display: inline-flex; align-items: center; gap: 0.35rem; color: #ef4444; font-weight: 600; background: rgba(239,68,68,0.1); padding: 0.3rem 0.6rem; border-radius: 6px; font-size: 0.8rem; letter-spacing: 0.5px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" /></svg>
                                    DISPATCHED
                                </span>
                            @endif
                            
                            @if($movement->reason)
                                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem; font-style: italic;">
                                    Reason: {{ $movement->reason }}
                                </div>
                            @elseif($movement->reference_party)
                                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem; font-style: italic;">
                                    Ref: {{ $movement->reference_party }}
                                </div>
                            @endif
                        </td>
                        <td style="padding: 1rem 1rem; text-align: right; font-weight: 700; font-family: monospace; font-size: 1.15rem; color: {{ $movement->type == 'in' ? '#10b981' : '#ef4444' }};">
                            {{ $movement->type == 'in' ? '+' : '-' }}{{ number_format($movement->quantity) }}
                        </td>
                        <td style="padding: 1rem 0 1rem 1rem; text-align: right; color: var(--text-primary); font-weight: 500;">
                            {{ $movement->user ? $movement->user->name : 'System' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="{{ route('manager.products.history', $product->id) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #3b82f6; font-weight: 500; text-decoration: none; padding: 0.5rem 1rem; border: 1px solid rgba(59,130,246,0.3); border-radius: 8px; transition: all 0.2s ease;" onmouseover="this.style.background='rgba(59,130,246,0.1)'" onmouseout="this.style.background='transparent'">
                See Full History
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
        @else
        <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted); background: var(--glass-bg-03); border-radius: 8px; border: 1px dashed var(--glass-border-20);">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 48px; height: 48px; margin: 0 auto 1rem auto; opacity: 0.5;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <p style="margin: 0;">No movement history found for this product.</p>
        </div>
        @endif
    </div>

</div>

<!-- Adjust Stock Modal -->
<div id="adjustModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 50; align-items: center; justify-content: center;">
    <div style="background: var(--bg-color); border: 1px solid var(--glass-border-10); border-radius: 16px; padding: 2rem; width: 100%; max-width: 500px; margin: 10vh auto; position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0; font-size: 1.25rem; color: var(--text-primary); font-weight: 600;">Adjust Stock: {{ $product->name }}</h3>
            <button type="button" onclick="document.getElementById('adjustModal').style.display='none'" style="background: none; border: none; color: var(--text-muted); cursor: pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 24px; height: 24px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <form action="{{ route('inventory.adjust') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">Adjustment Type</label>
                <select name="type" required class="form-input" style="width: 100%; padding: 0.75rem; background: var(--glass-bg-05); border: 1px solid var(--glass-border-20); border-radius: 8px; color: var(--text-primary);">
                    <option value="out">Remove Stock (Loss/Damage/Correction)</option>
                    <option value="in">Add Stock (Found/Correction)</option>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">Quantity to Adjust</label>
                <input type="number" name="quantity" required min="1" class="form-input" style="width: 100%; padding: 0.75rem; background: var(--glass-bg-05); border: 1px solid var(--glass-border-20); border-radius: 8px; color: var(--text-primary);">
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">Reason</label>
                <input type="text" name="reason" required placeholder="e.g. Damaged in transit, Cycle count correction..." class="form-input" style="width: 100%; padding: 0.75rem; background: var(--glass-bg-05); border: 1px solid var(--glass-border-20); border-radius: 8px; color: var(--text-primary);">
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="document.getElementById('adjustModal').style.display='none'" class="btn" style="background: transparent; border: 1px solid var(--glass-border-20); color: var(--text-primary);">Cancel</button>
                <button type="submit" class="btn" style="background: #3b82f6; border: none; color: white;">Save Adjustment</button>
            </div>
        </form>
    </div>
</div>
@endsection
