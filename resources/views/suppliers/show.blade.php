@extends('layouts.app')

@section('title', 'Detail Supplier: ' . $supplier->name)

@section('content')
<div class="container mx-auto max-w-5xl">
    <div class="mb-6 flex items-center justify-between">
        <nav class="flex text-sm text-gray-500 gap-2">
            <a href="{{ route('suppliers.index') }}" class="hover:text-indigo-600 transition">Supplier</a>
            <span>/</span>
            <span class="text-gray-800 font-medium">Profil Detail</span>
        </nav>
        <a href="{{ route('suppliers.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col items-center text-center mb-6">
                    <div class="h-20 w-20 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center text-3xl font-bold mb-4">
                        {{ substr($supplier->name, 0, 1) }}
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $supplier->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $supplier->company_name ?? 'Personal Supplier' }}</p>
                </div>

                <div class="space-y-4 border-t pt-6">
                    <div class="flex items-start gap-3">
                        <div class="text-indigo-500 mt-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold">Telepon</p>
                            <p class="text-sm text-gray-800 font-medium">{{ $supplier->phone }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="text-indigo-500 mt-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold">Email</p>
                            <p class="text-sm text-gray-800 font-medium">{{ $supplier->email ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="text-indigo-500 mt-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold">Alamat</p>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $supplier->address ?? 'Alamat belum diatur' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-2">
                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="w-full flex items-center justify-center bg-indigo-600 text-white px-4 py-2 rounded-xl font-bold hover:bg-indigo-700 transition">
                        Edit Profil
                    </a>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                    <p class="text-sm text-gray-500">Total Produk Disuplai</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $supplier->products->count() }} <span class="text-sm font-normal text-gray-400">Item</span></p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                    <p class="text-sm text-gray-500">Bergabung Sejak</p>
                    <p class="text-xl font-bold text-gray-900">{{ $supplier->created_at->format('d M Y') }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Daftar Produk dari Supplier Ini</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold">
                            <tr>
                                <th class="px-6 py-4">Produk</th>
                                <th class="px-6 py-4">Stok</th>
                                <th class="px-6 py-4">Harga</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($supplier->products as $product)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $product->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $product->category->name ?? 'No Category' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm {{ $product->stock <= 5 ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                                            {{ $product->stock }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-gray-400 italic text-sm">
                                        Supplier ini belum memiliki produk yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection