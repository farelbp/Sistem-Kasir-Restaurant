<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Product, StockMovement, Restock, RestockItem};

class StockController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->get();
        $movements = StockMovement::with('product')->orderByDesc('id')->limit(200)->get();
        return view('stocks.index', compact('products','movements'));
    }

    public function restock(Request $request)
    {
        $me = view()->shared('me');
        $data = $request->validate([
            'product_id' => ['required','integer','exists:products,id'],
            'qty' => ['required','integer','min:1','max:999999'],
            'unit_cost' => ['nullable','numeric','min:0'],
            'supplier_name' => ['nullable','string','max:120'],
            'note' => ['nullable','string','max:255'],
        ]);

        return DB::transaction(function () use ($data, $me) {
            $prod = Product::lockForUpdate()->findOrFail($data['product_id']);

            $qty = (int) $data['qty'];
            $unitCost = (float) ($data['unit_cost'] ?? $prod->cost);
            $subtotal = $qty * $unitCost;

            $restock = Restock::create([
                'supplier_name' => $data['supplier_name'] ?? null,
                'restock_date' => now()->toDateString(),
                'total_cost' => $subtotal,
                'created_by' => $me->id,
            ]);

            RestockItem::create([
                'restock_id' => $restock->id,
                'product_id' => $prod->id,
                'qty' => $qty,
                'unit_cost' => $unitCost,
                'subtotal' => $subtotal,
            ]);

            if ($prod->stock_enabled) {
                $prod->stock_qty = (int)$prod->stock_qty + $qty;
                $prod->save();

                StockMovement::create([
                    'product_id' => $prod->id,
                    'type' => 'in',
                    'qty' => $qty,
                    'ref_type' => 'restock',
                    'ref_id' => $restock->id,
                    'note' => $data['note'] ?? 'Restock',
                    'created_by' => $me->id,
                ]);
            }

            return back();
        });
    }

    public function adjust(Request $request)
    {
        $me = view()->shared('me');
        $data = $request->validate([
            'product_id' => ['required','integer','exists:products,id'],
            'qty' => ['required','integer','min:-999999','max:999999'],
            'note' => ['required','string','max:255'],
        ]);

        return DB::transaction(function () use ($data, $me) {
            $prod = Product::lockForUpdate()->findOrFail($data['product_id']);
            if ($prod->stock_enabled) {
                $prod->stock_qty = (int)$prod->stock_qty + (int)$data['qty'];
                $prod->save();

                StockMovement::create([
                    'product_id' => $prod->id,
                    'type' => 'adjust',
                    'qty' => (int)$data['qty'],
                    'ref_type' => 'adjust',
                    'ref_id' => null,
                    'note' => $data['note'],
                    'created_by' => $me->id,
                ]);
            }

            return back();
        });
    }
}
