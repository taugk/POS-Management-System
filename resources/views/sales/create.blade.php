@extends('layouts.app')

@section('title', 'Point of Sale')

@section('content')
<div x-data="posSystem()" 
     x-init="init()"
     @keydown.window="handleShortcuts($event)"
     class="flex flex-col h-full -mt-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Point of Sale</h1>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest" x-text="moment().format('dddd, DD MMMM YYYY')"></p>
        </div>
        <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 hidden md:block">
            <span class="text-xs font-black text-indigo-600 font-mono" x-text="moment().format('HH:mm:ss')"></span>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 items-start">
        
        {{-- LEFT SIDE: PRODUCT LIST --}}
        <div class="w-full lg:w-2/3 space-y-4">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" x-model="search" x-ref="searchInput" placeholder="Cari produk... (F1)" 
                           class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm transition-all">
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($products as $product)
                <template x-if="matchProduct('{{ strtolower($product->name) }}', '{{ $product->barcode }}')">
                    <button @click="addItem({
                            id: {{ $product->id }},
                            name: '{{ $product->name }}',
                            price: {{ $product->price }},
                            stock: {{ $product->stock }}
                         })" 
                         class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all duration-200 overflow-hidden flex flex-col text-left group h-full">
                        
                        <div class="h-32 md:h-40 bg-gray-50 flex items-center justify-center relative shrink-0">
                            <div class="absolute top-2 left-2 z-10">
                                <span class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-wider shadow-sm"
                                      :class="{{ $product->stock }} <= 5 ? 'bg-red-500 text-white' : 'bg-indigo-600 text-white'">
                                    {{ $product->stock }} Unit
                                </span>
                            </div>
                            @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            @endif
                        </div>
                        
                        <div class="p-3">
                            <h3 class="text-[11px] font-bold text-gray-700 leading-tight h-8 line-clamp-2 mb-1 group-hover:text-indigo-600">
                                {{ $product->name }}
                            </h3>
                            <p class="text-sm font-black text-gray-900">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                    </button>
                </template>
                @endforeach
            </div>
        </div>

        {{-- RIGHT SIDE: ORDER SUMMARY --}}
        <div class="w-full lg:w-1/3 lg:sticky lg:top-6">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden flex flex-col max-h-[calc(100vh-100px)]">
                <div class="p-4 border-b flex justify-between items-center bg-gray-50 text-[10px] font-black uppercase tracking-widest text-gray-800">
                    <span>Order Summary</span>
                    <button @click="resetCart()" class="text-red-500 hover:underline">Reset</button>
                </div>

                {{-- CART ITEMS --}}
                <div class="flex-1 overflow-y-auto p-4 space-y-4 custom-scrollbar min-h-[150px]">
                    <template x-for="(item, index) in cart" :key="item.id">
                        <div class="flex items-center gap-3 border-b border-gray-50 pb-3 animate-cart-in">
                            <div class="flex-1 min-w-0">
                                <p class="text-[11px] font-bold text-gray-800 truncate" x-text="item.name"></p>
                                <div class="flex items-center justify-between mt-2">
                                    <div class="flex items-center bg-gray-50 rounded-lg border border-gray-100">
                                        <button @click="updateQty(index, -1)" class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-indigo-600">-</button>
                                        <span class="px-2 text-[10px] font-black text-gray-700" x-text="item.qty"></span>
                                        <button @click="updateQty(index, 1)" class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-indigo-600">+</button>
                                    </div>
                                    <span class="text-xs font-black text-gray-900" x-text="formatNumber(item.price * item.qty)"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- PROMO SECTION --}}
                <div class="px-6 py-4 bg-amber-50/50 border-t border-amber-100">
                    <label class="text-[9px] font-black text-amber-600 uppercase tracking-widest block mb-2">Punya Kode Promo?</label>
                    <div class="flex gap-2">
                        <input type="text" x-model="promoCode" :disabled="appliedPromo"
                               placeholder="Contoh: DISKON10" 
                               class="flex-1 px-3 py-2 bg-white border border-amber-200 rounded-xl text-[11px] font-bold uppercase outline-none focus:border-amber-500 disabled:bg-gray-100">
                        
                        <button @click="appliedPromo ? removePromo() : checkPromo()" 
                                type="button"
                                :class="appliedPromo ? 'bg-red-500' : 'bg-amber-600'"
                                class="px-4 py-2 text-white rounded-xl text-[10px] font-black uppercase transition-all active:scale-95 shadow-sm shadow-amber-200">
                            <span x-text="appliedPromo ? 'Batal' : 'Cek'"></span>
                        </button>
                    </div>
                    <template x-if="appliedPromo">
                        <div class="mt-2 p-2 bg-white rounded-lg border border-amber-200 flex justify-between items-center animate-cart-in">
                            <div class="text-[10px] font-bold text-amber-700">
                                <span class="block text-[8px] uppercase opacity-60">Promo Aktif:</span>
                                <span x-text="appliedPromo.name"></span>
                            </div>
                            <span class="text-xs font-black text-red-500" x-text="'-' + formatNumber(discountAmount)"></span>
                        </div>
                    </template>
                </div>

                {{-- TOTAL CALCULATION --}}
                <div class="p-6 bg-gray-50 border-t border-gray-100 space-y-3">
                    <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        <span>Subtotal</span>
                        <span x-text="formatNumber(subtotal)"></span>
                    </div>

                    <div x-show="discountAmount > 0" class="flex justify-between text-[10px] font-bold text-red-500 uppercase tracking-widest">
                        <span>Discount</span>
                        <span x-text="'- ' + formatNumber(discountAmount)"></span>
                    </div>

                    <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        <span>Tax (12%)</span>
                        <span x-text="formatNumber(taxAmount)"></span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-t border-gray-200">
                        <span class="text-[10px] font-black text-gray-800 uppercase tracking-widest">Grand Total</span>
                        <span class="text-2xl font-black text-indigo-600 tracking-tighter" x-text="formatNumber(total)"></span>
                    </div>

                    <div class="space-y-1 mb-4">
                        <div class="flex justify-between items-center">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Received (F2)</label>
                            <span class="text-[9px] font-bold text-indigo-500 cursor-pointer" @click="pay_amount = total">UANG PAS</span>
                        </div>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-xs">Rp</span>
                            <input type="text" x-ref="payInput"
                                   x-bind:value="formatDisplay(pay_amount)"
                                   x-on:input="pay_amount = $event.target.value.replace(/\D/g, '')"
                                   class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-2xl text-gray-900 font-black text-lg outline-none focus:border-indigo-500 transition-all shadow-sm">
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-4 px-1">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Change</span>
                        <span class="text-sm font-black" :class="change < 0 ? 'text-red-500' : 'text-green-600'" x-text="formatNumber(change)"></span>
                    </div>

                    <button @click="submitSale" 
                            :disabled="cart.length === 0 || pay_amount < total"
                            class="w-full py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-lg active:scale-95 shadow-indigo-100"
                            :class="cart.length === 0 || pay_amount < total ? 'bg-gray-200 text-gray-400 cursor-not-allowed shadow-none' : 'bg-indigo-600 hover:bg-indigo-700 text-white'">
                        Checkout & Print (F8)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function posSystem() {
    return {
        search: '', 
        cart: [], 
        pay_amount: 0, 
        barcodeBuffer: '', 
        lastBarcodeTime: 0,
        taxRate: 0.12,
        
        // Promo variables
        promoCode: '',
        appliedPromo: null,
        discountAmount: 0,

        init() {
            this.initBarcodeScanner();
            // Watcher untuk validasi promo saat keranjang berubah
            this.$watch('subtotal', (value) => {
                if (this.appliedPromo && value < this.appliedPromo.min_purchase) {
                    this.removePromo();
                    Swal.fire({
                        icon: 'info',
                        title: 'Promo Dibatalkan',
                        text: 'Total belanja kurang dari syarat minimal promo ini.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                } else if (this.appliedPromo) {
                    this.recalculateDiscount();
                }
            });
        },

        get subtotal() { return this.cart.reduce((sum, i) => sum + (i.price * i.qty), 0); },
        get taxAmount() { 
            // Pajak dihitung dari total setelah diskon
            const taxable = Math.max(0, this.subtotal - this.discountAmount);
            return Math.round(taxable * this.taxRate); 
        },
        get total() { return (this.subtotal - this.discountAmount) + this.taxAmount; },
        get change() { return this.pay_amount - this.total; },

        formatDisplay(v) { return !v || v == 0 ? '' : new Intl.NumberFormat('id-ID').format(v); },
        formatNumber(n) { return 'Rp' + new Intl.NumberFormat('id-ID').format(n); },

        handleShortcuts(e) {
            if (e.key === 'F1') { e.preventDefault(); this.$refs.searchInput.focus(); }
            if (e.key === 'F2') { e.preventDefault(); this.$refs.payInput.focus(); }
            if (e.key === 'F8') { e.preventDefault(); this.submitSale(); }
        },

        initBarcodeScanner() {
            window.addEventListener('keypress', e => {
                const now = Date.now();
                if (now - this.lastBarcodeTime > 100) this.barcodeBuffer = '';
                if (e.key !== 'Enter') { this.barcodeBuffer += e.key; } 
                else { this.findAndAddByBarcode(this.barcodeBuffer); this.barcodeBuffer = ''; }
                this.lastBarcodeTime = now;
            });
        },

        findAndAddByBarcode(code) {
            const products = @json($products);
            const found = products.find(p => p.barcode === code);
            if (found) this.addItem({ id: found.id, name: found.name, price: found.price, stock: found.stock });
        },

        matchProduct(name, barcode) {
            return name.includes(this.search.toLowerCase()) || (barcode && barcode.includes(this.search));
        },

        addItem(product) {
            let found = this.cart.find(i => i.id === product.id);
            if (found) {
                if (found.qty < product.stock) found.qty++;
                else Swal.fire({ icon: 'warning', title: 'Stok Habis' });
            } else { this.cart.push({ ...product, qty: 1 }); }
        },

        updateQty(index, amount) {
            let item = this.cart[index];
            if (amount > 0 && item.qty >= item.stock) return;
            item.qty += amount;
            if (item.qty <= 0) this.cart.splice(index, 1);
        },

        resetCart() {
            this.cart = [];
            this.removePromo();
            this.pay_amount = 0;
        },

        // --- PROMO LOGIC ---
        async checkPromo() {
            if (!this.promoCode) return;
            if (this.cart.length === 0) return Swal.fire('Oops', 'Keranjang masih kosong', 'warning');

            Swal.fire({ title: 'Mengecek kupon...', didOpen: () => Swal.showLoading() });

            try {
                const res = await fetch("{{ route('promos.check') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ 
                        code: this.promoCode,
                        subtotal: this.subtotal 
                    })
                });
                
                const data = await res.json();
                Swal.close();
                
                if (data.valid) {
                    this.appliedPromo = data;
                    this.discountAmount = data.discount_value;
                    Swal.fire({ 
                        icon: 'success', 
                        title: 'Promo Berhasil!', 
                        text: data.message,
                        toast: true, 
                        position: 'top-end', 
                        showConfirmButton: false, 
                        timer: 3000 
                    });
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            } catch (e) {
                Swal.fire('Error', 'Gagal menghubungi server', 'error');
            }
        },

        removePromo() {
            this.appliedPromo = null;
            this.discountAmount = 0;
            this.promoCode = '';
        },

        recalculateDiscount() {
            if (!this.appliedPromo) return;
            // Jika persentase, hitung ulang berdasarkan subtotal baru
            if (this.appliedPromo.type === 'percentage') {
                let disc = (this.subtotal * this.appliedPromo.discount_rate) / 100;
                if (this.appliedPromo.max_discount && disc > this.appliedPromo.max_discount) {
                    disc = this.appliedPromo.max_discount;
                }
                this.discountAmount = disc;
            }
        },

        // --- SUBMIT & PRINT ---
        printReceipt(sale) {
            const printWindow = window.open('', '', 'width=300,height=600');
            printWindow.document.write(`
                <html>
                <head><style>
                    body { font-family: 'Courier New', monospace; width: 58mm; padding: 10px; font-size: 11px; line-height: 1.2; }
                    .center { text-align: center; }
                    .line { border-bottom: 1px dashed #000; margin: 5px 0; }
                    table { width: 100%; border-collapse: collapse; }
                    .total-row td { padding-top: 5px; }
                </style></head>
                <body>
                    <div class="center"><b>POS MASTER</b><br>Inv: ${sale.invoice_number}<br>${moment().format('DD/MM/YY HH:mm')}</div>
                    <div class="line"></div>
                    <table>
                        ${this.cart.map(i => `<tr><td>${i.name} x${i.qty}</td><td align="right">${new Intl.NumberFormat('id-ID').format(i.price * i.qty)}</td></tr>`).join('')}
                    </table>
                    <div class="line"></div>
                    <table>
                        <tr><td>Subtotal</td><td align="right">${new Intl.NumberFormat('id-ID').format(this.subtotal)}</td></tr>
                        ${this.discountAmount > 0 ? `<tr><td>Promo (${this.appliedPromo.name})</td><td align="right">-${new Intl.NumberFormat('id-ID').format(this.discountAmount)}</td></tr>` : ''}
                        <tr><td>Pajak (12%)</td><td align="right">${new Intl.NumberFormat('id-ID').format(this.taxAmount)}</td></tr>
                        <tr class="total-row"><td><b>TOTAL</b></td><td align="right"><b>${new Intl.NumberFormat('id-ID').format(this.total)}</b></td></tr>
                        <tr><td>Bayar</td><td align="right">${new Intl.NumberFormat('id-ID').format(this.pay_amount)}</td></tr>
                        <tr><td>Kembali</td><td align="right">${new Intl.NumberFormat('id-ID').format(this.change)}</td></tr>
                    </table>
                    <div class="line"></div>
                    <div class="center">TERIMA KASIH ATAS KUNJUNGANNYA</div>
                </body></html>
            `);
            printWindow.document.close();
            setTimeout(() => { printWindow.print(); printWindow.close(); }, 500);
        },

        async submitSale() {
            if (this.cart.length === 0 || this.pay_amount < this.total) return;
            Swal.fire({ title: 'Menyimpan Transaksi...', didOpen: () => Swal.showLoading() });
            try {
                const res = await fetch("{{ route('sales.store') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ 
                        items: this.cart.map(i => ({ product_id: i.id, quantity: i.qty })), 
                        pay_amount: this.pay_amount,
                        tax_amount: this.taxAmount,
                        promo_id: this.appliedPromo ? this.appliedPromo.promo_id : null,
                        discount_amount: this.discountAmount
                    })
                });
                const data = await res.json();
                if (data.success) {
                    this.printReceipt(data.data);
                    Swal.fire({ icon: 'success', title: 'Berhasil!', showConfirmButton: false, timer: 1000 }).then(() => window.location.reload());
                } else { Swal.fire({ icon: 'error', title: 'Gagal', text: data.message }); }
            } catch (e) { Swal.fire({ icon: 'error', title: 'Error Koneksi' }); }
        }
    }
}
</script>
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    @keyframes cartIn { from { transform: translateX(20px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    .animate-cart-in { animation: cartIn 0.3s ease-out; }
</style>
@endpush
@endsection