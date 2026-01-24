@extends('layouts.app')

@section('content')
    {{-- FLASH MESSAGE --}}
    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-red-700">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">
                Detail Booking
            </h1>
            <p class="text-sm text-gray-500">
                Dibuat: {{ $booking->created_at->format('d M Y H:i') }}
                ·
                Diubah: {{ $booking->updated_at->format('d M Y H:i') }}
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ session('back_url', route('bookings.index')) }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white hover:bg-gray-100 text-sm font-medium">
                ← Kembali
            </a>
            @if ($booking->status !== 'confirmed')
                <a href="{{ route('bookings.edit', $booking->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-yellow-100 hover:bg-yellow-200 text-sm font-medium">
                    ✏️ Edit
                </a>
            @endif


        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- ================= INFORMASI BOOKING ================= --}}
        <div class="md:col-span-2 bg-white rounded-xl p-6 shadow-sm space-y-4">
            <h2 class="text-lg font-semibold text-gray-800">
                Informasi Booking
            </h2>

            <div class="grid grid-cols-2 gap-y-3 text-sm">
                <div class="text-gray-500">Nama Pelanggan</div>
                <div class="font-medium">{{ $booking->customer->name }}</div>

                <div class="text-gray-500">Layanan</div>
                <div class="font-medium">
                    {{ $booking->service->name }}
                </div>

                <div class="text-gray-500">Tanggal</div>
                <div class="font-medium">
                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                </div>

                <div class="text-gray-500">Jam</div>
                <div class="font-medium">
                    {{ substr($booking->start_time, 0, 5) }} –
                    {{ substr($booking->end_time, 0, 5) }}
                </div>

                <div class="text-gray-500">Status Booking</div>
                <div>
                    <span
                        class="inline-block px-3 py-1 rounded-full text-xs
                        {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ strtoupper($booking->status) }}
                    </span>
                </div>
            </div>

            @if ($booking->notes)
                <div class="pt-4 border-t">
                    <p class="text-sm text-gray-500 mb-1">Catatan</p>
                    <p class="text-sm">{{ $booking->notes }}</p>
                </div>
            @endif
        </div>

        {{-- ================= PEMBAYARAN ================= --}}
        <div class="bg-white rounded-xl p-6">
            <h3 class="text-lg font-semibold mb-4">Pembayaran</h3>

            @if ($booking->payment)
                <div class="space-y-2 text-sm mb-4">
                    <p>
                        Total:
                        <strong>
                            Rp {{ number_format($booking->payment->total_amount, 0, ',', '.') }}
                        </strong>
                    </p>

                    <p>
                        Dibayar:
                        Rp {{ number_format($booking->payment->paid_amount, 0, ',', '.') }}
                    </p>

                    <p>
                        Sisa:
                        Rp {{ number_format($booking->payment->remaining_amount, 0, ',', '.') }}
                    </p>

                    <p>
                        Status:
                        <span class="font-semibold uppercase">
                            {{ $booking->payment->status }}
                        </span>
                    </p>
                </div>

                {{-- FORM BAYAR --}}
                @if ($booking->payment->remaining_amount == 0)
                    <p class="text-sm text-green-600 italic">
                        Pembayaran sudah lunas.
                    </p>
                @else
                    <form method="POST" action="{{ route('payments.update', $booking->payment->id) }}" class="space-y-3">
                        @csrf
                        @method('PUT')

                        <input type="number" name="pay_now" min="1" required
                            placeholder="Masukkan nominal pembayaran"
                            class="w-full rounded-xl border-gray-300
           focus:ring focus:ring-green-200
           px-4 py-3 text-sm">


                        <button type="submit"
                            class="w-full mt-3 py-3 rounded-xl
           bg-green-600 hover:bg-green-700
           text-white text-sm font-semibold
           transition duration-200 ease-in-out
           focus:outline-none focus:ring-2 focus:ring-green-300">
                            Simpan Pembayaran
                        </button>

                    </form>
                @endif
            @else
                <p class="text-sm text-red-500">
                    Data pembayaran belum tersedia.
                </p>
            @endif
        </div>

    </div>
@endsection
