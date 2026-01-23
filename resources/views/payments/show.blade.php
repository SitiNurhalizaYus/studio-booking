@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold">Detail Pembayaran</h1>
                <p class="text-sm text-gray-500">
                    Booking: {{ $payment->booking->customer->name }}
                </p>
            </div>

            <a href="{{ session('back_url', route('bookings.index')) }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
          bg-white hover:bg-gray-100 text-sm font-medium">
                <x-heroicon-o-arrow-left class="w-4 h-4" />
                Kembali
            </a>

        </div>

        {{-- INFO BOOKING --}}
        <div class="bg-white rounded-xl p-6 grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Pelanggan</p>
                <p class="font-medium">{{ $payment->booking->customer->name }}</p>
            </div>
            <div>
                <p class="text-gray-500">Layanan</p>
                <p class="font-medium">{{ $payment->booking->service->name }}</p>
            </div>
            <div>
                <p class="text-gray-500">Tanggal</p>
                <p class="font-medium">
                    {{ \Carbon\Carbon::parse($payment->booking->booking_date)->format('d M Y') }}
                </p>
            </div>
            <div>
                <p class="text-gray-500">Jam</p>
                <p class="font-medium">
                    {{ $payment->booking->start_time }} – {{ $payment->booking->end_time }}
                </p>
            </div>
        </div>


        {{-- PEMBAYARAN --}}
        <div class="bg-white rounded-xl p-6 space-y-6">
            <h2 class="text-lg font-semibold">Pembayaran</h2>

            {{-- RINGKASAN --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Total</p>
                    <p class="font-medium">
                        Rp {{ number_format($payment->total_amount, 0, ',', '.') }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Sudah Dibayar</p>
                    <p class="font-medium text-green-600">
                        Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Sisa</p>
                    <p class="font-medium text-red-600">
                        Rp {{ number_format($payment->remaining_amount, 0, ',', '.') }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Status</p>
                    @php
                        $statusClass = [
                            'pending' => 'bg-orange-100 text-orange-700',
                            'dp' => 'bg-purple-100 text-purple-700',
                            'paid' => 'bg-green-100 text-green-700',
                        ];
                    @endphp

                    <span
                        class="inline-flex px-3 py-1 rounded-full text-xs font-medium
    {{ $statusClass[$payment->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ paymentStatusLabel($payment->status) }}
                    </span>

                </div>
            </div>

            {{-- FORM BAYAR --}}
            @if ($payment->status !== 'paid')
                <form method="POST" action="{{ route('payments.update', $payment) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">
                            Bayar Sekarang
                        </label>
                        @if ($errors->has('pay_now'))
                            <div class="mb-2 text-sm text-red-600">
                                {{ $errors->first('pay_now') }}
                            </div>
                        @endif
                        <p class="text-xs text-gray-500 mb-2">
                            Pastikan nominal sesuai. Setiap pembayaran akan tercatat sebagai riwayat.
                        </p>
                        <input type="number" name="pay_now" max="{{ $payment->remaining_amount }}"
                            class="w-full rounded-lg border-gray-300 focus:ring focus:ring-green-200"
                            placeholder="Masukkan nominal pembayaran">
                    </div>

                    <button class="w-full py-3 rounded-xl bg-stone-400 text-white hover:bg-stone-500">
                        Simpan Pembayaran
                    </button>
                </form>
            @else
                <p class="text-sm text-green-600 italic">
                    Pembayaran sudah lunas.
                </p>
            @endif
        </div>
        {{-- RIWAYAT PEMBAYARAN --}}
        @if ($payment->histories && $payment->histories->isNotEmpty())
            <div class="bg-white rounded-xl p-6">
                <h3 class="text-sm font-semibold mb-4">
                    Riwayat Pembayaran
                </h3>

                <ul class="space-y-3 text-sm">
                    @foreach ($payment->histories as $history)
                        <li class="flex justify-between items-center">
                            <span class="text-gray-600">
                                {{ $history->type === 'dp' ? 'DP' : 'Pelunasan' }}
                                • {{ $history->created_at->format('d M Y H:i') }}
                            </span>

                            <span class="font-medium text-gray-800">
                                Rp {{ number_format($history->amount, 0, ',', '.') }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- ACTION --}}
        <div class="flex gap-3">
            <a href="{{ route('payments.invoice', $payment) }}" class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200">
                Cetak Invoice
            </a>
        </div>

    </div>
@endsection
