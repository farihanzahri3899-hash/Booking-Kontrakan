<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return Auth::user()->isOwnerOrAdmin()
                ? redirect()->route('owner.dashboard')
                : redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Process login request.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::user();

            if ($user->isOwnerOrAdmin()) {
                return redirect()->intended(route('owner.dashboard'))
                    ->with('success', "Selamat datang kembali, {$user->name}!");
            }

            return redirect()->intended(route('home'))
                ->with('success', "Selamat datang, {$user->name}! Silakan cari kontrakan idaman Anda.");
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'Email atau password yang Anda masukkan salah.']);
    }

    /**
     * Show register form.
     */
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.register');
    }

    /**
     * Process registration.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'role' => ['required', 'string', 'in:penyewa,pemilik'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'avatar' => 'https://ui-avatars.com/api/?name='.urlencode($validated['name']).'&background=0D9488&color=fff',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->isOwnerOrAdmin()) {
            return redirect()->route('owner.dashboard')
                ->with('success', 'Akun Pemilik berhasil dibuat! Selamat datang di dashboard Anda.');
        }

        return redirect()->route('home')
            ->with('success', 'Pendaftaran berhasil! Selamat datang di Booking Kontrakan.');
    }

    /**
     * Quick login helper for demo & easy testing.
     */
    public function quickLogin(string $role): RedirectResponse
    {
        $email = match ($role) {
            'pemilik', 'admin' => 'admin@kontrakan.test',
            default => 'budi@gmail.com',
        };

        $user = User::where('email', $email)->first();

        if ($user) {
            Auth::login($user);
            request()->session()->regenerate();

            if ($user->isOwnerOrAdmin()) {
                return redirect()->route('owner.dashboard')
                    ->with('success', "Login cepat berhasil sebagai {$user->name} (Pemilik/Admin).");
            }

            return redirect()->route('home')
                ->with('success', "Login cepat berhasil sebagai {$user->name} (Penyewa).");
        }

        return redirect()->route('login')->with('error', 'User demo tidak ditemukan.');
    }

    /**
     * Logout user.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'Anda telah berhasil keluar.');
    }
}
