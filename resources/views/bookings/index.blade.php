@extends('layouts.app')

@section('content')
@if (session('success'))
    <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 text-green-700">
        {{ session('success') }}
    </div>
@endif

@if ($errors->has('delete'))
    <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-red-700">
        {{ $errors->first('delete') }}
    </div>
@endif

<div class="space-y-10">

    {{-- JUDUL HALAMAN --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Daftar Booking</h1>
            <p class="text-sm text-gray-600">
                Kelola semua booking studio foto
            </p>
        </div>

        <a href="{{ route('bookings.create') }}" class="px-4 py-2 rounded-lg bg-stone-500 text-white text-sm">
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
