@extends('layouts.app')
@section('title', isset($user) ? 'Edit User' : 'Tambah User')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('users.index') }}" class="p-2.5 bg-white rounded-xl text-gray-400 hover:text-gray-600 shadow-sm border border-gray-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-xl font-black text-gray-800 uppercase tracking-tight">{{ isset($user) ? 'Edit Data Pengguna' : 'Buat Pengguna Baru' }}</h2>
    </div>

    <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" 
          method="POST" enctype="multipart/form-data" 
          class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 space-y-6">
        @csrf
        @if(isset($user)) @method('PUT') @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Nama --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
                       class="w-full border-gray-200 rounded-2xl px-4 py-3 focus:ring-indigo-500">
            </div>

            {{-- Email --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
                       class="w-full border-gray-200 rounded-2xl px-4 py-3 focus:ring-indigo-500">
            </div>

            {{-- Role --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Role Jabatan</label>
                <select name="role" class="w-full border-gray-200 rounded-2xl px-4 py-3 focus:ring-indigo-500">
                    <option value="cashier" {{ (isset($user) && $user->role == 'cashier') ? 'selected' : '' }}>Kasir</option>
                    <option value="admin" {{ (isset($user) && $user->role == 'admin') ? 'selected' : '' }}>Administrator</option>
                    <option value="owner" {{ (isset($user) && $user->role == 'owner') ? 'selected' : '' }}>Owner</option>
                </select>
            </div>

            {{-- Photo --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Foto Profil</label>
                <input type="file" name="profile_photo" class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-indigo-50 file:text-indigo-600 file:font-bold">
            </div>

            {{-- Password --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Password {{ isset($user) ? '(Kosongkan jika tidak ganti)' : '' }}</label>
                <input type="password" name="password" {{ isset($user) ? '' : 'required' }}
                       class="w-full border-gray-200 rounded-2xl px-4 py-3 focus:ring-indigo-500">
            </div>

            {{-- Confirm Password --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" 
                       class="w-full border-gray-200 rounded-2xl px-4 py-3 focus:ring-indigo-500">
            </div>
        </div>

        {{-- Is Active --}}
        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-2xl">
            <input type="checkbox" name="is_active" id="is_active" {{ (!isset($user) || $user->is_active) ? 'checked' : '' }}
                   class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
            <label for="is_active" class="text-xs font-bold text-gray-600 uppercase">Pengguna Aktif (Dapat Login)</label>
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
            {{ isset($user) ? 'Simpan Perubahan' : 'Daftarkan Pengguna' }}
        </button>
    </form>
</div>
@endsection