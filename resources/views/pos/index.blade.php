<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('KKV Checkout Counter') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="posSystem()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex gap-6">
            <!-- Product Selection -->
            <div class="w-2/3 bg-white border-2 border-accent shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] sm:rounded-2xl p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Products</h3>
                    <input type="text" x-model="search" placeholder="Search products..." class="border-2 border-accent rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary w-52">
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div class="relative border-2 border-accent p-4 rounded-xl flex flex-col items-center transition-all bg-wood-light"
                             :class="{'opacity-50 grayscale pointer-events-none': product.stock === 0, 'cursor-pointer hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]': product.stock > 0}"
                             @click="addToCart(product)">

                            <!-- Out of Stock overlay -->
                            <div x-show="product.stock === 0" class="absolute inset-0 z-10 flex items-center justify-center bg-white/60 rounded-xl">
                                <div class="bg-red-500 text-white font-black text-xs px-3 py-1 rounded border-2 border-red-700 rotate-[-10deg]">OUT OF STOCK</div>
                            </div>

                            <div class="font-bold text-center text-accent" x-text="product.name"></div>
                            <div class="text-[10px] text-accent/50 font-mono mt-0.5" x-text="product.barcode"></div>
                            <div class="text-accent/80 font-bold mt-2" x-text="'₱' + parseFloat(product.price).toFixed(2)"></div>
                            <div class="text-xs mt-1" :class="{'text-red-500 font-bold': product.stock <= 5, 'text-gray-500': product.stock > 5}">
                                Stock: <span x-text="product.stock"></span>
                            </div>
                        </div>
                    </template>
                    <div x-show="filteredProducts.length === 0" class="col-span-3 text-center py-8 text-gray-500">
                        No products found.
                    </div>
                </div>
            </div>

            <!-- Cart & Checkout -->
            <div class="w-1/3 bg-white border-2 border-accent shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] sm:rounded-2xl p-6 flex flex-col">
                <h3 class="text-lg font-bold mb-4 flex justify-between items-center">
                    Current Order
                    <button @click="clearCart" x-show="cart.length > 0" class="text-xs font-bold text-red-500 hover:text-red-700 transition-colors">Clear All</button>
                </h3>

                @if(session('error'))
                    <div class="text-red-500 mb-4 bg-red-50 border border-red-200 p-3 rounded-lg text-sm">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="text-red-500 mb-4 bg-red-50 border border-red-200 p-3 rounded-lg text-sm">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Cart Items -->
                <div class="flex-grow overflow-y-auto border-b mb-4 min-h-[200px] max-h-[400px]">
                    <template x-for="(item, index) in cart" :key="item.id">
                        <div class="flex justify-between items-center mb-3 pb-3 border-b border-gray-100 last:border-0">
                            <div class="flex-1 mr-2">
                                <div class="font-semibold text-sm" x-text="item.name"></div>
                                <div class="text-xs text-gray-500 mt-0.5" x-text="'₱' + item.price.toFixed(2) + ' each'"></div>
                                <!-- Qty Controls -->
                                <div class="flex items-center gap-1 mt-1.5">
                                    <button class="w-6 h-6 bg-accent text-white rounded font-bold text-xs hover:bg-accent/80 transition-colors" @click="updateQty(index, -1)">−</button>
                                    <input type="number" x-model.number="item.qty" @change="validateQty(index)"
                                           class="w-12 text-center text-sm font-bold border border-accent rounded py-0.5 focus:outline-none focus:ring-1 focus:ring-primary"
                                           style="-moz-appearance:textfield;"
                                           onfocus="this.select()">
                                    <button class="w-6 h-6 bg-accent text-white rounded font-bold text-xs hover:bg-accent/80 transition-colors" @click="updateQty(index, 1)">+</button>
                                    <span class="text-[10px] text-gray-400 ml-1">max: <span x-text="item.max_stock"></span></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <span class="font-bold" x-text="'₱' + (item.price * item.qty).toFixed(2)"></span>
                                <button class="text-red-500 px-2 py-1 bg-red-100 rounded hover:bg-red-200 text-xs font-bold" @click="removeFromCart(index)">✕</button>
                            </div>
                        </div>
                    </template>
                    <div x-show="cart.length === 0" class="text-gray-500 text-center py-8">Cart is empty</div>
                </div>

                <!-- Totals -->
                <div class="mb-4 border-b pb-4">
                    <div class="flex justify-between font-bold text-xl mb-2">
                        <span>Total Due:</span>
                        <span x-text="'₱' + totalDue.toFixed(2)"></span>
                    </div>
                </div>

                <!-- Checkout Form -->
                <form action="{{ route('pos.checkout') }}" method="POST">
                    @csrf
                    <template x-for="(item, index) in cart" :key="index">
                        <div>
                            <input type="hidden" :name="'items['+index+'][product_id]'" :value="item.id">
                            <input type="hidden" :name="'items['+index+'][qty]'" :value="item.qty">
                        </div>
                    </template>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Cash Tendered</label>
                        <input type="number" step="0.01" name="cash_tendered" x-model.number="cashTendered"
                               class="shadow appearance-none border-2 border-accent rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-lg" required>
                    </div>

                    <div class="mb-4 text-right">
                        <div class="font-bold text-lg">Change: <span x-text="'₱' + change.toFixed(2)" :class="{'text-red-500': change < 0, 'text-green-600': change >= 0}"></span></div>
                    </div>

                    <button type="submit"
                            class="w-full bg-primary border-2 border-accent hover:-translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all text-accent font-bold py-3 px-4 rounded-xl text-lg focus:outline-none focus:shadow-outline"
                            :disabled="!canCheckout"
                            :class="{'opacity-50 cursor-not-allowed hover:translate-y-0 hover:shadow-none': !canCheckout}">
                        CHECKOUT
                    </button>
                </form>
            </div>
        </div>

        <!-- Toast Notification -->
        <div x-show="toast.show" style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-full"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-full"
             class="fixed top-6 right-6 z-50 bg-red-500 text-white px-5 py-3.5 rounded-xl shadow-[4px_4px_0px_0px_rgba(153,27,27,1)] font-bold border-2 border-red-800 flex items-center gap-2 text-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span x-text="toast.message"></span>
        </div>
    </div>

    <script>
        function posSystem() {
            return {
                products: @json($products),
                search: '',
                cart: [],
                cashTendered: 0,
                toast: { show: false, message: '' },

                get filteredProducts() {
                    if (this.search === '') return this.products;
                    const query = this.search.toLowerCase();
                    return this.products.filter(p => p.name.toLowerCase().includes(query) || p.barcode.toLowerCase().includes(query));
                },

                showToast(message) {
                    this.toast.message = message;
                    this.toast.show = true;
                    setTimeout(() => { this.toast.show = false; }, 3000);
                },

                addToCart(product) {
                    if (product.stock === 0) return;

                    const existing = this.cart.find(item => item.id === product.id);
                    if (existing) {
                        if (existing.qty < product.stock) {
                            existing.qty++;
                        } else {
                            this.showToast(`Error: Only ${product.stock} units left for "${product.name}"`);
                        }
                    } else {
                        this.cart.push({
                            id: product.id,
                            name: product.name,
                            price: parseFloat(product.price),
                            qty: 1,
                            max_stock: product.stock
                        });
                    }
                },

                updateQty(index, amount) {
                    const item = this.cart[index];
                    const newQty = item.qty + amount;
                    if (newQty < 1) return;
                    if (newQty > item.max_stock) {
                        this.showToast(`Error: Only ${item.max_stock} units left`);
                        item.qty = item.max_stock;
                        return;
                    }
                    item.qty = newQty;
                },

                validateQty(index) {
                    const item = this.cart[index];
                    if (item.qty < 1 || isNaN(item.qty) || item.qty === '') {
                        item.qty = 1;
                    }
                    if (item.qty > item.max_stock) {
                        this.showToast(`Error: Only ${item.max_stock} units left`);
                        item.qty = item.max_stock;
                    }
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                clearCart() {
                    if (confirm('Clear the entire cart?')) {
                        this.cart = [];
                        this.cashTendered = 0;
                    }
                },

                get totalDue() {
                    return this.cart.reduce((total, item) => total + (item.price * item.qty), 0);
                },

                get change() {
                    if (!this.cashTendered) return 0;
                    return this.cashTendered - this.totalDue;
                },

                get canCheckout() {
                    const tendered = parseFloat(this.cashTendered) || 0;
                    return this.cart.length > 0 && tendered >= this.totalDue && tendered > 0;
                }
            }
        }
    </script>
</x-app-layout>
