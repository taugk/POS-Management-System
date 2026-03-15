@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="p-6 max-w-5xl mx-auto">
    
    {{-- HEADER & NAVIGATION --}}
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('users.index') }}" class="p-2.5 bg-white rounded-xl text-gray-400 hover:text-indigo-600 shadow-sm border border-gray-100 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight">Detail Pengguna</h2>
                <p class="text-sm text-gray-500 font-medium">Informasi lengkap akun dan hak akses sistem.</p>
            </div>
        </div>

        <div class="flex gap-3 w-full md:w-auto">
            <a href="{{ route('users.edit', $user->id) }}" class="flex-1 md:flex-none text-center bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
                Edit Profil
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- LEFT COLUMN: AVATAR & STATUS --}}
        <div class="space-y-6">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 text-center">
                <div class="relative inline-block mb-4">
                    <img src="{{ $user->profile_photo ? asset('storage/'.$user->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=6366f1&color=fff&size=512&bold=true' }}" 
                         class="w-32 h-32 rounded-[2rem] object-cover border-4 border-gray-50 shadow-md mx-auto">
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 rounded-full border-4 border-white {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}"></div>
                </div>
                
                <h3 class="text-xl font-black text-gray-800 tracking-tight">{{ $user->name }}</h3>
                <p class="text-xs font-bold text-indigo-500 uppercase tracking-[0.2em] mb-4">{{ $user->role }}</p>
                
                <div class="flex justify-center gap-2">
                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $user->is_active ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ $user->is_active ? 'Account Active' : 'Suspended' }}
                    </span>
                </div>
            </div>

            <div class="bg-indigo-900 p-6 rounded-[2rem] shadow-xl text-white relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-indigo-300 uppercase tracking-widest mb-4">System Info</p>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center border-b border-white/10 pb-2">
                            <span class="text-[11px] font-medium opacity-60">ID User</span>
                            <span class="text-xs font-mono font-bold">#USR-0{{ $user->id }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-white/10 pb-2">
                            <span class="text-[11px] font-medium opacity-60">Terdaftar Sejak</span>
                            <span class="text-xs font-bold">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
                {{-- Background Decoration --}}
                <svg class="absolute -right-4 -bottom-4 w-24 h-24 text-white/5" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3.005 3.005 0 013.75-2.906z"/></svg>
            </div>
        </div>

        {{-- RIGHT COLUMN: DETAILS & ACTIVITY --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Account Information --}}
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="w-2 h-4 bg-indigo-600 rounded-full"></span>
                    Informasi Akun
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 italic">Nama Lengkap</p>
                        <p class="text-sm font-bold text-gray-800">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 italic">Email Address</p>
                        <p class="text-sm font-bold text-indigo-600">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 italic">Role Akses</p>
                        <p class="text-sm font-bold text-gray-800 capitalize">{{ $user->role }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 italic">Email Terverifikasi</p>
                        <p class="text-sm font-bold {{ $user->email_verified_at ? 'text-green-600' : 'text-amber-500' }}">
                            {{ $user->email_verified_at ? 'Terverifikasi pada ' . $user->email_verified_at->format('d/m/Y') : 'Belum Verifikasi' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Hak Akses Info --}}
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                <h3 class="text-xs font-black text-gray-800 uppercase tracking-widest mb-6">Hak Akses Modul ({{ $user->role }})</h3>
                
                <div class="flex flex-wrap gap-2">
                    @if($user->role == 'owner' || $user->role == 'admin')
                        <span class="px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-[10px] font-bold text-gray-600 uppercase">Dashboard & Analytics</span>
                        <span class="px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-[10px] font-bold text-gray-600 uppercase">Manajemen Produk</span>
                        <span class="px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-[10px] font-bold text-gray-600 uppercase">Laporan Keuangan</span>
                    @endif
                    
                    @if($user->role == 'cashier' || $user->role == 'owner' || $user->role == 'admin')
                        <span class="px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-xl text-[10px] font-bold text-indigo-600 uppercase font-black">Transaksi POS</span>
                        <span class="px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-[10px] font-bold text-gray-600 uppercase">Input Stok</span>
                    @endif

                    @if($user->role == 'owner')
                        <span class="px-4 py-2 bg-red-50 border border-red-100 rounded-xl text-[10px] font-bold text-red-600 uppercase font-black">Manajemen User</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection