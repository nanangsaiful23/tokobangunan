<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('invoice_number')->unique(); // contoh: INV-20260829-0001

            $table->foreignUuid('cashier_id')->constrained('users')->restrictOnDelete();
            $table->string('customer_name')->nullable();

            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);

            $table->enum('payment_method', ['cash', 'qris', 'transfer', 'credit'])->default('cash');
            $table->decimal('paid_amount', 15, 2)->default(0);

            // 'credit' = sebagian/seluruhnya belum lunas (piutang), dilacak lewat paid_amount vs total
            $table->enum('status', ['paid', 'partial', 'unpaid'])->default('paid');

            $table->timestamps();

            $table->index(['created_at']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
