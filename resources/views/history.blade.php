<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customer History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border-2 border-accent shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] rounded-xl overflow-hidden p-6">
                <h3 class="text-xl font-bold text-accent mb-6 border-b-2 border-accent pb-2">Your Past Purchases</h3>
                
                <div class="space-y-4">
                    @forelse($transactions as $tx)
                        <div class="border-2 border-accent rounded-xl p-4 flex justify-between items-center hover:bg-wood-light transition-colors">
                            <div>
                                <div class="font-bold text-lg text-accent">Invoice #{{ $tx->invoice_no }}</div>
                                <div class="text-sm text-gray-600">{{ $tx->created_at->format('M d, Y H:i A') }}</div>
                                <div class="mt-2 text-sm text-gray-700">
                                    {{ $tx->items->count() }} items purchased
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-black text-xl text-accent">₱{{ number_format($tx->total_amount, 2) }}</div>
                                <a href="{{ route('invoice.show', $tx) }}" class="inline-block mt-2 bg-primary border border-accent text-accent font-bold px-4 py-1 rounded shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] text-sm">View Receipt</a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500 font-bold">
                            You have no purchase history yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
