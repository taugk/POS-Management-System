@extends('layouts.app')

@section('title', 'Detail Pengeluaran')

@section('content')
<div class="p-4 md:p-8 bg-gray-50 min-h-screen">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
            {{-- Badge Header --}}
            <div class="bg-indigo-600 p-8 text-center text-white">
                <div class="inline-flex p-3 bg-white/20 rounded-full mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h2 class="text-3xl font-black">Rp {{ number_format($expense->amount, 0, ',', '.') }}</h2>
                <p class="text-indigo-100 mt-1 uppercase tracking-widest text-xs font-bold">{{ $expense->reference_number }}</p>
            </div>

            <div class="p-8 space-y-6">
                <div class="grid grid-cols-2 gap-8 border-b border-gray-50 pb-6">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Nama Pengeluaran</p>
                        <p class="text-gray-800 font-bold">{{ $expense->name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Kategori</p>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-[10px] font-black uppercase">{{ $expense->category ?? 'Umum' }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-8 border-b border-gray-50 pb-6">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Tanggal Transaksi</p>
                        <p class="text-gray-800 font-medium">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Petugas (Admin)</p>
                        <p class="text-gray-800 font-medium">{{ $expense->user->name }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-2">Catatan Internal</p>
                    <div class="p-4 bg-gray-50 rounded-xl text-sm text-gray-600 leading-relaxed italic">
                        "{{ $expense->notes ?? 'Tidak ada catatan tambahan untuk transaksi ini.' }}"
                    </div>
                </div>

                <div class="flex gap-3 pt-4 no-print">
                    <a href="{{ route('expenses.index') }}" class="flex-1 text-center py-3 bg-gray-100 text-gray-600 rounded-xl font-bold hover:bg-gray-200 transition">Kembali</a>
                    <button onclick="window.print()" class="flex-1 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">Cetak Bukti</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection