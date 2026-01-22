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
    <body class="font-sans bg-cream text-espresso">
        <div class="flex min-h-screen">

            <!-- SIDEBAR -->
            <aside class="w-64 bg-taupe px-6 py-8">
                <div class="mb-10">
                    <h1 class="text-xl font-semibold">Studio Foto</h1>
                    <p class="text-sm text-gray-600">Booking System</p>
                </div>

                <nav class="space-y-2">
                    <a href="#" class="block px-4 py-2 rounded-lg bg-white font-medium">
                        Dashboard
                    </a>
                    <a href="#" class="block px-4 py-2 rounded-lg hover:bg-white/60">
                        Booking
                    </a>
                    <a href="#" class="block px-4 py-2 rounded-lg hover:bg-white/60">
                        Kalender
                    </a>
                    <a href="#" class="block px-4 py-2 rounded-lg hover:bg-white/60">
                        Pelanggan
                    </a>
                    <a href="#" class="block px-4 py-2 rounded-lg hover:bg-white/60">
                        Pembayaran
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-10">
                <!-- LOGOUT -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="w-full px-4 py-2 rounded-lg bg-white/70 hover:bg-white font-medium"
                        >
                            Keluar
                        </button>
                    </form>
                </nav>
            </aside>

            <!-- MAIN CONTENT -->
            <main class="flex-1 p-8">
                {{ $slot }}
            </main>

        </div>
    </body>

</html>
