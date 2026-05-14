<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="font-extrabold text-xl text-accent leading-tight tracking-tight">
                    Welcome to KKV !!!
                </h2>
                {{-- Role Badge --}}
                @php $role = auth()->user()->role; @endphp
                @if($role === 'admin')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest bg-accent text-white shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] border-2 border-accent">
                        👑 Admin
                    </span>
                @elseif($role === 'cashier')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest bg-blue-500 text-white shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] border-2 border-blue-700">
                        🧾 Cashier
                    </span>
                @elseif($role === 'customer')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest bg-green-500 text-white shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] border-2 border-green-700">
                        🛍️ Customer
                    </span>
                @endif
            </div>
            <span class="text-sm font-medium text-accent/60 font-mono">{{ now()->format('D, d M Y') }}</span>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8" x-data="dashboard()">

        {{-- ── SUMMARY CARDS ── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Today's Sales --}}
            <div class="relative bg-primary border-2 border-accent shadow-[5px_5px_0px_0px_rgba(0,0,0,1)] rounded-xl p-5 overflow-hidden">
                <div class="absolute -top-4 -right-4 text-[72px] font-black opacity-10 select-none leading-none">₱</div>
                <p class="text-xs font-bold uppercase tracking-widest text-accent/70 mb-1">Today's Sales</p>
                <p class="text-3xl font-black text-accent leading-tight">₱{{ number_format($todaySales, 2) }}</p>
                <p class="text-xs text-accent/60 mt-2 font-medium">{{ now()->format('M d, Y') }}</p>
            </div>

            {{-- Total Revenue --}}
            <div class="relative bg-white border-2 border-accent shadow-[5px_5px_0px_0px_rgba(0,0,0,1)] rounded-xl p-5 overflow-hidden">
                <div class="absolute -top-4 -right-4 text-[72px] font-black opacity-5 select-none leading-none">💰</div>
                <p class="text-xs font-bold uppercase tracking-widest text-accent/70 mb-1">Total Revenue</p>
                <p class="text-3xl font-black text-accent leading-tight">₱{{ number_format($totalRevenue, 2) }}</p>
                <p class="text-xs text-accent/60 mt-2 font-medium">All time</p>
            </div>

            {{-- Total Transactions --}}
            <div class="relative bg-accent border-2 border-accent shadow-[5px_5px_0px_0px_rgba(245,196,0,1)] rounded-xl p-5 overflow-hidden">
                <div class="absolute -top-4 -right-4 text-[72px] font-black opacity-10 select-none leading-none text-white">Σ</div>
                <p class="text-xs font-bold uppercase tracking-widest text-white/70 mb-1">Transactions</p>
                <p class="text-3xl font-black text-white leading-tight">{{ number_format($totalTx) }}</p>
                <p class="text-xs text-white/50 mt-2 font-medium">Total invoices issued</p>
            </div>

            {{-- Low Stock --}}
            <div class="relative bg-white border-2 border-red-500 shadow-[5px_5px_0px_0px_rgba(239,68,68,1)] rounded-xl p-5 overflow-hidden">
                <div class="absolute -top-4 -right-4 text-[72px] font-black opacity-5 select-none leading-none text-red-500">⚠</div>
                <p class="text-xs font-bold uppercase tracking-widest text-red-500/70 mb-1">Low Stock Alerts</p>
                <p class="text-3xl font-black text-red-600 leading-tight">{{ $lowStock }}</p>
                <p class="text-xs text-red-500/60 mt-2 font-medium">Products under 10 stock</p>
            </div>
        </div>

        {{-- ── QUICK ACTIONS ── --}}
        <div class="bg-wood-light border-2 border-accent shadow-[5px_5px_0px_0px_rgba(0,0,0,1)] rounded-xl p-6">
            <h3 class="text-sm font-bold uppercase tracking-widest text-accent/70 mb-4">Quick Actions</h3>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('pos.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-primary border-2 border-accent font-bold text-accent text-sm rounded-lg shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all">
                    New Sale
                </a>
                <a href="{{ route('products.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-white border-2 border-accent font-bold text-accent text-sm rounded-lg shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all">
                    Add Product
                </a>
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-white border-2 border-accent font-bold text-accent text-sm rounded-lg shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all">
                    Manage Products
                </a>
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('reports') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-accent text-white border-2 border-accent font-bold text-sm rounded-lg shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all">
                    Sales Reports
                </a>
                @endif
            </div>
        </div>


        {{-- ── CHART + RECENT TX ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- Sales Chart (3/5 width) --}}
            <div class="lg:col-span-3 bg-white border-2 border-accent shadow-[5px_5px_0px_0px_rgba(0,0,0,1)] rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-accent/60">Sales Overview</p>
                        <h3 class="text-lg font-black text-accent">Last 7 Days</h3>
                    </div>
                    <span class="text-xs font-mono bg-primary/20 border border-accent/20 text-accent px-3 py-1 rounded-full">Daily</span>
                </div>

                {{-- Chart bars --}}
                @php
                    $maxVal = $chartData->max('total') ?: 1;
                @endphp
                <div class="flex items-end justify-between gap-2 h-48">
                    @foreach ($chartData as $point)
                        @php $pct = ($point['total'] / $maxVal) * 100; @endphp
                        <div class="flex-1 flex flex-col items-center gap-1 group">
                            <span class="text-xs font-bold text-accent opacity-0 group-hover:opacity-100 transition-opacity font-mono">
                                ₱{{ number_format($point['total'], 0) }}
                            </span>
                            <div class="w-full relative rounded-t-sm overflow-hidden bg-wood border border-accent/20" style="height: 160px;">
                                <div class="absolute bottom-0 left-0 right-0 bg-primary border-t-2 border-accent transition-all duration-700"
                                     style="height: {{ max($pct, 2) }}%;"
                                     title="₱{{ number_format($point['total'], 2) }}">
                                </div>
                            </div>
                            <span class="text-xs font-bold text-accent/60">{{ $point['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- RECENT TRANSACTIONS (2/5 width) --}}
            <div class="lg:col-span-2 bg-white border-2 border-accent shadow-[5px_5px_0px_0px_rgba(0,0,0,1)] rounded-xl overflow-hidden flex flex-col">
                <div class="flex items-center justify-between px-6 py-4 border-b-2 border-accent/10 bg-wood-light">
                    <h3 class="text-lg font-black text-accent">Recent Sales</h3>
                </div>
                <div class="flex-1 overflow-y-auto p-4 space-y-3">
                    @forelse($recentTransactions as $tx)
                        <div class="flex justify-between items-center p-3 border-2 border-transparent hover:border-accent hover:bg-wood-light rounded-xl transition-all cursor-default">
                            <div>
                                <div class="font-bold text-accent">#{{ $tx->invoice_no }}</div>
                                <div class="text-xs text-gray-500">{{ $tx->created_at->format('M d, Y h:i A') }} • {{ $tx->items->count() }} items</div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-accent">₱{{ number_format($tx->total_amount, 2) }}</div>
                                <a href="{{ route('invoice.show', $tx) }}" class="text-xs text-primary hover:text-accent font-bold underline">View Receipt</a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500 text-sm">No recent transactions.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ── RESTOCK MODAL ── --}}
        <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" x-transition.opacity>
            <div @click.away="closeModal()" class="bg-white border-2 border-accent shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] rounded-2xl p-6 w-96 transform transition-all" x-transition>
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-black text-accent">Quick Restock</h3>
                    <button @click="closeModal()" class="text-gray-400 hover:text-red-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <p class="text-sm text-gray-600 mb-6 border-b border-accent/10 pb-4">
                    Add stock for <br><span class="font-bold text-accent text-lg" x-text="selectedProduct?.name"></span>
                </p>
                
                <div class="mb-6">
                    <label class="block text-accent text-xs font-bold uppercase tracking-widest mb-2">Quantity to Add</label>
                    <input type="number" x-model.number="restockQty" min="1" class="shadow-inner appearance-none border-2 border-accent rounded-lg w-full py-3 px-4 text-accent font-bold text-lg leading-tight focus:outline-none focus:ring-2 focus:ring-primary transition-all">
                </div>

                <button @click="submitRestock()" :disabled="isRestocking" class="w-full bg-primary border-2 border-accent text-accent font-black text-lg py-3 rounded-xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1 hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] flex justify-center items-center gap-2">
                    <svg x-show="!isRestocking" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    <span x-show="!isRestocking">Add Stock</span>
                    <span x-show="isRestocking">Saving...</span>
                </button>
            </div>
        </div>
        
        {{-- ── TOAST NOTIFICATION ── --}}
        <div x-show="toast.show" style="display: none;" x-transition.opacity.duration.300ms class="fixed top-6 right-6 z-50 bg-accent text-white px-6 py-4 rounded-xl shadow-[6px_6px_0px_0px_rgba(245,196,0,1)] font-bold border-2 border-primary flex items-center gap-3 transform transition-transform" :class="{'translate-y-0': toast.show, '-translate-y-10': !toast.show}">
            <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-accent">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span x-text="toast.message" class="text-lg"></span>
        </div>
    </div>

    <script>
        function dashboard() {
            return {
                products: @json($inventoryProducts ?? []),
                filter: 'all',
                isModalOpen: false,
                selectedProduct: null,
                restockQty: 1,
                isRestocking: false,
                toast: { show: false, message: '' },
                
                get filteredProducts() {
                    if (this.filter === 'out_of_stock') {
                        return this.products.filter(p => p.stock === 0);
                    }
                    return this.products;
                },

                openRestockModal(product) {
                    this.selectedProduct = product;
                    this.restockQty = 1;
                    this.isModalOpen = true;
                },

                closeModal() {
                    this.isModalOpen = false;
                    this.selectedProduct = null;
                },

                showToast(message) {
                    this.toast.message = message;
                    this.toast.show = true;
                    setTimeout(() => { this.toast.show = false; }, 3000);
                },

                submitRestock() {
                    if (this.restockQty < 1) return;
                    this.isRestocking = true;
                    
                    fetch(`/products/${this.selectedProduct.id}/restock`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ quantity: this.restockQty })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const idx = this.products.findIndex(p => p.id === this.selectedProduct.id);
                            if (idx !== -1) {
                                this.products[idx].stock = data.new_stock;
                            }
                            this.closeModal();
                            this.showToast(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to restock product.');
                    })
                    .finally(() => {
                        this.isRestocking = false;
                    });
                }
            }
        }
    </script>
</x-app-layout>
