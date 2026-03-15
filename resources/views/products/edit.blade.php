@extends('layouts.app')

@section('title', 'Edit Produk: ' . $product->name)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-gray-700 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Edit Produk</h1>
    </div>

    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required 
                    class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 py-2 shadow-sm">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="category_id" required 
                    class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 py-2 shadow-sm">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                <select name="supplier_id" required 
                    class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 py-2 shadow-sm">
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }} ({{ $supplier->company_name ?? 'Personal' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required 
                    class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 py-2 shadow-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 italic">Rp</span>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" required 
                        class="w-full pl-10 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 py-2 shadow-sm">
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Ganti Gambar Produk (Opsional)</label>
                
                <div class="flex flex-col md:flex-row gap-4 items-start">
                    @if($product->image)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $product->image) }}" class="h-24 w-24 object-cover rounded-lg border shadow-sm">
                            <div class="absolute inset-0 bg-black bg-opacity-40 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                <span class="text-[10px] text-white font-bold">Current</span>
                            </div>
                        </div>
                    @endif

                    <div class="flex-1 w-full">
                        <input type="file" name="image" accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition border border-gray-300 rounded-lg p-1">
                        <p class="text-[10px] text-gray-400 mt-2 italic">*Biarkan kosong jika tidak ingin mengubah gambar.</p>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Produk</label>
                <textarea name="description" rows="3" 
                    class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t flex justify-end gap-3">
            <a href="{{ route('products.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition text-sm font-medium">Batal</a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition font-bold text-sm">
                Perbarui Produk
            </button>
        </div>
    </form>
</div>
@endsection