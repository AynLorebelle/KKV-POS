<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->string('cashier_name');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('cash_tendered', 10, 2);
            $table->decimal('change', 10, 2);
            $table->decimal('vatable_sales', 10, 2);
            $table->decimal('vat_amount', 10, 2);
            $table->decimal('vat_exempt', 10, 2)->default(0);
            $table->decimal('zero_rated', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
