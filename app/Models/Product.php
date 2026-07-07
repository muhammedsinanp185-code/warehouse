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
        'min_stock_level',
        'category_id',
        'brand_id'
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
