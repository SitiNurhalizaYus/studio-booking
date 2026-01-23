@extends('layouts.app')

@section('content')
    <div class="p-6 space-y-6">

        {{-- HEADER --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-500">Selamat datang kembali, Admin</p>
        </div>

        {{-- KPI CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            {{-- Total Booking --}}
            <div class="bg-white rounded-xl p-5 shadow">
                <p class="text-sm text-gray-500">Total Booking</p>
                <h2 class="text-3xl font-bold">{{ $totalBooking }}</h2>
            </div>

            {{-- Pelanggan --}}
            <div class="bg-white rounded-xl p-5 shadow">
                <p class="text-sm text-gray-500">Pelanggan</p>
                <h2 class="text-3xl font-bold">{{ $totalCustomer }}</h2>
            </div>

            {{-- Revenue --}}
            <div class="bg-white rounded-xl p-5 shadow">
                <p class="text-sm text-gray-500">Revenue</p>
                <h2 class="text-3xl font-bold">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </h2>
            </div>

            {{-- Pending --}}
            <div class="bg-white rounded-xl p-5 shadow">
                <p class="text-sm text-gray-500">Pending</p>
                <h2 class="text-3xl font-bold">{{ $pendingBooking }}</h2>
            </div>
        </div>
        {{-- GRID: TRENDS + BOOKING TERBARU --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- BOOKING TRENDS --}}
            <div class="lg:col-span-2 bg-white rounded-xl p-6 shadow">
                <h3 class="font-semibold mb-4">Booking Trends</h3>

                <canvas id="bookingChart" height="120"></canvas>
            </div>

            {{-- BOOKING TERBARU --}}
            <div class="bg-white rounded-xl p-6 shadow">
                <h3 class="font-semibold mb-4">Booking Terbaru</h3>

                <div class="space-y-4">
                    @forelse($latestBookings as $booking)
                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg">
                            <div>
                                <p class="font-medium">{{ $booking->customer->name }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $booking->service->name }}
                                </p>
                            </div>

                            {{-- STATUS (PAKAI PARTIAL) --}}
                            @include('bookings.partials.status', [
                                'status' => $booking->status,
                            ])
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center">
                            Belum ada booking
                        </p>
                    @endforelse
                </div>
            </div>

        </div>


    </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const bookingData = @json($bookingTrends);

        const labels = bookingData.map(item => {
            const monthNames = [
                'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
            ];
            return monthNames[item.month - 1];
        });

        const totals = bookingData.map(item => item.total);

        const ctx = document.getElementById('bookingChart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Booking',
                    data: totals,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
