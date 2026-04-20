<x-layouts.app :title="'Tiket'">
    <div class="mx-auto max-w-2xl">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight">Tiket</h1>
                    <p class="mt-1 text-sm text-slate-600">Kode: <span class="font-medium">{{ $attende->kode_tiket }}</span></p>
                </div>

                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium
                    @class([
                        'bg-slate-100 text-slate-800' => $attende->status === 'belum',
                        'bg-emerald-100 text-emerald-800' => $attende->status === 'sudah',
                    ])">
                    {{ strtoupper($attende->status) }}
                </span>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 sm:items-center">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 flex items-center justify-center">
                    @php
                        // QR dibuat realtime saat halaman dibuka (server-side render).
                        echo \SimpleSoftwareIO\QrCode\Facades\QrCode::size(220)->margin(1)->generate($attende->kode_tiket);
                    @endphp
                </div>

                <div class="space-y-3 text-sm">
                    <div class="rounded-xl border border-slate-200 p-4">
                        <div class="text-xs text-slate-500">Check-in</div>
                        <div class="mt-1 font-semibold">
                            @if ($attende->status === 'sudah')
                                Sudah ({{ $attende->waktu_checkin?->format('d M Y H:i') }})
                            @else
                                Belum
                            @endif
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-200 p-4">
                        <div class="text-xs text-slate-500">Petunjuk</div>
                        <div class="mt-1 text-slate-700">
                            Tunjukkan QR ini saat check-in. QR berisi <span class="font-medium">kode_tiket</span>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

