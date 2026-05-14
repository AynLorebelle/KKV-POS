<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-xl text-accent leading-tight tracking-tight">
                {{ __('Sales Reports') }}
            </h2>
            <a href="{{ route('reports.export') }}" class="bg-primary border-2 border-accent text-accent font-bold px-4 py-2 rounded-lg shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-0.5 transition-all">
                Export to Excel
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border-2 border-accent shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] rounded-xl overflow-hidden p-6 mb-6 bg-wood-light/50">
                <form action="{{ route('reports') }}" method="GET" class="flex gap-4 items-end">
                    <div>
                        <label class="block text-accent text-xs font-bold uppercase tracking-widest mb-1">Filter by Date</label>
                        <input type="date" name="date" value="{{ request('date') }}" class="border-2 border-accent rounded-lg px-4 py-2 font-bold shadow-inner focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-accent text-xs font-bold uppercase tracking-widest mb-1">Filter by Cashier</label>
                        <input type="text" name="cashier" value="{{ request('cashier') }}" placeholder="Cashier Name" class="border-2 border-accent rounded-lg px-4 py-2 font-bold shadow-inner focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <button type="submit" class="bg-accent border-2 border-accent text-white font-bold px-6 py-2 rounded-lg shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-0.5 transition-all">Filter</button>
                        <a href="{{ route('reports') }}" class="ml-2 text-sm font-bold text-gray-500 hover:text-accent underline">Clear</a>
                    </div>
                </form>
            </div>

            <div class="bg-white border-2 border-accent shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] rounded-xl overflow-hidden p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs font-bold uppercase bg-accent text-white tracking-widest">
                            <tr>
                                <th class="px-6 py-3">Date</th>
                                <th class="px-6 py-3">Invoice No</th>
                                <th class="px-6 py-3">Cashier</th>
                                <th class="px-6 py-3">Total Amount</th>
                                <th class="px-6 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-accent/20">
                            @forelse($transactions as $tx)
                                <tr class="hover:bg-wood-light transition-colors">
                                    <td class="px-6 py-4 font-mono">{{ $tx->created_at->format('M d, Y H:i') }}</td>
                                    <td class="px-6 py-4 font-bold text-accent">#{{ $tx->invoice_no }}</td>
                                    <td class="px-6 py-4">{{ $tx->cashier_name }}</td>
                                    <td class="px-6 py-4 font-black text-accent text-lg">₱{{ number_format($tx->total_amount, 2) }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('invoice.show', $tx) }}" class="text-xs font-bold text-accent bg-primary px-3 py-1.5 border border-accent rounded-md shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 font-bold">No transactions found matching the criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
