@extends('layouts.app')

@section('title', 'Admin - Detail Transaksi ' . $sale->invoice_number)

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div class="flex items-center gap-4">
        <a href="{{ route('sales.index') }}" class="p-2 bg-white rounded-xl shadow-sm border border-gray-100 text-gray-400 hover:text-indigo-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Transaksi</h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-xs font-mono font-bold px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded border border-indigo-100">
                    {{ $sale->invoice_number }}
                </span>
                <span class="text-gray-300">•</span>
                <span class="text-xs text-gray-500 font-medium">Oleh: {{ $sale->user->name ?? 'System' }}</span>
            </div>
        </div>
    </div>
    
    <div class="flex items-center gap-3 w-full md:w-auto">
        <button onclick="window.print()" class="flex-1 md:flex-none justify-center bg-white border border-gray-200 px-5 py-2.5 rounded-xl shadow-sm text-sm font-bold text-gray-600 hover:bg-gray-50 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print
        </button>
        <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini? Stok akan dikembalikan.')" class="flex-1 md:flex-none">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full justify-center bg-red-50 text-red-600 px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-red-600 hover:text-white transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Batalkan
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Daftar Barang Belanja</h3>
                <span class="bg-indigo-50 text-indigo-600 text-[10px] font-black px-2 py-1 rounded-lg uppercase">
                    {{ $sale->items->count() }} Item
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100">
                            <th class="px-6 py-4">Produk</th>
                            <th class="px-6 py-4 text-center">Qty</th>
                            <th class="px-6 py-4 text-right">Harga</th>
                            <th class="px-6 py-4 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($sale->items as $item)
                        <tr class="group hover:bg-gray-50/80 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 group-hover:bg-white group-hover:shadow-sm transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">{{ $item->product->name }}</p>
                                        <p class="text-[10px] text-gray-400 font-mono">Barcode: {{ $item->product->barcode ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-bold text-gray-600 bg-gray-100 px-2 py-1 rounded-md">{{ $item->quantity }}</span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-gray-500 font-mono">
                                {{ number_format($item->unit_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-black text-gray-900 font-mono">
                                {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-6 border-b pb-4">Ringkasan Pembayaran</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center text-sm font-medium">
                    <span class="text-gray-500">Subtotal</span>
                    <span class="text-gray-800">Rp{{ number_format($sale->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm font-medium">
                    <span class="text-gray-500">Pajak (12%)</span>
                    <span class="text-gray-800">Rp{{ number_format($sale->tax_amount, 0, ',', '.') }}</span>
                </div>
                <div class="pt-4 border-t border-dashed border-gray-100 flex justify-between items-center">
                    <span class="text-sm font-black text-gray-800 uppercase tracking-tighter">Grand Total</span>
                    <span class="text-2xl font-black text-indigo-600 tracking-tighter">Rp{{ number_format($sale->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-gray-900 p-6 rounded-2xl shadow-xl text-white">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-6">Alur Uang Tunai</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 uppercase font-bold">Diterima</span>
                        <span class="text-lg font-black text-white">Rp{{ number_format($sale->pay_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="pt-4 border-t border-white/10 flex justify-between items-center">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 uppercase font-bold">Kembalian</span>
                        <span class="text-xl font-black text-green-400 font-mono">Rp{{ number_format($sale->change_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs uppercase">
                    {{ substr($sale->user->name ?? 'S', 0, 1) }}
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Operator Kasir</p>
                    <p class="text-xs font-bold text-gray-800">{{ $sale->user->name ?? 'System' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <div class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Waktu Input</p>
                    <p class="text-xs font-bold text-gray-800">{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        #sidebar, #mobile-open, header, .flex.justify-between, nav, footer, a, form { display: none !important; }
        body { background: white !important; margin: 0; padding: 0; }
        .grid { display: block !important; }
        .lg\:col-span-2, .space-y-6 { width: 100% !important; border: none !important; }
        .bg-gray-900 { background: white !important; color: black !important; border: 1px solid #eee; }
        .text-white { color: black !important; }
    }
</style>
@endsection