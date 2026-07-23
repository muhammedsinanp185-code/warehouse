<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_order_id',
        'status',
        'tracking_number'
    ];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }
}
