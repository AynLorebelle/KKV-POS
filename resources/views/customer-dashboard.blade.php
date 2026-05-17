<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="font-extrabold text-xl text-accent leading-tight">My Dashboard</h2>
                
            </div>
            <span class="text-sm font-medium text-accent/50 font-mono hidden sm:block">{{ now()->format('D, d M Y') }}</span>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Welcome card (no image, pure CSS) --}}
        <div class="relative bg-primary border-2 border-accent shadow-[5px_5px_0px_0px_rgba(0,0,0,1)] rounded-2xl p-6 overflow-hidden">
            {{-- Decorative circles --}}
            <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-accent/20 pointer-events-none"></div>
            <div class="absolute -bottom-8 -left-4 w-24 h-24 rounded-full bg-white/20 pointer-events-none"></div>
            <div class="relative">
                <p class="text-[10px] font-bold uppercase tracking-widest text-accent/60 mb-1">Hello, {{ auth()->user()->name }}! ✨</p>
                <h3 class="text-2xl font-black text-accent">Trending Products</h3>
                <p class="text-sm text-accent/70 mt-1">
                    Check out the top 5 best-selling products everyone is buying!
                </p>
            </div>
        </div>

        {{-- Trending Products --}}
        <div class="space-y-4">
            @forelse($trendingProducts as $index => $item)
                @php $product = $item->product; @endphp
                @if($product)
                <div class="bg-white border-2 border-accent shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] rounded-2xl overflow-hidden flex items-center p-4 transition-all hover:-translate-y-0.5 hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] relative">
                    
                    {{-- Rank Badge --}}
                    <div class="absolute top-0 left-0 bg-accent text-white font-black text-xs px-3 py-1 rounded-br-xl shadow-[2px_2px_0px_0px_rgba(0,0,0,0.5)] z-10">
                        #{{ $index + 1 }}
                    </div>

                    {{-- Image / Icon Placeholder --}}
                    <div class="w-20 h-20 bg-wood-light border-2 border-accent/20 rounded-xl flex items-center justify-center shrink-0 ml-2 shadow-inner overflow-hidden relative group">
                        <span class="text-3xl opacity-50 group-hover:scale-110 transition-transform duration-300">🛍️</span>
                        {{-- Fallback 'image' style representation --}}
                        <div class="absolute inset-0 bg-gradient-to-tr from-accent/5 to-transparent pointer-events-none"></div>
                    </div>

                    {{-- Product Info --}}
                    <div class="ml-5 flex-1">
                        <div class="font-black text-lg text-accent leading-tight">{{ $product->name }}</div>
                        <div class="text-xs text-gray-500 font-mono mt-1">{{ $product->barcode }}</div>
                    </div>

                    {{-- Price & Sales --}}
                    <div class="text-right flex flex-col justify-center items-end ml-4 border-l-2 border-accent/10 pl-5">
                        <div class="font-black text-xl text-accent">₱{{ number_format($product->price, 2) }}</div>
                        <div class="text-[10px] font-bold text-green-700 bg-green-100 border border-green-300 px-2.5 py-0.5 rounded-full mt-2 inline-flex items-center gap-1 shadow-sm uppercase tracking-wider">
                            🔥 {{ number_format($item->total_qty) }} sold
                        </div>
                    </div>

                </div>
                @endif
            @empty
                <div class="bg-white border-2 border-accent shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] rounded-2xl p-12 text-center">
                    <div class="text-6xl mb-4">🛒</div>
                    <p class="font-black text-accent text-lg">No trending products yet!</p>
                    <p class="text-sm text-gray-400 mt-2 mb-5">Be the first to make a purchase and start the trend.</p>
                    <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 bg-primary border-2 border-accent text-accent font-bold px-5 py-2.5 rounded-xl shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-none transition-all text-sm">
                         Browse Catalog
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
