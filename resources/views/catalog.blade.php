<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-extrabold text-xl text-accent leading-tight">Products Catalog</h2>
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest bg-green-500 text-white border-2 border-green-700">🛍️ Customer</span>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Welcome banner (CSS only, no images) --}}
        <div class="relative bg-primary border-2 border-accent shadow-[5px_5px_0px_0px_rgba(0,0,0,1)] rounded-2xl p-6 mb-8 overflow-hidden">
            <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-accent/20 pointer-events-none"></div>
            <div class="absolute -bottom-8 left-1/3 w-20 h-20 rounded-full bg-white/20 pointer-events-none"></div>
            <div class="relative">
                <p class="text-[10px] font-bold uppercase tracking-widest text-accent/60 mb-1">KKV Happy Finds ✨</p>
                <h3 class="text-2xl font-black text-accent">Browse our products</h3>
                <p class="text-sm text-accent/70 mt-1">Check availability and prices. Visit us in-store to purchase!</p>
            </div>
        </div>

        {{-- Search --}}
        <div class="mb-6">
            <input type="text" id="catalogSearch" placeholder="🔍  Search products by name..."
                   oninput="filterCatalog(this.value)"
                   class="w-full border-2 border-accent rounded-xl px-5 py-3 font-bold text-accent focus:outline-none focus:ring-2 focus:ring-primary bg-white placeholder:font-normal placeholder:text-gray-400">
        </div>

        {{-- Grid --}}
        <div id="catalogGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @forelse($products as $product)
                <div class="catalog-card bg-white border-2 border-accent shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] rounded-2xl overflow-hidden flex flex-col transition-all hover:-translate-y-1 hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]"
                     data-name="{{ strtolower($product->name) }}" data-barcode="{{ strtolower($product->barcode) }}">

                    {{-- Product card top: colored block with status badge, no image --}}
                    <div class="relative flex items-center justify-center py-8 {{ $product->stock === 0 ? 'bg-gray-100' : 'bg-wood-light' }} border-b-2 border-accent/10">
                        <span class="text-5xl select-none">🧴</span>
                        {{-- Stock badge --}}
                        @if($product->stock === 0)
                            <span class="absolute top-2 right-2 bg-red-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full border border-red-700 uppercase tracking-wide">Out of Stock</span>
                        @elseif($product->stock <= 10)
                            <span class="absolute top-2 right-2 bg-yellow-400 text-accent text-[9px] font-black px-2 py-0.5 rounded-full border border-yellow-500 uppercase tracking-wide">Low Stock</span>
                        @else
                            <span class="absolute top-2 right-2 bg-green-100 text-green-700 text-[9px] font-black px-2 py-0.5 rounded-full border border-green-300 uppercase tracking-wide">✓ Available</span>
                        @endif
                    </div>

                    <div class="p-4 flex flex-col flex-1">
                        <div class="font-black text-accent text-sm leading-tight">{{ $product->name }}</div>
                        <div class="text-[10px] font-mono text-gray-400 mt-0.5 mb-3">{{ $product->barcode }}</div>
                        <div class="mt-auto flex items-end justify-between">
                            <div class="font-black text-xl text-accent">₱{{ number_format($product->price, 2) }}</div>
                            <div class="text-[10px] {{ $product->stock === 0 ? 'text-red-400' : 'text-gray-400' }} font-medium">
                                {{ $product->stock > 0 ? $product->stock . ' in stock' : 'Unavailable' }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16 text-gray-400 font-medium">No products available yet.</div>
            @endforelse
        </div>

        <div id="noResults" class="hidden text-center py-16 text-gray-400 font-medium">
            <div class="text-4xl mb-3">🔍</div>
            No products match your search.
        </div>
    </div>

    <script>
        function filterCatalog(query) {
            const q = query.toLowerCase().trim();
            const cards = document.querySelectorAll('.catalog-card');
            let visible = 0;
            cards.forEach(card => {
                const match = !q || card.dataset.name.includes(q) || card.dataset.barcode.includes(q);
                card.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            document.getElementById('noResults').classList.toggle('hidden', visible > 0 || !q);
        }
    </script>
</x-app-layout>
