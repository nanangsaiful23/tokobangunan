<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SaleService
{
    /**
     * Buat transaksi penjualan dari data keranjang POS.
     *
     * @param  array<int, array{product_id: string, quantity: int}>  $cartItems
     * @param  array{customer_name?: string, discount?: float, payment_method: string, paid_amount: float}  $payload
     *
     * @throws \RuntimeException jika ada item stok tidak cukup
     */
    public function create(array $cartItems, array $payload, User $cashier): Sale
    {
        if (empty($cartItems)) {
            throw new \RuntimeException('Keranjang belanja masih kosong.');
        }

        return DB::transaction(function () use ($cartItems, $payload, $cashier) {
            $subtotal = 0;
            $resolvedItems = [];

            // Ambil data produk terbaru & hitung subtotal dulu, sebelum ada perubahan apapun.
            foreach ($cartItems as $item) {
                $product = Product::whereKey($item['product_id'])->firstOrFail();
                $quantity = (int) $item['quantity'];

                if ($quantity <= 0) {
                    throw new \RuntimeException("Jumlah untuk '{$product->name}' harus lebih dari 0.");
                }

                $lineSubtotal = $product->sell_price * $quantity;
                $subtotal += $lineSubtotal;

                $resolvedItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $product->sell_price,
                    'subtotal' => $lineSubtotal,
                ];
            }

            $discount = (float) ($payload['discount'] ?? 0);
            $total = max($subtotal - $discount, 0);
            $paidAmount = (float) ($payload['paid_amount'] ?? $total);

            $status = 'paid';
            if ($paidAmount <= 0) {
                $status = 'unpaid';
            } elseif ($paidAmount < $total) {
                $status = 'partial';
            }

            $sale = Sale::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'cashier_id' => $cashier->id,
                'customer_name' => $payload['customer_name'] ?? null,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $payload['payment_method'],
                'paid_amount' => $paidAmount,
                'status' => $status,
            ]);

            foreach ($resolvedItems as $resolved) {
                /** @var Product $product */
                $product = $resolved['product'];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name, // snapshot nama saat transaksi
                    'unit_price' => $resolved['unit_price'],
                    'quantity' => $resolved['quantity'],
                    'subtotal' => $resolved['subtotal'],
                ]);

                // Ini akan melempar exception (dan rollback seluruh transaksi)
                // jika stok ternyata tidak cukup, termasuk kalau ada race condition.
                StockMovement::record(
                    product: $product,
                    type: 'out',
                    quantity: $resolved['quantity'],
                    reason: 'sale',
                    referenceType: 'sale',
                    referenceId: $sale->id,
                    createdBy: $cashier->id,
                );
            }

            return $sale->fresh('items');
        });
    }

    private function generateInvoiceNumber(): string
    {
        $prefix = 'INV-'.now()->format('Ymd').'-';
        $lastToday = Sale::where('invoice_number', 'like', $prefix.'%')
            ->orderByDesc('invoice_number')
            ->value('invoice_number');

        $nextSequence = $lastToday
            ? ((int) substr($lastToday, -4)) + 1
            : 1;

        return $prefix.str_pad((string) $nextSequence, 4, '0', STR_PAD_LEFT);
    }
}
