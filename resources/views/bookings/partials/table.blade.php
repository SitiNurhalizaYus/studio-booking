@if ($bookings->isEmpty())
    <p class="text-sm text-gray-500">
        Tidak ada data booking.
    </p>
@else
<table class="w-full text-sm">
    <thead>
        <tr class="text-left text-gray-500">
            <th>No</th>
            <th>Nama Pelanggan</th>
            <th>Layanan</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Status</th>
            <th class="text-right">Aksi</th>
        </tr>
    </thead>
    <tbody class="divide-y">
        @foreach ($bookings as $index => $booking)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $booking->customer->name }}</td>
            <td>{{ $booking->service->name }}</td>
            <td>{{ $booking->booking_date }}</td>
            <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>

            <td>
                @include('bookings.partials.status', ['booking' => $booking])
            </td>

            <td class="text-right">
                @include('bookings.partials.actions', ['booking' => $booking])
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
