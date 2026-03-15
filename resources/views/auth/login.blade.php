<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - POS System</title>

@vite(['resources/css/app.css','resources/js/app.js'])

</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center">

<div class="w-full max-w-md bg-white p-8 rounded-xl shadow">

<h2 class="text-2xl font-bold text-center mb-6">
POS System Login
</h2>

@if(session('error'))
<div class="bg-red-100 text-red-700 p-3 rounded mb-4">
{{ session('error') }}
</div>
@endif

<form action="/login" method="POST" class="space-y-4">
@csrf

<div>
<label class="block text-sm font-medium mb-1">Email</label>
<input 
type="email" 
name="email" 
required
class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
</div>

<div>
<label class="block text-sm font-medium mb-1">Password</label>
<input 
type="password" 
name="password" 
required
class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
</div>

<button 
type="submit"
class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
Login
</button>

</form>

<p class="text-center text-sm text-gray-500 mt-6">
POS System © {{ date('Y') }}
</p>

</div>

</body>
</html>