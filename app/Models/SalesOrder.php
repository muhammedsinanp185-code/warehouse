<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'so_number',
        'reference_number',
        'status',
        'order_date',
        'expected_shipment_date',
        'payment_terms',
        'delivery_method',
        'customer_notes',
        'terms_conditions',
        'total_amount'
    ];

    protected $casts = [
        'order_date' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}
