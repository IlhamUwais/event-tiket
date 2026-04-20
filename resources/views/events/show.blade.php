<x-layouts.app :title="$event->nama_event">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            @php
                $img = $event->gambar ?? null;
                $imgUrl = $img
                    ? (\Illuminate\Support\Str::startsWith($img, ['http://', 'https://']) ? $img : asset($img))
                    : null;
            @endphp

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="aspect-video bg-slate-100">
                    @if ($imgUrl)
                        <img src="{{ $imgUrl }}" alt="{{ $event->nama_event }}" class="h-full w-full object-cover">
                    @else
                        <div class="h-full w-full flex items-center justify-center text-sm text-slate-500">No image</div>
                    @endif
                </div>
                <div class="p-6">
                    <div class="text-sm text-slate-500">
                        {{ $event->tanggal_event?->format('d M Y') }}
                        · {{ $event->jam_mulai }} - {{ $event->jam_selesai }}
                    </div>
                    <h1 class="mt-1 text-2xl font-semibold tracking-tight">{{ $event->nama_event }}</h1>
                    <p class="mt-4 whitespace-pre-line text-sm text-slate-700">{{ $event->deskripsi }}</p>
                </div>
            </div>
        </div>

        <aside class="space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-semibold tracking-tight">Venue</h2>
                <div class="mt-3 text-sm text-slate-700">
                    <div class="font-medium">{{ $event->venue?->nama_venue }}</div>
                    <div class="mt-1 text-slate-600">{{ $event->venue?->alamat }}</div>
                    <div class="mt-1 text-slate-500">Kapasitas: {{ $event->venue?->kapasitas }}</div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-semibold tracking-tight">Tiket tersedia</h2>
                <div class="mt-3 space-y-3">
                    @foreach ($event->tikets as $tiket)
                        <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 px-4 py-3">
                            <div>
                                <div class="text-sm font-medium">{{ $tiket->nama_tiket }}</div>
                                <div class="text-xs text-slate-500">Kuota: {{ $tiket->kuota }}</div>
                            </div>
                            <div class="text-sm font-semibold">
                                Rp {{ number_format($tiket->harga, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5">
                    @auth
                        <a href="{{ route('checkout.create', $event->id_event) }}"
                           class="block w-full rounded-lg bg-slate-900 px-4 py-2.5 text-center text-sm font-medium text-white hover:bg-slate-800">
                            Checkout
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="block w-full rounded-lg bg-slate-900 px-4 py-2.5 text-center text-sm font-medium text-white hover:bg-slate-800">
                            Login untuk beli tiket
                        </a>
                    @endauth
                </div>
            </div>
        </aside>
    </div>
</x-layouts.app>

