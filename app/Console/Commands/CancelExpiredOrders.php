<?php

namespace App\Console\Commands;

use App\Models\Orders;
use App\Models\Tiket;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CancelExpiredOrders extends Command
{
    protected $signature = 'orders:cancel-expired';
    protected $description = 'Cancel expired pending orders and restore ticket quota';

    public function handle(): int
    {
        $now = CarbonImmutable::now();

        $expiredIds = Orders::query()
            ->where('status', 'pending')
            ->where('expired_at', '<', $now)
            ->orderBy('id_order')
            ->pluck('id_order');

        $count = 0;

        foreach ($expiredIds as $id_order) {
            DB::transaction(function () use (&$count, $id_order, $now) {
                $order = Orders::query()
                    ->with('orderDetails')
                    ->where('id_order', $id_order)
                    ->lockForUpdate()
                    ->first();

                if (! $order || $order->status !== 'pending' || ($order->expired_at && $order->expired_at->isFuture())) {
                    return;
                }

                $detailByTicket = $order->orderDetails
                    ->groupBy('id_tiket')
                    ->map(fn ($rows) => (int) $rows->sum('qty'));

                if ($detailByTicket->isNotEmpty()) {
                    $tickets = Tiket::query()
                        ->whereIn('id_tiket', $detailByTicket->keys()->all())
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('id_tiket');

                    foreach ($detailByTicket as $id_tiket => $qty) {
                        $t = $tickets->get((int) $id_tiket);
                        if ($t) {
                            $t->increment('kuota', $qty);
                        }
                    }
                }

                $order->update([
                    'status' => 'cancel',
                    'cancel_reason' => 'expired',
                ]);

                $count++;
            });
        }

        $this->info("Cancelled {$count} expired orders.");

        return self::SUCCESS;
    }
}

