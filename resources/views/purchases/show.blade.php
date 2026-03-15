@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-4xl">
    <div class="bg-white rounded-2xl shadow-sm border p-8">
        <div class="flex justify-between items-start border-b pb-6 mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Purchase Order</h1>
                <p class="text-indigo-600 font-mono text-lg">{{ $purchase->reference_number }}</p>
            </div>
            <div class="text-right">
                <span class="px-3 py-1 rounded-full text-sm font-bold bg-gray-100">{{ strtoupper($purchase->status) }}</span>
                <p class="mt-2 text-sm text-gray-500">Tanggal: {{ $purchase->purchase_date }}</p>
            </div>
        </div>

        <div class="mb-8">
            <h3 class="text-xs uppercase font-bold text-gray-400 mb-2 tracking-widest">Supplier</h3>
            <p class="text-lg font-bold text-gray-800">{{ $purchase->supplier->name }}</p>
            <p class="text-gray-500 text-sm">{{ $purchase->supplier->company_name }}</p>
            <p class="text-gray-500 text-sm">{{ $purchase->supplier->phone }}</p>
        </div>

        <table class="w-full text-left mb-8">
            <thead class="bg-gray-50 border-y">
                <tr>
                    <th class="px-4 py-3 text-sm font-bold">Produk</th>
                    <th class="px-4 py-3 text-sm font-bold text-center">Qty</th>
                    <th class="px-4 py-3 text-sm font-bold text-right">Harga Beli</th>
                    <th class="px-4 py-3 text-sm font-bold text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($purchase->items as $item)
                <tr>
                    <td class="px-4 py-4 text-sm">{{ $item->product->name }}</td>
                    <td class="px-4 py-4 text-sm text-center">{{ $item->quantity }}</td>
                    <td class="px-4 py-4 text-sm text-right italic">Rp {{ number_format($item->cost_price, 0, ',', '.') }}</td>
                    <td class="px-4 py-4 text-sm text-right font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 font-bold">
                <tr>
                    <td colspan="3" class="px-4 py-4 text-right">TOTAL AKHIR</td>
                    <td class="px-4 py-4 text-right text-indigo-600 text-lg">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        @if($purchase->notes)
        <div class="p-4 bg-gray-50 rounded-lg text-sm italic text-gray-600">
            <strong>Catatan:</strong> {{ $purchase->notes }}
        </div>
        @endif
    </div>
</div>
@endsection