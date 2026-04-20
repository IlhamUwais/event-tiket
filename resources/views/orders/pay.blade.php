<x-layouts.app :title="'Pembayaran'">
    <div class="mx-auto max-w-2xl">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h1 class="text-xl font-semibold tracking-tight">Pembayaran</h1>
            <p class="mt-1 text-sm text-slate-600">Order #{{ $order->id_order }}</p>

            @error('order')
                <div class="mt-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                    {{ $message }}
                </div>
            @enderror

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="text-xs text-slate-500">Status</div>
                    <div class="mt-1 font-semibold">{{ strtoupper($order->status) }}</div>
                </div>
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="text-xs text-slate-500">Expired at</div>
                    <div class="mt-1 font-semibold">{{ $order->expired_at?->format('d M Y H:i') }}</div>
                </div>
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="text-xs text-slate-500">Final</div>
                    <div class="mt-1 font-semibold">Rp {{ number_format($order->final_price, 0, ',', '.') }}</div>
                </div>
            </div>

            @if ($order->status === 'pending' && $order->expired_at && $order->expired_at->isFuture())
                <form method="POST" action="{{ route('orders.pay.store', $order->id_order) }}" class="mt-6">
                    @csrf
                    <button type="submit"
                            class="w-full rounded-lg bg-slate-900 px-4 py-3 text-sm font-medium text-white hover:bg-slate-800">
                        Bayar Sekarang
                    </button>
                </form>
            @else
                <div class="mt-6 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                    Pembayaran tidak tersedia untuk status ini.
                </div>
            @endif

            <a href="{{ route('orders.show', $order->id_order) }}"
               class="mt-6 inline-block text-sm font-medium text-slate-900 underline underline-offset-4">
                Kembali ke detail order
            </a>
        </div>
    </div>
</x-layouts.app>

