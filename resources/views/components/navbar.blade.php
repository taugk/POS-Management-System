<header class="bg-white shadow-sm px-4 py-3 flex justify-between items-center no-print">
    <div class="flex items-center">
        <button @click="$dispatch('toggle-sidebar')" 
                class="text-gray-500 focus:outline-none hidden md:block hover:bg-gray-100 p-1 rounded transition-colors">
            <svg class="h-6 w-6 transition-transform duration-300" :class="isMinimized ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>

        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none md:hidden">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        
        <h2 class="ml-4 font-bold text-gray-800 tracking-tight">@yield('title', 'Overview')</h2>
    </div>
    
    <div class="flex items-center gap-4">
        <div class="hidden sm:flex flex-col text-right leading-none">
            <span class="text-xs font-black text-gray-900 uppercase">{{ Auth::user()->name ?? 'Administrator' }}</span>
            <span class="text-[9px] text-indigo-500 font-bold uppercase tracking-widest mt-1">Super Admin</span>
        </div>
        
        <div class="relative group">
            <img class="h-9 w-9 rounded-xl object-cover border-2 border-gray-50 shadow-sm group-hover:border-indigo-200 transition-all" 
                 src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=4F46E5&color=fff&bold=true" 
                 alt="Profile">
            <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
        </div>
    </div>
</header>