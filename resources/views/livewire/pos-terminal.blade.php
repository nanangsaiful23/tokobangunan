<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Katalog produk --}}
    <div class="lg:col-span-2">
        <h1 class="text-xl font-semibold mb-4">Kasir (POS)</h1>

        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama, SKU, atau barcode..."
               class="w-full rounded-md border-gray-300 shadow-sm text-sm mb-4">

        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @forelse ($this->products as $product)
                <button wire:click="addToCart('{{ $product->id }}')" type="button"
                        @if($product->current_stock <= 0) disabled @endif
                        class="text-left bg-white rounded-lg shadow-sm p-3 hover:ring-2 hover:ring-slate-400 disabled:opacity-40 disabled:cursor-not-allowed">
                    <p class="font-medium text-sm">{{ $product->name }}</p>
                    <p class="text-xs text-gray-400">{{ $product->sku }}</p>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-sm font-semibold">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</span>
                        <span class="text-xs {{ $product->isLowStock() ? 'text-amber-600 font-medium' : 'text-gray-400' }}">
                            Stok: {{ $product->current_stock }}
                        </span>
                    </div>
                </button>
            @empty
                <p class="col-span-3 text-sm text-gray-400 py-8 text-center">Produk tidak ditemukan.</p>
            @endforelse
        </div>
    </div>

    {{-- Keranjang --}}
    <div class="bg-white rounded-lg shadow-sm p-4 h-fit sticky top-4">
        <h2 class="font-medium mb-3">Keranjang</h2>

        @if ($errorMessage)
            <div class="mb-3 rounded-md bg-red-50 border border-red-200 text-red-700 px-3 py-2 text-xs">
                {{ $errorMessage }}
            </div>
        @endif

        <div class="space-y-2 max-h-64 overflow-y-auto mb-3">
            @forelse ($cart as $productId => $item)
                <div class="flex justify-between items-center text-sm border-b pb-2">
                    <div class="flex-1">
                        <p class="font-medium">{{ $item['name'] }}</p>
                        <p class="text-xs text-gray-400">Rp {{ number_format($item['price'], 0, ',', '.') }} / unit</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="decrementQty('{{ $productId }}')" type="button" class="w-6 h-6 rounded bg-gray-100 hover:bg-gray-200">-</button>
                        <span class="w-6 text-center">{{ $item['quantity'] }}</span>
                        <button wire:click="incrementQty('{{ $productId }}')" type="button" class="w-6 h-6 rounded bg-gray-100 hover:bg-gray-200">+</button>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-4">Keranjang kosong. Klik produk di sebelah kiri.</p>
            @endforelse
        </div>

        <div class="border-t pt-3 space-y-2">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Pelanggan (opsional)</label>
                <input type="text" wire:model="customerName" class="w-full rounded-md border-gray-300 shadow-sm text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Diskon (Rp)</label>
                <input type="number" min="0" wire:model.live="discount" class="w-full rounded-md border-gray-300 shadow-sm text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Metode Pembayaran</label>
                <select wire:model="paymentMethod" class="w-full rounded-md border-gray-300 shadow-sm text-sm">
                    <option value="cash">Tunai</option>
                    <option value="qris">QRIS</option>
                    <option value="transfer">Transfer Bank</option>
                    <option value="credit">Kredit / Piutang</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Jumlah Dibayar (Rp)</label>
                <input type="number" min="0" wire:model="paidAmount" placeholder="{{ (int) $this->total }}"
                       class="w-full rounded-md border-gray-300 shadow-sm text-sm">
                <p class="text-xs text-gray-400 mt-1">Kosongkan jika dibayar penuh (lunas).</p>
            </div>
        </div>

        <div class="border-t mt-3 pt-3 space-y-1 text-sm">
            <div class="flex justify-between text-gray-500">
                <span>Subtotal</span>
                <span>Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-gray-500">
                <span>Diskon</span>
                <span>- Rp {{ number_format($discount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between font-semibold text-base pt-1">
                <span>Total</span>
                <span>Rp {{ number_format($this->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <button wire:click="checkout" type="button" wire:loading.attr="disabled"
                class="w-full mt-4 bg-slate-800 hover:bg-slate-900 disabled:opacity-50 text-white rounded-md py-2.5 font-medium">
            <span wire:loading.remove>Selesaikan Transaksi</span>
            <span wire:loading>Memproses...</span>
        </button>
    </div>
</div>
