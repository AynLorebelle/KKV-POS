<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="font-extrabold text-xl text-accent leading-tight">My Dashboard</h2>
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest bg-green-500 text-white border-2 border-green-700">🛍️ Customer</span>
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
                <h3 class="text-2xl font-black text-accent">Your Purchase History</h3>
                <p class="text-sm text-accent/70 mt-1">
                    @if($transactions->count() > 0)
                        You have <strong>{{ $transactions->count() }}</strong> {{ Str::plural('order', $transactions->count()) }} on record.
                    @else
                        No purchases yet — visit us in-store to start shopping!
                    @endif
                </p>
            </div>
        </div>

        {{-- Transactions --}}
        <div class="space-y-4">
            @forelse($transactions as $tx)
                <div class="bg-white border-2 border-accent shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] rounded-2xl overflow-hidden transition-all hover:-translate-y-0.5 hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]">
                    {{-- Header --}}
                    <div class="flex items-center justify-between px-6 py-4 bg-wood-light border-b-2 border-accent/10">
                        <div>
                            <div class="font-black text-accent text-sm">Invoice #{{ $tx->invoice_no }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $tx->created_at->format('M d, Y · h:i A') }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-black text-xl text-accent">₱{{ number_format($tx->total_amount, 2) }}</div>
                            <div class="text-xs text-gray-400">{{ $tx->items->count() }} {{ Str::plural('item', $tx->items->count()) }}</div>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div class="px-6 py-4 space-y-2">
                        @foreach($tx->items as $item)
                            <div class="flex items-center justify-between text-sm">
                                <div>
                                    <span class="font-bold text-accent">{{ $item->product->name ?? 'Item' }}</span>
                                    <span class="text-xs text-gray-400 ml-2">₱{{ number_format($item->price, 2) }} × {{ $item->qty }}</span>
                                </div>
                                <span class="font-black text-accent">₱{{ number_format($item->amount, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 pb-4">
                        <a href="{{ route('invoice.show', $tx) }}"
                           class="inline-flex items-center gap-2 bg-primary border-2 border-accent text-accent font-bold px-4 py-2 rounded-xl shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-none transition-all text-sm">
                             View Receipt
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white border-2 border-accent shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] rounded-2xl p-12 text-center">
                    <div class="text-6xl mb-4"> </div>
                    <p class="font-black text-accent text-lg">No purchases yet!</p>
                    <p class="text-sm text-gray-400 mt-2 mb-5">Browse our catalog to see what's available in store.</p>
                    <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 bg-primary border-2 border-accent text-accent font-bold px-5 py-2.5 rounded-xl shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-none transition-all text-sm">
                         Browse Products
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
