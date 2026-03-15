<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Dashboard')</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .transition-all { transition-property: all; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 300ms; }
    </style>
</head>
<body class="bg-gray-100">
    <div x-data="{ 
            sidebarOpen: false, 
            isMinimized: localStorage.getItem('sidebarMinimized') === 'true' 
         }" 
         class="flex min-h-screen">

        {{-- Sidebar --}}
        @include('components.sidebar')

        <div class="flex-1 flex flex-col transition-all duration-300">
            {{-- Navbar --}}
            @include('components.navbar')

            {{-- Content --}}
            <main class="flex-1 p-6">
                @yield('content')
            </main>

            {{-- Footer --}}
            @include('components.footer')
        </div>

        {{-- Overlay Mobile --}}
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             x-cloak
             class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden">
        </div>
    </div>

    @stack('script')
</body>
</html>