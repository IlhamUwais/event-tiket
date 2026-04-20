<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Event Tiket') }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            body { font-family: ui-sans-serif, system-ui, sans-serif; }
        </style>
    @endif
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-6xl px-4 py-4 flex items-center justify-between gap-4">
            <a href="{{ route('events.index') }}" class="font-semibold tracking-tight">
                {{ config('app.name', 'Event Tiket') }}
            </a>

            <nav class="flex items-center gap-3 text-sm">
                <a href="{{ route('events.index') }}" class="px-3 py-2 rounded-md hover:bg-slate-100">Event</a>

                @auth
                    @if (auth()->user()->role === 'user')
                        <a href="{{ route('orders.index') }}" class="px-3 py-2 rounded-md hover:bg-slate-100">Pesanan</a>
                        <a href="{{ route('profile.edit') }}" class="px-3 py-2 rounded-md hover:bg-slate-100">Profile</a>
                    @elseif (auth()->user()->role === 'admin')
                        <a href="{{ url('/admin') }}" class="px-3 py-2 rounded-md bg-slate-900 text-white hover:bg-slate-800">Admin Panel</a>
                    @elseif (auth()->user()->role === 'petugas')
                        <a href="{{ \Filament\Facades\Filament::getPanel('petugas')->getUrl() }}" class="px-3 py-2 rounded-md bg-emerald-700 text-white hover:bg-emerald-800">Check-in Petugas</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-3 py-2 rounded-md hover:bg-slate-100">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-2 rounded-md hover:bg-slate-100">Login</a>
                    <a href="{{ route('register') }}" class="px-3 py-2 rounded-md bg-slate-900 text-white hover:bg-slate-800">Register</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8">
        @if (session('status'))
            <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        {{ $slot }}
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto max-w-6xl px-4 py-6 text-xs text-slate-500">
            © {{ date('Y') }} {{ config('app.name', 'Event Tiket') }}
        </div>
    </footer>
</body>
</html>

