@extends('layouts.app')

@section('title', 'Detail Produk: ' . $product->name)

@section('content')
<div class="container mx-auto max-w-5xl">
    <div class="mb-6 flex items-center justify-between">
        <nav class="flex text-sm text-gray-500 gap-2">
            <a href="{{ route('products.index') }}" class="hover:text-indigo-600 transition">Products</a>
            <span>/</span>
            <span class="text-gray-800 font-medium">Detail</span>
        </nav>
        <a href="{{ route('products.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-96 object-cover">
                @else
                    <div class="w-full h-80 bg-gray-50 flex flex-col items-center justify-center border-b border-dashed border-gray-200">
                        <svg class="w-20 h-20 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-gray-400">Tidak ada gambar tersedia</p>
                    </div>
                @endif

                <div class="p-6">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-full bg-indigo-100 text-indigo-700">
                            {{ $product->category->name ?? 'Uncategorized' }}
                        </span>
                        
                        <span class="px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-full bg-emerald-100 text-emerald-700 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            {{ $product->supplier->name ?? 'Tanpa Supplier' }}
                        </span>

                        <span class="text-gray-400 text-xs tracking-tighter italic ml-auto">ID: #{{ $product->id }}</span>
                    </div>
                    
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-4">{{ $product->name }}</h1>
                    
                    <div class="prose prose-indigo max-w-none">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Deskripsi Produk</h3>
                        <p class="text-gray-600 leading-relaxed italic">
                            {{ $product->description ?? 'Deskripsi produk belum ditambahkan.' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <div class="bg-white p-2 rounded-lg shadow-sm">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs">Tanggal Dibuat</p>
                            <p class="font-semibold text-gray-800">{{ $product->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <div class="bg-white p-2 rounded-lg shadow-sm">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs">Pembaruan Terakhir</p>
                            <p class="font-semibold text-gray-800">{{ $product->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-6">
                <div class="mb-6 pb-6 border-b border-gray-100">
                    <p class="text-sm font-medium text-gray-500 mb-1">Harga Satuan</p>
                    <h2 class="text-4xl font-bold text-gray-900 italic">Rp {{ number_format($product->price, 0, ',', '.') }}</h2>
                </div>

                <div class="mb-6 pb-6 border-b border-gray-100">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium text-gray-500">Persediaan Stok</p>
                        <span class="text-sm font-bold {{ $product->stock <= 5 ? 'text-red-600' : 'text-green-600' }}">
                            {{ $product->stock }} Unit
                        </span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                        <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" 
                             style="width: {{ $product->stock > 0 ? min(($product->stock / 50) * 100, 100) : 0 }}%"></div>
                    </div>
                </div>

                @if($product->supplier)
                <div class="mb-8 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                    <p class="text-[10px] uppercase font-bold text-emerald-600 mb-2 tracking-widest">Informasi Pemasok</p>
                    <div class="flex flex-col gap-1">
                        <p class="text-sm font-bold text-emerald-900">{{ $product->supplier->name }}</p>
                        <p class="text-xs text-emerald-700 italic">{{ $product->supplier->company_name ?? 'Personal' }}</p>
                        <a href="{{ route('suppliers.show', $product->supplier->id) }}" class="text-[10px] text-indigo-600 font-bold hover:underline mt-2 flex items-center">
                            Lihat Profil Supplier
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
                @endif

                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('products.edit', $product->id) }}" class="w-full flex items-center justify-center bg-indigo-600 text-white px-4 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2-0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Data
                    </a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini secara permanen?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center bg-white text-red-600 border border-red-100 px-4 py-3 rounded-xl font-bold hover:bg-red-50 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus Produk
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection