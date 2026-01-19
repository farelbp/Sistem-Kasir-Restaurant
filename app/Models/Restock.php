<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restock extends Model
{
    protected $fillable = ['supplier_name','restock_date','total_cost','created_by'];
    protected $casts = ['restock_date' => 'date','total_cost' => 'decimal:2'];

    public function items() { return $this->hasMany(RestockItem::class); }
}
