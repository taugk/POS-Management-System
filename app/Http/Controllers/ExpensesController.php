<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpensesController extends Controller
{
    public function index(Request $request)
{
    $start_date = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
    $end_date = $request->end_date ?? now()->format('Y-m-d');

    // Ambil data pengeluaran berdasarkan range tanggal
    $expenses = Expenses::whereBetween('expense_date', [$start_date, $end_date])
                        ->latest()
                        ->paginate(20)
                        ->withQueryString();

    // Hitung Ringkasan Keuangan berdasarkan range yang sama
    $totalExpenses = Expenses::whereBetween('expense_date', [$start_date, $end_date])->sum('amount');
    
    // Ambil data penjualan (Revenue) berdasarkan range yang sama
    $totalRevenue = \App\Models\Sales::whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])
                                     ->sum('total_price');

    $netProfit = $totalRevenue - $totalExpenses;

    return view('expenses.index', compact('expenses', 'totalExpenses', 'totalRevenue', 'netProfit', 'start_date', 'end_date'));
}

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'expense_date' => 'required|date',
            'category' => 'nullable|string',
        ]);

        // Generate Reference Number Otomatis (EXP-YYYYMMDD-RANDOM)
        $ref = 'EXP-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));

        Expenses::create([
            'reference_number' => $ref,
            'name' => $request->name,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'category' => $request->category,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function destroy(Expenses $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Catatan pengeluaran dihapus.');
    }
}