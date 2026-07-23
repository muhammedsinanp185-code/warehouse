<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'po_number',
        'reference_number',
        'vendor_id',
        'created_by',
        'status',
        'total_amount',
        'expected_date',
        'payment_terms',
        'delivery_method',
        'vendor_notes',
        'terms_conditions',
        'received_at',
        'received_by',
        'notes',
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'expected_date' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
