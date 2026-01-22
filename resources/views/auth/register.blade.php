<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-cream px-4">
        <div class="w-full max-w-md bg-white rounded-xl shadow-md overflow-hidden">

            <!-- HEADER -->
            <div class="bg-soft-cocoa px-6 py-6 text-center">
                <h1 class="text-xl font-semibold text-espresso">
                    Daftar Admin
                </h1>
                <p class="text-sm text-gray-700 mt-1">
                    Sistem Manajemen Booking Studio Foto
                </p>
            </div>

            <!-- FORM -->
            <div class="px-6 py-6">
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="name" value="Nama Lengkap" />
                        <x-text-input
                            id="name"
                            class="mt-1 w-full"
                            type="text"
                            name="name"
                            required
                            placeholder="Contoh: Siti Aminah"
                        />
                    </div>

                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input
                            id="email"
                            class="mt-1 w-full"
                            type="email"
                            name="email"
                            required
                            placeholder="admin@studio.com"
                        />
                    </div>

                    <div>
                        <x-input-label for="password" value="Kata Sandi" />
                        <x-text-input
                            id="password"
                            class="mt-1 w-full"
                            type="password"
                            name="password"
                            required
                            placeholder="Minimal 8 karakter"
                        />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />
                        <x-text-input
                            id="password_confirmation"
                            class="mt-1 w-full"
                            type="password"
                            name="password_confirmation"
                            required
                            placeholder="Ulangi kata sandi"
                        />
                    </div>

                    <button
                        class="w-full mt-4 bg-cocoa hover:bg-cocoa/90 text-white py-2.5 rounded-lg font-medium transition"
                    >
                        Daftar
                    </button>

                    <p class="text-sm text-center text-gray-600 mt-4">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-cocoa font-medium hover:underline">
                            Masuk
                        </a>
                    </p>
                </form>
            </div>

        </div>
    </div>
</x-guest-layout>
