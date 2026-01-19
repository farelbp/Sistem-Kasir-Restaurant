<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\{KitchenTicket, Transaction};

class PrintController extends Controller
{
    public function kot(KitchenTicket $ticket)
    {
        $ticket->load(['transaction.items','transaction.table','transaction.cashier']);
        $ticket->printed_count = (int)$ticket->printed_count + 1;
        $ticket->last_printed_at = now();
        $ticket->save();

        return view('print.kot', compact('ticket'));
    }

    public function receipt(Transaction $transaction)
    {
        $transaction->load(['items','table','cashier','payment']);
        return view('print.receipt', ['tx' => $transaction]);
    }
}
