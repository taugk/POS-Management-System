<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\Promo;
use App\Models\Sales_items;
use App\Models\Sales;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Halaman List Transaksi (Admin View)
     */
    public function index(Request $request)
    {
        $query = Sales::with(['user', 'items']);

        if ($request->filled('date')) {
            $query->whereDate('sale_date', $request->date);
        }

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%');
        }

        $sales = $query->latest()->paginate(15);

        $summary = [
            'total_revenue' => Sales::whereDate('sale_date', now())->sum('total_price'),
            'total_transactions' => Sales::whereDate('sale_date', now())->count(),
        ];

        return view('sales.index', compact('sales', 'summary'));
    }

    /**
     * Halaman Kasir
     */
    public function create()
    {
        // Mengambil produk yang stoknya masih tersedia
        $products = products::where('stock', '>', 0)->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'pay_amount' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'promo_id' => 'nullable|exists:promos,id', 
        ]);

        DB::beginTransaction();
        try {
            $subtotalPrice = 0;
            $itemsData = [];

            // 1. Hitung Subtotal dan Cek Stok
            foreach ($request->items as $item) {
                $product = products::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new Exception("Stok {$product->name} tidak mencukupi.");
                }

                $itemSubtotal = $product->price * $item['quantity'];
                $subtotalPrice += $itemSubtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal'   => $itemSubtotal,
                ];

                $product->decrement('stock', $item['quantity']);
            }

            // 2. Logika Promo & Diskon (Server-side Calculation)
            $discountAmount = 0;
            if ($request->filled('promo_id')) {
                $promo = Promo::find($request->promo_id);
                
                // Validasi ulang di sisi server untuk keamanan
                if ($promo && $promo->isValid() && $subtotalPrice >= $promo->min_purchase) {
                    $discountAmount = $promo->calculateDiscount($subtotalPrice);
                    
                    // Tambah jumlah penggunaan promo
                    $promo->increment('used_count');
                } else {
                    // Jika promo tiba-tiba tidak valid (misal: kuota habis saat transaksi)
                    throw new Exception("Kupon promo tidak dapat digunakan.");
                }
            }

            // 3. Kalkulasi Total Akhir
            // Sesuai aturan POS sebelumnya: Pajak dihitung dari (Subtotal - Diskon)
            $taxableAmount = max(0, $subtotalPrice - $discountAmount);
            $totalPrice = $taxableAmount + $request->tax_amount;
            $changeAmount = $request->pay_amount - $totalPrice;

            if ($changeAmount < 0) {
                throw new Exception("Uang pembayaran tidak mencukupi.");
            }

            // 4. Simpan Header Penjualan
            $sale = Sales::create([
                'invoice_number' => 'INV-' . date('YmdHis') . rand(10, 99),
                'user_id'        => Auth::id() ?? 1,
                'promo_id'       => $request->promo_id, // Simpan ID promo
                'discount_amount'=> $discountAmount,    // Simpan nilai rupiah diskon
                'total_price'    => $totalPrice,
                'tax_amount'     => $request->tax_amount,
                'pay_amount'     => $request->pay_amount,
                'change_amount'  => $changeAmount,
                'sale_date'      => now(),
                'status'         => 'completed',
            ]);

            // 5. Simpan Detail Item
            foreach ($itemsData as $data) {
                $sale->items()->create($data);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi Berhasil!',
                'data'    => $sale->load(['items.product', 'user'])
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Detail Transaksi
     */
    public function show($id)
    {
        $sale = Sales::with(['user', 'items.product'])->findOrFail($id);
        return view('sales.show', compact('sale'));
    }

    /**
     * Hapus Transaksi (Rollback Stok)
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $sale = Sales::findOrFail($id);
            
            foreach ($sale->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            $sale->delete();
            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Transaksi dibatalkan.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan transaksi.');
        }
    }
}