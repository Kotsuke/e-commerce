<x-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-zinc-900 px-4">
        <div class="w-full max-w-md bg-white dark:bg-zinc-800 rounded-xl shadow p-6 space-y-6">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-zinc-800 dark:text-white">Masuk ke Akun Anda</h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Silakan login menggunakan email dan password Anda</p>
            </div>

            {{-- Flash Messages --}}
            @if(session('errorMessage'))
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded text-sm">
                    {{ session('errorMessage') }}
                </div>
            @endif

            @if(session('successMessage'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded text-sm">
                    {{ session('successMessage') }}
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('customer.login') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email"
                        value="{{ old('email') }}"
                        required 
                        autofocus
                        class="mt-1 w-full rounded-lg border-zinc-300 dark:border-zinc-700 dark:bg-zinc-700 dark:text-white focus:ring-pink-500 focus:border-pink-500"
                    >
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password"
                        required
                        class="mt-1 w-full rounded-lg border-zinc-300 dark:border-zinc-700 dark:bg-zinc-700 dark:text-white focus:ring-pink-500 focus:border-pink-500"
                    >
                    @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="rounded border-zinc-300 dark:border-zinc-700 dark:bg-zinc-700 dark:text-white">
                    <label for="remember" class="ml-2 text-sm text-zinc-600 dark:text-zinc-400">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <div>
                    <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white py-2 px-4 rounded-lg font-semibold">
                        Masuk
                    </button>
                </div>
            </form>

            {{-- Link ke Register --}}
            <div class="text-center text-sm text-zinc-600 dark:text-zinc-400">
                Belum punya akun?
                <a href="{{ route('customer.register') }}" class="text-pink-600 hover:underline">Daftar di sini</a>
            </div>
        </div>
    </div>
</x-layout>
