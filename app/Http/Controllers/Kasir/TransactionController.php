<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{
    Transaction,
    TransactionItem,
    Product,
    KitchenTicket,
    Payment,
    StockMovement,
    Restock,
    RestockItem
};

class TransactionController extends Controller
{
    private function recalc(Transaction $tx): void
    {
        $subtotal = (float) $tx->items()->sum('subtotal');
        $tx->subtotal = $subtotal;
        $tx->grand_total = $subtotal - (float)$tx->discount + (float)$tx->tax + (float)$tx->service;
        $tx->save();
    }

    private function requireDraft(Transaction $tx): void
    {
        if ($tx->status !== 'draft') {
            abort(422, 'Transaksi sudah dikirim/selesai.');
        }
    }

    /**
     * Pastikan stok cukup untuk seluruh item transaksi (anti minus).
     * Dipanggil saat pembayaran (double-check).
     */
    private function assertStockEnoughForTx(Transaction $tx): void
    {
        $tx->loadMissing('items');

        $needs = [];
        foreach ($tx->items as $it) {
            $pid = (int) $it->product_id;
            $needs[$pid] = ($needs[$pid] ?? 0) + (int) $it->qty;
        }

        foreach ($needs as $productId => $qtyNeed) {
            $prod = Product::lockForUpdate()->findOrFail($productId);
            if ($prod->stock_enabled && (int)$prod->stock_qty < $qtyNeed) {
                abort(422, "Stok tidak cukup untuk {$prod->name}. Sisa stok: {$prod->stock_qty}.");
            }
        }
    }

    /**
     * Potong stok per product (grouped) + tulis stock movement.
     */
    private function deductStockForTx(Transaction $tx, int $byUserId, string $notePrefix): void
    {
        $tx->loadMissing('items');

        $needs = [];
        foreach ($tx->items as $it) {
            $pid = (int) $it->product_id;
            $needs[$pid] = ($needs[$pid] ?? 0) + (int) $it->qty;
        }

        foreach ($needs as $productId => $qtyNeed) {
            $prod = Product::lockForUpdate()->find($productId);
            if (!$prod || !$prod->stock_enabled) continue;

            $prod->stock_qty = (int)$prod->stock_qty - (int)$qtyNeed;
            $prod->save();

            StockMovement::create([
                'product_id' => $prod->id,
                'type' => 'sale',
                'qty' => -1 * (int)$qtyNeed,
                'ref_type' => 'transaction',
                'ref_id' => $tx->id,
                'note' => $notePrefix . ' ' . ($tx->bill_no ?? $tx->id),
                'created_by' => $byUserId,
            ]);
        }
    }

    public function add(Request $request)
    {
        $me = view()->shared('me');

        $data = $request->validate([
            'transaction_id' => ['required', 'integer', 'exists:transactions,id'],
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty' => ['nullable', 'integer', 'min:1'],
            'note' => ['nullable', 'string', 'max:200'],
        ]);

        $addQty = (int)($data['qty'] ?? 1);

        $result = DB::transaction(function () use ($data, $addQty, $me) {

            $tx = Transaction::lockForUpdate()->findOrFail($data['transaction_id']);

            if ((int)$tx->cashier_id !== (int)$me->id) abort(403);
            $this->requireDraft($tx);

            $p = Product::lockForUpdate()->findOrFail($data['product_id']);

            $item = TransactionItem::where('transaction_id', $tx->id)
                ->where('product_id', $p->id)
                ->lockForUpdate()
                ->first();

            $currentQty = $item ? (int)$item->qty : 0;
            $desiredQty = $currentQty + $addQty;

            // STOP (warning) jika stok tidak cukup
            if ($p->stock_enabled && $desiredQty > (int)$p->stock_qty) {
                return [
                    'ok' => false,
                    'msg' => "Stok tidak cukup untuk {$p->name}. Sisa stok: {$p->stock_qty}.",
                ];
            }

            $unitPrice = (int) $p->price;
            $unitCost  = (int) ($p->cost ?? 0);
            $hasUnitCost = \Schema::hasColumn('transaction_items', 'unit_cost');

            if ($item) {
                $item->qty = $desiredQty;
                if (!empty($data['note'])) $item->note = $data['note'];

                if (empty($item->unit_price)) $item->unit_price = $unitPrice;
                if ($hasUnitCost && empty($item->unit_cost)) $item->unit_cost = $unitCost;

                $item->subtotal = (int)$item->qty * (int)$item->unit_price;
                $item->save();
            } else {
                $payload = [
                    'transaction_id' => $tx->id,
                    'product_id' => $p->id,
                    'product_name' => $p->name,
                    'unit_price' => $unitPrice,
                    'qty' => $addQty,
                    'note' => $data['note'] ?? null,
                    'subtotal' => $addQty * $unitPrice,
                ];

                if ($hasUnitCost) $payload['unit_cost'] = $unitCost;

                TransactionItem::create($payload);
            }

            $this->recalc($tx);

            return ['ok' => true];
        });

        if (!$result['ok']) {
            return back()->with('error', $result['msg']);
        }

        return back()->with('success', 'Item ditambahkan.');
    }



    public function setQty(Request $request)
    {
        $me = view()->shared('me');
        $data = $request->validate([
            'item_id' => ['required', 'integer', 'exists:transaction_items,id'],
            'qty' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        return DB::transaction(function () use ($data, $me) {
            $item = TransactionItem::with('transaction')->lockForUpdate()->findOrFail($data['item_id']);
            $tx = Transaction::lockForUpdate()->findOrFail($item->transaction_id);

            if ((int)$tx->cashier_id !== (int)$me->id) abort(403);
            $this->requireDraft($tx);

            $prod = Product::lockForUpdate()->findOrFail($item->product_id);

            $newQty = (int)$data['qty'];
            if ($prod->stock_enabled && $newQty > (int)$prod->stock_qty) {
                return back()->withErrors([
                    'qty' => "Stok tidak cukup untuk {$prod->name}. Sisa stok: {$prod->stock_qty}."
                ]);
            }

            $item->qty = $newQty;

            // FIX: pakai unit_price (bukan unit_price null / bukan price)
            $item->subtotal = (int)$item->qty * (int)$item->unit_price;
            $item->save();

            $this->recalc($tx);
            return back();
        });
    }


    public function remove(Request $request)
    {
        $me = view()->shared('me');
        $data = $request->validate([
            'item_id' => ['required', 'integer', 'exists:transaction_items,id'],
        ]);

        return DB::transaction(function () use ($data, $me) {
            $item = TransactionItem::with('transaction')->lockForUpdate()->findOrFail($data['item_id']);
            $tx = Transaction::lockForUpdate()->findOrFail($item->transaction_id);

            if ((int)$tx->cashier_id !== (int)$me->id) abort(403);
            $this->requireDraft($tx);

            $item->delete();
            $this->recalc($tx);
            return back();
        });
    }

    public function setNote(Request $request)
    {
        $me = view()->shared('me');
        $data = $request->validate([
            'item_id' => ['required', 'integer', 'exists:transaction_items,id'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        return DB::transaction(function () use ($data, $me) {
            $item = TransactionItem::with('transaction')->lockForUpdate()->findOrFail($data['item_id']);
            $tx = Transaction::lockForUpdate()->findOrFail($item->transaction_id);

            if ((int)$tx->cashier_id !== (int)$me->id) abort(403);
            $this->requireDraft($tx);

            $item->note = $data['note'];
            $item->save();

            return back();
        });
    }

    public function sendToKitchen(Request $request)
    {
        $me = view()->shared('me');
        $data = $request->validate([
            'transaction_id' => ['required', 'integer', 'exists:transactions,id'],
        ]);

        return DB::transaction(function () use ($data, $me) {
            $tx = Transaction::with('items')
                ->lockForUpdate()
                ->where('id', $data['transaction_id'])
                ->where('cashier_id', $me->id)
                ->firstOrFail();

            $this->requireDraft($tx);

            if ($tx->items()->count() === 0) {
                return back()->withErrors(['cart' => 'Keranjang masih kosong.']);
            }

            $today = now()->toDateString();

            // queue running number per day
            $maxQueue = (int) Transaction::where('queue_date', $today)->lockForUpdate()->max('queue_no');
            $queueNo = $maxQueue + 1;

            // bill running number per day
            $maxBill = (int) Transaction::where('bill_date', $today)->lockForUpdate()->max('bill_running_no');
            $billRun = $maxBill + 1;
            $billNo = 'ASTA-' . now()->format('Ymd') . '-' . str_pad((string)$billRun, 4, '0', STR_PAD_LEFT);

            $tx->queue_date = $today;
            $tx->queue_no = $queueNo;
            $tx->bill_date = $today;
            $tx->bill_running_no = $billRun;
            $tx->bill_no = $billNo;
            $tx->status = 'sent_to_kitchen';
            $tx->sent_to_kitchen_at = now();
            $tx->save();

            $ticket = KitchenTicket::create([
                'transaction_id' => $tx->id,
                'ticket_no' => $billNo,
                'queue_no' => $queueNo,
                'queue_date' => $today,
                'status' => 'new',
                'printed_count' => 0,
                'last_printed_at' => null,
            ]);

            return redirect()->route('print.kot', $ticket->id);
        });
    }

    public function payCash(Request $request)
    {
        $me = view()->shared('me');
        $data = $request->validate([
            'transaction_id' => ['required', 'integer', 'exists:transactions,id'],
            'cash_received' => ['required', 'numeric', 'min:0'],
        ]);

        return DB::transaction(function () use ($data, $me) {
            $tx = Transaction::with('items')->lockForUpdate()->findOrFail($data['transaction_id']);

            if ($tx->status !== 'sent_to_kitchen' && $tx->status !== 'pending_verification') {
                abort(422, 'Status transaksi tidak bisa dibayar.');
            }

            $total = (float) $tx->grand_total;
            $received = (float) $data['cash_received'];

            if ($received < $total) {
                return back()->withErrors(['cash_received' => 'Uang diterima kurang dari total.']);
            }

            // DOUBLE CHECK stok sebelum paid (anti minus)
            $this->assertStockEnoughForTx($tx);

            $change = $received - $total;

            Payment::updateOrCreate(
                ['transaction_id' => $tx->id],
                [
                    'method' => 'cash',
                    'status' => 'paid',
                    'paid_amount' => $total,
                    'cash_received' => $received,
                    'change_amount' => $change,
                    'reference_no' => null,
                    'received_by' => $me->id,
                    'paid_at' => now(),
                    'verified_by' => null,
                    'verified_at' => null,
                ]
            );

            $tx->status = 'paid';
            $tx->paid_at = now();
            $tx->save();

            // potong stok (grouped by product)
            $this->deductStockForTx($tx, (int)$me->id, 'Sale');

            return redirect()->route('print.receipt', $tx->id);
        });
    }

    public function markTransferPending(Request $request)
    {
        $me = view()->shared('me');
        $data = $request->validate([
            'transaction_id' => ['required', 'integer', 'exists:transactions,id'],
            'reference_no' => ['nullable', 'string', 'max:50'],
        ]);

        return DB::transaction(function () use ($data, $me) {
            $tx = Transaction::lockForUpdate()->findOrFail($data['transaction_id']);
            if ($tx->status !== 'sent_to_kitchen') abort(422, 'Transaksi harus sudah dikirim ke dapur.');

            Payment::updateOrCreate(
                ['transaction_id' => $tx->id],
                [
                    'method' => 'transfer_manual',
                    'status' => 'pending_verification',
                    'paid_amount' => (float)$tx->grand_total,
                    'cash_received' => 0,
                    'change_amount' => 0,
                    'reference_no' => $data['reference_no'] ?? null,
                    'received_by' => $me->id,
                    'paid_at' => null,
                    'verified_by' => null,
                    'verified_at' => null,
                ]
            );

            $tx->status = 'pending_verification';
            $tx->save();

            return back();
        });
    }

    public function verifyTransfer(Request $request)
    {
        $me = view()->shared('me');
        $data = $request->validate([
            'transaction_id' => ['required', 'integer', 'exists:transactions,id'],
        ]);

        return DB::transaction(function () use ($data, $me) {
            $tx = Transaction::with('items')->lockForUpdate()->findOrFail($data['transaction_id']);
            if ($tx->status !== 'pending_verification') abort(422, 'Bukan transaksi pending verifikasi.');

            // DOUBLE CHECK stok sebelum paid (anti minus)
            $this->assertStockEnoughForTx($tx);

            $p = Payment::lockForUpdate()->where('transaction_id', $tx->id)->firstOrFail();
            $p->status = 'paid';
            $p->verified_by = $me->id;
            $p->verified_at = now();
            $p->paid_at = now();
            $p->save();

            $tx->status = 'paid';
            $tx->paid_at = now();
            $tx->save();

            // potong stok (grouped by product)
            $this->deductStockForTx($tx, (int)$me->id, 'Sale (transfer verified)');

            return redirect()->route('print.receipt', $tx->id);
        });
    }
}
