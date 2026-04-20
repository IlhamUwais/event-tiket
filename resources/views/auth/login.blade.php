<x-layouts.app :title="'Login'">
    <div class="mx-auto max-w-md">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h1 class="text-lg font-semibold tracking-tight">Login</h1>
            <p class="mt-1 text-sm text-slate-600">Masuk untuk melakukan pemesanan tiket.</p>

            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700">Email</label>
                    <input name="email" type="email" value="{{ old('email') }}" required autofocus
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

                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" name="remember" value="1" @checked(old('remember')) class="rounded border-slate-300">
                    Remember me
                </label>

                <button type="submit"
                        class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-slate-800">
                    Login
                </button>
            </form>

            <p class="mt-6 text-sm text-slate-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-medium text-slate-900 underline underline-offset-4">Register</a>
            </p>
        </div>
    </div>
</x-layouts.app>

