<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Category, Product, Table, Transaction, KitchenTicket};

class PosController extends Controller
{
    public function index(Request $request)
    {
        $me = view()->shared('me');

        $tableId = $request->integer('table_id');
        $tables = Table::where('is_active', true)->orderBy('code')->get();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        $productsQuery = Product::where('is_active', true)->with('category')->orderBy('name');
        if ($request->filled('category_id')) {
            $productsQuery->where('category_id', $request->integer('category_id'));
        }
        if ($request->filled('q')) {
            $q = trim($request->string('q'));
            $productsQuery->where('name', 'like', "%{$q}%");
        }
        $products = $productsQuery->limit(120)->get();

        $tx = null;
        if ($tableId) {
            $tx = Transaction::with('items')
                ->where('cashier_id', $me->id)
                ->where('table_id', $tableId)
                ->where('status', 'draft')
                ->latest('id')
                ->first();

            if (!$tx) {
                $tx = Transaction::create([
                    'cashier_id' => $me->id,
                    'table_id' => $tableId,
                    'status' => 'draft',
                    'subtotal' => 0,
                    'discount' => 0,
                    'tax' => 0,
                    'service' => 0,
                    'grand_total' => 0,
                ]);
                $tx->load('items');
            }
        }

        return view('kasir.pos', compact('tables', 'tableId', 'categories', 'products', 'tx'));
    }

    public function kitchenToday(Request $request)
    {
        $today = now()->toDateString();

        $tickets = KitchenTicket::with(['transaction.items', 'transaction.table', 'transaction.cashier', 'transaction.payment'])
            ->where('queue_date', $today)
            ->orderByDesc('queue_no')
            ->get();

        return view('kasir.kitchen_today', compact('tickets', 'today'));
    }
}
