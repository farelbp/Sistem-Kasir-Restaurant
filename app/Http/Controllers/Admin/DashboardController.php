<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Transaction, Payment, Product};

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $omzet = (float) Transaction::where('status','paid')->whereDate('paid_at',$today)->sum('grand_total');
        $paidCount = (int) Transaction::where('status','paid')->whereDate('paid_at',$today)->count();
        $pendingCount = (int) Transaction::where('status','pending_verification')->count();
        $lowStock = Product::where('stock_enabled', true)->orderBy('stock_qty')->limit(8)->get();

        return view('admin.dashboard', compact('today','omzet','paidCount','pendingCount','lowStock'));
    }
}
