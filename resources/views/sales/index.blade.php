@extends('layouts.app')

@section('title', 'Laporan Penjualan Detail')

@section('content')

{{-- Alpine.js & Moment.js for Enhanced Interactivity --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

<style>
    [x-cloak] { display: none !important; }

    @media print {
        .no-print { display: none !important; }
        .print-only { display: block !important; }
        @page { size: A4 landscape; margin: 1.5cm; }
        body { background: white !important; font-size: 10pt; color: black; }
        table { border-collapse: collapse !important; width: 100%; }
        th { background-color: #f3f4f6 !important; border: 1px solid #000 !important; color: black !important; }
        td { border: 1px solid #000 !important; }
        .shadow, .rounded-xl, .border { box-shadow: none !important; border-radius: 0 !important; }
    }

    /* Print Header - Hidden in Web View */
    .print-header { display: none; }
    @media print {
        .print-header { display: block; text-align: center; margin-bottom: 2rem; }
    }
</style>

<div class="p-4 md:p-8 bg-gray-50 min-h-screen">

    {{-- PRINT HEADER --}}
    <div class="print-header">
        <h1 class="text-3xl font-bold uppercase">Laporan Penjualan Detail</h1>
        <p class="text-lg">Periode: {{ request('start_date') ?? 'Awal' }} s/d {{ request('end_date') ?? 'Sekarang' }}</p>
        <p class="text-sm">Dicetak oleh: {{ Auth::user()->name }} pada {{ now()->format('d/m/Y H:i') }}</p>
        <hr class="mt-4 border-black">
    </div>

    {{-- WEB HEADER & ACTIONS --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-6 no-print">
        <div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Laporan Penjualan</h2>
            <p class="text-sm md:text-base text-gray-500 mt-1">Kelola data transaksi dan ekspor laporan secara profesional.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
            {{-- EXPORT DROPDOWN --}}
            <div x-data="{ open: false }" class="relative flex-1 md:flex-none">
                <button 
                    @click="open = !open" 
                    type="button"
                    class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl flex items-center justify-center gap-2 font-bold text-sm shadow-lg transition-all active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v1M16 10l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export Laporan
                </button>

                <div 
                    x-show="open" 
                    x-cloak
                    @click.outside="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100 z-[60] py-2 overflow-hidden">
                    
                    <button @click="exportToExcel(); open = false" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-green-50 flex items-center gap-3 transition">
                        <span class="p-1.5 bg-green-100 text-green-600 rounded-lg font-bold text-xs uppercase">Xls</span> 
                        <div>
                            <p class="font-bold leading-none">Microsoft Excel</p>
                            <p class="text-[10px] text-gray-400">Export ke format .xlsx</p>
                        </div>
                    </button>
                    <button @click="exportToPDF(); open = false" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-red-50 flex items-center gap-3 transition">
                        <span class="p-1.5 bg-red-100 text-red-600 rounded-lg font-bold text-xs uppercase">Pdf</span>
                        <div>
                            <p class="font-bold leading-none">Dokumen PDF</p>
                            <p class="text-[10px] text-gray-400">Layout landscape standar</p>
                        </div>
                    </button>
                    <button onclick="window.print()" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-3 transition">
                        <span class="p-1.5 bg-gray-100 text-gray-600 rounded-lg font-bold text-xs uppercase">Prt</span>
                        <div>
                            <p class="font-bold leading-none">Cetak Sekarang</p>
                            <p class="text-[10px] text-gray-400">Kirim ke mesin printer</p>
                        </div>
                    </button>
                </div>
            </div>

            <a href="{{ route('sales.create') }}" 
               class="flex-1 md:flex-none text-center bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg transition-all active:scale-95">
                + POS Baru
            </a>
        </div>
    </div>

    {{-- FILTER SECTION --}}
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-8 no-print">
        <form method="GET" action="{{ route('sales.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-gray-400 uppercase mb-1 ml-1">Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nomor Invoice / Kasir..." 
                       class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase mb-1 ml-1">Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border-gray-200 rounded-xl px-4 py-2.5">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase mb-1 ml-1">Selesai</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border-gray-200 rounded-xl px-4 py-2.5">
            </div>
            <div class="flex items-end gap-2">
                <button class="flex-1 bg-gray-900 text-white px-4 py-2.5 rounded-xl font-bold hover:bg-black transition">Filter</button>
                <a href="{{ route('sales.index') }}" class="p-2.5 border border-gray-200 rounded-xl hover:bg-gray-50 transition text-gray-500">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- GRAFIK --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 no-print overflow-hidden">
        <h3 class="font-bold text-gray-800 mb-6 flex items-center gap-2 text-lg">
            <span class="w-2 h-6 bg-indigo-600 rounded-full"></span>
            Tren Penjualan Harian
        </h3>
        <div class="relative w-full h-[250px] md:h-[350px]">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    {{-- TABLE SECTION --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="salesTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">No. Invoice</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Kasir</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Subtotal</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Pajak (PPN)</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Total Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php $subtotal = 0; $tax = 0; $total = 0; @endphp
                    @forelse($sales as $sale)
                        @php 
                            $currSub = $sale->total_price - $sale->tax_amount;
                            $subtotal += $currSub; 
                            $tax += $sale->tax_amount; 
                            $total += $sale->total_price; 
                        @endphp
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-bold text-indigo-600 tracking-tighter">{{ $sale->invoice_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $sale->user->name ?? 'System' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-600">Rp{{ number_format($currSub, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-amber-600">Rp{{ number_format($sale->tax_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-gray-900">Rp{{ number_format($sale->total_price, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400">Tidak ada data transaksi ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50 font-bold border-t-2 border-gray-100">
                    <tr>
                        <td colspan="3" class="px-6 py-5 text-right text-gray-500 uppercase text-xs">Akumulasi Hal Ini</td>
                        <td class="px-6 py-5 text-right">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                        <td class="px-6 py-5 text-right text-amber-600">Rp{{ number_format($tax, 0, ',', '.') }}</td>
                        <td class="px-6 py-5 text-right text-indigo-600 text-lg">Rp{{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="p-6 bg-white no-print">
            {{ $sales->links() }}
        </div>
    </div>
</div>

{{-- LIBRARIES --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

<script>
    // Helper to format/unformat currency
    const formatIDR = (num) => "Rp" + num.toLocaleString('id-ID');
    const cleanNum = (text) => parseFloat(text.replace(/Rp|\./g, '').trim()) || 0;

    const salesTable = document.getElementById('salesTable');

    function getCleanData() {
        const rows = Array.from(salesTable.querySelectorAll('tbody tr'));
        if(rows[0].innerText.includes("Tidak ada data")) return [];
        
        return rows.map(tr => {
            const td = tr.querySelectorAll('td');
            return [
                td[0].innerText, // Waktu
                td[1].innerText, // Invoice
                td[2].innerText, // Kasir
                cleanNum(td[3].innerText), // Subtotal
                cleanNum(td[4].innerText), // Tax
                cleanNum(td[5].innerText)  // Total
            ];
        });
    }

    // --- EXCEL ---
    function exportToExcel() {
        const rawData = getCleanData();
        const header = [
            ["LAPORAN PENJUALAN DETAIL"],
            ["Periode:", "{{ request('start_date') ?? 'Awal' }} s/d {{ request('end_date') ?? 'Sekarang' }}"],
            ["Dicetak pada:", moment().format('DD/MM/YYYY HH:mm')],
            [],
            ["Waktu", "No. Invoice", "Kasir", "Subtotal", "Pajak", "Total Akhir"]
        ];

        const footer = [
            [],
            ["", "", "GRAND TOTAL", 
                rawData.reduce((a,b) => a + b[3], 0),
                rawData.reduce((a,b) => a + b[4], 0),
                rawData.reduce((a,b) => a + b[5], 0)
            ]
        ];

        const finalSheetData = [...header, ...rawData, ...footer];
        const ws = XLSX.utils.aoa_to_sheet(finalSheetData);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Laporan Penjualan");
        XLSX.writeFile(wb, `Laporan_Sales_${moment().format('YYYYMMDD')}.xlsx`);
    }

    // --- PDF ---
    function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');
        const data = getCleanData();

        doc.setFontSize(20).text("LAPORAN PENJUALAN DETAIL", 14, 15);
        doc.setFontSize(10).text(`Periode: {{ request('start_date') ?? 'Awal' }} - {{ request('end_date') ?? 'Sekarang' }}`, 14, 22);
        doc.text(`Dicetak: ${moment().format('DD/MM/YYYY HH:mm')}`, 14, 27);

        const tableBody = data.map(r => [r[0], r[1], r[2], formatIDR(r[3]), formatIDR(r[4]), formatIDR(r[5])]);

        doc.autoTable({
            startY: 35,
            head: [['Waktu', 'No. Invoice', 'Kasir', 'Subtotal', 'Pajak', 'Total']],
            body: tableBody,
            theme: 'striped',
            headStyles: { fillColor: [63, 63, 70], halign: 'center' },
            columnStyles: { 3: { halign: 'right' }, 4: { halign: 'right' }, 5: { halign: 'right', fontStyle: 'bold' } },
            foot: [[
                { content: 'TOTAL KESELURUHAN', colSpan: 3, styles: { halign: 'right', fillColor: [240, 240, 240] } },
                { content: formatIDR(data.reduce((a,b)=>a+b[3],0)), styles: { halign: 'right', fillColor: [240, 240, 240] } },
                { content: formatIDR(data.reduce((a,b)=>a+b[4],0)), styles: { halign: 'right', fillColor: [240, 240, 240] } },
                { content: formatIDR(data.reduce((a,b)=>a+b[5],0)), styles: { halign: 'right', fontStyle: 'bold', textColor: [79, 70, 229], fillColor: [240, 240, 240] } }
            ]]
        });

        doc.save('Laporan_Penjualan_Detail.pdf');
    }

    // --- CHART ---
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const chartLabels = @json($sales->pluck('created_at')->map(fn($d) => $d->format('d/m')));
        const chartValues = @json($sales->pluck('total_price'));

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: chartValues,
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.05)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#4f46e5'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { callback: v => 'Rp' + v.toLocaleString('id-ID') } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>

@endsection