@extends('layouts.app')

@section('title', 'Produk')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold">Produk</h1>
        <a href="{{ route('products.create') }}" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-md text-sm font-medium">
            + Tambah Produk
        </a>
    </div>

    <form method="GET" class="flex gap-2 mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, SKU, atau barcode..."
               class="flex-1 rounded-md border-gray-300 shadow-sm text-sm">
        <label class="flex items-center gap-2 text-sm bg-white px-3 rounded-md border border-gray-300">
            <input type="checkbox" name="low_stock" value="1" @checked(request('low_stock')) onchange="this.form.submit()">
            Stok menipis saja
        </label>
        <button class="bg-white border border-gray-300 px-4 rounded-md text-sm">Cari</button>
    </form>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    <th class="px-4 py-3">SKU</th>
                    <th class="px-4 py-3">Nama Produk</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3 text-right">Harga Jual</th>
                    <th class="px-4 py-3 text-right">Stok</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr class="border-t {{ $product->isLowStock() ? 'bg-amber-50' : '' }}">
                        <td class="px-4 py-3 text-gray-500">{{ $product->sku }}</td>
                        <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $product->category?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right {{ $product->isLowStock() ? 'text-amber-600 font-semibold' : '' }}">
                            {{ $product->current_stock }} {{ $product->unit }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('products.edit', $product) }}" class="text-slate-600 hover:underline">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">Belum ada produk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
@endsection
