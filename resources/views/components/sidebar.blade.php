<aside id="sidebar" 
    {{-- Inisialisasi state: membaca dari localStorage agar posisi terakhir tetap terjaga --}}
    x-data="{ isMinimized: localStorage.getItem('sidebarMinimized') === 'true' }"
    x-init="$watch('isMinimized', value => localStorage.setItem('sidebarMinimized', value))"
    {{-- Listener untuk menangkap trigger dari Navbar --}}
    @toggle-sidebar.window="isMinimized = !isMinimized"
    {{-- Class dinamis: lebar berubah dari w-64 ke w-20 --}}
    :class="{ 
        'w-64': !isMinimized, 
        'w-20': isMinimized,
        'translate-x-0': sidebarOpen,
        '-translate-x-full': !sidebarOpen
    }"
    class="bg-indigo-900 text-white min-h-screen fixed md:relative z-50 transition-all duration-300 transform md:translate-x-0 flex flex-col overflow-hidden border-r border-indigo-800 shadow-2xl">
    
    <div class="p-6 text-2xl font-bold border-b border-indigo-800 flex items-center gap-3 overflow-hidden whitespace-nowrap">
        <div class="bg-white p-1 rounded-lg shrink-0">
            <svg class="w-6 h-6 text-indigo-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        {{-- Teks POS MASTER hilang saat isMinimized true --}}
        <span x-show="!isMinimized" x-transition.opacity class="font-bold">POS MASTER</span>
    </div>

    <nav class="mt-6 px-4 flex-1 overflow-y-auto space-y-1 pb-10 custom-scrollbar">
        
        {{-- Pola: Tambahkan x-show="!isMinimized" pada label kategori dan teks menu --}}
        {{-- Tambahkan :class dinamis pada <a> untuk menengahkan ikon saat sidebar kecil --}}

        <p x-show="!isMinimized" class="text-[10px] uppercase text-indigo-400 font-bold px-4 mb-2 tracking-widest whitespace-nowrap">Utama</p>
        <div x-show="isMinimized" class="border-t border-indigo-800 my-4 md:block hidden"></div>
        
        <a href="{{ route('dashboard') }}" 
           class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-800 {{ request()->is('dashboard*') ? 'bg-indigo-800 font-bold border-l-4 border-white' : '' }}"
           :class="isMinimized ? 'justify-center px-0' : 'gap-3'">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span x-show="!isMinimized" class="whitespace-nowrap">Dashboard</span>
        </a>

        <a href="{{ route('sales.create') }}" 
           class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-800 {{ request()->routeIs('sales.create') ? 'bg-indigo-800 font-bold border-l-4 border-white' : '' }}"
           :class="isMinimized ? 'justify-center px-0' : 'gap-3'">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            <span x-show="!isMinimized" class="whitespace-nowrap">Point of Sale</span>
        </a>

        <p x-show="!isMinimized" class="text-[10px] uppercase text-indigo-400 font-bold px-4 mt-6 mb-2 tracking-widest whitespace-nowrap">Inventori & Suplai</p>
        <div x-show="isMinimized" class="border-t border-indigo-800 my-4 md:block hidden"></div>

        <a href="{{ route('products.index') }}" 
           class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-800 {{ request()->routeIs('products.*') ? 'bg-indigo-800 font-bold border-l-4 border-white' : '' }}"
           :class="isMinimized ? 'justify-center px-0' : 'gap-3'">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            <span x-show="!isMinimized" class="whitespace-nowrap">Daftar Produk</span>
        </a>

        <a href="{{ route('category.index') }}" 
           class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-800 {{ request()->routeIs('category.*') ? 'bg-indigo-800 font-bold border-l-4 border-white' : '' }}"
           :class="isMinimized ? 'justify-center px-0' : 'gap-3'">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            <span x-show="!isMinimized" class="whitespace-nowrap">Kategori</span>
        </a>

        <a href="{{ route('suppliers.index') }}" 
           class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-800 {{ request()->routeIs('suppliers.*') ? 'bg-indigo-800 font-bold border-l-4 border-white' : '' }}"
           :class="isMinimized ? 'justify-center px-0' : 'gap-3'">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span x-show="!isMinimized" class="whitespace-nowrap">Supplier</span>
        </a>

        <a href="{{ route('purchases.index') }}" 
           class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-800 {{ request()->routeIs('purchases.*') ? 'bg-indigo-800 font-bold border-l-4 border-white' : '' }}"
           :class="isMinimized ? 'justify-center px-0' : 'gap-3'">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1m-7-1a1 1 0 001 1h1" />
            </svg>
            <span x-show="!isMinimized" class="whitespace-nowrap">Pesanan (PO)</span>
        </a>

        <p x-show="!isMinimized" class="text-[10px] uppercase text-indigo-400 font-bold px-4 mt-6 mb-2 tracking-widest whitespace-nowrap">CRM & Marketing</p>
        <div x-show="isMinimized" class="border-t border-indigo-800 my-4 md:block hidden"></div>
        
        <a href="#" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-800"
           :class="isMinimized ? 'justify-center px-0' : 'gap-3'">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span x-show="!isMinimized" class="whitespace-nowrap">Pelanggan</span>
        </a>

        <a href="{{ route('promos.index') }}" 
           class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-800 {{ request()->routeIs('promos.*') ? 'bg-indigo-800 font-bold border-l-4 border-white' : '' }}"
           :class="isMinimized ? 'justify-center px-0' : 'gap-3'">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 0h12m-12 0H0"/></svg>
            <span x-show="!isMinimized" class="whitespace-nowrap">Promo & Diskon</span>
        </a>

        <p x-show="!isMinimized" class="text-[10px] uppercase text-indigo-400 font-bold px-4 mt-6 mb-2 tracking-widest whitespace-nowrap">Laporan & Keuangan</p>
        <div x-show="isMinimized" class="border-t border-indigo-800 my-4 md:block hidden"></div>

        <a href="{{ route('sales.index') }}" 
           class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-800 {{ (request()->routeIs('sales.index') || request()->routeIs('sales.show')) ? 'bg-indigo-800 font-bold border-l-4 border-white' : '' }}"
           :class="isMinimized ? 'justify-center px-0' : 'gap-3'">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <span x-show="!isMinimized" class="whitespace-nowrap">Laporan Penjualan</span>
        </a>

        <a href="{{ route('expenses.index') }}" 
           class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-800 {{ request()->routeIs('expenses.*') ? 'bg-indigo-800 font-bold border-l-4 border-white' : '' }}"
           :class="isMinimized ? 'justify-center px-0' : 'gap-3'">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span x-show="!isMinimized" class="whitespace-nowrap">Kas & Biaya</span>
        </a>

        <p x-show="!isMinimized" class="text-[10px] uppercase text-indigo-400 font-bold px-4 mt-6 mb-2 tracking-widest whitespace-nowrap">Sistem & Admin</p>
        <div x-show="isMinimized" class="border-t border-indigo-800 my-4 md:block hidden"></div>

        <a href="{{ route('users.index') }}" 
           class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-800 {{ request()->routeIs('users.*') ? 'bg-indigo-800 font-bold border-l-4 border-white' : '' }}"
           :class="isMinimized ? 'justify-center px-0' : 'gap-3'">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            <span x-show="!isMinimized" class="whitespace-nowrap">Manajemen User</span>
        </a>

        <a href="{{ route('settings.index') }}" 
           class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-800 {{ request()->routeIs('settings.*') ? 'bg-indigo-800 font-bold border-l-4 border-white' : '' }}"
           :class="isMinimized ? 'justify-center px-0' : 'gap-3'">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span x-show="!isMinimized" class="whitespace-nowrap">Pengaturan Toko</span>
        </a>
    </nav>

    <div class="p-4 border-t border-indigo-800">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" 
                class="flex items-center rounded text-indigo-300 hover:text-white hover:bg-red-600 transition duration-200 font-semibold w-full py-2.5"
                :class="isMinimized ? 'justify-center px-0' : 'px-4 gap-3'">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span x-show="!isMinimized" class="whitespace-nowrap">Keluar</span>
            </button>
        </form>
    </div>
</aside>