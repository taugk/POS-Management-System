@extends('layouts.app')

@section('title', 'Manajemen Keuangan & Kas')

@section('content')
{{-- Pendukung Library --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

<style>
    [x-cloak] { display: none !important; }
    @media print {
        .no-print { display: none !important; }
        .print-only { display: block !important; }
        @page { size: A4 landscape; margin: 1cm; }
        body { background: white; color: black; }
    }
</style>

<div class="p-4 md:p-8 bg-gray-50 min-h-screen">

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-6 no-print">
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Financial Summary</h2>
            <p class="text-sm md:text-base text-gray-500 mt-1">Pantau arus kas, pengeluaran, dan laba bersih secara akurat.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
            {{-- EXPORT DROPDOWN --}}
            <div x-data="{ open: false }" class="relative flex-1 md:flex-none">
                <button @click="open = !open" type="button" class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl flex items-center justify-center gap-2 font-bold text-sm shadow-lg transition active:scale-95">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v1M16 10l-4 4m0 0l-4-4m4 4V4" /></svg>
                    Export Laporan
                </button>
                <div x-show="open" x-cloak @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 z-[60] py-2">
                    <button onclick="exportToExcel()" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-green-50 flex items-center gap-3 transition">
                        <span class="bg-green-100 text-green-600 p-1 rounded text-[10px] font-bold">XLS</span> Microsoft Excel
                    </button>
                    <button onclick="exportToPDF()" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-red-50 flex items-center gap-3 transition">
                        <span class="bg-red-100 text-red-600 p-1 rounded text-[10px] font-bold">PDF</span> Dokumen PDF
                    </button>
                    <div class="border-t my-1"></div>
                    <button onclick="window.print()" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-3 transition italic">
                        Cetak Printer
                    </button>
                </div>
            </div>

            <a href="{{ route('expenses.create') }}" class="flex-1 md:flex-none text-center bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-100 transition active:scale-95">
                + Catat Biaya Baru
            </a>
        </div>
    </div>

    {{-- FINANCIAL WIDGETS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 opacity-5 group-hover:opacity-10 transition">
                <svg class="w-32 h-32 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" /></svg>
            </div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Pemasukan</p>
            <h3 class="text-3xl font-black text-green-600 tracking-tighter">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            <p class="text-[10px] text-gray-400 mt-2 font-medium italic">* Berdasarkan range tanggal dipilih</p>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 opacity-5 group-hover:opacity-10 transition">
                <svg class="w-32 h-32 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
            </div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Pengeluaran</p>
            <h3 class="text-3xl font-black text-red-600 tracking-tighter">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h3>
            <p class="text-[10px] text-gray-400 mt-2 font-medium italic">* Seluruh biaya operasional</p>
        </div>

        <div class="{{ $netProfit >= 0 ? 'bg-indigo-600' : 'bg-red-800' }} p-6 rounded-3xl shadow-xl text-white relative overflow-hidden transition-colors duration-500">
            <p class="text-xs font-bold text-indigo-200 uppercase tracking-widest mb-1">Laba Bersih (Net)</p>
            <h3 class="text-3xl font-black tracking-tighter">Rp {{ number_format($netProfit, 0, ',', '.') }}</h3>
            <div class="mt-2 flex items-center gap-2">
                <span class="bg-white/20 px-3 py-0.5 rounded-full text-[9px] font-black uppercase">
                    Status: {{ $netProfit >= 0 ? 'Profit' : 'Loss' }}
                </span>
            </div>
        </div>
    </div>

    {{-- FILTER PERIODE --}}
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 mb-8 no-print">
        <form method="GET" action="{{ route('expenses.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $start_date }}" 
                       class="w-full border-gray-200 rounded-2xl px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $end_date }}" 
                       class="w-full border-gray-200 rounded-2xl px-4 py-2.5 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-gray-900 text-white px-6 py-2.5 rounded-2xl font-bold hover:bg-black transition shadow-lg shadow-gray-200">
                    Terapkan
                </button>
                <a href="{{ route('expenses.index') }}" class="p-2.5 bg-gray-100 text-gray-500 rounded-2xl hover:bg-gray-200 transition" title="Reset">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </a>
            </div>
            <div class="relative">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Pencarian Cepat</label>
                <input type="text" id="jsSearchInput" onkeyup="filterTable()" placeholder="Ketik nama biaya..." 
                       class="w-full border-gray-200 rounded-2xl px-4 py-2.5 focus:ring-indigo-500">
            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="expenseTable" class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50 text-gray-400 text-[10px] uppercase font-bold tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-8 py-5">No. Referensi</th>
                        <th class="px-8 py-5">Deskripsi / Catatan</th>
                        <th class="px-8 py-5">Kategori</th>
                        <th class="px-8 py-5 text-right">Jumlah</th>
                        <th class="px-8 py-5 text-center">Tanggal</th>
                        <th class="px-8 py-5 text-right no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($expenses as $e)
                    <tr class="hover:bg-gray-50/80 transition duration-150 group">
                        <td class="px-8 py-5 font-mono font-bold text-indigo-600 text-xs">
                            {{ $e->reference_number }}
                        </td>
                        <td class="px-8 py-5">
                            <div class="font-bold text-gray-800">{{ $e->name }}</div>
                            <div class="text-[10px] text-gray-400 italic leading-tight">{{ $e->notes ?? 'N/A' }}</div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter">
                                {{ $e->category ?? 'Umum' }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right font-black text-red-600 text-sm">
                            Rp {{ number_format($e->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-8 py-5 text-center text-xs text-gray-500 font-medium">
                            {{ \Carbon\Carbon::parse($e->expense_date)->format('d/m/Y') }}
                        </td>
                        <td class="px-8 py-5 text-right no-print">
                            <form action="{{ route('expenses.destroy', $e->id) }}" method="POST" onsubmit="return confirm('Hapus data pengeluaran?')">
                                @csrf @method('DELETE')
                                <button class="p-2 text-gray-300 hover:text-red-500 transition opacity-0 group-hover:opacity-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <p class="italic">Tidak ada data biaya pada periode ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($expenses->hasPages())
        <div class="px-8 py-5 bg-gray-50/50 border-t border-gray-100 no-print">
            {{ $expenses->links() }}
        </div>
        @endif
    </div>
</div>

{{-- LOGIC JAVASCRIPT --}}
<script>
    // Pencarian Tabel Real-time
    function filterTable() {
        const query = document.getElementById('jsSearchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#expenseTable tbody tr');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? "" : "none";
        });
    }

    // Helper Bersihkan Angka
    const cleanNum = (t) => parseInt(t.replace(/[^0-9]/g, '')) || 0;

    // EXPORT PDF
    function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');
        
        // Judul & Periode
        doc.setFontSize(22).text("LAPORAN LABA RUGI TOKO", 14, 18);
        doc.setFontSize(10).setTextColor(100).text(`Periode: {{ date('d M Y', strtotime($start_date)) }} - {{ date('d M Y', strtotime($end_date)) }}`, 14, 25);
        
        // Ringkasan Keuangan Box
        doc.autoTable({
            startY: 32,
            head: [['Total Pemasukan', 'Total Pengeluaran', 'Laba Bersih']],
            body: [[
                "Rp {{ number_format($totalRevenue, 0, ',', '.') }}",
                "Rp {{ number_format($totalExpenses, 0, ',', '.') }}",
                "Rp {{ number_format($netProfit, 0, ',', '.') }}"
            ]],
            theme: 'grid',
            headStyles: { fillColor: [79, 70, 229], halign: 'center' },
            columnStyles: { 0: { halign: 'center' }, 1: { halign: 'center' }, 2: { halign: 'center', fontStyle: 'bold' } }
        });

        // Detail Tabel
        const body = Array.from(document.querySelectorAll('#expenseTable tbody tr'))
            .filter(r => r.style.display !== 'none')
            .map(r => [
                r.cells[0].innerText,
                r.cells[1].innerText.split('\n')[0],
                r.cells[2].innerText,
                r.cells[3].innerText,
                r.cells[4].innerText
            ]);

        doc.autoTable({
            startY: doc.lastAutoTable.finalY + 10,
            head: [['Referensi', 'Deskripsi', 'Kategori', 'Jumlah', 'Tanggal']],
            body: body,
            theme: 'striped',
            headStyles: { fillColor: [50, 50, 50] }
        });

        doc.save(`Laporan_Keuangan_{{ date('Ymd') }}.pdf`);
    }

    // EXPORT EXCEL
    function exportToExcel() {
        const data = [
            ["LAPORAN LABA RUGI"],
            ["Periode:", "{{ $start_date }} s/d {{ $end_date }}"],
            [],
            ["RINGKASAN"],
            ["Pemasukan", {{ $totalRevenue }}],
            ["Pengeluaran", {{ $totalExpenses }}],
            ["Laba Bersih", {{ $netProfit }}],
            [],
            ["DETAIL PENGELUARAN"],
            ["Referensi", "Deskripsi", "Kategori", "Jumlah", "Tanggal"]
        ];

        document.querySelectorAll('#expenseTable tbody tr').forEach(r => {
            if(r.style.display !== 'none') {
                data.push([
                    r.cells[0].innerText,
                    r.cells[1].innerText.split('\n')[0],
                    r.cells[2].innerText,
                    cleanNum(r.cells[3].innerText),
                    r.cells[4].innerText
                ]);
            }
        });

        const ws = XLSX.utils.aoa_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Laporan");
        XLSX.writeFile(wb, "Laporan_Keuangan.xlsx");
    }
</script>
@endsection