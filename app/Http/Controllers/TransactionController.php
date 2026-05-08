<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;

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

        foreach ($request->items as $itemData) {
            $product = Product::find($itemData['product_id']);
            $amount = $product->price * $itemData['qty'];
            $totalAmount += $amount;

            $transactionItems[] = new TransactionItem([
                'product_id' => $product->id,
                'qty' => $itemData['qty'],
                'price' => $product->price,
                'amount' => $amount,
            ]);
        }

        if ($request->cash_tendered < $totalAmount) {
            return back()->with('error', 'Cash tendered is less than total amount.');
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

        return redirect()->route('invoice.show', $transaction->id);
    }

    public function invoice(Transaction $transaction)
    {
        $transaction->load('items.product');

        return view('invoice.show', compact('transaction'));
    }
}
