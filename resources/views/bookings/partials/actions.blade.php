<div class="flex items-center gap-2">

    {{-- DETAIL --}}
    <a href="{{ route('bookings.show', $booking) }}"
       class="inline-flex items-center justify-center w-8 h-8 rounded-lg
              bg-blue-50 text-blue-600 hover:bg-blue-100"
       title="Detail">
        <x-heroicon-o-eye class="w-4 h-4" />
    </a>

@if (!in_array($booking->status, ['completed', 'cancelled']))
    {{-- EDIT (HANYA JIKA BELUM SELESAI) --}}
        <a href="{{ route('bookings.edit', $booking->id) }}"
           class="inline-flex items-center justify-center w-8 h-8 rounded-lg
                  bg-yellow-50 text-yellow-600 hover:bg-yellow-100"
           title="Edit Booking">
            <x-heroicon-o-pencil-square class="w-4 h-4" />
        </a>


    {{-- DELETE (OPSIONAL, JIKA KAMU PAKAI) --}}
        <form action="{{ route('bookings.destroy', $booking->id) }}"
      method="POST"
      onsubmit="return confirmDelete()">
    @csrf
    @method('DELETE')

    <button type="submit"
        class="inline-flex items-center justify-center
               w-9 h-9 rounded-lg
               bg-red-100 text-red-600
               hover:bg-red-200"
        title="Hapus Booking">
        🗑
    </button>
</form><script>
function confirmDelete() {
    return confirm(
        'Yakin ingin menghapus booking ini?\n\nData booking dan pembayaran akan dihapus dan tidak bisa dikembalikan.'
    );
}
</script>


@endif

</div>
