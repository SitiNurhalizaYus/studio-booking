@extends('layouts.app')

@section('content')
    <!-- HEADER -->
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">
                Detail Booking
            </h1>
            {{-- <p class="text-sm text-gray-500">
                Booking atas nama {{ $booking->customer->name ?? '-' }}
            </p> --}}

            {{-- META TIME --}}
            <div class="mt-2 text-[11px] text-gray-400 space-x-2">
                <span>Dibuat: {{ $booking->created_at->format('d M Y H:i') }}</span>
                <span>•</span>
                <span>Diubah: {{ $booking->updated_at->format('d M Y H:i') }}</span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ session('back_url', route('bookings.index')) }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
          bg-white hover:bg-gray-100 text-sm font-medium">
                <x-heroicon-o-arrow-left class="w-4 h-4" />
                Kembali
            </a>

            @if (!in_array($booking->status, ['completed', 'cancelled']))
                <a href="{{ route('bookings.edit', $booking->id) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg
                      border border-yellow-300 text-yellow-700
                      hover:bg-yellow-50 text-xs font-medium transition">
                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                    Edit
                </a>
            @endif


        </div>
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
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg
                           hover:bg-blue-700 transition">
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
                            'waiting_payment' => 'bg-orange-100 text-orange-700',       
                            'confirmed' => 'bg-green-100 text-green-700',
                            'completed' => 'bg-blue-100 text-blue-700',
                            'cancelled' => 'bg-red-100 text-red-700',
                        ];
                    @endphp

                    <span
                        class="inline-flex px-3 py-1 rounded-full text-xs font-medium
    {{ $statusMap[$status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ bookingStatusLabel($booking->status ?? 'pending') }}

                    </span>

                </div>


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
        {{-- <div class="bg-white rounded-xl p-6 space-y-4">
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

                    @php
                        $map = [
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'dp' => 'bg-blue-100 text-blue-700',
                            'paid' => 'bg-green-100 text-green-700',
                        ];

                        $status = $payment->status ?? 'pending';
                    @endphp

                    <span
                        class="inline-flex px-3 py-1 rounded-full text-xs font-medium
        {{ $map[$status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ paymentStatusLabel($status) }}
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
        </div> --}}

        <div class="bg-white rounded-xl p-6">
            <h3 class="font-semibold mb-4">Pembayaran</h3>

            <div class="space-y-2 text-sm">
                <p>Total: <b>Rp {{ number_format($booking->payment->total_amount, 0, ',', '.') }}</b></p>
                <p>Dibayar: <span class="text-green-600">
                        Rp {{ number_format($booking->payment->paid_amount, 0, ',', '.') }}
                    </span></p>
                <p>Sisa: <span class="text-red-600">
                        Rp {{ number_format($booking->payment->remaining_amount, 0, ',', '.') }}
                    </span></p>

                <span
                    class="inline-flex px-3 py-1 rounded-full text-xs font-medium
            {{ $booking->payment->status === 'paid'
                ? 'bg-green-100 text-green-700'
                : ($booking->payment->status === 'dp'
                    ? 'bg-blue-100 text-blue-700'
                    : 'bg-yellow-100 text-yellow-700') }}">
                    {{ paymentStatusLabel($booking->payment->status) }}
                </span>
            </div>

            <a href="{{ route('payments.show', $booking->payment) }}"
                class="mt-4 inline-block w-full text-center px-4 py-2 rounded-lg bg-green-600 text-white">
                Kelola Pembayaran
            </a>
        </div>



    </div>
@endsection
