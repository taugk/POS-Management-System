@extends('layouts.app')

@section('title', 'Edit Supplier: ' . $supplier->name)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('suppliers.index') }}" class="text-gray-500 hover:text-gray-700 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Supplier</h1>
            <p class="text-sm text-gray-500">Perbarui informasi kontak pemasok Anda.</p>
        </div>
    </div>

    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2 italic">Nama Kontak <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required 
                    class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 py-2 shadow-sm">
            </div>

            <div class="md:col-span-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2 italic">Nama Perusahaan</label>
                <input type="text" name="company_name" value="{{ old('company_name', $supplier->company_name) }}" 
                    class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 py-2 shadow-sm">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2 italic">No. Telepon <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 004.815 4.815l.773-1.548a1 1 0 011.06-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path></svg>
                    </span>
                    <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}" required 
                        class="w-full pl-10 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 py-2 shadow-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2 italic">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path></svg>
                    </span>
                    <input type="email" name="email" value="{{ old('email', $supplier->email) }}" 
                        class="w-full pl-10 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 py-2 shadow-sm"
                        placeholder="example@company.com">
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2 italic">Alamat Lengkap</label>
                <textarea name="address" rows="3" 
                    class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 py-2 shadow-sm">{{ old('address', $supplier->address) }}</textarea>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t flex justify-end gap-3">
            <a href="{{ route('suppliers.index') }}" 
                class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                Batal
            </a>
            <button type="submit" 
                class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition font-bold text-sm">
                Perbarui Supplier
            </button>
        </div>
    </form>
</div>
@endsection