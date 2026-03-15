<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('supplier')->latest()->paginate(10);
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = products::all();
        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'items' => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            $purchase = Purchase::create([
            'user_id' => Auth::id(),
                'supplier_id' => $request->supplier_id,
                'reference_number' => 'PUR-' . strtoupper(uniqid()), // Sesuai kolom migration
                'purchase_date' => $request->purchase_date,
                'total_amount' => 0,
                'status' => $request->status ?? 'pending',
                'notes' => $request->notes,
            ]);

            $totalAmount = 0;

            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['cost_price'];
                
                $purchase->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'cost_price' => $item['cost_price'],
                    'subtotal' => $subtotal,
                ]);

                $totalAmount += $subtotal;

                // Update stok jika status diterima
                if ($request->status == 'received') {
                    products::find($item['product_id'])->increment('stock', $item['quantity']);
                }
            }

            $purchase->update(['total_amount' => $totalAmount]);
        });

        return redirect()->route('purchases.index')->with('success', 'Pesanan berhasil dibuat.');
    }

    public function show($id)
    {
        $purchase = Purchase::with(['supplier', 'items.product'])->findOrFail($id);
        return view('purchases.show', compact('purchase'));
    }

    public function edit($id)
    {
        $purchase = Purchase::findOrFail($id);
        // Proteksi jika status sudah received biasanya tidak bisa diedit datanya, hanya statusnya
        return view('purchases.edit', compact('purchase'));
    }

    public function update(Request $request, $id)
    {
        $purchase = Purchase::findOrFail($id);

        if ($purchase->status == 'received') {
            return back()->with('error', 'Pesanan yang sudah diterima tidak dapat diubah.');
        }

        DB::transaction(function () use ($request, $purchase) {
            if ($request->status == 'received' && $purchase->status != 'received') {
                foreach ($purchase->items as $item) {
                    products::find($item->product_id)->increment('stock', $item->quantity);
                }
            }
            $purchase->update($request->only(['status', 'notes', 'purchase_date']));
        });

        return redirect()->route('purchases.index')->with('success', 'Status pesanan berhasil diperbarui.');
    }
}