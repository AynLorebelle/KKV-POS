<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_no', 'cashier_name', 'total_amount',
        'cash_tendered', 'change', 'vatable_sales',
        'vat_amount', 'vat_exempt', 'zero_rated',
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
