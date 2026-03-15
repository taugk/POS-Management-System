@extends('layouts.app')

@section('title', 'Catat Pengeluaran Baru')

@section('content')
<div class="p-4 md:p-8 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto">
        {{-- BREADCRUMB & HEADER --}}
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
            <a href="{{ route('expenses.index') }}" class="hover:text-indigo-600">Expenses</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-900 font-medium">Buat Baru</span>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                <h2 class="text-xl font-bold text-gray-800">Form Catat Biaya</h2>
                <p class="text-xs text-gray-500">Pastikan data pengeluaran diisi dengan benar untuk akurasi laporan keuangan.</p>
            </div>

            <form action="{{ route('expenses.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama Pengeluaran --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Nama / Deskripsi Pengeluaran</label>
                        <input type="text" name="name" required placeholder="Contoh: Pembayaran Listrik Toko Maret" 
                               class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jumlah (Amount) --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Jumlah (Rp)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 font-bold">Rp</span>
                            <input type="number" name="amount" required placeholder="0" 
                                   class="w-full border-gray-200 rounded-xl pl-12 pr-4 py-3 focus:ring-2 focus:ring-indigo-500 transition">
                        </div>
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Tanggal Pengeluaran</label>
                        <input type="date" name="expense_date" required value="{{ date('Y-m-d') }}"
                               class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition">
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Kategori</label>
                        <select name="category" class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="Operasional">Operasional</option>
                            <option value="Gaji Karyawan">Gaji Karyawan</option>
                            <option value="Sewa Tempat">Sewa Tempat</option>
                            <option value="Listrik & Air">Listrik & Air</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    {{-- Catatan Tambahan --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" rows="3" placeholder="Tambahkan keterangan detail jika diperlukan..." 
                                  class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-50">
                    <a href="{{ route('expenses.index') }}" class="px-6 py-3 text-sm font-bold text-gray-500 hover:text-gray-700 transition">Batal</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-indigo-200 transition active:scale-95">
                        Simpan Pengeluaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection