<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\TransactionItem;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['barcode', 'name', 'price', 'stock'];

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
