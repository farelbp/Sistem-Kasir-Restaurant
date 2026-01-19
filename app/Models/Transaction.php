<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'bill_date','bill_running_no','bill_no','queue_date','queue_no','status',
        'cashier_id','table_id','order_type','customer_name','notes',
        'subtotal','discount','tax','service','grand_total',
        'sent_to_kitchen_at','paid_at'
    ];

    protected $casts = [
        'bill_date' => 'date',
        'queue_date' => 'date',
        'sent_to_kitchen_at' => 'datetime',
        'paid_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'service' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    public function cashier() { return $this->belongsTo(User::class, 'cashier_id'); }
    public function table() { return $this->belongsTo(Table::class); }
    public function items() { return $this->hasMany(TransactionItem::class); }
    public function payment() { return $this->hasOne(Payment::class); }
    public function kitchenTicket() { return $this->hasOne(KitchenTicket::class); }
}
