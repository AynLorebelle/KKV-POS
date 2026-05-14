<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Products') }}
            </h2>
            <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary border-2 border-accent rounded-lg font-bold text-accent shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-none transition-all text-sm">
                + Add Product
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="inventoryTable()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border-2 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-6 font-bold" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Inventory Table -->
            <div class="bg-white border-2 border-accent shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] sm:rounded-2xl overflow-hidden">
                <!-- Header with filters -->
                <div class="flex items-center justify-between px-6 py-4 border-b-2 border-accent/10 bg-wood-light">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-accent/60">Inventory</p>
                        <h3 class="text-lg font-black text-accent">Advanced Inventory Management</h3>
                    </div>
                    <div class="flex gap-2">
                        <button @click="filter = 'all'" :class="{'bg-accent text-white border-accent': filter === 'all', 'bg-white text-accent border-accent': filter !== 'all'}" class="px-3 py-1 text-xs font-bold border rounded-md shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">All Products</button>
                        <button @click="filter = 'out_of_stock'" :class="{'bg-red-500 text-white border-red-700': filter === 'out_of_stock', 'bg-white text-red-500 border-red-500': filter !== 'out_of_stock'}" class="px-3 py-1 text-xs font-bold border rounded-md shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">Out of Stock</button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-accent text-white text-xs font-bold uppercase tracking-widest">
                                <th class="px-6 py-3 text-left">Product Info</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-center">On-Hand Stock</th>
                                <th class="px-6 py-3 text-center">Units Sold</th>
                                <th class="px-6 py-3 text-right">Revenue</th>
                                <th class="px-6 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-accent/10">
                            <template x-for="product in filteredProducts" :key="product.id">
                                <tr class="hover:bg-wood-light/50 transition-colors" :class="{'opacity-60 grayscale bg-gray-50': product.stock === 0}">
                                    <td class="px-6 py-3">
                                        <div class="font-black text-accent text-sm" x-text="product.name"></div>
                                        <div class="text-xs font-mono text-accent/60" x-text="product.barcode"></div>
                                    </td>
                                    <td class="px-6 py-3">
                                        <span x-show="product.stock > 10" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-800 border border-green-200">In Stock</span>
                                        <span x-show="product.stock > 0 && product.stock <= 10" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">Low Stock</span>
                                        <span x-show="product.stock === 0" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-800 border border-red-200">Out of Stock</span>
                                    </td>
                                    <td class="px-6 py-3 text-center font-black text-lg" :class="{'text-red-500': product.stock === 0, 'text-accent': product.stock > 0}" x-text="product.stock"></td>
                                    <td class="px-6 py-3 text-center font-bold text-accent/80" x-text="product.units_sold || 0"></td>
                                    <td class="px-6 py-3 text-right font-black text-accent" x-text="'₱' + (parseFloat(product.revenue) || 0).toFixed(2)"></td>
                                    <td class="px-6 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2" :data-id="product.id">
                                            @if(auth()->user()->role === 'admin')
                                            <button @click="openRestockModal(product)" class="text-xs font-bold text-accent bg-primary px-2 py-1 border border-accent rounded-md shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all">
                                                Restock
                                            </button>
                                            @endif
                                            <a :href="'/products/' + product.id + '/edit'" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-2 py-1 border border-indigo-300 rounded-md transition-colors">Edit</a>
                                            @if(auth()->user()->role === 'admin')
                                            <form :action="'/products/' + product.id" method="POST" @submit.prevent="confirmDelete($event, product.name)">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 bg-red-50 px-2 py-1 border border-red-200 rounded-md transition-colors">Delete</button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="filteredProducts.length === 0">
                                <td colspan="6" class="px-6 py-12 text-center text-accent/40 font-medium">No products match the filter.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Restock Modal -->
        <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" x-transition.opacity>
            <div @click.away="closeModal()" class="bg-white border-2 border-accent shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] rounded-2xl p-6 w-96" x-transition>
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
                    <input type="number" x-model.number="restockQty" min="1" class="shadow-inner appearance-none border-2 border-accent rounded-lg w-full py-3 px-4 text-accent font-bold text-lg leading-tight focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <button @click="submitRestock()" :disabled="isRestocking" class="w-full bg-primary border-2 border-accent text-accent font-black text-lg py-3 rounded-xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1 hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] transition-all disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center gap-2">
                    <span x-show="!isRestocking">Add Stock</span>
                    <span x-show="isRestocking">Saving...</span>
                </button>
            </div>
        </div>

        <!-- Toast -->
        <div x-show="toast.show" style="display: none;" x-transition.opacity.duration.300ms class="fixed top-6 right-6 z-50 bg-accent text-white px-6 py-4 rounded-xl shadow-[6px_6px_0px_0px_rgba(245,196,0,1)] font-bold border-2 border-primary flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            <span x-text="toast.message"></span>
        </div>
    </div>

    <script>
        function inventoryTable() {
            return {
                products: @json($products),
                filter: 'all',
                isModalOpen: false,
                selectedProduct: null,
                restockQty: 1,
                isRestocking: false,
                toast: { show: false, message: '' },

                get filteredProducts() {
                    if (this.filter === 'out_of_stock') return this.products.filter(p => p.stock === 0);
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

                confirmDelete(event, name) {
                    if (confirm(`Are you sure you want to delete "${name}"? This cannot be undone.`)) {
                        event.target.submit();
                    }
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
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            const idx = this.products.findIndex(p => p.id === this.selectedProduct.id);
                            if (idx !== -1) this.products[idx].stock = data.new_stock;
                            this.closeModal();
                            this.showToast(data.message);
                        }
                    })
                    .catch(() => alert('Failed to restock product.'))
                    .finally(() => { this.isRestocking = false; });
                }
            }
        }
    </script>
</x-app-layout>
