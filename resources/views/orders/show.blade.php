<x-layouts.app :title="'Detail Order'">
    <div class="flex flex-col gap-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight">Order #{{ $order->id_order }}</h1>
                    <p class="mt-1 text-sm text-slate-600">
                        Tanggal: {{ $order->tanggal_order?->format('d M Y') }}
                        · Status: <span class="font-medium">{{ strtoupper($order->status) }}</span>
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    @if ($order->status === 'pending')
                        <a href="{{ route('orders.pay', $order->id_order) }}"
                           class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-slate-800">
                            Bayar
                        </a>
                    @endif
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="text-xs text-slate-500">Total</div>
                    <div class="mt-1 font-semibold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                </div>
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="text-xs text-slate-500">Voucher</div>
                    <div class="mt-1 font-semibold">{{ $order->voucher?->code ?? '-' }}</div>
                    <div class="mt-1 text-xs text-slate-500">Diskon: Rp {{ number_format($order->discount, 0, ',', '.') }}</div>
                </div>
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="text-xs text-slate-500">Final</div>
                    <div class="mt-1 font-semibold">Rp {{ number_format($order->final_price, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="font-semibold tracking-tight">Detail tiket</h2>
            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-left text-slate-500">
                        <tr>
                            <th class="py-2">Event</th>
                            <th class="py-2">Tiket</th>
                            <th class="py-2">Qty</th>
                            <th class="py-2">Harga</th>
                            <th class="py-2">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach ($order->orderDetails as $detail)
                            <tr>
                                <td class="py-3 pr-4">
                                    {{ $detail->tiket?->event?->nama_event ?? '-' }}
                                </td>
                                <td class="py-3 pr-4">{{ $detail->tiket?->nama_tiket ?? '-' }}</td>
                                <td class="py-3 pr-4">{{ $detail->qty }}</td>
                                <td class="py-3 pr-4">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                <td class="py-3 pr-4">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if ($order->status === 'confirm')
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-semibold tracking-tight">Tiket (Attendees)</h2>
                <p class="mt-1 text-sm text-slate-600">Klik untuk membuka tiket dan QR code.</p>

                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($order->orderDetails as $detail)
                        @foreach ($detail->attendes as $attende)
                            <a href="{{ route('tickets.show', $attende->kode_tiket) }}"
                               class="rounded-xl border border-slate-200 p-4 hover:bg-slate-50">
                                <div class="text-xs text-slate-500">{{ $detail->tiket?->nama_tiket }}</div>
                                <div class="mt-1 font-semibold tracking-tight">{{ $attende->kode_tiket }}</div>
                                <div class="mt-2 text-xs">
                                    <span class="inline-flex items-center rounded-full px-2 py-1 font-medium
                                        @class([
                                            'bg-slate-100 text-slate-800' => $attende->status === 'belum',
                                            'bg-emerald-100 text-emerald-800' => $attende->status === 'sudah',
                                        ])">
                                        {{ strtoupper($attende->status) }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>

