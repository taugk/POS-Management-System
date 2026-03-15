@extends('layouts.app')

@section('content')
<div class="p-4 md:p-8 bg-gray-50 min-h-screen">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight">Kupon & Promo</h2>
            <p class="text-sm text-gray-500">Kelola strategi diskon untuk meningkatkan penjualan.</p>
        </div>
        <a href="{{ route('promos.create') }}" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-amber-200 transition active:scale-95">
            + Buat Promo Baru
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($promos as $promo)
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative group">
            {{-- Status Badge --}}
            <div class="absolute top-4 right-4">
                <span class="{{ $promo->isValid() ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                    {{ $promo->isValid() ? 'Aktif' : 'Non-Aktif/Expired' }}
                </span>
            </div>

            <div class="p-6">
                <div class="text-xs font-bold text-amber-500 mb-1 uppercase tracking-widest">{{ $promo->type }}</div>
                <h3 class="text-xl font-black text-gray-800 mb-4">{{ $promo->name }}</h3>
                
                <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl p-4 mb-6 text-center relative">
                    <span class="text-xs text-gray-400 uppercase font-bold block mb-1">Kode Promo</span>
                    <span class="text-2xl font-mono font-black text-indigo-600">{{ $promo->code }}</span>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-400">Potongan:</span>
                        <span class="font-bold text-gray-800">
                            {{ $promo->type == 'percentage' ? $promo->discount_amount.'%' : 'Rp '.number_format($promo->discount_amount, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-400">Min. Belanja:</span>
                        <span class="font-bold text-gray-800 text-xs">Rp {{ number_format($promo->min_purchase, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2 no-print pt-4 border-t border-gray-50">
                    <a href="{{ route('promos.show', $promo->id) }}" class="flex-1 text-center py-2.5 bg-gray-100 text-gray-600 rounded-xl font-bold text-xs hover:bg-gray-200 transition">Detail</a>
                    <a href="{{ route('promos.edit', $promo->id) }}" class="flex-1 text-center py-2.5 bg-indigo-50 text-indigo-600 rounded-xl font-bold text-xs hover:bg-indigo-100 transition">Edit</a>
                    <form action="{{ route('promos.destroy', $promo->id) }}" method="POST" onsubmit="return confirm('Hapus promo ini?')" class="flex-none">
                        @csrf @method('DELETE')
                        <button class="p-2.5 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-8">
        {{ $promos->links() }}
    </div>
</div>
@endsection