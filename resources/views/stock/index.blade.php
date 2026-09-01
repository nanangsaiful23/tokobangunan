@extends('layouts.app')

@section('title', 'Stok')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Stok</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Riwayat pergerakan stok --}}
        <div class="lg:col-span-2">
            <form method="GET" class="mb-4">
                <select name="product_id" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm text-sm">
                    <option value="">Semua Produk</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @selected(request('product_id') == $product->id)>{{ $product->name }}</option>
                    @endforeach
                </select>
            </form>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 text-left">
                        <tr>
                            <th class="px-4 py-3">Waktu</th>
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3">Tipe</th>
                            <th class="px-4 py-3">Alasan</th>
                            <th class="px-4 py-3 text-right">Jumlah</th>
                            <th class="px-4 py-3">Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($movements as $movement)
                            <tr class="border-t">
                                <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ $movement->created_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-3 font-medium">{{ $movement->product?->name }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ $movement->type === 'in' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                        {{ $movement->type === 'in' ? 'Masuk' : 'Keluar' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-500 capitalize">{{ $movement->reason }}</td>
                                <td class="px-4 py-3 text-right font-medium">
                                    {{ $movement->type === 'in' ? '+' : '-' }}{{ $movement->quantity }}
                                </td>
                                <td class="px-4 py-3 text-gray-500">{{ $movement->creator?->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">Belum ada pergerakan stok.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $movements->links() }}</div>
        </div>

        {{-- Form penyesuaian manual --}}
        <div class="bg-white rounded-lg shadow-sm p-4 h-fit">
            <h2 class="font-medium mb-3">Penyesuaian Stok Manual</h2>
            <form method="POST" action="{{ route('stock.adjust') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Produk</label>
                    <select name="product_id" required class="w-full rounded-md border-gray-300 shadow-sm text-sm">
                        <option value="">- Pilih produk -</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (stok: {{ $product->current_stock }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tipe</label>
                    <select name="type" required class="w-full rounded-md border-gray-300 shadow-sm text-sm">
                        <option value="in">Tambah Stok (Masuk)</option>
                        <option value="out">Kurangi Stok (Keluar)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Jumlah</label>
                    <input type="number" name="quantity" min="1" required class="w-full rounded-md border-gray-300 shadow-sm text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Catatan (opsional)</label>
                    <textarea name="note" rows="2" placeholder="mis. koreksi stok opname, barang rusak, dll"
                              class="w-full rounded-md border-gray-300 shadow-sm text-sm"></textarea>
                </div>
                <button class="w-full bg-slate-800 hover:bg-slate-900 text-white rounded-md py-2 text-sm font-medium">
                    Simpan Penyesuaian
                </button>
            </form>
        </div>
    </div>
@endsection
