@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-4xl">
    <h1 class="text-2xl font-bold mb-6">Buat Pesanan ke Supplier</h1>

    <form action="{{ route('purchases.store') }}" method="POST" class="bg-white p-6 rounded-xl shadow-sm border">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Pilih Supplier</label>
                <select name="supplier_id" class="w-full border-gray-300 rounded-lg mt-1">
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->company_name }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Pesan</label>
                <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-lg mt-1">
            </div>
        </div>

        <div class="mb-6">
            <h3 class="font-bold text-gray-800 mb-2 italic">Daftar Item</h3>
            <table class="w-full text-left border" id="items-table">
                <thead class="bg-gray-50 text-xs uppercase">
                    <tr>
                        <th class="p-3">Produk</th>
                        <th class="p-3">Qty</th>
                        <th class="p-3">Harga Beli (Satuan)</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="item-row">
                        <td class="p-2">
                            <select name="items[0][product_id]" class="w-full border-gray-200 rounded-md text-sm">
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="p-2"><input type="number" name="items[0][quantity]" class="w-20 border-gray-200 rounded-md text-sm" value="1"></td>
                        <td class="p-2"><input type="number" name="items[0][cost_price]" class="w-32 border-gray-200 rounded-md text-sm" placeholder="Rp"></td>
                        <td class="p-2"><button type="button" class="text-red-500 font-bold">X</button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="mt-2 text-sm text-indigo-600 font-bold">+ Tambah Baris</button>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Status Pesanan</label>
            <select name="status" class="w-full border-gray-300 rounded-lg mt-1">
                <option value="pending">Pending (Rencana)</option>
                <option value="ordered">Ordered (Sudah Dipesan)</option>
                <option value="received">Received (Barang Sudah Sampai & Stok Bertambah)</option>
            </select>
        </div>

        <div class="flex justify-end gap-3 border-t pt-6">
            <a href="{{ route('purchases.index') }}" class="px-4 py-2 bg-gray-100 rounded-lg text-sm">Batal</a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-bold shadow-md">Simpan & Proses</button>
        </div>
    </form>
</div>
@endsection