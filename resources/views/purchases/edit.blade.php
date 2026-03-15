@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-lg">
    <h1 class="text-2xl font-bold mb-6 italic text-gray-800">Update Status Pesanan</h1>

    <form action="{{ route('purchases.update', $purchase->id) }}" method="POST" class="bg-white p-6 rounded-xl shadow-sm border">
        @csrf @method('PUT')
        
        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-400 uppercase tracking-widest">Referensi</label>
            <p class="text-lg font-bold text-indigo-600">{{ $purchase->reference_number }}</p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Status Pesanan</label>
            <select name="status" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500">
                <option value="pending" {{ $purchase->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="ordered" {{ $purchase->status == 'ordered' ? 'selected' : '' }}>Ordered</option>
                <option value="received" {{ $purchase->status == 'received' ? 'selected' : '' }}>Received (Stok akan otomatis bertambah)</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
            <textarea name="notes" rows="3" class="w-full border-gray-300 rounded-lg">{{ $purchase->notes }}</textarea>
        </div>

        <div class="flex justify-end gap-3 border-t pt-6">
            <a href="{{ route('purchases.index') }}" class="px-4 py-2 text-gray-500 bg-gray-100 rounded-lg">Batal</a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-bold shadow-md">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection