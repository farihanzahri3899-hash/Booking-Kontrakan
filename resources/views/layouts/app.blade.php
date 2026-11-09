<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sewa Kontrakan Nyaman & Terpercaya') - {{ config('app.name', 'Booking Kontrakan') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen text-slate-800 antialiased bg-slate-50 selection:bg-brand-500 selection:text-white">

    <!-- Navbar -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200/80 transition-all shadow-sm" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand Logo -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-brand-700 to-brand-500 flex items-center justify-center text-white shadow-md shadow-brand-500/20 group-hover:scale-105 transition-transform duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xl font-bold tracking-tight text-slate-900 flex items-center gap-1">
                                Kontrakan<span class="text-brand-600">ID</span>
                            </span>
                            <span class="block text-[11px] font-medium text-slate-600 -mt-0.5">Sewa Mudah & Nyaman</span>
                        </div>
                    </a>

                    <!-- Navigation Links -->
                    <nav class="hidden md:flex items-center gap-1">
                        <a href="{{ route('home') }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('home') ? 'text-brand-700 bg-brand-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Beranda
                        </a>
                        <a href="{{ route('properties.index') }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('properties.*') ? 'text-brand-700 bg-brand-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            Jelajah Kontrakan
                        </a>
                    </nav>
                </div>

                <!-- Right Menu: Auth / Actions -->
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors border border-indigo-200">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <span>Panel Admin</span>
                            </a>
                        @elseif(auth()->user()->isOwner())
                            <a href="{{ route('owner.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-brand-700 bg-brand-50 hover:bg-brand-100 rounded-lg transition-colors border border-brand-200">
                                <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                                <span>Panel Pemilik</span>
                            </a>
                        @endif

                        @if(auth()->user()->isOwnerOrAdmin())

                            <!-- Notification Bell Dropdown -->
                            <div class="relative" x-data="{ notifOpen: false }" @click.outside="notifOpen = false">
                                <button @click="notifOpen = !notifOpen" type="button" class="relative p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors focus:outline-none" aria-label="Notifikasi">
                                    <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    @if(!empty($navbarUnreadNotificationsCount) && $navbarUnreadNotificationsCount > 0)
                                        <span class="absolute top-1 right-1 flex h-4 w-4">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                            <span class="relative inline-flex items-center justify-center rounded-full h-4 w-4 bg-rose-600 text-[10px] font-bold text-white leading-none">
                                                {{ $navbarUnreadNotificationsCount > 9 ? '9+' : $navbarUnreadNotificationsCount }}
                                            </span>
                                        </span>
                                    @endif
                                </button>

                                <!-- Dropdown Panel -->
                                <div x-show="notifOpen" x-cloak 
                                     x-transition:enter="transition ease-out duration-150" 
                                     x-transition:enter-start="transform opacity-0 scale-95" 
                                     x-transition:enter-end="transform opacity-100 scale-100" 
                                     x-transition:leave="transition ease-in duration-100" 
                                     x-transition:leave-start="transform opacity-100 scale-100" 
                                     x-transition:leave-end="transform opacity-0 scale-95" 
                                     class="absolute right-0 mt-2 w-80 sm:w-96 rounded-2xl bg-white shadow-2xl ring-1 ring-black/5 divide-y divide-slate-100 py-1 z-50">
                                    
                                    <!-- Header -->
                                    <div class="px-4 py-3 flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-bold text-slate-900">Notifikasi Booking</span>
                                            @if(!empty($navbarUnreadNotificationsCount) && $navbarUnreadNotificationsCount > 0)
                                                <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                    {{ $navbarUnreadNotificationsCount }} baru
                                                </span>
                                            @endif
                                        </div>
                                        @if(!empty($navbarUnreadNotificationsCount) && $navbarUnreadNotificationsCount > 0)
                                            <form action="{{ route('owner.notifications.read-all') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-xs text-brand-600 hover:text-brand-700 font-semibold hover:underline">
                                                    Tandai dibaca
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <!-- Recent Notifications List -->
                                    <div class="max-h-80 overflow-y-auto divide-y divide-slate-50">
                                        @forelse($navbarRecentNotifications ?? [] as $navNotif)
                                            @php
                                                $nData = $navNotif->data;
                                                $isNavUnread = is_null($navNotif->read_at);
                                                $nType = $nData['property_type'] ?? 'rumah';
                                                $typeBadgeClass = match($nType) {
                                                    'rumah' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    'kost' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                    'petakan' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                    'paviliun' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                    default => 'bg-slate-100 text-slate-700 border-slate-200',
                                                };
                                            @endphp
                                            <a href="{{ route('owner.notifications.read-redirect', $navNotif->id) }}" class="block px-4 py-3 hover:bg-slate-50 transition-colors {{ $isNavUnread ? 'bg-brand-50/40' : '' }}">
                                                <div class="flex items-start gap-3">
                                                    <div class="w-8 h-8 rounded-lg bg-brand-100 text-brand-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center gap-1.5 mb-1">
                                                            <span class="inline-block px-1.5 py-0.2 rounded text-[10px] font-bold border {{ $typeBadgeClass }}">
                                                                {{ $nData['property_type_label'] ?? 'Kontrakan' }}
                                                            </span>
                                                            @if($isNavUnread)
                                                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                                            @endif
                                                            <span class="text-[10px] text-slate-600 ml-auto">{{ $navNotif->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-xs font-semibold text-slate-900 truncate">
                                                            {{ $nData['property_title'] ?? 'Kontrakan' }}
                                                        </p>
                                                        <p class="text-[11px] text-slate-600 truncate mt-0.5">
                                                            {{ $nData['tenant_name'] ?? 'Penyewa' }} &bull; {{ $nData['formatted_grand_total'] ?? '' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        @empty
                                            <div class="px-4 py-8 text-center text-xs text-slate-600">
                                                <svg class="w-8 h-8 text-slate-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                                Belum ada notifikasi pesanan baru.
                                            </div>
                                        @endforelse
                                    </div>

                                    <!-- Footer Link -->
                                    <div class="p-2 text-center bg-slate-50/70">
                                        <a href="{{ route('owner.notifications.index') }}" class="block py-1.5 text-xs font-bold text-brand-700 hover:text-brand-800 transition-colors">
                                            Buka Pusat Notifikasi &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('bookings.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-semibold text-slate-700 hover:text-brand-600 hover:bg-slate-100 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                                <span>Pesanan Saya</span>
                            </a>
                        @endif

                        <!-- User Profile Dropdown -->
                        <div class="relative ml-2" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" type="button" class="flex items-center gap-3 p-1.5 pl-3 rounded-full border border-slate-200 hover:border-slate-300 hover:bg-slate-50 transition-colors">
                                <div class="text-left hidden lg:block">
                                    <p class="text-xs font-semibold text-slate-900 leading-tight">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] font-medium text-slate-600 capitalize">{{ auth()->user()->role }}</p>
                                </div>
                                <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=059669&color=fff' }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover border border-slate-200">
                                <svg class="w-4 h-4 text-slate-600 pr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-56 rounded-xl bg-white shadow-xl ring-1 ring-black/5 divide-y divide-slate-100 py-1 z-50">
                                <div class="px-4 py-3">
                                    <p class="text-xs text-slate-600">Masuk sebagai</p>
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-slate-600 truncate">{{ auth()->user()->email }}</p>
                                </div>

                                <div class="py-1">
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-indigo-700 bg-indigo-50/50 hover:bg-indigo-50 font-bold">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                            Dashboard Admin
                                        </a>
                                    @endif

                                    @if(auth()->user()->isOwnerOrAdmin())
                                        <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-brand-600 font-medium">
                                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                            Dashboard Pemilik
                                        </a>
                                        <a href="{{ route('owner.properties.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-brand-600 font-medium">
                                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            Kelola Kontrakan
                                        </a>
                                        <a href="{{ route('owner.bookings.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-brand-600 font-medium">
                                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                            Permohonan Booking
                                        </a>
                                        <a href="{{ route('owner.notifications.index') }}" class="flex items-center justify-between px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-brand-600 font-medium">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                                <span>Pusat Notifikasi</span>
                                            </div>
                                            @if(!empty($navbarUnreadNotificationsCount) && $navbarUnreadNotificationsCount > 0)
                                                <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white leading-none">
                                                    {{ $navbarUnreadNotificationsCount }}
                                                </span>
                                            @endif
                                        </a>
                                    @else
                                        <a href="{{ route('bookings.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-brand-600 font-medium">
                                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                            Riwayat Booking Saya
                                        </a>
                                    @endif

                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-brand-600 font-medium">
                                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Pengaturan Profil
                                    </a>
                                </div>

                                <div class="py-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-brand-600 rounded-lg transition-colors">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-lg shadow-sm shadow-brand-600/30 transition-all hover:scale-[1.02]">
                                Daftar Akun
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Mobile Hamburger Button -->
                <div class="flex md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2 text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div x-show="mobileMenuOpen" x-cloak x-transition class="md:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-6 space-y-3">
            <div class="space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-base font-semibold rounded-lg {{ request()->routeIs('home') ? 'bg-brand-50 text-brand-700' : 'text-slate-700 hover:bg-slate-50' }}">
                    Beranda
                </a>
                <a href="{{ route('properties.index') }}" class="block px-3 py-2 text-base font-semibold rounded-lg {{ request()->routeIs('properties.*') ? 'bg-brand-50 text-brand-700' : 'text-slate-700 hover:bg-slate-50' }}">
                    Jelajah Kontrakan
                </a>
            </div>

            <div class="pt-3 border-t border-slate-100">
                @auth
                    <div class="flex items-center gap-3 px-3 py-2">
                        <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=059669&color=fff' }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover border border-slate-200">
                        <div>
                            <p class="text-sm font-bold text-slate-900 leading-tight">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-600 capitalize">{{ auth()->user()->role }}</p>
                        </div>
                    </div>

                    <div class="mt-2 space-y-1">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-sm font-bold text-indigo-700 bg-indigo-50/70 hover:bg-indigo-100 rounded-lg">
                                Dashboard Administrator
                            </a>
                        @endif

                        @if(auth()->user()->isOwnerOrAdmin())
                            <a href="{{ route('owner.dashboard') }}" class="block px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg">
                                Dashboard Pemilik
                            </a>
                            <a href="{{ route('owner.properties.index') }}" class="block px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg">
                                Kelola Kontrakan
                            </a>
                            <a href="{{ route('owner.bookings.index') }}" class="block px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg">
                                Permohonan Booking
                            </a>
                            <a href="{{ route('owner.notifications.index') }}" class="flex items-center justify-between px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg">
                                <span>Pusat Notifikasi</span>
                                @if(!empty($navbarUnreadNotificationsCount) && $navbarUnreadNotificationsCount > 0)
                                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-rose-500 text-white">
                                        {{ $navbarUnreadNotificationsCount }} Baru
                                    </span>
                                @endif
                            </a>
                        @else
                            <a href="{{ route('bookings.index') }}" class="block px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg">
                                Pesanan Saya
                            </a>
                        @endif

                        <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg">
                            Pengaturan Profil
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="pt-2">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 text-sm font-medium text-rose-600 hover:bg-rose-50 rounded-lg">
                                Keluar
                            </button>
                        </form>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-2 pt-2">
                        <a href="{{ route('login') }}" class="w-full text-center py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="w-full text-center py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-lg shadow-sm transition-colors">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Global Alert Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="flex items-center justify-between p-4 mb-4 text-emerald-800 bg-emerald-50 rounded-xl border border-emerald-200 shadow-sm" role="alert">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
                <button @click="show = false" type="button" class="text-emerald-500 hover:text-emerald-800 p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition class="flex items-center justify-between p-4 mb-4 text-rose-800 bg-rose-50 rounded-xl border border-rose-200 shadow-sm" role="alert">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-rose-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
                <button @click="show = false" type="button" class="text-rose-500 hover:text-rose-800 p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        @if (session('info'))
            <div x-data="{ show: true }" x-show="show" x-transition class="flex items-center justify-between p-4 mb-4 text-blue-800 bg-blue-50 rounded-xl border border-blue-200 shadow-sm" role="alert">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium">{{ session('info') }}</p>
                </div>
                <button @click="show = false" type="button" class="text-blue-500 hover:text-blue-800 p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div x-data="{ show: true }" x-show="show" x-transition class="p-4 mb-4 text-rose-800 bg-rose-50 rounded-xl border border-rose-200 shadow-sm" role="alert">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-rose-100 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-rose-900">Perhatikan beberapa hal berikut:</p>
                            <ul class="mt-1 list-disc list-inside text-xs text-rose-700 space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button @click="show = false" type="button" class="text-rose-500 hover:text-rose-800 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 pt-16 pb-12 border-t border-slate-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Col 1: Brand & About -->
                <div class="md:col-span-1 space-y-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-brand-400 flex items-center justify-center text-white shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-white">
                            Kontrakan<span class="text-brand-400">ID</span>
                        </span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Platform pencarian dan booking kontrakan terpercaya di Indonesia. Transaksi aman, foto terverifikasi, langsung terhubung dengan pemilik.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-brand-500/10 text-brand-400 border border-brand-500/20">
                            <span class="w-2 h-2 rounded-full bg-brand-400 animate-pulse"></span>
                            Layanan Aktif 24/7
                        </span>
                    </div>
                </div>

                <!-- Col 2: Kota Populer -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Kota Populer</h4>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li><a href="{{ route('properties.index', ['city' => 'Jakarta Selatan']) }}" class="hover:text-white transition-colors">Kontrakan Jakarta Selatan</a></li>
                        <li><a href="{{ route('properties.index', ['city' => 'Bandung']) }}" class="hover:text-white transition-colors">Kontrakan Bandung</a></li>
                        <li><a href="{{ route('properties.index', ['city' => 'Yogyakarta']) }}" class="hover:text-white transition-colors">Kontrakan Yogyakarta</a></li>
                        <li><a href="{{ route('properties.index', ['city' => 'Surabaya']) }}" class="hover:text-white transition-colors">Kontrakan Surabaya</a></li>
                        <li><a href="{{ route('properties.index', ['city' => 'Depok']) }}" class="hover:text-white transition-colors">Kontrakan Depok</a></li>
                        <li><a href="{{ route('properties.index', ['city' => 'Malang']) }}" class="hover:text-white transition-colors">Kontrakan Malang</a></li>
                    </ul>
                </div>

                <!-- Col 3: Tipe Kontrakan -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Tipe Kontrakan</h4>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li><a href="{{ route('properties.index', ['type' => 'rumah']) }}" class="hover:text-white transition-colors">Rumah Kontrakan Keluarga</a></li>
                        <li><a href="{{ route('properties.index', ['type' => 'petakan']) }}" class="hover:text-white transition-colors">Kontrakan Petakan 3 Sekat</a></li>
                        <li><a href="{{ route('properties.index', ['type' => 'paviliun']) }}" class="hover:text-white transition-colors">Paviliun Mandiri Nyaman</a></li>
                        <li><a href="{{ route('properties.index', ['type' => 'kost']) }}" class="hover:text-white transition-colors">Kost Eksklusif & Kamar Sewa</a></li>
                    </ul>
                </div>

                <!-- Col 4: Bantuan & Hubungi Kami -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Pusat Bantuan</h4>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-5 h-5 text-brand-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span>WhatsApp CS: 0812-8912-3456</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-5 h-5 text-brand-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>Email: bantuan@kontrakan.id</span>
                        </li>
                        <li class="pt-2">
                            <a href="{{ route('quick-login', 'pemilik') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand-400 hover:text-brand-300">
                                <span>Demo Login Pemilik &rarr;</span>
                            </a>
                            <br>
                            <a href="{{ route('quick-login', 'penyewa') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand-400 hover:text-brand-300 mt-1">
                                <span>Demo Login Penyewa &rarr;</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} KontrakanID. Hak Cipta Dilindungi Undang-Undang.</p>
                <div class="flex items-center gap-6">
                    <span class="hover:text-slate-400 cursor-pointer">Syarat & Ketentuan</span>
                    <span class="hover:text-slate-400 cursor-pointer">Kebijakan Privasi</span>
                    <span class="hover:text-slate-400 cursor-pointer">Panduan Keamanan</span>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
