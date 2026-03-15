@extends('layouts.app')

@section('title', 'Dashboard Overview')

@section('content')
<div class="p-4 md:p-8 bg-gray-50 min-h-screen pb-20">
    
    {{-- HEADER & REAL-TIME CLOCK --}}
    <div class="mb-8 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div>
            <h2 class="text-3xl font-black text-gray-800 uppercase tracking-tighter">Dashboard</h2>
            <p class="text-sm text-gray-500 font-medium italic">Monitoring performa bisnis & arus kas secara real-time.</p>
        </div>
        
        <div class="flex flex-wrap gap-3 no-print">
            <div class="bg-white px-5 py-3 rounded-[1.5rem] shadow-sm border border-gray-100 text-center min-w-[110px] group hover:border-indigo-500 transition-all">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">WIB (Jakarta)</p>
                <span id="clock-wib" class="text-sm font-black text-indigo-600 font-mono italic">--:--:--</span>
            </div>
            <div class="bg-white px-5 py-3 rounded-[1.5rem] shadow-sm border border-gray-100 text-center min-w-[110px] group hover:border-indigo-500 transition-all">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">WITA (Bali)</p>
                <span id="clock-wita" class="text-sm font-black text-indigo-600 font-mono italic">--:--:--</span>
            </div>
            <div class="bg-white px-5 py-3 rounded-[1.5rem] shadow-sm border border-gray-100 text-center min-w-[110px] group hover:border-indigo-500 transition-all">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">WIT (Papua)</p>
                <span id="clock-wit" class="text-sm font-black text-indigo-600 font-mono italic">--:--:--</span>
            </div>
        </div>
    </div>

    {{-- ROW 1: FINANCIAL STATS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-green-50 text-green-600 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Revenue</span>
            </div>
            <h3 class="text-xl font-black text-gray-800">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h3>
            <p class="text-[9px] text-gray-400 mt-1 uppercase font-bold tracking-tighter italic">Omzet Bulan Ini</p>
        </div>

        <div class="{{ $monthlyNetProfit >= 0 ? 'bg-indigo-600 shadow-indigo-100' : 'bg-red-800 shadow-red-100' }} p-6 rounded-[2rem] shadow-xl text-white group transition-all duration-500">
            <p class="text-[10px] font-black text-white/60 uppercase tracking-widest mb-4 italic italic">Net Profit</p>
            <h3 class="text-xl font-black tracking-tighter">Rp {{ number_format($monthlyNetProfit, 0, ',', '.') }}</h3>
            <p class="text-[9px] text-white/50 mt-1 uppercase font-bold tracking-widest italic">Laba Bersih Toko</p>
        </div>

        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-4 text-[9px] font-black text-gray-400 uppercase tracking-widest italic">
                <span>Inventory</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xl font-black text-gray-800 leading-none">{{ $productCount }}</p>
                    <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest">Items</p>
                </div>
                <div class="border-l pl-4 border-gray-100">
                    <p class="text-xl font-black text-gray-800 leading-none">{{ $supplierCount }}</p>
                    <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest">Vendors</p>
                </div>
            </div>
        </div>

        <div class="bg-amber-500 p-6 rounded-[2rem] shadow-lg border border-amber-600 text-white">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-white/20 text-white rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 0h12m-12 0H0"/></svg>
                </div>
                <span class="text-[10px] font-black text-amber-100 uppercase tracking-widest">Promos</span>
            </div>
            <h3 class="text-xl font-black tracking-tighter">{{ $promoCount }} Promo Aktif</h3>
            <p class="text-[9px] text-amber-100 mt-1 font-bold uppercase tracking-widest italic italic">Kupon Berlaku</p>
        </div>
    </div>

    {{-- ROW 2: CHART & ALERT --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <h3 class="text-xs font-black text-gray-800 uppercase tracking-widest mb-8 flex items-center gap-2">
                <span class="w-2 h-4 bg-indigo-600 rounded-full"></span> Tren Penjualan
            </h3>
            <div class="h-64">
                <canvas id="dashboardSalesChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xs font-black text-red-500 uppercase tracking-widest">Stok Menipis</h3>
                <span class="px-2 py-1 bg-red-100 text-red-600 rounded-lg text-[8px] font-black uppercase tracking-tighter">Alert</span>
            </div>
            <div class="space-y-3">
                @forelse($lowStockProducts as $lp)
                <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-2xl border border-gray-100 hover:bg-red-50 hover:border-red-200 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center font-black text-red-600 border border-gray-100 group-hover:border-red-300">
                        {{ $lp->stock }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-800 truncate">{{ $lp->name }}</p>
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest italic italic">Restock Segera</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-10">
                    <p class="text-xs text-gray-400 font-bold italic uppercase">Stok Amann</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ROW 3: RECENT SALES & PROMO TRACKER --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Recent Sales Table --}}
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xs font-black text-gray-800 uppercase tracking-widest flex items-center gap-2">
                    <span class="w-2 h-4 bg-green-500 rounded-full"></span> Transaksi Terakhir
                </h3>
                <a href="{{ route('sales.index') }}" class="text-[9px] font-black text-indigo-600 hover:underline uppercase italic">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[9px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">
                            <th class="pb-3 px-2 italic">Invoice</th>
                            <th class="pb-3 px-2 text-right italic">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @php $recentSales = \App\Models\Sales::latest()->limit(5)->get(); @endphp
                        @foreach($recentSales as $sale)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-2 font-mono text-[10px] font-bold text-indigo-600 tracking-tighter">{{ $sale->invoice_number }}</td>
                            <td class="py-4 px-2 text-right text-xs font-black text-gray-800 italic italic">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Promo Tracker --}}
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <h3 class="text-xs font-black text-gray-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                <span class="w-2 h-4 bg-amber-500 rounded-full"></span> Kampanye Promo Aktif
            </h3>
            <div class="space-y-4">
                @php $activePromos = \App\Models\Promo::where('is_active', true)->limit(3)->get(); @endphp
                @foreach($activePromos as $ap)
                <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100">
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <p class="text-[10px] font-black text-amber-700 uppercase tracking-tight">{{ $ap->name }}</p>
                            <p class="text-[8px] text-amber-600/70 font-bold uppercase italic italic">Hingga: {{ $ap->end_date->format('d M') }}</p>
                        </div>
                        <span class="text-[9px] font-black text-amber-800 uppercase italic italic tracking-tighter">{{ $ap->used_count }} Terpakai</span>
                    </div>
                    @if($ap->usage_limit)
                    <div class="w-full bg-amber-200/40 rounded-full h-1">
                        <div class="bg-amber-500 h-1 rounded-full shadow-sm" style="width: {{ ($ap->used_count / $ap->usage_limit) * 100 }}%"></div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- JAVASCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function updateClocks() {
        const zones = [{ id: 'clock-wib', zone: 'Asia/Jakarta' }, { id: 'clock-wita', zone: 'Asia/Makassar' }, { id: 'clock-wit', zone: 'Asia/Jayapura' }];
        zones.forEach(z => {
            const time = new Intl.DateTimeFormat('id-ID', { timeZone: z.zone, hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }).format(new Date());
            document.getElementById(z.id).textContent = time.replace(/\./g, ':');
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateClocks();
        setInterval(updateClocks, 1000);

        const ctx = document.getElementById('dashboardSalesChart').getContext('2d');
        const salesData = @json($salesData);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: salesData.map(d => d.date),
                datasets: [{
                    label: 'Sales',
                    data: salesData.map(d => d.total),
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.05)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    borderWidth: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, grid: { color: '#f3f4f6' } }, x: { grid: { display: false } } }
            }
        });
    });
</script>
@endsection