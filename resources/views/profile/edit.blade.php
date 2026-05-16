<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ROW 1: Profile Info + Update Password side by side --}}
            <div class="grid grid-cols-2 gap-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- ROW 2: Delete Account full width --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</x-app-layout>