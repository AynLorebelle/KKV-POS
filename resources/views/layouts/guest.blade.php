<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-accent antialiased bg-wood">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
            <!-- Background decoration -->
            <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-primary rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
            <div class="absolute bottom-[-20%] right-[-10%] w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl opacity-50"></div>

            <div class="relative z-10 flex flex-col items-center">
                <a href="/" class="flex flex-col items-center gap-2 mb-8 group">
                    <img src="{{ asset('images/logo.png') }}" alt="KKV Logo" class="w-24 h-24 object-contain group-hover:scale-105 transition-transform drop-shadow-lg">
                    <span class="font-bold text-2xl tracking-tight text-accent group-hover:text-accent/80 transition-colors">CAVAN BRANCH</span>
                </a>
            </div>

            <div class="relative z-10 w-full sm:max-w-md mt-6 px-8 py-10 bg-white border-2 border-accent shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
