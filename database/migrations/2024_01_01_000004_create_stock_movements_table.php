<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained('products')->cascadeOnDelete();

            // 'in'  = barang masuk (pembelian, retur penjualan, penyesuaian tambah)
            // 'out' = barang keluar (penjualan, retur pembelian, penyesuaian kurang)
            $table->enum('type', ['in', 'out']);
            $table->integer('quantity'); // selalu positif; arah ditentukan kolom 'type'

            // Alasan pergerakan stok, penting untuk audit & laporan.
            $table->enum('reason', ['sale', 'purchase', 'adjustment', 'initial'])->default('adjustment');

            // Referensi polymorphic ringan ke transaksi asal (mis. sale_id), tanpa FK
            // supaya ledger tidak coupled ke satu tabel transaksi tertentu.
            $table->string('reference_type')->nullable(); // contoh: 'sale'
            $table->uuid('reference_id')->nullable();

            $table->text('note')->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamp('created_at')->useCurrent();
            // Tidak ada updated_at/soft delete secara sengaja: ledger bersifat append-only,
            // koreksi dilakukan dengan menambah entri baru, bukan mengedit entri lama.

            $table->index(['product_id', 'created_at']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
