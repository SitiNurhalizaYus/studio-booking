@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl p-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ $service->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">
                Rp {{ number_format($service->price,0,',','.') }} • {{ $service->duration }} jam
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('services.edit', $service) }}"
               class="px-3 py-2 rounded-lg bg-yellow-100 text-yellow-700 text-sm">
                ✏ Edit
            </a>

            <form method="POST" action="{{ route('services.destroy', $service) }}"
                  onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
                @csrf
                @method('DELETE')
                <button class="px-3 py-2 rounded-lg bg-red-100 text-red-700 text-sm">
                    🗑 Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="border-t pt-4">
        <h2 class="font-semibold mb-3">Deskripsi Layanan</h2>

        <ul class="list-disc pl-6 space-y-1 text-sm text-gray-700">
            @foreach(explode("\n", $service->description) as $line)
                @if(trim($line) !== '')
                    <li>{{ $line }}</li>
                @endif
            @endforeach
        </ul>
    </div>
</div>
@endsection
