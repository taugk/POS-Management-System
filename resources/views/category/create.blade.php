@extends('layouts.app')

@section('title', isset($category) ? 'Edit Kategori' : 'Tambah Kategori')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('category.index') }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">{{ isset($category) ? 'Edit' : 'Tambah' }} Kategori</h1>
    </div>

    <form action="{{ isset($category) ? route('category.update', $category->id) : route('category.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        @csrf
        @if(isset($category)) @method('PUT') @endif
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2 italic">Nama Kategori</label>
                <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required 
                    class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 py-2.5 shadow-sm"
                    placeholder="Contoh: Elektronik, Makanan, dll.">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-8 pt-6 border-t flex justify-end gap-3">
            <a href="{{ route('category.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Batal</a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm transition font-bold">
                {{ isset($category) ? 'Perbarui Kategori' : 'Simpan Kategori' }}
            </button>
        </div>
    </form>
</div>
@endsection