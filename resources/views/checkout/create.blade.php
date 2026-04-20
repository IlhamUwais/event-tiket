<x-layouts.app :title="'Checkout'">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h1 class="text-xl font-semibold tracking-tight">Checkout</h1>
                <p class="mt-1 text-sm text-slate-600">
                    {{ $event->nama_event }} · {{ $event->tanggal_event?->format('d M Y') }}
                </p>

                <form method="POST" action="{{ route('checkout.store', $event->id_event) }}" class="mt-6 space-y-5" id="checkoutForm">
                    @csrf

                    <div class="space-y-3">
                        <h2 class="text-sm font-semibold tracking-tight">Pilih tiket & qty</h2>

                        @error('items')
                            <p class="text-sm text-rose-600">{{ $message }}</p>
                        @enderror

                        @foreach ($event->tikets as $tiket)
                            <div class="rounded-xl border border-slate-200 p-4">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <div class="text-sm font-medium">{{ $tiket->nama_tiket }}</div>
                                        <div class="mt-1 text-xs text-slate-500">Kuota realtime: {{ $tiket->kuota }}</div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="text-sm font-semibold" data-price="{{ (int) $tiket->harga }}">
                                            Rp {{ number_format($tiket->harga, 0, ',', '.') }}
                                        </div>
                                        <input
                                            type="number"
                                            min="0"
                                            max="100"
                                            name="items[{{ $tiket->id_tiket }}]"
                                            value="{{ old("items.{$tiket->id_tiket}", 0) }}"
                                            class="w-24 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-slate-200 qty-input"
                                            data-ticket-id="{{ $tiket->id_tiket }}"
                                            data-ticket-price="{{ (int) $tiket->harga }}"
                                        />
                                    </div>
                                </div>
                                @error("items.{$tiket->id_tiket}")
                                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>

                    <div class="rounded-xl border border-slate-200 p-4">
                        <h2 class="text-sm font-semibold tracking-tight">Voucher</h2>
                        <p class="mt-1 text-xs text-slate-500">Opsional. Voucher akan divalidasi saat checkout.</p>

                        <div class="mt-3 flex gap-2">
                            <input
                                name="voucher"
                                id="voucherInput"
                                value="{{ old('voucher') }}"
                                placeholder="Masukkan code voucher (contoh: PROMO10)"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-slate-200"
                            />
                            <button type="button" id="voucherCheckBtn"
                                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm hover:bg-slate-50">
                                Cek
                            </button>
                        </div>
                        @error('voucher')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-slate-600" id="voucherHint"></p>
                    </div>

                    <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-3 text-sm font-medium text-white hover:bg-slate-800">
                        Checkout
                    </button>
                </form>
            </div>
        </div>

        <aside class="space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-semibold tracking-tight">Ringkasan</h2>
                <div class="mt-4 space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">Total</span>
                        <span class="font-semibold" id="totalText">Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">Diskon</span>
                        <span class="font-semibold text-emerald-700" id="discountText">Rp 0</span>
                    </div>
                    <div class="h-px bg-slate-200"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-700 font-medium">Final</span>
                        <span class="font-semibold" id="finalText">Rp 0</span>
                    </div>
                </div>
                <p class="mt-4 text-xs text-slate-500">
                    Catatan: diskon voucher ditampilkan setelah tombol “Cek” ditekan, dan tetap akan divalidasi lagi di server saat checkout.
                </p>
            </div>
        </aside>
    </div>

    <script>
        const money = (n) => 'Rp ' + (n || 0).toLocaleString('id-ID');
        const qtyInputs = Array.from(document.querySelectorAll('.qty-input'));
        const totalText = document.getElementById('totalText');
        const discountText = document.getElementById('discountText');
        const finalText = document.getElementById('finalText');
        const voucherHint = document.getElementById('voucherHint');
        const voucherInput = document.getElementById('voucherInput');
        const voucherCheckBtn = document.getElementById('voucherCheckBtn');

        let discountPercent = 0;

        function calcTotal() {
            let total = 0;
            for (const el of qtyInputs) {
                const qty = parseInt(el.value || '0', 10) || 0;
                const price = parseInt(el.dataset.ticketPrice || '0', 10) || 0;
                total += qty * price;
            }
            const discount = Math.round(total * (discountPercent / 100));
            const final = Math.max(0, total - discount);
            totalText.textContent = money(total);
            discountText.textContent = money(discount);
            finalText.textContent = money(final);
        }

        qtyInputs.forEach((el) => el.addEventListener('input', calcTotal));
        calcTotal();

        voucherCheckBtn.addEventListener('click', async () => {
            voucherHint.textContent = 'Mengecek voucher...';
            discountPercent = 0;
            calcTotal();

            const code = (voucherInput.value || '').trim();
            if (!code) {
                voucherHint.textContent = '';
                return;
            }

            try {
                const res = await fetch(@json(route('checkout.voucherPreview', $event->id_event)), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token()),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ voucher: code }),
                });

                const data = await res.json();
                if (!res.ok) {
                    voucherHint.textContent = data?.message || 'Voucher tidak valid.';
                    discountPercent = 0;
                } else {
                    discountPercent = data.discount_percent || 0;
                    voucherHint.textContent = `Voucher valid: diskon ${discountPercent}%`;
                }
                calcTotal();
            } catch (e) {
                voucherHint.textContent = 'Gagal mengecek voucher. Coba lagi.';
                discountPercent = 0;
                calcTotal();
            }
        });
    </script>
</x-layouts.app>

