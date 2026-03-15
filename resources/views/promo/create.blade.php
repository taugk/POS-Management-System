@extends('layouts.app')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="p-4 md:p-8 bg-gray-50 min-h-screen" x-data="{ promoType: 'percentage' }">
    <div class="max-w-4xl mx-auto">
        <form action="{{ route('promos.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 bg-indigo-600 text-white">
                    <h2 class="text-2xl font-black uppercase tracking-tight">Buat Promo Baru</h2>
                    <p class="text-indigo-100 text-sm">Tentukan aturan main diskon Anda untuk menarik pelanggan.</p>
                </div>

                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama & Kode --}}
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama Promo</label>
                        <input type="text" name="name" required placeholder="Contoh: Diskon Akhir Tahun" class="w-full border-gray-200 rounded-2xl px-4 py-3 focus:ring-indigo-500">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Kode Kupon (Unik)</label>
                        <input type="text" name="code" required placeholder="Contoh: NEWYEAR2026" class="w-full border-gray-200 rounded-2xl px-4 py-3 font-mono font-bold text-indigo-600 uppercase">
                    </div>

                    {{-- Type & Value --}}
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Tipe Diskon</label>
                        <select name="type" x-model="promoType" class="w-full border-gray-200 rounded-2xl px-4 py-3">
                            <option value="percentage">Persentase (%)</option>
                            <option value="fixed">Nominal Tetap (Rp)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nilai Potongan</label>
                        <input type="number" name="discount_amount" required placeholder="0" class="w-full border-gray-200 rounded-2xl px-4 py-3">
                    </div>

                    {{-- Max Discount (Conditional) --}}
                    <div class="md:col-span-2" x-show="promoType === 'percentage'" x-transition x-cloak>
                        <label class="block text-[10px] font-black text-amber-500 uppercase tracking-widest mb-2">Maksimal Potongan (Opsional)</label>
                        <input type="number" name="max_discount" placeholder="Tanpa batas" class="w-full border-amber-200 bg-amber-50/30 rounded-2xl px-4 py-3 focus:ring-amber-500">
                    </div>

                    {{-- Syarat & Limit --}}
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Minimal Belanja (Rp)</label>
                        <input type="number" name="min_purchase" required value="0" class="w-full border-gray-200 rounded-2xl px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Kuota Penggunaan (Opsional)</label>
                        <input type="number" name="usage_limit" placeholder="Tanpa batas" class="w-full border-gray-200 rounded-2xl px-4 py-3">
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Mulai Berlaku</label>
                        <input type="date" name="start_date" required value="{{ date('Y-m-d') }}" class="w-full border-gray-200 rounded-2xl px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Berakhir Pada</label>
                        <input type="date" name="end_date" required class="w-full border-gray-200 rounded-2xl px-4 py-3">
                    </div>

                    <div class="md:col-span-2 flex items-center gap-2 py-4 border-t border-gray-50">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded-md text-indigo-600 focus:ring-indigo-500 w-5 h-5">
                        <span class="text-sm font-bold text-gray-700">Aktifkan Promo Ini Sekarang</span>
                    </div>
                </div>

                <div class="p-8 bg-gray-50 flex justify-end gap-3">
                    <a href="{{ route('promos.index') }}" class="px-8 py-3 text-sm font-bold text-gray-500 hover:text-gray-700">Batal</a>
                    <button type="submit" class="bg-indigo-600 text-white px-10 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-100 transition active:scale-95">Simpan Promo</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection