<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'customer') {
            return redirect()->route('history');
        }

        $today = Carbon::today();

        // Summary cards
        $todaySales   = Transaction::whereDate('created_at', $today)->sum('total_amount');
        $totalRevenue = Transaction::sum('total_amount');
        $totalTx      = Transaction::count();
        $lowStock     = Product::where('stock', '<=', 10)->count();

        // Advanced Inventory Dashboard
        $inventoryProducts = Product::withSum('transactionItems as units_sold', 'qty')
            ->withSum('transactionItems as revenue', 'amount')
            ->get();

        // Recent transactions (last 10)
        $recentTransactions = Transaction::with('items')
            ->latest()
            ->take(10)
            ->get();

        // Sales chart – last 7 days
        $chartData = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);
            return [
                'label' => $date->format('D'),
                'date'  => $date->toDateString(),
                'total' => (float) Transaction::whereDate('created_at', $date)->sum('total_amount'),
            ];
        });

        return view('dashboard', compact(
            'todaySales',
            'totalRevenue',
            'totalTx',
            'lowStock',
            'inventoryProducts',
            'recentTransactions',
            'chartData'
        ));
    }

    public function history()
    {
        // Dummy logic for customer history based on cashier_name matching user name (assuming that's how it's linked for now since there's no user_id on transactions)
        $transactions = Transaction::with('items.product')->where('cashier_name', auth()->user()->name)->latest()->get();
        return view('history', compact('transactions'));
    }
}
