<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $movements = StockMovement::with(['product', 'creator'])
            ->when($request->query('product_id'), fn ($q, $id) => $q->where('product_id', $id))
            ->latest('created_at')
            ->paginate(30)
            ->withQueryString();

        $products = Product::active()->orderBy('name')->get();

        return view('stock.index', compact('movements', 'products'));
    }

    public function adjust(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type' => ['required', 'in:in,out'],
            'quantity' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        try {
            StockMovement::record(
                product: $product,
                type: $data['type'],
                quantity: $data['quantity'],
                reason: 'adjustment',
                note: $data['note'] ?? 'Penyesuaian manual',
                createdBy: $request->user()->id,
            );
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('status', 'Stok berhasil disesuaikan.');
    }
}
