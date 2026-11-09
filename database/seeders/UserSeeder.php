<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Administrator
        User::firstOrCreate(
            ['email' => 'admin@kontrakan.test'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081200000001',
                'avatar' => 'https://ui-avatars.com/api/?name=Admin&background=4F46E5&color=fff',
            ]
        );

        // Pemilik Properti Utama
        User::firstOrCreate(
            ['email' => 'pemilik@kontrakan.test'],
            [
                'name' => 'H. Bambang Sudarmo (Pemilik)',
                'password' => Hash::make('password'),
                'role' => 'pemilik',
                'phone' => '081289123456',
                'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&auto=format&fit=crop&q=80',
            ]
        );

        // Penyewa 1
        User::firstOrCreate(
            ['email' => 'budi@gmail.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'penyewa',
                'phone' => '085712345678',
                'avatar' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=200&auto=format&fit=crop&q=80',
            ]
        );

        // Penyewa 2
        User::firstOrCreate(
            ['email' => 'siti@gmail.com'],
            [
                'name' => 'Siti Rahmawati',
                'password' => Hash::make('password'),
                'role' => 'penyewa',
                'phone' => '087812349999',
                'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&auto=format&fit=crop&q=80',
            ]
        );
    }
}
