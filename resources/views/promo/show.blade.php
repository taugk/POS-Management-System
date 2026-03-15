@extends('layouts.app')

@section('content')
<div class="p-4 md:p-8 bg-gray-50 min-h-screen">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-[3rem] shadow-xl overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 p-12 text-center text-white relative">
                <div class="absolute top-8 right-8">
                     <span class="bg-white/20 px-4 py-1 rounded-full text-[10px] font-black tracking-widest uppercase">{{ $promo->type }}</span>
                </div>
                <h3 class="text-sm font-bold uppercase tracking-[0.3em] mb-4 opacity-70 italic">Kupon Eksklusif</h3>
                <h2 class="text-5xl font-black mb-4 tracking-tighter">{{ $promo->code }}</h2>
                <div class="text-lg font-bold">{{ $promo->name }}</div>
            </div>

            <div class="p-12 space-y-8">
                <div class="grid grid-cols-2 gap-8 text-center border-b border-gray-50 pb-8">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-2">Digunakan</p>
                        <p class="text-3xl font-black text-gray-800">{{ $promo->used_count }} <span class="text-sm text-gray-300">/ {{ $promo->usage_limit ?? '∞' }}</span></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-2">Status</p>
                        <p class="text-xl font-black {{ $promo->isValid() ? 'text-green-500' : 'text-red-500' }} uppercase tracking-widest">
                            {{ $promo->isValid() ? 'Berlaku' : 'Tidak Berlaku' }}
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Potongan Harga</span>
                        <span class="font-black text-indigo-600">{{ $promo->type == 'percentage' ? $promo->discount_amount.'%' : 'Rp '.number_format($promo->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Minimal Belanja</span>
                        <span class="font-bold text-gray-800 text-xs">Rp {{ number_format($promo->min_purchase, 0, ',', '.') }}</span>
                    </div>
                    @if($promo->type == 'percentage')
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Maks. Potongan</span>
                        <span class="font-bold text-amber-500 text-xs">{{ $promo->max_discount ? 'Rp '.number_format($promo->max_discount, 0, ',', '.') : 'Tanpa Batas' }}</span>
                    </div>
                    @endif
                </div>

                <div class="pt-8 flex gap-3">
                    <a href="{{ route('promos.index') }}" class="flex-1 py-4 bg-gray-100 text-gray-600 text-center rounded-2xl font-bold hover:bg-gray-200 transition">Kembali</a>
                    <a href="{{ route('promos.edit', $promo->id) }}" class="flex-1 py-4 bg-indigo-600 text-white text-center rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">Ubah Aturan</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection