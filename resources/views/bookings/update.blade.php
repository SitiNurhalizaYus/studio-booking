@extends('layouts.app')

@section('content')

{{-- ERROR MESSAGE --}}
@if ($errors->any())
    <div class="mb-4 rounded-lg bg-red-50 p-4 text-red-700">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- BACK --}}
<div class="mb-6">
    <a href="{{ session('back_url', route('bookings.index')) }}"
       class="text-sm text-gray-500 hover:underline">
        ← Kembali
    </a>
</div>

<form id="bookingForm"
      method="POST"
      action="{{ route('bookings.update', $booking->id) }}">
@csrf
@method('PUT')

<div class="bg-white rounded-2xl shadow-sm p-8 space-y-8">

    <h1 class="text-2xl font-semibold text-gray-800">
        Edit Booking
    </h1>

    {{-- INFORMASI PELANGGAN --}}
    <div>
        <h2 class="text-lg font-semibold mb-4">Informasi Pelanggan</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="text"
                   name="name"
                   value="{{ old('name', $booking->customer->name) }}"
                   required
                   class="w-full rounded-xl border-gray-200">

            <input type="text"
                   name="phone"
                   value="{{ old('phone', $booking->customer->phone) }}"
                   required
                   class="w-full rounded-xl border-gray-200">
        </div>
    </div>

    {{-- DETAIL BOOKING --}}
    <div>
        <h2 class="text-lg font-semibold mb-4">Detail Booking</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <select name="service_id"
                    id="service_id"
                    required
                    class="w-full rounded-xl border-gray-200">
                @foreach ($services as $service)
                    <option value="{{ $service->id }}"
                        {{ old('service_id', $booking->service_id) == $service->id ? 'selected' : '' }}>
                        {{ $service->name }}
                    </option>
                @endforeach
            </select>

            <input type="date"
                   name="booking_date"
                   id="booking_date"
                   value="{{ old('booking_date', $booking->booking_date) }}"
                   required
                   class="w-full rounded-xl border-gray-200">
        </div>

        {{-- SLOT --}}
        <div class="mt-5">
            <label class="block text-sm font-medium mb-2">
                Pilih Jam
            </label>

            <div id="slots" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <p class="text-sm text-gray-400 col-span-full">
                    Pilih layanan & tanggal
                </p>
            </div>

            <input type="hidden" name="start_time" id="start_time"
                   value="{{ old('start_time', $booking->start_time) }}">
            <input type="hidden" name="end_time" id="end_time"
                   value="{{ old('end_time', $booking->end_time) }}">
        </div>
    </div>

    {{-- CATATAN --}}
    <div>
        <h2 class="text-lg font-semibold mb-4">Catatan</h2>
        <textarea name="note"
                  rows="3"
                  class="w-full rounded-xl border-gray-200">{{ old('note', $booking->notes) }}</textarea>
    </div>

    {{-- SUBMIT --}}
    <button type="submit"
            class="w-full py-3 rounded-xl bg-green-600 text-white hover:bg-green-700 transition">
        Simpan Perubahan
    </button>

</div>
</form>

{{-- SCRIPT SLOT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const dateInput   = document.getElementById('booking_date');
    const serviceInput = document.getElementById('service_id');
    const slotsBox    = document.getElementById('slots');
    const startInput  = document.getElementById('start_time');
    const endInput    = document.getElementById('end_time');

    async function loadSlots() {
        const date = dateInput.value;
        const serviceId = serviceInput.value;

        slotsBox.innerHTML = '';

        if (!date || !serviceId) {
            slotsBox.innerHTML =
                '<p class="text-sm text-gray-400 col-span-full">Pilih layanan & tanggal</p>';
            return;
        }

        const res = await fetch(
            `/bookings/available-slots?booking_date=${date}&service_id=${serviceId}&booking_id={{ $booking->id }}`,
            { headers: { 'X-Requested-With': 'XMLHttpRequest' } }
        );

        const slots = await res.json();

        if (!slots.length) {
            slotsBox.innerHTML =
                '<p class="text-red-500 col-span-full">Tidak ada slot tersedia</p>';
            return;
        }

        slots.forEach(slot => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = `${slot.start} - ${slot.end}`;
            btn.className =
                'rounded-xl border px-4 py-2 text-sm hover:bg-green-100 transition';

            // Tandai slot lama milik booking ini
            if (slot.start === startInput.value.substring(0,5)) {
                btn.classList.add('bg-green-500', 'text-white');
            }

            btn.onclick = () => {
                document.querySelectorAll('#slots button')
                    .forEach(b => b.classList.remove('bg-green-500', 'text-white'));

                btn.classList.add('bg-green-500', 'text-white');
                startInput.value = slot.start;
                endInput.value = slot.end;
            };

            slotsBox.appendChild(btn);
        });
    }

    dateInput.addEventListener('change', loadSlots);
    serviceInput.addEventListener('change', loadSlots);

    // Load slot saat halaman edit dibuka
    loadSlots();
});
</script>

@endsection
