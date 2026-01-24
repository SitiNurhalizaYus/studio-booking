@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl p-6">
    <h1 class="text-xl font-semibold mb-6">Tambah Layanan</h1>

    <form method="POST" action="{{ route('services.store') }}" class="space-y-5">
        @csrf

        <div>
            <label class="text-sm font-medium">Nama Layanan</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full rounded-lg border-gray-300 mt-1">
            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-sm font-medium">Harga (Rp)</label>
            <input type="number" name="price" value="{{ old('price') }}"
                   class="w-full rounded-lg border-gray-300 mt-1">
            @error('price') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-sm font-medium">Durasi (Jam)</label>
            <input type="number" name="duration" value="{{ old('duration') }}"
                   class="w-full rounded-lg border-gray-300 mt-1">
            @error('duration') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-sm font-medium">Deskripsi Layanan</label>
            <textarea name="description" rows="6"
                      class="w-full rounded-lg border-gray-300 mt-1"
                      placeholder="Pisahkan informasi dengan baris baru...">{{ old('description') }}</textarea>
            @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('services.index') }}"
               class="px-4 py-2 rounded-lg bg-gray-100">Batal</a>
            <button class="px-4 py-2 rounded-lg bg-stone-700 text-white">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
