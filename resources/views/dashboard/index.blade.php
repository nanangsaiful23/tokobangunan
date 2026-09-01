@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-4">
            <p class="text-sm text-gray-500">Transaksi Hari Ini</p>
            <p class="text-2xl font-semibold mt-1">{{ $summary['today_transaction_count'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4">
            <p class="text-sm text-gray-500">Omzet Hari Ini</p>
            <p class="text-2xl font-semibold mt-1">Rp {{ number_format($summary['today_revenue'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4">
            <p class="text-sm text-gray-500">Piutang Belum Lunas (Hari Ini)</p>
            <p class="text-2xl font-semibold mt-1">Rp {{ number_format($summary['today_outstanding'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 {{ $summary['low_stock_count'] > 0 ? 'ring-1 ring-amber-300' : '' }}">
            <p class="text-sm text-gray-500">Produk Stok Menipis</p>
            <p class="text-2xl font-semibold mt-1 {{ $summary['low_stock_count'] > 0 ? 'text-amber-600' : '' }}">
                {{ $summary['low_stock_count'] }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-4">
            <h2 class="font-medium mb-3">⚠️ Stok Menipis</h2>
            @forelse ($lowStockProducts as $product)
                <div class="flex justify-between items-center py-2 border-b last:border-0 text-sm">
                    <div>
                        <p class="font-medium">{{ $product->name }}</p>
                        <p class="text-gray-400">{{ $product->sku }}</p>
                    </div>
                    <span class="text-amber-600 font-medium">{{ $product->current_stock }} {{ $product->unit }}</span>
                </div>
            @empty
                <p class="text-sm text-gray-400">Tidak ada produk dengan stok menipis. 👍</p>
            @endforelse
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4">
            <h2 class="font-medium mb-3">🧾 Transaksi Terbaru</h2>
            @forelse ($recentSales as $sale)
                <a href="{{ route('sales.show', $sale) }}" class="flex justify-between items-center py-2 border-b last:border-0 text-sm hover:bg-gray-50 -mx-2 px-2 rounded">
                    <div>
                        <p class="font-medium">{{ $sale->invoice_number }}</p>
                        <p class="text-gray-400">{{ $sale->cashier?->name }} · {{ $sale->created_at->format('d M H:i') }}</p>
                    </div>
                    <span class="font-medium">Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
                </a>
            @empty
                <p class="text-sm text-gray-400">Belum ada transaksi hari ini.</p>
            @endforelse
        </div>
    </div>
@endsection
