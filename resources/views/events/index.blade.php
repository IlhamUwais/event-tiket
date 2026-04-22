<x-layouts.app :title="'Event'">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-semibold tracking-tight">Daftar Event</h1>
                <p class="mt-1 text-sm text-slate-600">Cari dan pesan tiket event.</p>
            </div>

            <form method="GET" action="{{ route('events.index') }}" class="flex gap-2">
                <input
                    name="q"
                    value="{{ $search }}"
                    placeholder="Search nama event..."
                    class="w-full sm:w-72 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-slate-200"
                />
                <button class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
                    Search
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($events as $event)
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="aspect-video bg-slate-100">
                        @if ($event->gambar)
                            <img src="{{ $event->gambar_url }}" alt="{{ $event->nama_event }}" class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full flex items-center justify-center text-sm text-slate-500">No image</div>
                        @endif
                    </div>
                    <div class="p-5">
                        <div class="text-sm text-slate-500">
                            {{ $event->tanggal_event?->format('d M Y') }}
                            · {{ $event->venue?->nama_venue }}
                        </div>
                        <h2 class="mt-1 font-semibold tracking-tight">
                            <a href="{{ route('events.show', $event->id_event) }}" class="hover:underline">
                                {{ $event->nama_event }}
                            </a>
                        </h2>
                        <p class="mt-2 line-clamp-2 text-sm text-slate-600">
                            {{ $event->deskripsi }}
                        </p>

                        <div class="mt-4 flex items-center justify-between gap-3">
                            <a href="{{ route('events.show', $event->id_event) }}"
                               class="rounded-lg border border-slate-300 px-3 py-2 text-sm hover:bg-slate-50">
                                Lihat detail
                            </a>
                            @auth
                                <a href="{{ route('checkout.create', $event->id_event) }}"
                                   class="rounded-lg bg-slate-900 px-3 py-2 text-sm font-medium text-white hover:bg-slate-800">
                                    Checkout
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="rounded-lg bg-slate-900 px-3 py-2 text-sm font-medium text-white hover:bg-slate-800">
                                    Login untuk beli
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-2xl border border-slate-200 bg-white p-8 text-center text-sm text-slate-600">
                    Event tidak ditemukan.
                </div>
            @endforelse
        </div>

        <div>
            {{ $events->links() }}
        </div>
    </div>
</x-layouts.app>

