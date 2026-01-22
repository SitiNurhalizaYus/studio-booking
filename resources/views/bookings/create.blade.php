<x-app-layout>
    </div>


    <!-- Card Form -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <form method="POST" action="#" class="space-y-5">
            @csrf


            <!-- Nama Pelanggan -->
            <div>
                <label class="block text-sm font-medium mb-1">Nama Pelanggan</label>
                <input type="text" placeholder="Masukkan nama pelanggan"
                    class="w-full rounded-lg border-gray-300 focus:ring-taupe focus:border-taupe" />
            </div>


            <!-- Layanan -->
            <div>
                <label class="block text-sm font-medium mb-1">Layanan</label>
                <select class="w-full rounded-lg border-gray-300 focus:ring-taupe focus:border-taupe">
                    <option>Pilih layanan</option>
                    <option>Wedding Photography</option>
                    <option>Portrait Session</option>
                    <option>Product Photo</option>
                </select>
            </div>


            <!-- Tanggal & Jam -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal</label>
                    <input type="date"
                        class="w-full rounded-lg border-gray-300 focus:ring-taupe focus:border-taupe" />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Jam</label>
                    <input type="time"
                        class="w-full rounded-lg border-gray-300 focus:ring-taupe focus:border-taupe" />
                </div>
            </div>


            <!-- Paket -->
            <div>
                <label class="block text-sm font-medium mb-1">Paket</label>
                <select class="w-full rounded-lg border-gray-300 focus:ring-taupe focus:border-taupe">
                    <option>Basic</option>
                    <option>Premium</option>
                </select>
            </div>


            <!-- Action -->
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('bookings.index') }}"
                    class="px-4 py-2 rounded-lg border border-gray-300 text-sm hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-taupe text-white text-sm hover:opacity-90">
                    Simpan Booking
                </button>
            </div>
        </form>
    </div>
    </div>
</x-app-layout>
