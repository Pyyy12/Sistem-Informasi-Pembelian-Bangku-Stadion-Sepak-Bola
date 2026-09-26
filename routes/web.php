<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StadiumTicketController;

// Rute Tampilan Pembeli (Ala Bioskop)
Route::get('/', [StadiumTicketController::class, 'customerIndex'])->name('customer.stadium');

// Rute Tampilan Admin Kasir (Drag & Drop)git
Route::get('/admin/stadium', [StadiumTicketController::class, 'adminIndex'])->name('admin.stadium');

// Handler Transaksi Checkout
Route::post('/stadium/checkout', [StadiumTicketController::class, 'checkout'])->name('stadium.checkout');