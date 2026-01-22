@extends('layouts.app')

@section('content')
    <div class="bg-cream min-h-screen p-8">
        <div class="max-w-7xl mx-auto">

            <!-- HEADER -->
            <div class="mb-8">
                <h1 class="text-3xl font-semibold text-espresso">Dashboard</h1>
                <p class="text-gray-600">Selamat datang kembali, Admin</p>
            </div>

            <!-- STAT CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Total Booking</p>
                    <h2 class="text-3xl font-semibold mt-2">156</h2>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Pelanggan</p>
                    <h2 class="text-3xl font-semibold mt-2">89</h2>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Revenue</p>
                    <h2 class="text-3xl font-semibold mt-2">Rp 45M</h2>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Pending</p>
                    <h2 class="text-3xl font-semibold mt-2">12</h2>
                </div>
            </div>

            <!-- MAIN GRID -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- BOOKING TRENDS (placeholder) -->
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold mb-4">Booking Trends</h3>
                    <div class="h-48 bg-cream rounded-lg flex items-center justify-center text-gray-400">
                        Grafik (placeholder)
                    </div>
                </div>

                <!-- BOOKING TERBARU -->
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold mb-4">Booking Terbaru</h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center bg-cream rounded-lg p-3">
                            <div>
                                <p class="font-medium">Budi Santoso</p>
                                <p class="text-sm text-gray-500">Wedding Photo</p>
                            </div>
                            <span class="text-xs bg-green-200 text-green-800 px-3 py-1 rounded-full">
                                confirmed
                            </span>
                        </div>

                        <div class="flex justify-between items-center bg-cream rounded-lg p-3">
                            <div>
                                <p class="font-medium">Siti Aminah</p>
                                <p class="text-sm text-gray-500">Portrait</p>
                            </div>
                            <span class="text-xs bg-yellow-200 text-yellow-800 px-3 py-1 rounded-full">
                                pending
                            </span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
