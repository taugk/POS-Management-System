<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installer - Admin Setup</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .input { @apply w-full border border-gray-300 px-3 py-2 rounded mt-1 focus:ring-2 focus:ring-blue-500 outline-none transition-all; }
        .btn-primary { @apply bg-blue-600 text-white px-8 py-2 rounded font-semibold hover:bg-blue-700 transition active:scale-95 flex items-center justify-center; }
        .btn-secondary { @apply bg-gray-200 text-gray-700 px-6 py-2 rounded font-semibold hover:bg-gray-300 transition flex items-center justify-center; }
    </style>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">

<div class="max-w-xl w-full bg-white p-8 rounded-xl shadow-lg border border-gray-200">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Admin Account</h2>
            <p class="text-sm text-gray-500">Step 3 of 3: Create your superuser</p>
        </div>
        <span class="text-xs font-bold uppercase tracking-widest text-blue-500 bg-blue-50 px-3 py-1 rounded-full">Step 3</span>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('installer.storeAdmin') }}" method="POST">
        @csrf

        <div class="space-y-5">
            <div>
                <label class="text-sm font-semibold text-gray-600">Full Name</label>
                <input type="text" name="name" class="input" value="{{ old('name') }}" placeholder="John Doe" required>
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-600">Email Address</label>
                <input type="email" name="email" class="input" value="{{ old('email') }}" placeholder="admin@system.com" required>
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-600">Password</label>
                <input type="password" name="password" class="input" placeholder="Minimal 8 characters" required minlength="8">
                <p class="text-[10px] text-gray-400 mt-1 italic">*This password will be used to log in for the first time.</p>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between mt-8 border-t pt-6 gap-3">
            <a href="{{ route('installer.store') }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Previous Step
            </a>

            <button type="submit" class="btn-primary">
                <span>Finalize Installation</span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </button>
        </div>
    </form>
</div>

</body>
</html>
