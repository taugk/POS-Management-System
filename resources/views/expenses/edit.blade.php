@extends('layouts.app')

@section('title', 'Edit Pengeluaran')

@section('content')
<div class="p-4 md:p-8 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-amber-50/50">
                <h2 class="text-xl font-bold text-amber-800">Edit Catatan: {{ $expense->reference_number }}</h2>
                <p class="text-xs text-amber-600">Perubahan data akan memperbarui laporan keuangan secara otomatis.</p>
            </div>

            <form action="{{ route('expenses.update', $expense->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Nama / Deskripsi</label>
                        <input type="text" name="name" value="{{ $expense->name }}" required 
                               class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Jumlah (Rp)</label>
                        <input type="number" name="amount" value="{{ (int)$expense->amount }}" required 
                               class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 tracking-wider">Tanggal</label>
                        <input type="date" name="expense_date" value="{{ \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d') }}" required
                               class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-50">
                    <a href="{{ route('expenses.index') }}" class="px-6 py-3 text-sm font-bold text-gray-500 hover:text-gray-700 transition">Batal</a>
                    <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg transition active:scale-95">
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection