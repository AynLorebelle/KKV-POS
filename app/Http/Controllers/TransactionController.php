<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function pos()
    {
        $products = Product::all();

        return view('pos.index', compact('products'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'cash_tendered' => 'required|numeric|min:0',
        ]);

        $totalAmount = 0;
        $transactionItems = [];

        DB::beginTransaction();

        try {
            foreach ($request->items as $itemData) {
                $product = Product::lockForUpdate()->find($itemData['product_id']);
                
                if ($product->stock < $itemData['qty']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                $amount = $product->price * $itemData['qty'];
                $totalAmount += $amount;

                $transactionItems[] = new TransactionItem([
                    'product_id' => $product->id,
                    'qty' => $itemData['qty'],
                    'price' => $product->price,
                    'amount' => $amount,
                ]);

                $product->stock -= $itemData['qty'];
                $product->save();
            }

            if ($request->cash_tendered < $totalAmount) {
                throw new \Exception('Cash tendered is less than total amount.');
            }

            $change = $request->cash_tendered - $totalAmount;

            $vatableSales = round($totalAmount / 1.12, 2);
            $vatAmount = $totalAmount - $vatableSales;

            $lastTransaction = Transaction::latest('id')->first();
            $nextId = $lastTransaction ? $lastTransaction->id + 1 : 1;
            $invoiceNo = str_pad($nextId + 14338, 10, '0', STR_PAD_LEFT);

            $transaction = Transaction::create([
                'invoice_no' => $invoiceNo,
                'cashier_name' => auth()->user()->name ?? 'RENIE MARK ABONG',
                'total_amount' => $totalAmount,
                'cash_tendered' => $request->cash_tendered,
                'change' => $change,
                'vatable_sales' => $vatableSales,
                'vat_amount' => $vatAmount,
                'vat_exempt' => 0,
                'zero_rated' => 0,
            ]);

            $transaction->items()->saveMany($transactionItems);

            DB::commit();

            return redirect()->route('invoice.show', $transaction->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function invoice(Transaction $transaction)
    {
        $transaction->load('items.product');

        return view('invoice.show', compact('transaction'));
    }

    public function reports(Request $request)
    {
        $query = Transaction::with('items.product')->latest();

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->month) {
            [$year, $month] = explode('-', $request->month);
            $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
        }

        if ($request->cashier) {
            $query->where('cashier_name', 'like', '%' . $request->cashier . '%');
        }

        $transactions = $query->paginate(20);

        return view('reports', compact('transactions'));
    }

    public function export()
    {
        if (class_exists(\Maatwebsite\Excel\Facades\Excel::class)) {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\SalesExport, 'sales_report.xlsx');
        }
        
        // Fallback if excel is not available
        return back()->with('error', 'Excel export not configured properly.');
    }
}
