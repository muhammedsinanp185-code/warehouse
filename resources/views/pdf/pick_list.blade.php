<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pick List {{ $purchaseOrder->po_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #000; margin: 0; padding: 10px; }
        .header { border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 32px; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 5px 0 0 0; font-size: 16px; font-weight: bold; }
        .meta { margin-bottom: 30px; font-size: 14px; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; border: 2px solid #000; }
        .items-table th { border: 2px solid #000; padding: 15px; font-size: 14px; text-transform: uppercase; background: #eee; }
        .items-table td { border: 1px solid #000; padding: 15px; font-size: 16px; }
        .checkbox { width: 30px; height: 30px; border: 2px solid #000; display: inline-block; }
    </style>
</head>
<body>
    <div class="header">
        <h1>WAREHOUSE PICK LIST</h1>
        <p>PO REF: {{ $purchaseOrder->po_number }}</p>
    </div>

    <div class="meta">
        <strong>Date:</strong> {{ now()->format('M d, Y') }} <br>
        <strong>Status:</strong> PENDING RECEIPT <br>
        <strong>Picker Name:</strong> _________________________
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50px; text-align: center;">Done</th>
                <th>Product / SKU</th>
                <th style="text-align: center; width: 100px;">Expected Qty</th>
                <th style="text-align: center; width: 100px;">Received Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchaseOrder->items as $item)
            <tr>
                <td style="text-align: center;"><div class="checkbox"></div></td>
                <td>
                    <strong>{{ $item->product->name ?? 'Unknown Product' }}</strong><br>
                    <span style="font-family: monospace; font-size: 12px;">{{ $item->product->sku ?? 'N/A' }}</span>
                </td>
                <td style="text-align: center; font-weight: bold; font-size: 20px;">{{ number_format($item->quantity) }}</td>
                <td style="text-align: center;"></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 50px; border-top: 1px dashed #000; padding-top: 20px;">
        <strong>Manager Signature:</strong> _________________________ &nbsp;&nbsp;&nbsp;&nbsp; <strong>Date:</strong> ___ / ___ / ______
    </div>
</body>
</html>
