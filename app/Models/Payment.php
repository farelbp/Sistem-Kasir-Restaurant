<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'transaction_id','method','status','paid_amount','cash_received','change_amount',
        'reference_no','proof_url','received_by','verified_by','verified_at','paid_at'
    ];

    protected $casts = [
        'paid_amount' => 'decimal:2',
        'cash_received' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'verified_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function transaction() { return $this->belongsTo(Transaction::class); }
}
