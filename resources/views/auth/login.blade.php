<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-cream px-4">
        <div class="w-full max-w-md bg-white rounded-xl shadow-md overflow-hidden">

            <!-- HEADER -->
            <div class="bg-soft-cocoa px-6 py-6 text-center">
                <h1 class="text-xl font-semibold text-espresso">
                    Masuk Admin
                </h1>
                <p class="text-sm text-gray-700 mt-1">
                    Sistem Manajemen Booking Studio Foto
                </p>
            </div>

            <!-- FORM -->
            <div class="px-6 py-6">
                <!-- SESSION STATUS -->
                <x-auth-session-status
                    class="mb-4 text-sm text-green-600"
                    :status="session('status')"
                />

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- EMAIL -->
                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input
                            id="email"
                            class="mt-1 w-full"
                            type="email"
                            name="email"
                            required
                            autofocus
                            placeholder="admin@studio.com"
                        />
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <x-input-label for="password" value="Kata Sandi" />
                        <x-text-input
                            id="password"
                            class="mt-1 w-full"
                            type="password"
                            name="password"
                            required
                            placeholder="Masukkan kata sandi"
                        />
                    </div>

                    <!-- REMEMBER & FORGOT -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center text-gray-600">
                            <input
                                type="checkbox"
                                name="remember"
                                class="rounded border-gray-300 text-cocoa focus:ring-cocoa"
                            >
                            <span class="ml-2">Ingat saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a
                                href="{{ route('password.request') }}"
                                class="text-cocoa font-medium hover:underline"
                            >
                                Lupa kata sandi?
                            </a>
                        @endif
                    </div>

                    <!-- BUTTON -->
                    <button
                        class="w-full mt-4 bg-cocoa hover:bg-cocoa/90 text-white py-2.5 rounded-lg font-medium transition"
                    >
                        Masuk
                    </button>

                    <!-- REGISTER LINK -->
                    <p class="text-sm text-center text-gray-600 mt-4">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-cocoa font-medium hover:underline">
                            Daftar
                        </a>
                    </p>
                </form>
            </div>

        </div>
    </div>
</x-guest-layout>
