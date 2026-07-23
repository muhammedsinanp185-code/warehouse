<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'user_id',
        'type', // purchase_receipt, sales_shipment, adjustment, transfer
        'quantity',
        'document_type',
        'document_id',
        'balance_after',
        'reason'
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function document()
    {
        return $this->morphTo();
    }
}
