<div class="flex justify-end gap-2">

    {{-- DETAIL --}}
    <a href="{{ route('bookings.show', $booking->id) }}"
        class="p-2 rounded-md bg-gray-100 hover:bg-gray-200">
        <x-heroicon-o-eye class="w-4 h-4" />
    </a>

    {{-- JIKA BOOKING SELESAI → PRINT STRUK --}}
    @if ($booking->status === 'completed')
        <a href="{{ route('bookings.invoice', $booking->id) }}"
            target="_blank"
            class="p-2 rounded-md bg-blue-100 hover:bg-blue-200">
            <x-heroicon-o-printer class="w-4 h-4" />
        </a>

    {{-- JIKA BELUM SELESAI → EDIT & DELETE --}}
    @else
        <a href="{{ route('bookings.edit', $booking->id) }}"
            class="p-2 rounded-md bg-yellow-100 hover:bg-yellow-200">
            <x-heroicon-o-pencil class="w-4 h-4" />
        </a>

        <form method="POST"
            action="{{ route('bookings.destroy', $booking->id) }}"
            onsubmit="return confirm('Yakin hapus booking ini?')">
            @csrf
            @method('DELETE')
            <button
                class="p-2 rounded-md bg-red-100 hover:bg-red-200">
                <x-heroicon-o-trash class="w-4 h-4" />
            </button>
        </form>
    @endif

</div>
