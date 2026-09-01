@extends('layouts.app')

@section('title', 'Penjualan')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold">Riwayat Penjualan</h1>
        <a href="{{ route('sales.create') }}" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-md text-sm font-medium">
            + Transaksi Baru
        </a>
    </div>

    <form method="GET" class="mb-4">
        <select name="status" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm text-sm">
            <option value="">Semua Status</option>
            <option value="paid" @selected(request('status') === 'paid')>Lunas</option>
            <option value="partial" @selected(request('status') === 'partial')>Sebagian</option>
            <option value="unpaid" @selected(request('status') === 'unpaid')>Belum Bayar</option>
        </select>
    </form>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    <th class="px-4 py-3">Invoice</th>
                    <th class="px-4 py-3">Waktu</th>
                    <th class="px-4 py-3">Kasir</th>
                    <th class="px-4 py-3">Pelanggan</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr class="border-t hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('sales.show', $sale) }}'">
                        <td class="px-4 py-3 font-medium">{{ $sale->invoice_number }}</td>
                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ $sale->created_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $sale->cashier?->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $sale->customer_name ?? '-' }}</td>
                        <td class="px-4 py-3 text-right font-medium">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            @php
                                $badge = match($sale->status) {
                                    'paid' => 'bg-green-50 text-green-700',
                                    'partial' => 'bg-amber-50 text-amber-700',
                                    default => 'bg-red-50 text-red-700',
                                };
                                $label = match($sale->status) {
                                    'paid' => 'Lunas',
                                    'partial' => 'Sebagian',
                                    default => 'Belum Bayar',
                                };
                            @endphp
                            <span class="px-2 py-0.5 rounded text-xs font-medium {{ $badge }}">{{ $label }}</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $sales->links() }}</div>
@endsection
