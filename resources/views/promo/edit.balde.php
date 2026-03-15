@extends('layouts.app')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="p-4 md:p-8 bg-gray-50 min-h-screen" x-data="{ promoType: '{{ $promo->type }}' }">
    <div class="max-w-4xl mx-auto">
        <form action="{{ route('promos.update', $promo->id) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 bg-amber-500 text-white">
                    <h2 class="text-2xl font-black uppercase tracking-tight">Edit Promo: {{ $promo->code }}</h2>
                </div>

                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Isian sama dengan Create, tambahkan value dari $promo --}}
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama Promo</label>
                        <input type="text" name="name" value="{{ $promo->name }}" required class="w-full border-gray-200 rounded-2xl px-4 py-3">
                    </div>
                    {{-- ... Field lainnya menyesuaikan ... --}}
                    
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Tipe Diskon</label>
                        <select name="type" x-model="promoType" class="w-full border-gray-200 rounded-2xl px-4 py-3">
                            <option value="percentage">Persentase (%)</option>
                            <option value="fixed">Nominal Tetap (Rp)</option>
                        </select>
                    </div>
                </div>

                <div class="p-8 bg-gray-50 flex justify-end gap-3">
                    <button type="submit" class="bg-indigo-600 text-white px-10 py-3 rounded-2xl font-bold">Update Promo</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection