<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Point of Sale') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="posSystem()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex gap-6">
            <!-- Product Selection -->
            <div class="w-2/3 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Products</h3>
                <div class="grid grid-cols-3 gap-4">
                    <template x-for="product in products" :key="product.id">
                        <div class="border p-4 rounded cursor-pointer hover:bg-gray-50 flex flex-col items-center" @click="addToCart(product)">
                            <div class="font-bold text-center" x-text="product.name"></div>
                            <div class="text-gray-600 mt-2" x-text="'₱' + parseFloat(product.price).toFixed(2)"></div>
                        </div>
                    </template>
                    <div x-show="products.length === 0" class="col-span-3 text-center py-4 text-gray-500">
                        No products available. Add some in the Products tab.
                    </div>
                </div>
            </div>

            <!-- Cart & Checkout -->
            <div class="w-1/3 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col">
                <h3 class="text-lg font-bold mb-4">Current Order</h3>
                
                @if(session('error'))
                    <div class="text-red-500 mb-4">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="text-red-500 mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex-grow overflow-y-auto border-b mb-4 min-h-[200px] max-h-[400px]">
                    <template x-for="(item, index) in cart" :key="index">
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <div class="font-semibold" x-text="item.name"></div>
                                <div class="text-sm text-gray-500"><span x-text="item.qty"></span> x <span x-text="'₱' + item.price.toFixed(2)"></span></div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold" x-text="'₱' + (item.price * item.qty).toFixed(2)"></span>
                                <button class="text-red-500 px-2 py-1 bg-red-100 rounded hover:bg-red-200" @click="removeFromCart(index)">X</button>
                            </div>
                        </div>
                    </template>
                    <div x-show="cart.length === 0" class="text-gray-500 text-center py-4">Cart is empty</div>
                </div>

                <div class="mb-4 border-b pb-4">
                    <div class="flex justify-between font-bold text-xl mb-2">
                        <span>Total Due:</span>
                        <span x-text="'₱' + totalDue.toFixed(2)"></span>
                    </div>
                </div>

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
                        <input type="number" step="0.01" name="cash_tendered" x-model.number="cashTendered" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-lg" required>
                    </div>

                    <div class="mb-4 text-right">
                        <div class="font-bold text-lg">Change: <span x-text="'₱' + change.toFixed(2)" :class="{'text-red-500': change < 0, 'text-green-600': change >= 0}"></span></div>
                    </div>

                    <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded text-lg focus:outline-none focus:shadow-outline" :disabled="cart.length === 0 || change < 0" :class="{'opacity-50 cursor-not-allowed': cart.length === 0 || change < 0}">
                        CHECKOUT
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function posSystem() {
            return {
                products: @json($products),
                cart: [],
                cashTendered: 0,
                
                addToCart(product) {
                    const existing = this.cart.find(item => item.id === product.id);
                    if (existing) {
                        existing.qty++;
                    } else {
                        this.cart.push({
                            id: product.id,
                            name: product.name,
                            price: parseFloat(product.price),
                            qty: 1
                        });
                    }
                },
                
                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                get totalDue() {
                    return this.cart.reduce((total, item) => total + (item.price * item.qty), 0);
                },

                get change() {
                    if (!this.cashTendered) return 0;
                    return this.cashTendered - this.totalDue;
                }
            }
        }
    </script>
</x-app-layout>
