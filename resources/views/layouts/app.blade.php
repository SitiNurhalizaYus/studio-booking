<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-cream text-espresso overflow-hidden">

    <div class="flex h-screen">


        <!-- SIDEBAR -->
        <aside class="w-64 bg-taupe px-6 py-8 flex flex-col h-full shrink-0">
            <!-- LOGO -->
            <div class="mb-10">
                <h1 class="text-xl font-semibold">Studio Foto</h1>
                <p class="text-sm text-gray-600">Booking System</p>
            </div>

            <!-- NAVIGATION -->
            <nav class="space-y-1 flex-1">

                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg
                    {{ request()->routeIs('dashboard') ? 'bg-white font-semibold' : 'hover:bg-white/60' }}">
                    <x-heroicon-o-home class="w-5 h-5" />
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('bookings.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg
                    {{ request()->routeIs('bookings.*') ? 'bg-white font-semibold' : 'hover:bg-white/60' }}">
                    <x-heroicon-o-calendar-days class="w-5 h-5" />
                    <span>Booking</span>
                </a>

                {{-- <a href="{{ route('calendar.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg
                    {{ request()->routeIs('calendar.*') ? 'bg-white font-semibold' : 'hover:bg-white/60' }}">
                    <x-heroicon-o-clock class="w-5 h-5" />
                    <span>Kalender</span>
                </a>

                <a href="{{ route('services.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg
                    {{ request()->routeIs('services.*') ? 'bg-white font-semibold' : 'hover:bg-white/60' }}">
                    <x-heroicon-o-users class="w-5 h-5" />
                    <span>Pelanggan</span>
                </a>

                <a href="{{ route('payments.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg
                    {{ request()->routeIs('payments.*') ? 'bg-white font-semibold' : 'hover:bg-white/60' }}">
                    <x-heroicon-o-credit-card class="w-5 h-5" />
                    <span>Pembayaran</span>
                </a> --}}

            </nav>

            <!-- ADMIN INFO (BOTTOM) -->
            <div class="mt-6 pt-4 border-t border-black/10 px-2">

                <!-- User Info -->
                <div class="flex items-center gap-3 mb-3">
                    <x-heroicon-o-user class="w-5 h-5 text-gray-600" />

                    <div class="leading-tight">
                        <p class="text-sm font-semibold text-gray-800">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-xs text-gray-600">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-black/10 px-2"></div>
                <!-- Logout (below user info) -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition">
                        <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5" />
                        <span>Keluar</span>
                    </button>
                </form>
        </aside>

        <!-- MAIN CONTENT -->
         <main class="flex-1 min-h-screen overflow-y-auto">
            <div class="max-w-7xl mx-auto px-6 py-6">
                @yield('content')
            </div>
        </main>

    </div>
</body>

</html>
