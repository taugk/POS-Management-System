<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installer - Store Setup</title>
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
            <h2 class="text-2xl font-bold text-gray-800">Store Information</h2>
            <p class="text-sm text-gray-500">Step 2 of 3: Configure your business profile</p>
        </div>
        <span class="text-xs font-bold uppercase tracking-widest text-blue-500 bg-blue-50 px-3 py-1 rounded-full">Step 2</span>
    </div>

    {{-- Notifikasi Error Validasi --}}
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('installer.storeStore') }}" method="POST">
        @csrf

        <div class="space-y-5">
            {{-- Store Name --}}
            <div>
                <label class="text-sm font-semibold text-gray-600">Store Name</label>
                <input type="text" name="store_name" class="input" 
                       value="{{ old('store_name') }}" 
                       placeholder="e.g. Toko Berkah Jaya" required>
            </div>

            {{-- Store Address --}}
            <div>
                <label class="text-sm font-semibold text-gray-600">Store Address</label>
                <textarea name="store_address" class="input" rows="3" 
                          placeholder="e.g. Jl. Raya Kuningan No. 123" required>{{ old('store_address') }}</textarea>
            </div>

            {{-- Store Phone --}}
            <div>
                <label class="text-sm font-semibold text-gray-600">Phone / WhatsApp</label>
                <input type="text" name="store_phone" class="input" 
                       value="{{ old('store_phone') }}" 
                       placeholder="e.g. 08123456789" required>
                <p class="text-[10px] text-gray-400 mt-1 italic">*This number will be displayed on the sales receipt.</p>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between mt-8 border-t pt-6 gap-3">
            <a href="{{ route('installer.database') }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Previous Step
            </a>

            <button type="submit" class="btn-primary">
                <span>Next: Admin Setup</span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </button>
        </div>
    </form>
</div>

</body>
</html>