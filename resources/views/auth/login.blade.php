<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Role Access Info -->
    <div class="mb-6 grid grid-cols-3 gap-2 text-center">
        <div class="p-2 rounded-xl border-2 border-accent/20 bg-amber-50">
            <div class="text-xl mb-1">👑</div>
            <div class="text-[10px] font-black text-accent uppercase tracking-wide">Admin</div>
            <div class="text-[9px] text-gray-500 mt-0.5">Full access</div>
        </div>
        <div class="p-2 rounded-xl border-2 border-accent/20 bg-blue-50">
            <div class="text-xl mb-1">🧾</div>
            <div class="text-[10px] font-black text-accent uppercase tracking-wide">Cashier</div>
            <div class="text-[9px] text-gray-500 mt-0.5">POS & Products</div>
        </div>
        <div class="p-2 rounded-xl border-2 border-accent/20 bg-green-50">
            <div class="text-xl mb-1">🛍️</div>
            <div class="text-[10px] font-black text-accent uppercase tracking-wide">Customer</div>
            <div class="text-[9px] text-gray-500 mt-0.5">Order history</div>
        </div>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-2 border-accent text-primary shadow-sm focus:ring-primary" name="remember">
                <span class="ms-2 text-sm text-accent/80 font-medium">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm font-medium text-accent/70 hover:text-accent rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
