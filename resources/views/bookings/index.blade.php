@extends('layouts.app')

@section('content')
<div class="space-y-10">

    {{-- JUDUL HALAMAN --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Daftar Booking</h1>
            <p class="text-sm text-gray-600">
                Kelola semua booking studio foto
            </p>
        </div>

        <a href="{{ route('bookings.create') }}" class="px-4 py-2 rounded-lg bg-taupe text-white text-sm">
            + Tambah Booking
        </a>
    </div>

    {{-- CARD 1: BOOKING AKTIF --}}
<div class="bg-white rounded-xl p-6 mb-8">
    <h2 class="text-lg font-semibold mb-4">
        Booking Aktif
    </h2>

    @include('bookings.partials.table', [
        'bookings' => $activeBookings
    ])
</div>

{{-- CARD 2: BOOKING SELESAI --}}
<div class="bg-white rounded-xl p-6">
    <h2 class="text-lg font-semibold mb-4">
        Booking Selesai
    </h2>

    @include('bookings.partials.table', [
        'bookings' => $completedBookings
    ])
</div>


@endsection
