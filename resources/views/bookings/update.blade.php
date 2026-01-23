@extends('layouts.app')

@section('content')

    @if ($errors->any())
        <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4">
            <ul class="text-sm text-red-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-5xl mx-auto pb-24">

        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">
                    Edit Booking
                </h1>
                <p class="text-sm text-gray-500">
                    Perbarui data booking studio foto
                </p>
            </div>

            <a href="{{ session('back_url', route('bookings.index')) }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
          bg-white hover:bg-gray-100 text-sm font-medium">
                <x-heroicon-o-arrow-left class="w-4 h-4" />
                Kembali
            </a>

        </div>

        <form method="POST" action="{{ route('bookings.update', $booking->id) }}" onsubmit="return confirmUpdate()">

            @csrf
            @method('PUT')
            @if (in_array($booking->status, ['completed', 'cancelled']))
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                    <p class="text-sm text-yellow-700">
                        Booking ini sudah {{ $booking->status }} dan tidak dapat diedit.
                    </p>
                </div>
            @endif


            {{-- INFORMASI PELANGGAN --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    Informasi Pelanggan
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="name" value="{{ $booking->customer->name }}" required
                        class="w-full rounded-xl border-gray-200 focus:ring focus:ring-green-200">

                    <input type="text" name="phone" value="{{ $booking->customer->phone }}" required
                        class="w-full rounded-xl border-gray-200 focus:ring focus:ring-green-200">
                </div>
            </div>

            {{-- DETAIL BOOKING --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    Detail Booking
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <select name="service_id" id="service_id"
                        class="rounded-xl border-gray-200 focus:ring focus:ring-green-200">
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}"
                                {{ $booking->service_id == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>

                    <input type="date" name="booking_date" id="booking_date" value="{{ $booking->booking_date }}"
                        min="{{ date('Y-m-d') }}" class="rounded-xl border-gray-200 focus:ring focus:ring-green-200">
                </div>

                {{-- SLOT JAM --}}
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Pilih Jam
                    <div id="slot-loading" class="hidden text-sm text-gray-500 mt-2">
                        Memuat slot waktu...
                    </div>

                    <div id="slots" class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4"></div>
                </label>

                <div id="slotBox" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">

                </div>

                <input type="hidden" name="start_time" id="start_time">
                <input type="hidden" name="end_time" id="end_time">
                <input type="hidden" id="old_name" value="{{ $booking->customer->name }}">
                <input type="hidden" id="old_phone" value="{{ $booking->customer->phone }}">
                <input type="hidden" id="old_service" value="{{ $booking->service_id }}">
                <input type="hidden" id="old_date" value="{{ $booking->booking_date }}">
                <input type="hidden" id="old_start" value="{{ substr($booking->start_time, 0, 5) }}">
                <input type="hidden" id="old_end" value="{{ substr($booking->end_time, 0, 5) }}">

                @error('booking_time')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- CATATAN --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    Catatan
                </h2>

                <textarea name="notes" rows="3" class="w-full rounded-xl border-gray-200 focus:ring focus:ring-green-200"
                    placeholder="Catatan tambahan (opsional)">{{ $booking->notes }}</textarea>
            </div>

            {{-- SUBMIT --}}
            <button id="saveBtn" type="submit"
                class="w-full py-3 rounded-xl bg-gray-300 text-gray-500 cursor-not-allowed" disabled>
                Simpan Perubahan
            </button>


        </form>
    </div>

    {{-- SCRIPT SLOT JAM --}}
    <script>
        const bookingId = {{ $booking->id }};
        const dateInput = document.getElementById('booking_date');
        const serviceInput = document.getElementById('service_id');
        const slotBox = document.getElementById('slotBox');
        const startInput = document.getElementById('start_time');
        const endInput = document.getElementById('end_time');

        const currentStart = "{{ substr($booking->start_time, 0, 5) }}";
        const currentEnd = "{{ substr($booking->end_time, 0, 5) }}";

        async function loadSlots() {
            if (!dateInput.value || !serviceInput.value) return;

            slotBox.innerHTML = '';
            document.getElementById('slot-loading').classList.remove('hidden');

            const res = await fetch(
                `/bookings/available-slots?booking_date=${dateInput.value}&service_id=${serviceInput.value}&booking_id=${bookingId}`
            );

            const slots = await res.json();

            document.getElementById('slot-loading').classList.add('hidden');

            if (slots.length === 0) {
                slotBox.innerHTML =
                    `<p class="col-span-full text-sm text-red-500">
                Tidak ada slot tersedia
            </p>`;
                startInput.value = '';
                endInput.value = '';
                updateSaveButton();
                return;
            }

            slots.forEach(slot => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = `${slot.start} - ${slot.end}`;
                btn.className = 'slot-btn px-4 py-2 rounded-xl border text-sm';

                if (
                    slot.start === currentStart &&
                    slot.end === currentEnd &&
                    dateInput.value === document.getElementById('old_date').value &&
                    serviceInput.value === document.getElementById('old_service').value
                ) {
                    btn.classList.add('bg-green-200', 'border-green-500');
                    startInput.value = slot.start;
                    endInput.value = slot.end;
                }


                btn.onclick = () => {
                    document.querySelectorAll('.slot-btn')
                        .forEach(b => b.classList.remove('bg-green-200', 'border-green-500'));

                    btn.classList.add('bg-green-200', 'border-green-500');
                    startInput.value = slot.start;
                    endInput.value = slot.end;
                    updateSaveButton();
                };

                slotBox.appendChild(btn);
            });
        }


        loadSlots();
        dateInput.addEventListener('change', () => {
            resetSelectedTime();
            loadSlots();
        });

        serviceInput.addEventListener('change', () => {
            resetSelectedTime();
            loadSlots();
        });


        function confirmUpdate() {
            if (!isChanged()) return false;

            return confirm(
                'Simpan perubahan booking?\n\nPerubahan jadwal akan mempengaruhi jadwal studio.'
            );
        }


        const saveBtn = document.getElementById('saveBtn');

        function isFormComplete() {
            return (
                document.querySelector('[name="name"]').value.trim() !== '' &&
                document.querySelector('[name="phone"]').value.trim() !== '' &&
                serviceInput.value &&
                dateInput.value &&
                startInput.value &&
                endInput.value
            );
        }

        function isChanged() {
            return (
                document.querySelector('[name="name"]').value !== document.getElementById('old_name').value ||
                document.querySelector('[name="phone"]').value !== document.getElementById('old_phone').value ||
                serviceInput.value !== document.getElementById('old_service').value ||
                dateInput.value !== document.getElementById('old_date').value ||
                startInput.value !== document.getElementById('old_start').value ||
                endInput.value !== document.getElementById('old_end').value
            );
        }

        function updateSaveButton() {
            if (isFormComplete() && isChanged()) {
                saveBtn.disabled = false;
                saveBtn.className =
                    'w-full py-3 rounded-xl bg-green-600 text-white hover:bg-green-700';
            } else {
                saveBtn.disabled = true;
                saveBtn.className =
                    'w-full py-3 rounded-xl bg-gray-300 text-gray-500 cursor-not-allowed';
            }
        }

        document.querySelectorAll('input, select, textarea')
            .forEach(el => el.addEventListener('input', updateSaveButton));

        updateSaveButton();

        function resetSelectedTime() {
            startInput.value = '';
            endInput.value = '';

            document.querySelectorAll('.slot-btn')
                .forEach(b => b.classList.remove('bg-green-200', 'border-green-500'));

            updateSaveButton();
        }
    </script>

@endsection
