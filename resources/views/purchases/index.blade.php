@extends('layouts.app')

@section('title', 'Daftar Pesanan Pembelian')

@section('content')
{{-- Library pendukung --}}
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
        table { border-collapse: collapse !important; width: 100%; }
        th, td { border: 1px solid #ddd !important; padding: 8px !important; }
    }
    .print-header { display: none; }
    @media print { .print-header { display: block; text-align: center; margin-bottom: 20px; } }
</style>

<div class="p-4 md:p-8 bg-gray-50 min-h-screen">
    
    {{-- PRINT HEADER --}}
    <div class="print-header">
        <h1 class="text-2xl font-bold uppercase">Laporan Pesanan Pembelian (PO)</h1>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
        <hr class="my-4 border-black">
    </div>

    {{-- WEB HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 no-print">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800">Pesanan Pembelian (PO)</h2>
            <p class="text-sm text-gray-500">Manajemen stok masuk dan pesanan ke supplier.</p>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto">
            {{-- EXPORT DROPDOWN --}}
            <div x-data="{ open: false }" class="relative flex-1 md:flex-none">
                <button @click="open = !open" type="button" class="w-full bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl flex items-center justify-center gap-2 font-bold text-sm shadow transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v1M16 10l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export
                </button>

                <div x-show="open" x-cloak @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border z-50 py-2">
                    <button @click="exportToExcel(); open = false" class="w-full text-left px-4 py-2 hover:bg-gray-50 flex items-center gap-2 text-sm">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span> Export Excel
                    </button>
                    <button @click="exportToPDF(); open = false" class="w-full text-left px-4 py-2 hover:bg-gray-50 flex items-center gap-2 text-sm">
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span> Export PDF
                    </button>
                    <button onclick="window.print()" class="w-full text-left px-4 py-2 hover:bg-gray-50 flex items-center gap-2 text-sm">
                        <span class="w-2 h-2 bg-gray-500 rounded-full"></span> Cetak Laporan
                    </button>
                </div>
            </div>

            <a href="{{ route('purchases.create') }}" class="flex-1 md:flex-none bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow text-center">
                + PO Baru
            </a>
        </div>
    </div>

    {{-- JAVASCRIPT FILTER CARD --}}
    <div class="bg-white p-5 rounded-2xl shadow-sm border mb-6 no-print">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase ml-1">Cari Data (Instant)</label>
                <input type="text" id="jsSearchInput" placeholder="Ketik Ref No atau Supplier..." 
                       class="w-full border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition">
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase ml-1">Status</label>
                <select id="jsStatusFilter" class="w-full border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-indigo-500 transition">
                    <option value="">Semua Status</option>
                    <option value="PENDING">PENDING</option>
                    <option value="ORDERED">ORDERED</option>
                    <option value="RECEIVED">RECEIVED</option>
                </select>
            </div>
            <div class="flex items-end justify-between gap-4">
                <button onclick="resetFilters()" class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-sm font-bold hover:bg-gray-200 transition">
                    Reset
                </button>
                <p class="text-xs text-gray-400 italic mb-2">Ditemukan: <span id="visibleCount" class="font-bold text-indigo-600">0</span> data</p>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table id="poTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-xs font-bold uppercase text-gray-500 border-b">
                        <th class="px-6 py-4">Ref No</th>
                        <th class="px-6 py-4">Supplier</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($purchases as $p)
                    <tr class="hover:bg-gray-50/80 transition duration-150">
                        <td class="px-6 py-4 font-mono font-bold text-indigo-600">
                            <a href="{{ route('purchases.show', $p->id) }}" class="hover:underline">{{ $p->reference_number }}</a>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-700">{{ $p->supplier->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($p->purchase_date)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">Rp {{ number_format($p->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'ordered' => 'bg-blue-100 text-blue-700',
                                    'received' => 'bg-green-100 text-green-700',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $statusClasses[strtolower($p->status)] ?? 'bg-gray-100' }}">
                                {{ $p->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right no-print">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('purchases.show', $p->id) }}" class="p-2 text-gray-400 hover:text-indigo-600 transition" title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                @if(strtolower($p->status) != 'received')
                                <a href="{{ route('purchases.edit', $p->id) }}" class="p-2 text-gray-400 hover:text-blue-600 transition" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2-0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- PAGINATION --}}
        <div class="p-6 bg-gray-50 border-t no-print">
            {{ $purchases->links() }}
        </div>
    </div>
</div>

{{-- SCRIPT LOGIC --}}
<script>
    const searchInput = document.getElementById('jsSearchInput');
    const statusFilter = document.getElementById('jsStatusFilter');
    const tableRows = document.querySelectorAll('#poTable tbody tr');
    const visibleCount = document.getElementById('visibleCount');

    // 1. Fungsi Filter Real-time
    function applyFilter() {
        const query = searchInput.value.toLowerCase();
        const status = statusFilter.value.toUpperCase();
        let count = 0;

        tableRows.forEach(row => {
            const refNo = row.cells[0].innerText.toLowerCase();
            const supplier = row.cells[1].innerText.toLowerCase();
            const rowStatus = row.cells[4].innerText.toUpperCase();

            const matchSearch = refNo.includes(query) || supplier.includes(query);
            const matchStatus = status === "" || rowStatus.includes(status);

            if (matchSearch && matchStatus) {
                row.style.display = "";
                count++;
            } else {
                row.style.display = "none";
            }
        });
        visibleCount.innerText = count;
    }

    searchInput.addEventListener('input', applyFilter);
    statusFilter.addEventListener('change', applyFilter);

    function resetFilters() {
        searchInput.value = "";
        statusFilter.value = "";
        applyFilter();
    }

    // 2. Ambil Data yang Terfilter untuk Export
    function getFilteredData() {
        return Array.from(tableRows)
            .filter(row => row.style.display !== 'none')
            .map(row => [
                row.cells[0].innerText.trim(),
                row.cells[1].innerText.trim(),
                row.cells[2].innerText.trim(),
                row.cells[3].innerText.replace(/[Rp. ]/g, ''),
                row.cells[4].innerText.trim()
            ]);
    }

    // 3. Export Excel
    function exportToExcel() {
        const data = getFilteredData();
        if(data.length === 0) return alert("Data tidak tersedia!");

        const header = [["LAPORAN PESANAN PEMBELIAN"], ["Waktu Cetak: " + moment().format('LLLL')], [], ["REF NO", "SUPPLIER", "TANGGAL", "TOTAL (IDR)", "STATUS"]];
        const ws = XLSX.utils.aoa_to_sheet([...header, ...data]);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "PO_Data");
        XLSX.writeFile(wb, `PO_Report_${moment().format('YYYYMMDD')}.xlsx`);
    }

    // 4. Export PDF
    function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');
        const data = getFilteredData();
        
        doc.setFontSize(18).text("LAPORAN PESANAN PEMBELIAN", 14, 15);
        doc.setFontSize(10).text(`Dicetak pada: ${moment().format('DD/MM/YYYY HH:mm')}`, 14, 22);

        doc.autoTable({
            startY: 30,
            head: [['REF NO', 'SUPPLIER', 'TANGGAL', 'TOTAL', 'STATUS']],
            body: data.map(r => [r[0], r[1], r[2], "Rp " + parseInt(r[3]).toLocaleString('id-ID'), r[4]]),
            theme: 'striped',
            headStyles: { fillColor: [79, 70, 229] }
        });

        doc.save('Laporan_PO_Filtered.pdf');
    }

    // Hitung awal
    window.onload = applyFilter;
</script>

@endsection