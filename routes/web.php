<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;

Route::get('/', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id_event}', [EventController::class, 'show'])->name('events.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role.user'])->group(function () {
    Route::get('/events/{id_event}/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/events/{id_event}/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/events/{id_event}/voucher-preview', [CheckoutController::class, 'voucherPreview'])->name('checkout.voucherPreview');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id_order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id_order}/pay', [OrderController::class, 'pay'])->name('orders.pay');
    Route::post('/orders/{id_order}/pay', [OrderController::class, 'payStore'])->name('orders.pay.store');

    Route::get('/tickets/{kode_tiket}', [TicketController::class, 'show'])->name('tickets.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
