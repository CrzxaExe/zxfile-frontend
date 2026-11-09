<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::post('/inventory/update', [ProductController::class, 'updateInventory']);
Route::post('/inventory/sell', [ProductController::class, 'sellProduct']);