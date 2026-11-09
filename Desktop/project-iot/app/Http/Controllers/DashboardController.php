<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductionLog;

class DashboardController extends Controller
{
    public function index()
    {
        $product = Product::first();
        $logs = ProductionLog::with('rak')->latest()->take(10)->get();

        return view('dashboard', compact('product', 'logs'));
    }
}