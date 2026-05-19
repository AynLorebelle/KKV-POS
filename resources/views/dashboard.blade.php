<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <h2 class="font-extrabold text-xl text-accent leading-tight tracking-tight">
                    Welcome to KKV !!!
                </h2>
              
            </div>
            <span class="text-sm font-medium text-accent/60 font-mono">{{ now()->format('D, d M Y') }}</span>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- ── ROW 1: SUMMARY CARDS ── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="relative bg-primary border-2 border-accent shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] rounded-xl p-5 overflow-hidden">
                <div class="absolute -top-3 -right-3 text-[64px] font-black opacity-5 select-none leading-none">₱</div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-accent/70 mb-1">Today's Sales</p>
                <p class="text-2xl font-black text-accent leading-tight">₱{{ number_format($todaySales, 2) }}</p>
                <p class="text-[10px] text-accent/60 mt-1.5 font-medium">{{ now()->format('M d, Y') }}</p>
            </div>
            <div class="relative bg-white border-2 border-accent shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] rounded-xl p-5 overflow-hidden">
                <div class="absolute -top-3 -right-3 text-[64px] font-black opacity-10 select-none leading-none">💰</div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-accent/70 mb-1">Total Revenue</p>
                <p class="text-2xl font-black text-accent leading-tight">₱{{ number_format($totalRevenue, 2) }}</p>
                <p class="text-[10px] text-accent/60 mt-1.5 font-medium">All time</p>
            </div>
            <div class="relative bg-white border-2 border-accent shadow-[4px_4px_0px_0px_rgba(245,196,0,1)] rounded-xl p-5 overflow-hidden">
                <div class="absolute -top-3 -right-3 text-[64px] font-black opacity-10 select-none leading-none text-accent">Σ</div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-accent/70 mb-1">Transactions</p>
                <p class="text-2xl font-black text-accent leading-tight">{{ number_format($totalTx) }}</p>
                <p class="text-[10px] text-accent/50 mt-1.5 font-medium">Total invoices</p>
            </div>
            <div class="relative bg-white border-2 border-red-500 shadow-[4px_4px_0px_0px_rgba(239,68,68,1)] rounded-xl p-5 overflow-hidden">
                <div class="absolute -top-3 -right-3 text-[64px] font-black opacity-10 select-none leading-none text-red-500">⚠</div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-red-500/70 mb-1">Low Stock Alerts</p>
                <p class="text-2xl font-black text-red-600 leading-tight">{{ $lowStock }}</p>
                <p class="text-[10px] text-red-500/60 mt-1.5 font-medium">Products ≤ 10 units</p>
            </div>
        </div>

        {{-- ── ROW 2: QUICK ACTIONS ── --}}
        <div class="bg-wood-light border-2 border-accent shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] rounded-xl p-5">
            <p class="text-[10px] font-bold uppercase tracking-widest text-accent/60 mb-3">Quick Actions</p>
            <div class="flex flex-wrap w-full gap-3">
                <a href="{{ route('pos.index') }}" class="flex-1 flex justify-center items-center gap-2 px-4 py-2 bg-primary border-2 border-accent font-bold text-accent text-sm rounded-lg shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-none transition-all whitespace-nowrap"> New Sale</a>
                <a href="{{ route('products.create') }}" class="flex-1 flex justify-center items-center gap-2 px-4 py-2 bg-white border-2 border-accent font-bold text-accent text-sm rounded-lg shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-none transition-all whitespace-nowrap"> Add Product</a>
                <a href="{{ route('products.index') }}" class="flex-1 flex justify-center items-center gap-2 px-4 py-2 bg-white border-2 border-accent font-bold text-accent text-sm rounded-lg shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-none transition-all whitespace-nowrap"> Manage Products</a>
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('reports') }}" class="flex-1 flex justify-center items-center gap-2 px-4 py-2 bg-white text-accent border-2 border-accent font-bold text-sm rounded-lg shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-none transition-all whitespace-nowrap"> Sales Reports</a>
                <a href="{{ route('admin.staff.create') }}" class="flex-1 flex justify-center items-center gap-2 px-4 py-2 bg-white border-2 border-accent font-bold text-accent text-sm rounded-lg shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-none transition-all whitespace-nowrap"> Add Staff</a>
                @endif
            </div>
        </div>

        {{-- ── ROW 3: MONTHLY CHART + DATE FILTER ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Sales Overview (Weekly & Monthly) (2/3) --}}
            <div class="lg:col-span-2 bg-white border-2 border-accent shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] rounded-xl p-6 flex flex-col gap-6">
                
                {{-- Weekly Section --}}
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-accent/60">Sales Overview</p>
                            <h3 class="text-lg font-black text-accent">This Week</h3>
                        </div>
                        <span class="text-[10px] font-mono bg-primary/20 border border-accent/20 text-accent px-3 py-1 rounded-full">Weekly</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex-1 bg-wood-light border border-accent/20 rounded-xl p-4 flex flex-col justify-center">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-accent/60 mb-1">Total Sales</p>
                            <p class="text-2xl font-black text-accent">₱{{ number_format($thisWeekSales, 2) }}</p>
                        </div>
                        <div class="flex-1 bg-wood-light border border-accent/20 rounded-xl p-4 flex flex-col justify-center">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-accent/60 mb-1">Transactions</p>
                            <p class="text-2xl font-black text-accent">{{ number_format($thisWeekTx) }}</p>
                        </div>
                    </div>
                </div>

                <hr class="border-t-2 border-accent/10">

                {{-- Monthly Section --}}
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h3 class="text-lg font-black text-accent">Monthly Sales — Last 12 Months</h3>
                        </div>
                        <span class="text-[10px] font-mono bg-primary/20 border border-accent/20 text-accent px-3 py-1 rounded-full">Monthly</span>
                    </div>
                    @php $maxVal = collect($monthlyData)->max('total') ?: 1; @endphp
                    <div class="flex items-end justify-between gap-1 h-44">
                        @foreach ($monthlyData as $point)
                            @php $pct = ($point['total'] / $maxVal) * 100; @endphp
                            <div class="flex-1 flex flex-col items-center gap-1 group">
                                <span class="text-[9px] font-bold text-accent opacity-0 group-hover:opacity-100 transition-opacity font-mono whitespace-nowrap">
                                    ₱{{ number_format($point['total'], 0) }}
                                </span>
                                <div class="w-full relative rounded-t overflow-hidden bg-wood border border-accent/20" style="height: 128px;">
                                    <div class="absolute bottom-0 left-0 right-0 bg-primary border-t-2 border-accent transition-all duration-700"
                                         style="height: {{ max($pct, 2) }}%;"
                                         title="₱{{ number_format($point['total'], 2) }}">
                                    </div>
                                </div>
                                <span class="text-[8px] font-bold text-accent/60 whitespace-nowrap">{{ $point['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- DATE FILTER / CALENDAR LOOKUP (1/3) --}}
            <div class="bg-white border-2 border-accent shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] rounded-xl p-6 flex flex-col">
                <div class="mb-4">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-accent/60">Date Filter</p>
                    <h3 class="text-lg font-black text-accent">Check a Specific Day</h3>
                </div>

                <form method="GET" action="{{ route('dashboard') }}" class="flex flex-col gap-3">
                    <input type="date" name="filter_date" value="{{ $selectedDate }}"
                           class="border-2 border-accent rounded-xl px-4 py-3 font-bold text-accent focus:outline-none focus:ring-2 focus:ring-primary w-full">
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-primary border-2 border-accent text-accent font-bold py-2 rounded-lg shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all">
                            View Day
                        </button>
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 bg-white border-2 border-accent text-accent font-bold rounded-lg shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all text-sm">✕</a>
                    </div>
                </form>

                @if($selectedDate)
                    <div class="mt-4 pt-4 border-t-2 border-accent/10">
                        <p class="text-[10px] uppercase tracking-widest font-bold text-accent/60 mb-1">{{ \Carbon\Carbon::parse($selectedDate)->format('D, M d Y') }}</p>
                        <p class="text-2xl font-black text-accent">₱{{ number_format($filteredTotal, 2) }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $filteredTx->count() }} transaction(s) that day</p>

                        @if($filteredTx->isNotEmpty())
                            <div class="mt-3 space-y-2 max-h-48 overflow-y-auto pr-1">
                                @foreach($filteredTx as $tx)
                                    <div class="flex justify-between items-center p-2 bg-wood-light rounded-lg border border-accent/10 text-xs">
                                        <div>
                                            <div class="font-bold text-accent">#{{ $tx->invoice_no }}</div>
                                            <div class="text-gray-500">{{ $tx->created_at->format('h:i A') }}</div>
                                        </div>
                                        <div class="font-black text-accent">₱{{ number_format($tx->total_amount, 2) }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="mt-3 text-sm text-gray-400 italic">No sales recorded on this day.</p>
                        @endif
                    </div>
                @else
                    <div class="mt-4 pt-4 border-t-2 border-accent/10 text-center text-gray-400 text-sm flex-1 flex flex-col items-center justify-center gap-2">
                        
                        <p class="font-medium">Pick a date to see<br>daily sales breakdown</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── ROW 4: RECENT TRANSACTIONS ── --}}
        <div class="bg-white border-2 border-accent shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b-2 border-accent/10 bg-wood-light">
                <h3 class="text-lg font-black text-accent">Recent Sales</h3>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('reports') }}" class="text-xs font-bold text-accent underline">View All Reports →</a>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-accent text-white text-xs font-bold uppercase tracking-widest">
                        <tr>
                            <th class="px-6 py-3 text-left">Invoice</th>
                            <th class="px-6 py-3 text-left">Cashier</th>
                            <th class="px-6 py-3 text-left">Date & Time</th>
                            <th class="px-6 py-3 text-center">Items</th>
                            <th class="px-6 py-3 text-right">Total</th>
                            <th class="px-6 py-3 text-center">Receipt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-accent/10">
                        @forelse($recentTransactions as $tx)
                            <tr class="hover:bg-wood-light/50 transition-colors">
                                <td class="px-6 py-3 font-black text-accent">#{{ $tx->invoice_no }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ $tx->cashier_name }}</td>
                                <td class="px-6 py-3 text-gray-500 font-mono text-xs">{{ $tx->created_at->format('M d, Y h:i A') }}</td>
                                <td class="px-6 py-3 text-center font-bold">{{ $tx->items->count() }}</td>
                                <td class="px-6 py-3 text-right font-black text-accent">₱{{ number_format($tx->total_amount, 2) }}</td>
                                <td class="px-6 py-3 text-center">
                                    <a href="{{ route('invoice.show', $tx) }}" class="text-xs font-bold text-accent bg-primary px-3 py-1.5 border border-accent rounded-md shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 font-medium">No recent transactions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>