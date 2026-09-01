@extends('layouts.app')

@section('title', 'Invoice ' . $sale->invoice_number)

@section('content')
    <div class="max-w-xl mx-auto">
        <div class="flex justify-between items-center mb-4 print:hidden">
            <a href="{{ route('sales.index') }}" class="text-sm text-gray-500">&larr; Kembali ke riwayat</a>
            <button onclick="window.print()" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-md text-sm font-medium">
                🖨️ Cetak Struk
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="text-center mb-4">
                <h1 class="font-semibold text-lg">{{ config('app.name', 'Toko Bangunan') }}</h1>
                <p class="text-sm text-gray-500">{{ $sale->invoice_number }}</p>
                <p class="text-xs text-gray-400">{{ $sale->created_at->format('d M Y, H:i') }}</p>
            </div>

            <div class="text-sm text-gray-600 mb-4 space-y-1">
                <div class="flex justify-between"><span>Kasir</span><span>{{ $sale->cashier?->name }}</span></div>
                @if ($sale->customer_name)
                    <div class="flex justify-between"><span>Pelanggan</span><span>{{ $sale->customer_name }}</span></div>
                @endif
            </div>

            <table class="w-full text-sm mb-4">
                <thead class="text-gray-400 text-left border-b">
                    <tr>
                        <th class="py-2">Item</th>
                        <th class="py-2 text-right">Qty</th>
                        <th class="py-2 text-right">Harga</th>
                        <th class="py-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sale->items as $item)
                        <tr class="border-b border-dashed">
                            <td class="py-2">{{ $item->product_name }}</td>
                            <td class="py-2 text-right">{{ $item->quantity }}</td>
                            <td class="py-2 text-right">{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="py-2 text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="space-y-1 text-sm border-t pt-3">
                <div class="flex justify-between text-gray-500">
                    <span>Subtotal</span><span>Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>Diskon</span><span>- Rp {{ number_format($sale->discount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-semibold text-base">
                    <span>Total</span><span>Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-500 pt-2 border-t mt-2">
                    <span>Dibayar ({{ strtoupper($sale->payment_method) }})</span>
                    <span>Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</span>
                </div>
                @if ($sale->outstandingBalance() > 0)
                    <div class="flex justify-between text-red-600 font-medium">
                        <span>Sisa Piutang</span>
                        <span>Rp {{ number_format($sale->outstandingBalance(), 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>

            <p class="text-center text-xs text-gray-400 mt-6">Terima kasih atas kunjungan Anda 🙏</p>
        </div>
    </div>
@endsection
