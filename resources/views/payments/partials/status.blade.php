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
