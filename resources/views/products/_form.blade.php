@php $p = $product; @endphp

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
        <input type="text" name="sku" value="{{ old('sku', $p?->sku) }}" required
               class="w-full rounded-md border-gray-300 shadow-sm text-sm">
        @error('sku') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Barcode (opsional)</label>
        <input type="text" name="barcode" value="{{ old('barcode', $p?->barcode) }}"
               class="w-full rounded-md border-gray-300 shadow-sm text-sm">
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
    <input type="text" name="name" value="{{ old('name', $p?->name) }}" required
           class="w-full rounded-md border-gray-300 shadow-sm text-sm">
    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
        <select name="category_id" class="w-full rounded-md border-gray-300 shadow-sm text-sm">
            <option value="">- Tanpa kategori -</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $p?->category_id) == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
        <input type="text" name="unit" value="{{ old('unit', $p?->unit ?? 'pcs') }}" required placeholder="pcs, sak, batang, m3..."
               class="w-full rounded-md border-gray-300 shadow-sm text-sm">
    </div>
</div>

<div class="grid grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli (Modal)</label>
        <input type="number" step="0.01" min="0" name="cost_price" value="{{ old('cost_price', $p?->cost_price ?? 0) }}" required
               class="w-full rounded-md border-gray-300 shadow-sm text-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual</label>
        <input type="number" step="0.01" min="0" name="sell_price" value="{{ old('sell_price', $p?->sell_price ?? 0) }}" required
               class="w-full rounded-md border-gray-300 shadow-sm text-sm">
        @error('sell_price') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Ambang Batas Stok Menipis</label>
        <input type="number" min="0" name="reorder_threshold" value="{{ old('reorder_threshold', $p?->reorder_threshold ?? 0) }}" required
               class="w-full rounded-md border-gray-300 shadow-sm text-sm">
    </div>
</div>

@if (! $p)
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Stok Awal</label>
        <input type="number" min="0" name="initial_stock" value="{{ old('initial_stock', 0) }}"
               class="w-full rounded-md border-gray-300 shadow-sm text-sm">
        <p class="text-xs text-gray-400 mt-1">Akan tercatat otomatis sebagai pergerakan stok masuk.</p>
    </div>
@endif
