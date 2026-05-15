<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <h2 class="font-extrabold text-xl text-accent leading-tight tracking-tight">
                    {{ __('Sales Reports') }}
                </h2>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest bg-accent text-white shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] border-2 border-accent">
                    👑 Admin
                </span>
            </div>
            <div class="flex gap-2 no-print">
                <button onclick="window.print()" class="inline-flex items-center gap-2 bg-white border-2 border-accent text-accent font-bold px-4 py-2 rounded-lg shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-0.5 transition-all text-sm">
                    🖨️ Print Report
                </button>
                <a href="{{ route('reports.export') }}" class="inline-flex items-center gap-2 bg-primary border-2 border-accent text-accent font-bold px-4 py-2 rounded-lg shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-0.5 transition-all text-sm">
                    📥 Export Excel
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Print-only header --}}
    <div class="print-only hidden">
        <div class="text-center mb-6 border-b-2 border-black pb-4">
            <h1 class="text-2xl font-black">KKV RETAIL CORPORATION</h1>
            <p class="text-sm">Sales Report</p>
            @if(request('date'))
                <p class="text-sm font-bold mt-1">Date: {{ \Carbon\Carbon::parse(request('date'))->format('M d, Y') }}</p>
            @elseif(request('month'))
                <p class="text-sm font-bold mt-1">Month: {{ \Carbon\Carbon::parse(request('month').'-01')->format('F Y') }}</p>
            @else
                <p class="text-sm font-bold mt-1">All Transactions</p>
            @endif
            <p class="text-xs text-gray-600 mt-1">Printed: {{ now()->format('M d, Y h:i A') }}</p>
        </div>
    </div>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ── FILTER BAR ── --}}
            <div class="bg-wood-light border-2 border-accent shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] rounded-xl p-5 no-print">
                <p class="text-[10px] font-bold uppercase tracking-widest text-accent/60 mb-3">Filter Reports</p>
                <form action="{{ route('reports') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-accent text-[11px] font-bold uppercase tracking-widest mb-1">Specific Date</label>
                        <input type="date" name="date" value="{{ request('date') }}"
                               class="border-2 border-accent rounded-lg px-4 py-2 font-bold shadow-inner focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-accent text-[11px] font-bold uppercase tracking-widest mb-1">By Month</label>
                        <input type="month" name="month" value="{{ request('month') }}"
                               class="border-2 border-accent rounded-lg px-4 py-2 font-bold shadow-inner focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-accent text-[11px] font-bold uppercase tracking-widest mb-1">By Cashier</label>
                        <input type="text" name="cashier" value="{{ request('cashier') }}" placeholder="Cashier Name"
                               class="border-2 border-accent rounded-lg px-4 py-2 font-bold shadow-inner focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="bg-accent border-2 border-accent text-white font-bold px-5 py-2 rounded-lg shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-0.5 transition-all">Apply</button>
                        <a href="{{ route('reports') }}" class="px-5 py-2 bg-white border-2 border-accent text-accent font-bold rounded-lg shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-0.5 transition-all">Clear</a>
                    </div>
                </form>
            </div>

            {{-- ── SUMMARY STRIP (shows total for current filter) ── --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 no-print">
                <div class="bg-primary border-2 border-accent rounded-xl p-4 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-accent/70">Filtered Revenue</p>
                    <p class="text-xl font-black text-accent">₱{{ number_format($transactions->sum('total_amount'), 2) }}</p>
                </div>
                <div class="bg-white border-2 border-accent rounded-xl p-4 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-accent/70">Transactions Shown</p>
                    <p class="text-xl font-black text-accent">{{ $transactions->total() }}</p>
                </div>
                <div class="bg-white border-2 border-accent rounded-xl p-4 shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-accent/70">Avg. per Transaction</p>
                    <p class="text-xl font-black text-accent">
                        ₱{{ $transactions->count() > 0 ? number_format($transactions->sum('total_amount') / $transactions->count(), 2) : '0.00' }}
                    </p>
                </div>
            </div>

            {{-- ── TRANSACTIONS TABLE ── --}}
            <div class="bg-white border-2 border-accent shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs font-bold uppercase bg-accent text-white tracking-widest">
                            <tr>
                                <th class="px-6 py-3">Date & Time</th>
                                <th class="px-6 py-3">Invoice No</th>
                                <th class="px-6 py-3">Cashier</th>
                                <th class="px-6 py-3 text-right">Total Amount</th>
                                <th class="px-6 py-3 text-center no-print">Receipt</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-accent/10">
                            @forelse($transactions as $tx)
                                <tr class="hover:bg-wood-light transition-colors">
                                    <td class="px-6 py-3 font-mono text-xs text-gray-600">{{ $tx->created_at->format('M d, Y H:i') }}</td>
                                    <td class="px-6 py-3 font-black text-accent">#{{ $tx->invoice_no }}</td>
                                    <td class="px-6 py-3 text-gray-600">{{ $tx->cashier_name }}</td>
                                    <td class="px-6 py-3 font-black text-accent text-right">₱{{ number_format($tx->total_amount, 2) }}</td>
                                    <td class="px-6 py-3 text-center no-print">
                                        <a href="{{ route('invoice.show', $tx) }}" class="text-xs font-bold text-accent bg-primary px-3 py-1.5 border border-accent rounded-md shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-none transition-all">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-medium">No transactions found matching the criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        {{-- Print grand total footer --}}
                        @if($transactions->count() > 0)
                        <tfoot class="bg-wood-light border-t-2 border-accent">
                            <tr>
                                <td colspan="3" class="px-6 py-3 font-black text-accent text-right uppercase tracking-widest text-xs">Grand Total:</td>
                                <td class="px-6 py-3 font-black text-accent text-right text-lg">₱{{ number_format($transactions->sum('total_amount'), 2) }}</td>
                                <td class="no-print"></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
                <div class="p-4 no-print">
                    {{ $transactions->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            body { background: white !important; font-size: 12px; }
            nav, header, footer { display: none !important; }
            .py-8 { padding: 0 !important; }
            .max-w-7xl { max-width: 100% !important; padding: 0 !important; }
            table { border-collapse: collapse; width: 100%; }
            th, td { border: 1px solid #ddd; padding: 6px 10px; }
            thead { background-color: #000 !important; color: white !important; -webkit-print-color-adjust: exact; }
            .rounded-xl, .shadow-\[4px_4px_0px_0px_rgba\(0\,0\,0\,1\)\] { border-radius: 0 !important; box-shadow: none !important; }
            tfoot { background-color: #f5f5f5 !important; font-weight: bold; }
        }
    </style>
</x-app-layout>
