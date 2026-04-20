<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Email atau password salah.']);
        }

        $request->session()->regenerate();

        $user = $request->user();

        if ($user && $user->role === 'admin') {
            $adminPanelUrl = Filament::getPanel('admin')?->getUrl() ?? url('/admin');

            return redirect()->intended($adminPanelUrl);
        }

        if ($user && $user->role === 'petugas') {
            $petugasPanelUrl = Filament::getPanel('petugas')?->getUrl() ?? url('/petugas');

            return redirect()->intended($petugasPanelUrl);
        }

        return redirect()->intended(route('events.index'));
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('events.index');
    }
}

