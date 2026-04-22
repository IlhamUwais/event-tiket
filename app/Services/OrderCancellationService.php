<?php

namespace App\Services;

use App\Models\Orders;
use App\Models\Tiket;
use App\Models\Voucher;
use App\Models\voucher_usages;

class OrderCancellationService
{
    /**
     * Kembalikan kuota tiket dan status pemakaian voucher untuk order yang dibatalkan/expired.
     * Panggil di dalam transaksi DB; order harus sudah memuat orderDetails.
     */
    public function releaseReservedInventory(Orders $order): void
    {
        $order->loadMissing('orderDetails');

        foreach ($order->orderDetails as $detail) {
            $tiket = Tiket::query()
                ->where('id_tiket', $detail->id_tiket)
                ->lockForUpdate()
                ->first();

            if ($tiket) {
                $tiket->increment('kuota', (int) $detail->qty);
            }
        }

        if ($order->id_voucher === null) {
            return;
        }

        voucher_usages::query()
            ->where('id_order', $order->id_order)
            ->delete();

        $voucher = Voucher::query()
            ->where('id_voucher', $order->id_voucher)
            ->lockForUpdate()
            ->first();

        if (! $voucher) {
            return;
        }

        $next = max(0, (int) $voucher->used_count - 1);
        $voucher->forceFill(['used_count' => $next])->save();
    }
}
