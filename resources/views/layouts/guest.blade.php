<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>KKV</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            @keyframes floatY { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
            @keyframes twinkle { 0%,100%{opacity:1;transform:scale(1) rotate(0deg)} 50%{opacity:.4;transform:scale(.7) rotate(15deg)} }
            .logo-float { animation: floatY 3.5s ease-in-out infinite; }
            .twinkle-a { animation: twinkle 2s ease-in-out infinite; }
            .twinkle-b { animation: twinkle 2.6s ease-in-out infinite .5s; }
            .twinkle-c { animation: twinkle 1.9s ease-in-out infinite 1s; }
        </style>
    </head>
    <body class="font-sans text-accent antialiased" style="background: radial-gradient(ellipse at top, #fff8d6 0%, #fef3a0 60%, #fde88a 100%); min-height:100vh;">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10 relative overflow-hidden">

            {{-- Soft bg blobs --}}
            <div class="absolute top-[-5%] left-[-5%] w-72 h-72 rounded-full opacity-30 pointer-events-none" style="background:#f5c400; filter:blur(80px);"></div>
            <div class="absolute bottom-[-10%] right-[-5%] w-64 h-64 rounded-full opacity-20 pointer-events-none" style="background:#f5c400; filter:blur(60px);"></div>

            {{-- Floating sparkles --}}
            <span class="twinkle-a absolute top-[12%] left-[15%] text-2xl pointer-events-none select-none">✨</span>
            <span class="twinkle-b absolute top-[20%] right-[12%] text-xl pointer-events-none select-none">⭐</span>
            <span class="twinkle-c absolute bottom-[20%] left-[10%] text-lg pointer-events-none select-none">💛</span>
            <span class="twinkle-a absolute bottom-[15%] right-[18%] text-xl pointer-events-none select-none">✨</span>

            {{-- Logo --}}
            <a href="/" class="flex flex-col items-center mb-4 group cursor-pointer">
                <div class="logo-float">
                    <img src="{{ asset('images/kkv logo.png') }}"
                         alt="KKV Happy Finds"
                         class="w-44 h-44 object-contain drop-shadow-[0_8px_24px_rgba(245,196,0,0.5)] group-hover:scale-105 transition-transform duration-300">
                </div>
                <span class="text-[10px] font-bold tracking-[0.35em] text-accent/50 uppercase mt-2 group-hover:text-accent/70 transition-colors">Cavan Branch</span>
            </a>

            {{-- Form Card --}}
            <div class="w-full sm:max-w-md bg-white border-2 border-accent shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] rounded-2xl px-8 py-8">
                {{ $slot }}
            </div>

            <p class="mt-6 text-xs text-accent/40 font-medium">© {{ date('Y') }} KKV Happy Finds · All rights reserved</p>
        </div>
    </body>
</html>
