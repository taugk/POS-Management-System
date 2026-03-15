<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $setting = Settings::first(); 
        return view('settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name'    => 'required|string|max:255',
            'store_address' => 'nullable|string',
            'store_phone'   => 'nullable|string|max:20',
        ]);

        // Menggunakan updateOrCreate agar data selalu tersimpan di ID 1
        Settings::updateOrCreate(['id' => 1], $validated);

        return back()->with('success', 'Pengaturan toko berhasil disimpan!');
    }
}
