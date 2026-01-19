<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $fillable = [
        'transaction_id','product_id','product_name','unit_price','unit_cost',
        'qty','note','subtotal'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function transaction() { return $this->belongsTo(Transaction::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
