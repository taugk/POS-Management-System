<?php

namespace App\Http\Controllers;

use App\Models\products; // Tetap gunakan 'products' sesuai nama model Anda
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    /**
     * Menampilkan daftar produk dengan relasi Kategori dan Supplier.
     */
    public function index()
    {
        // Eager loading untuk performa maksimal
        $products = products::with(['category', 'supplier'])->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    /**
     * Menampilkan form tambah produk.
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        $suppliers = Supplier::orderBy('name', 'asc')->get();
        
        return view('products.create', compact('categories', 'suppliers'));
    }

    /**
     * Menyimpan produk baru.
     */
    public function store(Request $request)
    {
        $valData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $valData['image'] = $request->file('image')->store('products', 'public');
        }

        products::create($valData);

        return redirect()->route('products.index')
                         ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail produk.
     */
    public function show($id)
    {
        $product = products::with(['category', 'supplier'])->findOrFail($id);
        return view('products.show', compact('product'));
    }

    /**
     * Menampilkan form edit produk.
     */
    public function edit($id)
    {
        $product = products::findOrFail($id);
        $categories = Category::orderBy('name', 'asc')->get();
        $suppliers = Supplier::orderBy('name', 'asc')->get();
        
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Memperbarui data produk.
     */
    public function update(Request $request, $id)
    {
        $product = products::findOrFail($id);

        $valData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada file baru yang diunggah
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $valData['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($valData);

        return redirect()->route('products.index')
                         ->with('success', 'Data produk berhasil diperbarui.');
    }

    /**
     * Menghapus produk.
     */
    public function destroy($id)
    {
        $product = products::findOrFail($id);
        
        // Hapus file gambar dari storage
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
                         ->with('success', 'Produk berhasil dihapus.');
    }
}