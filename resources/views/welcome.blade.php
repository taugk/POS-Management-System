<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>POS Installer</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center">

    <div class="max-w-xl w-full bg-white shadow-xl rounded-2xl p-10 text-center">

        <!-- Logo / Title -->
        <h1 class="text-4xl font-bold text-blue-600 mb-4">
            POS System
        </h1>

        <p class="text-gray-600 mb-8">
            Welcome to the POS Installation Wizard.  
            This installer will guide you through the setup process.
        </p>

        <!-- System Check -->
        <div class="bg-gray-50 rounded-lg p-6 text-left mb-8">

            <h2 class="font-semibold text-lg mb-4">
                System Requirements
            </h2>

            <ul class="space-y-2 text-sm">

                <li class="flex justify-between">
                    <span>PHP Version</span>
                    <span class="text-green-600 font-medium">
                        {{ phpversion() }}
                    </span>
                </li>

                <li class="flex justify-between">
                    <span>Laravel Version</span>
                    <span class="text-green-600 font-medium">
                        {{ app()->version() }}
                    </span>
                </li>

                <li class="flex justify-between">
                    <span>Storage Writable</span>
                    <span class="{{ is_writable(storage_path()) ? 'text-green-600' : 'text-red-600' }}">
                        {{ is_writable(storage_path()) ? 'OK' : 'Not Writable' }}
                    </span>
                </li>

                <li class="flex justify-between">
                    <span>Cache Writable</span>
                    <span class="{{ is_writable(base_path('bootstrap/cache')) ? 'text-green-600' : 'text-red-600' }}">
                        {{ is_writable(base_path('bootstrap/cache')) ? 'OK' : 'Not Writable' }}
                    </span>
                </li>

            </ul>

        </div>

        <!-- Install Button -->
        <a href="{{ route('installer.database') }}"
           class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition">
            Start Installation
        </a>

    </div>

</body>
</html>