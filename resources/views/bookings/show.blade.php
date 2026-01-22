<x-app-layout>
    <!-- HEADER -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Detail Booking</h1>
            <p class="text-sm text-gray-500">
                Booking atas nama {{ $booking->customer->name ?? '-' }}
            </p>
        </div>

        <a href="{{ route('bookings.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white hover:bg-gray-100 text-sm font-medium">
            <x-heroicon-o-arrow-left class="w-4 h-4" />
            Kembali
        </a>
    </div>

    <!-- CONTENT -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- INFO BOOKING -->
        <div class="lg:col-span-2 bg-white rounded-xl p-6 space-y-4">
            <h2 class="text-lg font-semibold mb-4">Informasi Booking</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Nama Pelanggan</p>
                    <p class="font-medium">{{ $booking->customer->name ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Layanan</p>
                    <p class="font-medium">{{ $booking->service->name ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Tanggal</p>
                    <p class="font-medium">
                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                    </p>
                </div>


                <div>
                    <p class="text-sm text-gray-500 mb-1">Jam</p>
                    <p class="font-medium">
                        {{ $booking->start_time }} – {{ $booking->end_time }}
                    </p>

                    {{-- ACTION BUTTON --}}
                    <div class="mt-4">
                        {{-- JIKA SUDAH CONFIRMED --}}
                        @if ($booking->status === 'confirmed')
                            <form method="POST" action="{{ route('bookings.complete', $booking) }}">
                                @csrf
                                @method('PATCH')

                                <button
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg
                           hover:bg-green-700 transition">
                                    Tandai Selesai
                                </button>
                            </form>
                        @endif

                        {{-- JIKA SUDAH SELESAI --}}
                        @if ($booking->status === 'completed')
                            <span class="inline-block mt-2 text-sm text-gray-500 italic">
                                Booking telah selesai
                            </span>
                        @endif

                        {{-- JIKA DIBATALKAN --}}
                        @if ($booking->status === 'cancelled')
                            <span class="inline-block mt-2 text-sm text-red-500 italic">
                                Booking dibatalkan
                            </span>
                        @endif
                    </div>
                </div>

                <div>
                    <p class="text-gray-500">Status Booking</p>
                    @php
                        $status = $booking->status ?? 'pending';

                        $statusMap = [
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'confirmed' => 'bg-green-100 text-green-700',
                            'completed' => 'bg-blue-100 text-blue-700',
                            'cancelled' => 'bg-red-100 text-red-700',
                        ];
                    @endphp


                    <span
                        class="inline-flex px-3 py-1 rounded-full text-xs font-medium
    {{ $statusMap[$status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($status) }}
                    </span>

                </div>
               

                @if ($booking->status === 'confirmed')
                    <form method="POST" action="{{ route('bookings.complete', $booking) }}">
                        @csrf
                        @method('PATCH')

                        <button class="px-4 py-2 bg-green-600 text-white rounded-lg">
                            Tandai Selesai
                        </button>
                    </form>
                @endif
                 @if ($booking->notes)
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Catatan</p>
                        <div class="mt-1 p-3 bg-gray-50 rounded-md text-sm">
                            {{ $booking->notes }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- INFO PEMBAYARAN -->
        <div class="bg-white rounded-xl p-6 space-y-4">
            <h2 class="text-lg font-semibold">Pembayaran</h2>

            @php
                $payment = $booking->payment;
            @endphp

            <div class="text-sm space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-500">Total</span>
                    <span class="font-medium">
                        Rp {{ number_format($payment->total_amount ?? 0, 0, ',', '.') }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Dibayar</span>
                    <span class="font-medium">
                        Rp {{ number_format($payment->paid_amount ?? 0, 0, ',', '.') }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Sisa</span>
                    <span class="font-medium">
                        Rp {{ number_format($payment->remaining_amount ?? 0, 0, ',', '.') }}
                    </span>
                </div>

                <div class="pt-2">
                    <span class="text-gray-500 text-sm">Status Pembayaran</span><br>
                    <span
                        class="inline-flex px-3 py-1 rounded-full text-xs font-medium
                        {{ ($payment->status ?? '') === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ ucfirst($payment->status ?? 'pending') }}
                    </span>
                </div>
            </div>

            <!-- ACTION -->
            <div class="pt-4 space-y-2">
                <button
                    class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-green-600 text-white text-sm hover:bg-green-700">
                    <x-heroicon-o-chat-bubble-left-right class="w-4 h-4" />
                    Kirim WhatsApp
                </button>
            </div>
        </div>

    </div>
</x-app-layout>
