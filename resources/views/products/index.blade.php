@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
{{-- Library pendukung untuk interaksi & Export --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

<div class="container mx-auto p-4 md:p-6">
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 no-print">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 tracking-tight">Products</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola inventaris, kategori, dan supplier produk Anda secara efisien.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            {{-- TOMBOL EXPORT --}}
            <div class="flex bg-gray-100 p-1 rounded-xl border border-gray-200">
                <button onclick="exportExcel()" class="flex items-center gap-2 px-4 py-2 text-sm font-bold text-gray-700 hover:bg-white hover:shadow-sm rounded-lg transition uppercase tracking-wider">
                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/></svg>
                    Excel
                </button>
                <button onclick="exportPDF()" class="flex items-center gap-2 px-4 py-2 text-sm font-bold text-gray-700 hover:bg-white hover:shadow-sm rounded-lg transition uppercase tracking-wider">
                    <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/></svg>
                    PDF
                </button>
            </div>

            <a href="{{ route('products.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl flex items-center shadow-lg transition active:scale-95 font-bold text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Produk
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3 shadow-sm">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- FILTER JAVASCRIPT (Real-time) --}}
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row gap-4 no-print">
        <div class="flex-1 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari nama produk, supplier, atau ID..." 
                   class="w-full border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
        </div>
        <div class="w-full md:w-64">
            <select id="categoryFilter" onchange="filterTable()" class="w-full border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Semua Kategori</option>
                {{-- Kategori diisi otomatis oleh JS --}}
            </select>
        </div>
        <div class="flex items-center text-xs text-gray-400 px-2 italic">
            Menampilkan: <span id="visibleCount" class="font-bold text-indigo-600 mx-1">0</span> produk
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="productTable" class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50 text-gray-500 text-xs uppercase font-bold tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Gambar</th>
                        <th class="px-6 py-4">Produk</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Supplier</th> 
                        <th class="px-6 py-4">Harga</th>
                        <th class="px-6 py-4">Stok</th>
                        <th class="px-6 py-4 text-right no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50/80 transition duration-150 group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-12 w-12 object-cover rounded-xl border border-gray-100 shadow-sm group-hover:scale-105 transition">
                            @else
                                <div class="h-12 w-12 bg-gray-50 rounded-xl flex items-center justify-center border border-dashed border-gray-200">
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 group-hover:text-indigo-600 transition">{{ $product->name }}</div>
                            <div class="text-[10px] text-gray-400 font-mono tracking-tighter uppercase">SKU: #{{ $product->id }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-tight">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap italic">
                            {{ $product->supplier->name ?? 'General Supplier' }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->stock <= 5)
                                <span class="bg-red-50 text-red-600 px-3 py-1 rounded-lg text-xs font-bold border border-red-100 animate-pulse">
                                    Low: {{ $product->stock }}
                                </span>
                            @else
                                <span class="bg-green-50 text-green-600 px-3 py-1 rounded-lg text-xs font-bold border border-green-100">
                                    {{ $product->stock }} Units
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right no-print">
                            <div class="flex justify-end gap-1 opacity-0 group-hover:opacity-100 transition">
                                <a href="{{ route('products.show', $product->id) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="View Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('products.edit', $product->id) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2-0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-16 text-center text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                <p class="italic">Belum ada produk yang tersedia.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($products->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 no-print">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>

{{-- LOGIC JAVASCRIPT --}}
<script>
    // Fungsi Filter Real-time
    function filterTable() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const category = document.getElementById('categoryFilter').value.toLowerCase();
        const rows = document.querySelectorAll('#productTable tbody tr');
        let count = 0;

        rows.forEach(row => {
            const name = row.cells[1].innerText.toLowerCase();
            const supplier = row.cells[3].innerText.toLowerCase();
            const catText = row.cells[2].innerText.toLowerCase().trim();
            
            const matchSearch = name.includes(query) || supplier.includes(query);
            const matchCategory = category === "" || catText === category;

            if (matchSearch && matchCategory) {
                row.style.display = "";
                count++;
            } else {
                row.style.display = "none";
            }
        });
        document.getElementById('visibleCount').innerText = count;
    }

    // Inisialisasi Kategori Otomatis dari Tabel
    document.addEventListener('DOMContentLoaded', () => {
        const rows = document.querySelectorAll('#productTable tbody tr');
        const filterSelect = document.getElementById('categoryFilter');
        const categories = new Set();

        rows.forEach(row => {
            const catText = row.cells[2]?.innerText.trim();
            if(catText && catText !== "Belum ada produk.") {
                categories.add(catText);
            }
        });

        categories.forEach(cat => {
            const opt = document.createElement('option');
            opt.value = cat.toLowerCase();
            opt.text = cat;
            filterSelect.appendChild(opt);
        });
        
        // Hitung awal
        filterTable();
    });

    // Fitur Export EXCEL
    function exportExcel() {
        const table = document.getElementById('productTable');
        const clone = table.cloneNode(true);
        
        // Pembersihan clone tabel (hapus kolom aksi & gambar)
        clone.querySelectorAll('tr').forEach(row => {
            row.deleteCell(-1); // Aksi
            row.deleteCell(0);  // Gambar
        });

        const wb = XLSX.utils.table_to_book(clone, { sheet: "Data Produk" });
        XLSX.writeFile(wb, "Laporan_Stok_Produk.xlsx");
    }

    // Fitur Export PDF
    function exportPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');
        const rows = [];
        const visibleRows = document.querySelectorAll('#productTable tbody tr');

        visibleRows.forEach(row => {
            if(row.style.display !== 'none') {
                const cells = row.cells;
                rows.push([
                    cells[1].innerText.split('\n')[0], // Nama Produk
                    cells[2].innerText.trim(),         // Kategori
                    cells[3].innerText.trim(),         // Supplier
                    cells[4].innerText.trim(),         // Harga
                    cells[5].innerText.trim()          // Stok
                ]);
            }
        });

        doc.setFontSize(18).text("LAPORAN INVENTARIS PRODUK", 14, 15);
        doc.setFontSize(10).text(`Periode: ${new Date().toLocaleDateString('id-ID')}`, 14, 22);

        doc.autoTable({
            head: [['Nama Produk', 'Kategori', 'Supplier', 'Harga', 'Stok']],
            body: rows,
            startY: 28,
            theme: 'striped',
            headStyles: { fillColor: [79, 70, 229] }
        });

        doc.save("Laporan_Produk_Filtered.pdf");
    }
</script>

@endsection