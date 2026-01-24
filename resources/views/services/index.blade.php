@extends('layouts.app')

@section('content')

{{-- HEADER --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold">Layanan Studio</h1>
        <p class="text-sm text-gray-500">
            Kelola paket dan layanan studio foto
        </p>
    </div>

    <a href="{{ route('services.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
              bg-stone-500 hover:bg-stone-600 text-white text-sm font-medium">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Layanan
    </a>
</div>

{{-- SEARCH --}}
<form method="GET" class="mb-6">
    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Cari layanan atau deskripsi..."
        class="w-full md:w-1/2 rounded-xl border-gray-300 px-4 py-2 text-sm
               focus:ring-stone-300 focus:border-stone-400"
        onkeydown="if(event.key==='Enter'){ this.form.submit(); }"
    >
</form>

{{-- GRID CARD --}}
@if ($services->count())
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach ($services as $service)
        <a href="{{ route('services.show', $service) }}"
           class="block bg-white rounded-xl p-5 border
                  hover:shadow-md hover:border-stone-300 transition">

            <div class="flex items-start justify-between mb-2">
                <h2 class="font-semibold text-gray-800">
                    {{ $service->name }}
                </h2>

                <span class="text-xs px-2 py-1 rounded-full bg-stone-100 text-stone-600">
                    {{ $service->duration }} jam
                </span>
            </div>

            <p class="text-sm text-gray-500 mb-3">
                {{ Str::limit(strip_tags($service->description), 90) }}
            </p>

            <div class="text-xl font-semibold text-gray-800">
                Rp {{ number_format($service->price, 0, ',', '.') }}
            </div>
        </a>
    @endforeach
</div>

{{-- PAGINATION --}}
<div class="mt-8">
    {{ $services->links() }}
</div>

@else
<div class="bg-white rounded-xl p-6 text-center text-gray-500">
    Tidak ada layanan ditemukan.
</div>
@endif

@endsection
