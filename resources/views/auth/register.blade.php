<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Role Selection -->
        <div class="mt-5">
            <x-input-label :value="__('I am registering as a...')" class="mb-2 font-bold" />
            <div class="grid grid-cols-3 gap-3">

                <!-- Admin -->
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="admin" class="sr-only peer" {{ old('role', 'customer') === 'admin' ? 'checked' : '' }}>
                    <div class="flex flex-col items-center gap-1 p-3 rounded-xl border-2 border-gray-200 text-center transition-all peer-checked:border-accent peer-checked:bg-primary peer-checked:shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] hover:border-accent/50">
                        <span class="text-2xl">👑</span>
                        <span class="text-xs font-black text-accent uppercase tracking-wide">Admin</span>
                        <span class="text-[10px] text-gray-500 leading-tight">Full system access</span>
                    </div>
                </label>

                <!-- Cashier -->
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="cashier" class="sr-only peer" {{ old('role', 'customer') === 'cashier' ? 'checked' : '' }}>
                    <div class="flex flex-col items-center gap-1 p-3 rounded-xl border-2 border-gray-200 text-center transition-all peer-checked:border-accent peer-checked:bg-primary peer-checked:shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] hover:border-accent/50">
                        <span class="text-2xl">🧾</span>
                        <span class="text-xs font-black text-accent uppercase tracking-wide">Cashier</span>
                        <span class="text-[10px] text-gray-500 leading-tight">POS & products</span>
                    </div>
                </label>

                <!-- Customer -->
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="customer" class="sr-only peer" {{ old('role', 'customer') === 'customer' ? 'checked' : '' }}>
                    <div class="flex flex-col items-center gap-1 p-3 rounded-xl border-2 border-gray-200 text-center transition-all peer-checked:border-accent peer-checked:bg-primary peer-checked:shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] hover:border-accent/50">
                        <span class="text-2xl">🛍️</span>
                        <span class="text-xs font-black text-accent uppercase tracking-wide">Customer</span>
                        <span class="text-[10px] text-gray-500 leading-tight">View order history</span>
                    </div>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-5">
            <a class="underline text-sm font-medium text-accent/70 hover:text-accent rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

