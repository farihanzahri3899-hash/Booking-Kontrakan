<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show edit profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update user profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:25'],
            'avatar_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'avatar_url' => ['nullable', 'url', 'max:500'],
            'remove_avatar' => ['nullable', 'boolean'],
        ]);

        $avatar = $user->avatar;

        if ($request->boolean('remove_avatar')) {
            if ($user->avatar && str_contains($user->avatar, '/storage/')) {
                $storagePath = str_replace('/storage/', '', $user->avatar);
                if (Storage::disk('public')->exists($storagePath)) {
                    Storage::disk('public')->delete($storagePath);
                }
            }
            $avatar = 'https://ui-avatars.com/api/?name='.urlencode($validated['name']).'&background=059669&color=fff';
        } elseif ($request->hasFile('avatar_file')) {
            if ($user->avatar && str_contains($user->avatar, '/storage/')) {
                $storagePath = str_replace('/storage/', '', $user->avatar);
                if (Storage::disk('public')->exists($storagePath)) {
                    Storage::disk('public')->delete($storagePath);
                }
            }

            $path = $request->file('avatar_file')->store('avatars', 'public');
            $avatar = Storage::url($path);
        } elseif (! empty($validated['avatar_url'])) {
            $avatar = $validated['avatar_url'];
        } elseif ($avatar && str_contains($avatar, 'ui-avatars.com')) {
            $avatar = 'https://ui-avatars.com/api/?name='.urlencode($validated['name']).'&background=059669&color=fff';
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'avatar' => $avatar,
        ]);

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok dengan akun Anda.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'password.min' => 'Kata sandi baru minimal harus terdiri dari 6 karakter.',
        ]);

        /** @var User $user */
        $user = $request->user();

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Kata sandi berhasil diperbarui dengan aman!');
    }

    /**
     * Delete user account permanently.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Kata sandi wajib diisi untuk mengonfirmasi penghapusan akun.',
            'password.current_password' => 'Kata sandi yang Anda masukkan salah.',
        ]);

        /** @var User $user */
        $user = $request->user();

        // Safety check for active rentals or confirmed bookings
        if ($user->role === 'pemilik') {
            $hasActiveBookings = Booking::whereIn('property_id', $user->properties()->pluck('id'))
                ->whereIn('status', ['confirmed', 'active'])
                ->exists();

            if ($hasActiveBookings) {
                return back()->with('error', 'Tidak dapat menghapus akun karena properti kontrakan Anda masih memiliki penyewa aktif atau booking yang telah dikonfirmasi.');
            }
        } elseif ($user->role === 'penyewa') {
            $hasActiveBookings = $user->bookings()
                ->whereIn('status', ['confirmed', 'active'])
                ->exists();

            if ($hasActiveBookings) {
                return back()->with('error', 'Tidak dapat menghapus akun karena Anda masih memiliki masa sewa aktif atau booking yang telah disetujui.');
            }
        }

        // Clean up stored avatar if exists on public storage
        if ($user->avatar && str_contains($user->avatar, '/storage/')) {
            $storagePath = str_replace('/storage/', '', $user->avatar);
            if (Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->delete($storagePath);
            }
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Akun Anda berhasil dihapus.');
    }
}
