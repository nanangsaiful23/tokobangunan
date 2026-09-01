<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\SaleService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PosTerminal extends Component
{
    public string $search = '';

    /** @var array<int, array{product_id: string, name: string, price: float, quantity: int, stock: int}> */
    public array $cart = [];

    public ?string $customerName = null;
    public float $discount = 0;
    public string $paymentMethod = 'cash';
    public ?float $paidAmount = null;

    public ?string $errorMessage = null;

    public function addToCart(string $productId): void
    {
        $product = Product::findOrFail($productId);

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
        } else {
            $this->cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->sell_price,
                'quantity' => 1,
                'stock' => $product->current_stock,
            ];
        }

        $this->errorMessage = null;
    }

    public function incrementQty(string $productId): void
    {
        $this->cart[$productId]['quantity']++;
    }

    public function decrementQty(string $productId): void
    {
        if ($this->cart[$productId]['quantity'] <= 1) {
            unset($this->cart[$productId]);

            return;
        }

        $this->cart[$productId]['quantity']--;
    }

    public function removeFromCart(string $productId): void
    {
        unset($this->cart[$productId]);
    }

    #[Computed]
    public function subtotal(): float
    {
        return collect($this->cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
    }

    #[Computed]
    public function total(): float
    {
        return max($this->subtotal - $this->discount, 0);
    }

    #[Computed]
    public function products()
    {
        return Product::active()
            ->search($this->search)
            ->orderBy('name')
            ->limit(30)
            ->get();
    }

    public function checkout(SaleService $saleService)
    {
        if (empty($this->cart)) {
            $this->errorMessage = 'Keranjang masih kosong.';

            return;
        }

        $items = collect($this->cart)
            ->map(fn ($item) => ['product_id' => $item['product_id'], 'quantity' => $item['quantity']])
            ->values()
            ->all();

        try {
            $sale = $saleService->create(
                cartItems: $items,
                payload: [
                    'customer_name' => $this->customerName,
                    'discount' => $this->discount,
                    'payment_method' => $this->paymentMethod,
                    'paid_amount' => $this->paidAmount ?? $this->total,
                ],
                cashier: auth()->user(),
            );
        } catch (\RuntimeException $e) {
            $this->errorMessage = $e->getMessage();

            return;
        }

        return redirect()->route('sales.show', $sale)->with('status', 'Transaksi berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.pos-terminal');
    }
}
