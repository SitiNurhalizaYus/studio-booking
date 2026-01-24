<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $services = Service::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        })
            ->orderBy('created_at', 'desc')
            ->paginate(8)
            ->withQueryString();

        return view('services.index', compact('services'));
    }


    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name'        => 'required|string|max:255',
                'price'       => 'required|numeric|min:0',
                'duration'    => 'required|integer|min:1',
                'description' => 'required|string|min:10',
            ],
            [
                'name.required'        => 'Nama layanan wajib diisi.',
                'price.required'       => 'Harga layanan wajib diisi.',
                'price.numeric'        => 'Harga harus berupa angka.',
                'duration.required'    => 'Durasi layanan wajib diisi.',
                'duration.integer'     => 'Durasi harus berupa angka (jam).',
                'description.required' => 'Deskripsi layanan wajib diisi.',
                'description.min'      => 'Deskripsi minimal 10 karakter.',
            ]
        );

        Service::create($validated);

        return redirect()
            ->route('services.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate(
            [
                'name'        => 'required|string|max:255',
                'price'       => 'required|numeric|min:0',
                'duration'    => 'required|integer|min:1',
                'description' => 'required|string|min:10',
            ],
            [
                'name.required'        => 'Nama layanan wajib diisi.',
                'price.required'       => 'Harga layanan wajib diisi.',
                'price.numeric'        => 'Harga harus berupa angka.',
                'duration.required'    => 'Durasi layanan wajib diisi.',
                'duration.integer'     => 'Durasi harus berupa angka (jam).',
                'description.required' => 'Deskripsi layanan wajib diisi.',
                'description.min'      => 'Deskripsi minimal 10 karakter.',
            ]
        );

        $service->update($validated);

        return redirect()
            ->route('services.show', $service)
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }
}
