<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    /**
     * Menampilkan daftar promo dengan fitur pencarian.
     */
    public function index(Request $request)
    {
        $query = Promo::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        $promos = $query->latest()->paginate(10);
        return view('promo.index', compact('promos'));
    }

    /**
     * Menampilkan form pembuatan promo.
     */
    public function create()
    {
        return view('promo.create');
    }

    /**
     * Menyimpan promo baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:promos,code|max:50',
            'type' => 'required|in:percentage,fixed',
            'discount_amount' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_purchase' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean'
        ]);

        Promo::create($request->all());

        return redirect()->route('promos.index')
            ->with('success', 'Promo berhasil dibuat.');
    }

    /**
     * Menampilkan detail promo (opsional, bisa digunakan untuk melihat statistik penggunaan).
     */
    public function show(Promo $promo)
    {
        return view('promo.show', compact('promo'));
    }

    /**
     * Menampilkan form edit promo.
     */
    public function edit(Promo $promo)
    {
        return view('promo.edit', compact('promo'));
    }

    /**
     * Memperbarui data promo.
     */
    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:promos,code,' . $promo->id,
            'type' => 'required|in:percentage,fixed',
            'discount_amount' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_purchase' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean'
        ]);

        // Mengambil is_active secara manual jika menggunakan checkbox di view
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $promo->update($data);

        return redirect()->route('promos.index')
            ->with('success', 'Promo berhasil diperbarui.');
    }

    /**
     * Menghapus promo.
     */
    public function destroy(Promo $promo)
    {
        $promo->delete();

        return redirect()->route('promos.index')
            ->with('success', 'Promo berhasil dihapus.');
    }

    /**
     * Method tambahan (API) untuk mengecek kode promo saat transaksi di kasir (POS).
     */
    
    public function checkPromo(Request $request)
{
    $promo = Promo::where('code', $request->code)->first();

    // 1. Cek keberadaan kode
    if (!$promo) {
        return response()->json(['valid' => false, 'message' => 'Kode promo tidak ditemukan.'], 404);
    }

    // 2. Cek Status Aktif (is_active)
    if (!$promo->is_active) {
        return response()->json(['valid' => false, 'message' => 'Maaf, promo ini sedang tidak aktif.'], 422);
    }

    // 3. Cek Rentang Tanggal (Expired)
    $today = now()->startOfDay();
    if ($today < $promo->start_date) {
        return response()->json(['valid' => false, 'message' => 'Promo baru bisa digunakan pada ' . $promo->start_date->format('d/m/Y')], 422);
    }
    if ($today > $promo->end_date) {
        return response()->json(['valid' => false, 'message' => 'Maaf, promo ini sudah kadaluarsa.'], 422);
    }

    // 4. Cek Kuota (Usage Limit)
    if ($promo->usage_limit !== null && $promo->used_count >= $promo->usage_limit) {
        return response()->json(['valid' => false, 'message' => 'Maaf, kuota penggunaan promo ini sudah habis.'], 422);
    }

    // 5. Cek Minimal Belanja
    if ($request->subtotal < $promo->min_purchase) {
        return response()->json([
            'valid' => false, 
            'message' => 'Syarat minimal belanja Rp ' . number_format($promo->min_purchase, 0, ',', '.') . ' belum tercapai.'
        ], 422);
    }

    // Jika semua lolos
    return response()->json([
        'valid' => true,
        'promo_id' => $promo->id,
        'name' => $promo->name,
        'type' => $promo->type,
        'discount_value' => $promo->calculateDiscount($request->subtotal),
        'message' => 'Promo "' . $promo->name . '" berhasil diterapkan!'
    ]);
}
}