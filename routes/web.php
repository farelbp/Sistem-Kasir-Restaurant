<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Kasir\PosController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Kasir\PrintController;
use App\Http\Controllers\Admin\MasterController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Kasir\TransactionController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'show'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('loggedin')->group(function () {

    Route::prefix('kasir')->name('kasir.')->middleware('role:kasir')->group(function () {
        Route::get('/pos', [PosController::class, 'index'])->name('pos');
        Route::post('/cart/add', [TransactionController::class, 'add'])->name('cart.add');
        Route::post('/cart/qty', [TransactionController::class, 'setQty'])->name('cart.qty');
        Route::post('/cart/remove', [TransactionController::class, 'remove'])->name('cart.remove');
        Route::post('/cart/note', [TransactionController::class, 'setNote'])->name('cart.note');

        Route::post('/send-to-kitchen', [TransactionController::class, 'sendToKitchen'])->name('send');
        Route::get('/kitchen-today', [PosController::class, 'kitchenToday'])->name('kitchen_today');

        Route::post('/pay/cash', [TransactionController::class, 'payCash'])->name('pay.cash');
        Route::post('/pay/transfer-pending', [TransactionController::class, 'markTransferPending'])->name('pay.transfer_pending');
        Route::post('/pay/verify-transfer', [TransactionController::class, 'verifyTransfer'])->name('pay.verify_transfer');
    });

    Route::prefix('print')->name('print.')->group(function () {
        Route::get('/kot/{ticket}', [PrintController::class, 'kot'])->name('kot');
        Route::get('/receipt/{transaction}', [PrintController::class, 'receipt'])->name('receipt');
    });

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/master', [MasterController::class, 'index'])->name('master.index');
        Route::post('/master/category', [MasterController::class, 'saveCategory'])->name('master.category.save');
        Route::post('/master/product', [MasterController::class, 'saveProduct'])->name('master.product.save');
        Route::post('/master/table', [MasterController::class, 'saveTable'])->name('master.table.save');
        Route::post('/master/delete', [MasterController::class, 'delete'])->name('master.delete');

        Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');

        Route::get('/stocks', [StockController::class, 'index'])->name('stocks');
        Route::post('/stocks/restock', [StockController::class, 'restock'])->name('stocks.restock');
        Route::post('/stocks/adjust', [StockController::class, 'adjust'])->name('stocks.adjust');
        Route::post('/categories/{category}/update', [MasterController::class, 'updateCategory'])->name('master.category.update');
        Route::post('/products/{product}/update', [MasterController::class, 'updateProduct'])->name('master.product.update');
        Route::post('/tables/{table}/update', [MasterController::class, 'updateTable'])->name('master.table.update');

        Route::patch('/categories/{category}/toggle', [MasterController::class, 'toggleCategory'])->name('master.category.toggle');
        Route::patch('/products/{product}/toggle', [MasterController::class, 'toggleProduct'])->name('master.product.toggle');
        Route::patch('/tables/{table}/toggle', [MasterController::class, 'toggleTable'])->name('master.table.toggle');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::post('/users/update', [UserController::class, 'update'])->name('users.update');
        Route::post('/users/toggle', [UserController::class, 'toggle'])->name('users.toggle');
    });
});