@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Edit Produk</h1>

    <form method="POST" action="{{ route('products.update', $product) }}" class="bg-white rounded-lg shadow-sm p-6 max-w-2xl space-y-4">
        @csrf
        @method('PUT')
        @include('products._form', ['product' => $product])
        <div class="pt-2 flex justify-between items-center">
            <div>
                <button class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-md text-sm font-medium">Simpan Perubahan</button>
                <a href="{{ route('products.index') }}" class="ml-2 text-sm text-gray-500">Batal</a>
            </div>
            <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Nonaktifkan produk ini?')">
                @csrf
                @method('DELETE')
                <button class="text-sm text-red-600 hover:underline">Nonaktifkan Produk</button>
            </form>
        </div>
    </form>

    <p class="text-xs text-gray-400 mt-3 max-w-2xl">
        Catatan: stok produk tidak bisa diubah dari sini. Gunakan menu <a href="{{ route('stock.index') }}" class="underline">Stok</a> untuk penyesuaian stok, supaya tetap tercatat di riwayat.
    </p>
@endsection
