@extends('layouts.app')

@section('title', 'Tambah Supplier')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('suppliers.index') }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Tambah Supplier</h1>
    </div>

    <form action="{{ route('suppliers.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kontak <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 py-2">
            </div>
            <div class="md:col-span-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Perusahaan</label>
                <input type="text" name="company_name" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 py-2">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon <span class="text-red-500">*</span></label>
                <input type="text" name="phone" required class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 py-2">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input type="email" name="email" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 py-2">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap</label>
                <textarea name="address" rows="3" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 py-2"></textarea>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t flex justify-end gap-3">
            <a href="{{ route('suppliers.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Batal</a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-bold shadow-sm transition">
                Simpan Supplier
            </button>
        </div>
    </form>
</div>
@endsection