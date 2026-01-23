{{-- $status = $booking->status ?? 'pending'; --}}

@php
    $statusClass = [
        'pending' => 'bg-orange-100 text-orange-700',
        'waiting_payment' => 'bg-yellow-100 text-yellow-700',       
        'confirmed' => 'bg-green-100 text-green-700',
        'completed' => 'bg-indigo-100 text-indigo-700',
        'cancelled' => 'bg-red-100 text-red-700',
    ];
@endphp

<span
    class="inline-flex px-3 py-1 rounded-full text-xs font-medium
    {{ $statusClass[$booking->status] ?? 'bg-gray-100 text-gray-600' }}">
    {{ bookingStatusLabel($booking->status) }}
</span>
