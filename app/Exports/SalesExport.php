<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Transaction::with('items.product')->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Invoice No',
            'Date',
            'Cashier Name',
            'Total Amount',
            'Items (Qty x Product)',
        ];
    }

    public function map($transaction): array
    {
        $itemsString = $transaction->items->map(function ($item) {
            return $item->qty . 'x ' . ($item->product->name ?? 'Unknown');
        })->implode(', ');

        return [
            $transaction->invoice_no,
            $transaction->created_at->format('Y-m-d H:i:s'),
            $transaction->cashier_name,
            $transaction->total_amount,
            $itemsString,
        ];
    }
}
