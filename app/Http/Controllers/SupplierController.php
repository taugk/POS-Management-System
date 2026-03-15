<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Menampilkan daftar supplier dengan pagination dan pencarian.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        // Fitur Pencarian (Opsional)
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('company_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
        }

        $suppliers = $query->latest()->paginate(10);

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Menampilkan form untuk menambah supplier baru.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Menyimpan data supplier baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone'        => 'required|string|max:20',
            'email'        => 'nullable|email|unique:suppliers,email',
            'address'      => 'nullable|string',
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan ke sistem.');
    }

    /**
     * Menampilkan detail lengkap supplier dan daftar produk yang mereka pasok.
     */
    public function show($id)
    {
        // Eager load relasi produk agar tidak terjadi N+1 query
        $supplier = Supplier::with(['products.category'])->findOrFail($id);

        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Menampilkan form untuk mengedit data supplier.
     */
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Memperbarui data supplier di database.
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone'        => 'required|string|max:20',
            'email'        => 'nullable|email|unique:suppliers,email,' . $supplier->id,
            'address'      => 'nullable|string',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Informasi supplier berhasil diperbarui.');
    }

    /**
     * Menghapus supplier dari database.
     */
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);

        // Proteksi: Cek apakah supplier masih memiliki produk terkait
        if ($supplier->products()->count() > 0) {
            return back()->with('error', 'Gagal menghapus! Supplier ini masih terhubung dengan beberapa produk.');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier telah berhasil dihapus dari sistem.');
    }
}