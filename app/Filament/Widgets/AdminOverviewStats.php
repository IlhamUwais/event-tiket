<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\OrderDetail;
use App\Models\Orders;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class AdminOverviewStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $paidStatuses = ['paid', 'confirm'];

        $totalEvents = Event::query()->count();
        $upcomingEvents = Event::query()
            ->whereDate('tanggal_event', '>=', Carbon::today())
            ->count();

        $totalTransactions = Orders::query()->count();
        $paidTransactions = Orders::query()
            ->whereIn('status', $paidStatuses)
            ->count();

        $totalRevenue = Orders::query()
            ->whereIn('status', $paidStatuses)
            ->sum('final_price');

        $topEvent = Event::query()
            ->select('events.id_event', 'events.nama_event')
            ->selectSub(
                OrderDetail::query()
                    ->selectRaw('COALESCE(SUM(order_details.qty), 0)')
                    ->join('orders', 'orders.id_order', '=', 'order_details.id_order')
                    ->join('tikets', 'tikets.id_tiket', '=', 'order_details.id_tiket')
                    ->whereColumn('tikets.id_event', 'events.id_event')
                    ->whereIn('orders.status', $paidStatuses),
                'tickets_sold'
            )
            ->orderByDesc('tickets_sold')
            ->first();

        return [
            Stat::make('Total Event', number_format($totalEvents))
                ->description('Event tersedia di sistem')
                ->color('primary'),

            Stat::make('Event Akan Datang', number_format($upcomingEvents))
                ->description('Tanggal event >= hari ini')
                ->color('info'),

            Stat::make('Total Transaksi', number_format($totalTransactions))
                ->description(number_format($paidTransactions).' transaksi paid/confirm')
                ->color('warning'),

            Stat::make('Pendapatan', 'Rp '.number_format((float) $totalRevenue, 0, ',', '.'))
                ->description('Akumulasi order paid + confirm')
                ->color('success'),

            Stat::make('Event Paling Laris', $topEvent?->nama_event ?? '-')
                ->description(($topEvent ? number_format((int) $topEvent->tickets_sold) : '0').' tiket terjual')
                ->color('gray'),
        ];
    }
}
