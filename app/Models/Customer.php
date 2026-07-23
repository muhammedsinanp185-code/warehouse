<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'billing_address', 'shipping_address'];

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }
}
