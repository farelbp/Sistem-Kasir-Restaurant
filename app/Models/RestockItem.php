<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestockItem extends Model
{
    protected $fillable = ['restock_id','product_id','qty','unit_cost','subtotal'];
    protected $casts = ['unit_cost' => 'decimal:2','subtotal'=>'decimal:2'];

    public function restock() { return $this->belongsTo(Restock::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
