<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StockMovement extends Model
{
    use HasUuid;

    const UPDATED_AT = null; // ledger append-only, tidak ada updated_at

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'reason',
        'reference_type',
        'reference_id',
        'note',
        'created_by',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Satu-satunya cara resmi untuk mengubah stok produk. Jangan pernah
     * mengubah Product::current_stock secara langsung di luar method ini —
     * supaya current_stock selalu konsisten dengan histori ledger, dan supaya
     * saat nanti butuh mode offline-sync, semua perubahan stok sudah tercatat
     * sebagai event yang bisa di-replay/diverifikasi.
     *
     * Menggunakan row lock (lockForUpdate) untuk mencegah race condition saat
     * dua transaksi mengubah stok produk yang sama secara bersamaan.
     *
     * @throws \RuntimeException jika stok keluar melebihi stok tersedia
     */
    public static function record(
        Product $product,
        string $type,
        int $quantity,
        string $reason,
        ?string $referenceType = null,
        ?string $referenceId = null,
        ?string $note = null,
        ?string $createdBy = null,
    ): self {
        return DB::transaction(function () use ($product, $type, $quantity, $reason, $referenceType, $referenceId, $note, $createdBy) {
            /** @var Product $locked */
            $locked = Product::whereKey($product->id)->lockForUpdate()->firstOrFail();

            if ($type === 'out' && $locked->current_stock < $quantity) {
                throw new \RuntimeException(
                    "Stok '{$locked->name}' tidak cukup. Tersedia: {$locked->current_stock}, diminta: {$quantity}."
                );
            }

            $movement = self::create([
                'product_id' => $locked->id,
                'type' => $type,
                'quantity' => $quantity,
                'reason' => $reason,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'note' => $note,
                'created_by' => $createdBy,
            ]);

            $locked->current_stock = $type === 'in'
                ? $locked->current_stock + $quantity
                : $locked->current_stock - $quantity;
            $locked->save();

            // Sinkronkan instance asli yang dipegang caller.
            $product->current_stock = $locked->current_stock;

            return $movement;
        });
    }
}
