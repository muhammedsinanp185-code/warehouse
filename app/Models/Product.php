<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 
        'sku', 
        'price', 
        'quantity', 
        'min_stock_level'
    ];
    
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
