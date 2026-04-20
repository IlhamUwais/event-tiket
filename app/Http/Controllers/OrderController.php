<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Orders::query()
            ->with(['orderDetails.tiket.event'])
            ->where('id_user', $request->user()->id_user)
            ->orderByDesc('id_order')
            ->paginate(10);

        return view('orders.index', [
            'orders' => $orders,
        ]);
    }

    public function show(Request $request, int $id_order)
    {
        $order = Orders::query()
            ->with(['voucher', 'orderDetails.tiket.event', 'orderDetails.attendes'])
            ->where('id_order', $id_order)
            ->where('id_user', $request->user()->id_user)
            ->firstOrFail();

        return view('orders.show', [
            'order' => $order,
        ]);
    }

    public function pay(Request $request, int $id_order)
    {
        $order = Orders::query()
            ->with(['voucher', 'orderDetails.tiket.event'])
            ->where('id_order', $id_order)
            ->where('id_user', $request->user()->id_user)
            ->firstOrFail();

        return view('orders.pay', [
            'order' => $order,
            'now' => CarbonImmutable::now(),
        ]);
    }

    public function payStore(Request $request, int $id_order)
    {
        DB::transaction(function () use ($request, $id_order) {
            $order = Orders::query()
                ->where('id_order', $id_order)
                ->where('id_user', $request->user()->id_user)
                ->lockForUpdate()
                ->firstOrFail();

            if ($order->status !== 'pending') {
                throw ValidationException::withMessages([
                    'order' => 'Pesanan tidak dalam status pending.',
                ]);
            }

            if ($order->expired_at && $order->expired_at->isPast()) {
                throw ValidationException::withMessages([
                    'order' => 'Pesanan sudah expired.',
                ]);
            }

            $order->update([
                'status' => 'paid',
            ]);
        });

        return redirect()->route('orders.show', $id_order)
            ->with('status', 'Pembayaran berhasil. Menunggu konfirmasi admin.');
    }
}

