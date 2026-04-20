<x-layouts.app :title="'Riwayat Pembelian'">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold tracking-tight">Riwayat Pembelian</h1>
            <p class="mt-1 text-sm text-slate-600">Daftar semua order kamu.</p>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="divide-y divide-slate-200">
            @forelse ($orders as $order)
                <div class="p-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="text-sm font-medium">
                            Order #{{ $order->id_order }}
                        </div>
                        <div class="mt-1 text-xs text-slate-500">
                            {{ $order->tanggal_order?->format('d M Y') }}
                            · Total: Rp {{ number_format($order->final_price, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium
                            @class([
                                'bg-amber-100 text-amber-800' => $order->status === 'pending',
                                'bg-sky-100 text-sky-800' => $order->status === 'paid',
                                'bg-emerald-100 text-emerald-800' => $order->status === 'confirm',
                                'bg-rose-100 text-rose-800' => $order->status === 'cancel',
                            ])">
                            {{ strtoupper($order->status) }}
                        </span>

                        <a href="{{ route('orders.show', $order->id_order) }}"
                           class="rounded-lg border border-slate-300 px-3 py-2 text-sm hover:bg-slate-50">
                            Detail
                        </a>

                        @if ($order->status === 'pending')
                            <a href="{{ route('orders.pay', $order->id_order) }}"
                               class="rounded-lg bg-slate-900 px-3 py-2 text-sm font-medium text-white hover:bg-slate-800">
                                Bayar
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-sm text-slate-600">
                    Belum ada order.
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</x-layouts.app>

