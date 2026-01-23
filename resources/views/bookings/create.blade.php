@extends('layouts.app')

@section('content')
    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-red-700">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">
                Tambah Booking Baru
            </h1>
            <p class="text-sm text-gray-500">
                Lengkapi form untuk membuat booking
            </p>
        </div>

        <a href="{{ session('back_url', route('bookings.index')) }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
          bg-white hover:bg-gray-100 text-sm font-medium">
                <x-heroicon-o-arrow-left class="w-4 h-4" />
                Kembali
            </a>

    </div>

    <form id="bookingForm" method="POST" action="{{ route('bookings.store') }}">
        @csrf

        <div class="bg-white rounded-2xl shadow-sm p-8 space-y-8">

            {{-- ================= INFORMASI PELANGGAN ================= --}}
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    Informasi Pelanggan
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="name" required placeholder="Nama pelanggan"
                        class="w-full rounded-xl border-gray-200 focus:ring focus:ring-green-200">

                    <input type="text" name="phone" required placeholder="08xxxxxxxxxx"
                        class="w-full rounded-xl border-gray-200 focus:ring focus:ring-green-200">
                </div>
            </div>

            {{-- ================= DETAIL BOOKING ================= --}}
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    Detail Booking
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <select name="service_id" id="service_id" required
                        class="w-full rounded-xl border-gray-200 focus:ring focus:ring-green-200">
                        <option value="">Pilih layanan</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}">
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>

                    <input type="date" name="booking_date" id="booking_date" min="{{ date('Y-m-d') }}" required
                        class="w-full rounded-xl border-gray-200 focus:ring focus:ring-green-200">
                </div>

                {{-- SLOT JAM --}}
                <div class="mt-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Jam
                    </label>

                    <div id="slots" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <p class="text-sm text-gray-400 col-span-full">
                            Pilih layanan & tanggal terlebih dahulu
                        </p>
                    </div>

                    <input type="hidden" name="start_time" id="start_time" required>
                    <input type="hidden" name="end_time" id="end_time" required>
                </div>
            </div>

            {{-- ================= CATATAN ================= --}}
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    Catatan
                </h2>

                <textarea name="note" rows="3" placeholder="Catatan tambahan (opsional)"
                    class="w-full rounded-xl border-gray-200 focus:ring focus:ring-green-200"></textarea>
            </div>

            {{-- ================= SUBMIT ================= --}}
            <button id="submitBtn" disabled
                class="w-full py-3 rounded-xl bg-gray-300 text-gray-500 cursor-not-allowed transition">
                Simpan Booking
            </button>

        </div>
    </form>

    {{-- ================= SCRIPT ================= --}}
    <script>
        const dateInput = document.getElementById('booking_date');
        const serviceInput = document.getElementById('service_id');
        const slotsBox = document.getElementById('slots');
        const startInput = document.getElementById('start_time');
        const endInput = document.getElementById('end_time');
        const form = document.getElementById('bookingForm');
        const submitBtn = document.getElementById('submitBtn');

        async function loadSlots() {
            const date = dateInput.value;
            const serviceId = serviceInput.value;

            if (!date || !serviceId) {
                slotsBox.innerHTML =
                    '<p class="text-sm text-gray-400 col-span-full">Pilih layanan & tanggal</p>';
                return;
            }

            const res = await fetch(
                `/bookings/available-slots?booking_date=${date}&service_id=${serviceId}`
            );
            const slots = await res.json();

            slotsBox.innerHTML = '';
            startInput.value = '';
            endInput.value = '';

            if (slots.length === 0) {
                slotsBox.innerHTML =
                    '<p class="text-red-500 col-span-full">Tidak ada slot tersedia</p>';
                return;
            }

            slots.forEach(slot => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className =
                    'rounded-xl border px-4 py-2 text-sm hover:bg-green-100 transition';

                btn.textContent = `${slot.start} - ${slot.end}`;

                btn.onclick = () => {
                    document
                        .querySelectorAll('#slots button')
                        .forEach(b => b.classList.remove('bg-green-500', 'text-white'));

                    btn.classList.add('bg-green-500', 'text-white');

                    startInput.value = slot.start;
                    endInput.value = slot.end;
                    validateForm();
                };

                slotsBox.appendChild(btn);
            });
        }

        function validateForm() {
            const valid =
                form.name.value &&
                form.phone.value &&
                serviceInput.value &&
                dateInput.value &&
                startInput.value;

            submitBtn.disabled = !valid;
            submitBtn.className = valid ?
                'w-full py-3 rounded-xl bg-green-600 text-white hover:bg-green-700 transition' :
                'w-full py-3 rounded-xl bg-gray-300 text-gray-500 cursor-not-allowed transition';
        }

        dateInput.addEventListener('change', loadSlots);
        serviceInput.addEventListener('change', loadSlots);
        form.addEventListener('input', validateForm);
    </script>
@endsection
