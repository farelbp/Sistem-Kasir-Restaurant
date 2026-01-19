<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitchenTicket extends Model
{
    protected $fillable = [
        'transaction_id','ticket_no','queue_no','queue_date','status','printed_count','last_printed_at'
    ];

    protected $casts = [
        'queue_date' => 'date',
        'last_printed_at' => 'datetime',
    ];

    public function transaction() { return $this->belongsTo(Transaction::class); }
}
