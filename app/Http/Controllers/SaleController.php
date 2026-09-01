<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /** Riwayat transaksi (halaman kasir/POS ada di App\Livewire\PosTerminal) */
    public function index(Request $request)
    {
        $sales = Sale::with('cashier')
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('sales.index', compact('sales'));
    }

    public function show(Sale $sale)
    {
        $sale->load(['items', 'cashier']);

        return view('sales.show', compact('sale'));
    }
}
