@extends('layouts.app')

@section('content')


<div class="bg-white rounded-xl p-4 mb-6">
    <form method="GET" action="{{ route('reports.finance') }}"
          class="flex flex-wrap items-end gap-4">

        <div>
            <label class="text-sm text-gray-600 block mb-1">
                Laporan Keuangan Bulanan
            </label>
            <input type="month"
                   name="month"
                   value="{{ request('month') ?? now()->format('Y-m') }}"
                   class="border rounded-lg px-3 py-2">
        </div>

        <button type="submit"
                class="px-4 py-2 rounded-lg bg-stone-400 text-white hover:bg-stone-500">
            Cetak Laporan
        </button>
    </form>
</div>
<div class="bg-stone-100 rounded-xl p-4 mb-4 text-sm">
    <strong>Info:</strong>
    Laporan keuangan menampilkan transaksi dengan status
    <span class="font-semibold">Lunas</span> per bulan.
</div>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Pembayaran</h1>
            <p class="text-sm text-gray-500">
                Kelola dan pantau status pembayaran
            </p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Invoice</th>
                    <th class="px-4 py-3 text-left">Pelanggan</th>
                    <th class="px-4 py-3 text-left">Layanan</th>
                    <th class="px-4 py-3 text-left">Total</th>
                    <th class="px-4 py-3 text-left">Dibayar</th>
                    <th class="px-4 py-3 text-left">Sisa</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($payments as $payment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-4 py-3 font-mono text-xs">
                            {{ $payment->invoice_number ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $payment->booking->customer->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $payment->booking->service->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            Rp {{ number_format($payment->total_amount, 0, ',', '.') }}
                        </td>

                        <td class="px-4 py-3">
                            Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}
                        </td>

                        <td class="px-4 py-3">
                            Rp {{ number_format($payment->remaining_amount, 0, ',', '.') }}
                        </td>

                        <td class="px-4 py-3">
                            @php
                                $statusMap = [
                                    'pending' => 'bg-orange-100 text-orange-700',
                                    'dp' => 'bg-purple-100 text-purple-700',
                                    'paid' => 'bg-green-100 text-green-700',
                                ];
                            @endphp
                            <span
                                class="inline-flex px-3 py-1 rounded-full text-xs font-medium
                            {{ $statusMap[$payment->status] ?? 'bg-gray-100 text-gray-600' }}">
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


                            </span>
                        </td>

                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('payments.show', $payment) }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                                  bg-blue-50 text-blue-600 hover:bg-blue-100"
                                title="Detail Pembayaran">
                                <x-heroicon-o-eye class="w-4 h-4" />
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-6 text-center text-gray-500">
                            Belum ada transaksi pembayaran
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
