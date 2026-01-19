<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['category_id','name','sku','price','cost','image_url','stock_enabled','stock_qty','is_active'];
    protected $casts = ['stock_enabled' => 'boolean','is_active' => 'boolean','price'=>'decimal:2','cost'=>'decimal:2'];

    public function category() { return $this->belongsTo(Category::class); }
}
