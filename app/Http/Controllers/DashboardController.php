<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Customers get their own dashboard view (purchase history)
        if (auth()->user()->role === 'customer') {
            $transactions = \App\Models\Transaction::with('items.product')
                ->where('cashier_name', auth()->user()->name)
                ->latest()
                ->get();
            return view('customer-dashboard', compact('transactions'));
        }

        $today = Carbon::today();

        // Summary cards
        $todaySales   = Transaction::whereDate('created_at', $today)->sum('total_amount');
        $totalRevenue = Transaction::sum('total_amount');
        $totalTx      = Transaction::count();
        $lowStock     = Product::where('stock', '<=', 10)->count();

        // Recent transactions (last 10)
        $recentTransactions = Transaction::with('items')
            ->latest()
            ->take(10)
            ->get();

        // Sales chart – last 7 days (for quick overview bar)
        $chartData = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);
            return [
                'label' => $date->format('D'),
                'date'  => $date->toDateString(),
                'total' => (float) Transaction::whereDate('created_at', $date)->sum('total_amount'),
            ];
        });

        // Monthly chart – last 12 months
        $monthlyData = collect(range(11, 0))->map(function ($monthsAgo) {
            $date = Carbon::now()->startOfMonth()->subMonths($monthsAgo);
            return [
                'label' => $date->format('M Y'),
                'month' => $date->format('Y-m'),
                'total' => (float) Transaction::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('total_amount'),
            ];
        });

        // Date-filtered sales (for the calendar filter widget)
        $selectedDate  = $request->get('filter_date', null);
        $filteredTx    = null;
        $filteredTotal = 0;

        if ($selectedDate) {
            $filteredTx    = Transaction::with('items')->whereDate('created_at', $selectedDate)->latest()->get();
            $filteredTotal = $filteredTx->sum('total_amount');
        }

        return view('dashboard', compact(
            'todaySales',
            'totalRevenue',
            'totalTx',
            'lowStock',
            'recentTransactions',
            'chartData',
            'monthlyData',
            'selectedDate',
            'filteredTx',
            'filteredTotal'
        ));
    }

    public function history()
    {
        $transactions = Transaction::with('items.product')
            ->where('cashier_name', auth()->user()->name)
            ->latest()
            ->get();
        return view('history', compact('transactions'));
    }
}
