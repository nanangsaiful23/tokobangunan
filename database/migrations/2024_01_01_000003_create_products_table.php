<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('sku')->unique();
            $table->string('barcode')->nullable()->unique();
            $table->string('name');
            $table->foreignUuid('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('unit')->default('pcs'); // satuan: pcs, sak, batang, m3, dll

            $table->decimal('cost_price', 15, 2)->default(0);   // harga beli/modal
            $table->decimal('sell_price', 15, 2)->default(0);   // harga jual

            // Stok saat ini disimpan sebagai kolom cache untuk kecepatan baca (mis. tampil di
            // katalog POS tanpa SUM ribuan baris). Sumber kebenaran tetap tabel stock_movements;
            // kolom ini WAJIB hanya diubah lewat StockMovement, tidak pernah di-update langsung.
            $table->integer('current_stock')->default(0);
            $table->integer('reorder_threshold')->default(0); // ambang batas stok menipis

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['name']);
            $table->index(['is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
