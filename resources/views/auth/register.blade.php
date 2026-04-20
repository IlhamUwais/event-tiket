<x-layouts.app :title="'Register'">
    <div class="mx-auto max-w-md">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h1 class="text-lg font-semibold tracking-tight">Register</h1>
            <p class="mt-1 text-sm text-slate-600">Buat akun untuk memesan tiket event.</p>

            <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700">Nama</label>
                    <input name="name" type="text" value="{{ old('name') }}" required autofocus
                           class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-slate-200">
                    @error('name')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Email</label>
                    <input name="email" type="email" value="{{ old('email') }}" required
                           class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-slate-200">
                    @error('email')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Password</label>
                    <input name="password" type="password" required
                           class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-slate-200">
                    @error('password')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Konfirmasi Password</label>
                    <input name="password_confirmation" type="password" required
                           class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-slate-200">
                </div>

                <button type="submit"
                        class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-slate-800">
                    Register
                </button>
            </form>

            <p class="mt-6 text-sm text-slate-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-medium text-slate-900 underline underline-offset-4">Login</a>
            </p>
        </div>
    </div>
</x-layouts.app>

