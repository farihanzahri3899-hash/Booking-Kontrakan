<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin home page / dashboard.
     */
    public function index(Request $request): View
    {
        $roleFilter = $request->query('role');
        $search = $request->query('q');

        // Stats summary
        $stats = [
            'total_users' => User::count(),
            'total_tenants' => User::where('role', 'penyewa')->count(),
            'total_owners' => User::where('role', 'pemilik')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_properties' => Property::count(),
            'total_bookings' => Booking::count(),
        ];

        // Query users with search & filter
        $query = User::query()->latest();

        if ($roleFilter && in_array($roleFilter, ['penyewa', 'pemilik', 'admin'], true)) {
            $query->where('role', $roleFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(12)->withQueryString();

        return view('admin.dashboard', compact('stats', 'users', 'roleFilter', 'search'));
    }

    /**
     * Grant property owner permission to a tenant.
     */
    public function grantOwner(User $user): RedirectResponse
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Pengguna ini sudah merupakan Administrator.');
        }

        if ($user->role === 'pemilik') {
            return back()->with('info', "Pengguna {$user->name} sudah berstatus Pemilik Kontrakan.");
        }

        $user->update(['role' => 'pemilik']);

        return back()->with('success', "Izin Pemilik Kontrakan berhasil diberikan kepada {$user->name} ({$user->email}). Akun kini dapat mendaftarkan dan mengelola unit kontrakan.");
    }

    /**
     * Revoke property owner permission and set role back to tenant.
     */
    public function revokeOwner(User $user): RedirectResponse
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak dapat mengubah role Administrator.');
        }

        if ($user->role === 'penyewa') {
            return back()->with('info', "Pengguna {$user->name} sudah berstatus Penyewa.");
        }

        $user->update(['role' => 'penyewa']);

        return back()->with('info', "Izin Pemilik untuk {$user->name} ({$user->email}) telah dicabut. Role dikembalikan menjadi Penyewa.");
    }

    /**
     * Delete a user account from system.
     */
    public function destroyUser(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $user->name;
        $email = $user->email;
        $user->delete();

        return back()->with('success', "Akun {$name} ({$email}) berhasil dihapus dari sistem.");
    }
}
