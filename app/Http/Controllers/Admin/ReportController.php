<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $from = $request->date('from') ? $request->date('from')->format('Y-m-d') : now()->startOfMonth()->toDateString();
        $to = $request->date('to') ? $request->date('to')->format('Y-m-d') : now()->toDateString();

        $txs = Transaction::with(['table','cashier'])
            ->where('status','paid')
            ->whereDate('paid_at','>=',$from)
            ->whereDate('paid_at','<=',$to)
            ->orderBy('paid_at','desc')
            ->get();

        $summary = [
            'omzet' => (float) $txs->sum('grand_total'),
            'count' => (int) $txs->count(),
        ];

        // Rekap per produk dari snapshot item
        $items = DB::table('transaction_items')
            ->join('transactions','transactions.id','=','transaction_items.transaction_id')
            ->where('transactions.status','paid')
            ->whereDate('transactions.paid_at','>=',$from)
            ->whereDate('transactions.paid_at','<=',$to)
            ->selectRaw('transaction_items.product_name as name, SUM(transaction_items.qty) as qty, SUM(transaction_items.subtotal) as omzet, SUM(transaction_items.unit_cost * transaction_items.qty) as cogs')
            ->groupBy('transaction_items.product_name')
            ->orderByDesc('omzet')
            ->get();

        $gross = [
            'revenue' => (float) $items->sum('omzet'),
            'cogs' => (float) $items->sum('cogs'),
            'profit' => (float) $items->sum('omzet') - (float) $items->sum('cogs'),
        ];

        return view('reports.sales', compact('from','to','txs','summary','items','gross'));
    }
}
