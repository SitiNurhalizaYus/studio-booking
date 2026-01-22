@php
    $status = $booking->status ?? 'pending';

    $statusMap = [
        'pending' => 'bg-yellow-100 text-yellow-700',
        'confirmed' => 'bg-green-100 text-green-700',
        'completed' => 'bg-blue-100 text-blue-700 animate-pulse',
        'cancelled' => 'bg-red-100 text-red-700',
    ];
@endphp

<span
    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium transition
    {{ $statusMap[$status] ?? 'bg-gray-100 text-gray-700' }}">
    {{ ucfirst($status) }}
</span>
