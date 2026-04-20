<x-layouts.app :title="'Profile'">
    <div class="mx-auto max-w-xl">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h1 class="text-xl font-semibold tracking-tight">Profile</h1>
            <p class="mt-1 text-sm text-slate-600">Kelola data akun kamu.</p>

            <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-slate-700">Nama</label>
                    <input name="name" type="text" value="{{ old('name', $user->name) }}" required
                           class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-slate-200">
                    @error('name')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Email</label>
                    <input name="email" type="email" value="{{ old('email', $user->email) }}" required
                           class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-slate-200">
                    @error('email')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="text-sm font-semibold tracking-tight">Ubah password</div>
                    <p class="mt-1 text-xs text-slate-500">Kosongkan jika tidak ingin mengganti password.</p>

                    <div class="mt-3 space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Password baru</label>
                            <input name="password" type="password"
                                   class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-slate-200">
                            @error('password')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Konfirmasi password baru</label>
                            <input name="password_confirmation" type="password"
                                   class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-slate-200">
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="w-full rounded-lg bg-slate-900 px-4 py-3 text-sm font-medium text-white hover:bg-slate-800">
                    Simpan
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>

