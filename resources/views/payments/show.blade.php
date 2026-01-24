@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold">Detail Pembayaran</h1>
                <p class="text-sm text-gray-500">
                    No Invoice: <span class="font-medium">{{ $payment->invoice_number }}</span>
                </p>
                <p class="text-xs text-gray-400">
                    Dibuat: {{ $payment->created_at->format('d M Y') }}
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
            @if ($payment->remaining_amount > 0)

    <form method="POST" action="{{ route('payments.update', $payment) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm text-gray-600 mb-1">
                Bayar Sekarang
            </label>

            @error('pay_now')
                <div class="text-sm text-red-600 mb-2">{{ $message }}</div>
            @enderror

            <input type="number"
                   name="pay_now"
                   max="{{ $payment->remaining_amount }}"
                   class="w-full rounded-lg border-gray-300"
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
        @if ($payment->histories->isNotEmpty())
    <div class="bg-white rounded-xl p-6">
        <h3 class="text-sm font-semibold mb-4">Riwayat Pembayaran</h3>

        <ul class="space-y-3 text-sm">
            @foreach ($payment->histories as $history)
                <li class="flex justify-between">
                    <span class="text-gray-600">
                        {{ $history->type === 'dp' ? 'DP' : 'Pelunasan' }}
                        • {{ $history->created_at->format('d M Y H:i') }}
                    </span>
                    <span class="font-medium">
                        Rp {{ number_format($history->amount, 0, ',', '.') }}
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
@endif



        {{-- ACTION --}}
        <div class="flex gap-3">
            <a href="{{ route('payments.invoice', $payment) }}"
                class="inline-flex items-center gap-2
              px-4 py-2 rounded-lg
              bg-blue-100 hover:bg-blue-200
              text-sm font-medium text-blue-800">

                {{-- ICON PRINT --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z" />
                </svg>

                Cetak Invoice
            </a>
        </div>


    </div>
@endsection
