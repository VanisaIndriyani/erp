<!-- Sidebar (Fixed Width, Gradient, Rounded Right) -->
<aside class="w-[260px] bg-gradient-to-b from-emerald-600 to-emerald-900 text-white flex flex-col h-screen shrink-0 transition-all duration-300 z-50 rounded-r-2xl shadow-2xl overflow-hidden fixed left-0 top-0 lg:relative hidden lg:flex" id="sidebarMenu">
    
    <!-- Brand -->
    <div class="py-6 flex items-center justify-center px-6 border-b border-white/10 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-2 text-decoration-none group">
            <div class="bg-white/10 p-1.5 rounded-lg border border-white/10 group-hover:bg-white/20 transition-all duration-300 shadow-lg shadow-emerald-900/20">
                <img src="{{ asset('img/Logo KJH New.png') }}" alt="Logo" class="w-8 h-8 object-contain">
            </div>
            <div class="flex flex-col text-center">
                <span class="text-white font-heading font-bold text-lg leading-tight tracking-tight">Inventory<span class="text-emerald-400">ERP</span></span>
            </div>
        </a>
    </div>

    <!-- Scrollable Content -->
    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-2 custom-scrollbar">
        
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 group relative {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white shadow-lg shadow-emerald-900/20' : 'text-emerald-100/70 hover:bg-white/5 hover:text-white' }}">
            @if(request()->routeIs('dashboard'))
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>
            @endif
            <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-emerald-400' : 'text-current opacity-70 group-hover:opacity-100' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Section: Master Data -->
        <div class="mt-6 mb-2 px-3 text-[10px] font-bold text-emerald-400/80 uppercase tracking-widest">Master Data</div>
        
        <div x-data="{ open: {{ request()->is('master*') || request()->routeIs('items.*') || request()->routeIs('suppliers.*') || request()->routeIs('customers.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 group relative {{ request()->is('master*') || request()->routeIs('items.*') || request()->routeIs('suppliers.*') || request()->routeIs('customers.*') ? 'text-white' : 'text-emerald-100/70 hover:bg-white/5 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 opacity-70 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                    </svg>
                    <span>Data Master</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-300" :class="{'rotate-90': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1" class="pl-4 mt-1 space-y-1">
                <a href="{{ route('items.index') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('items.*') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('items.*'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Item Barang
                </a>
                <a href="{{ route('suppliers.index') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('suppliers.*') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('suppliers.*'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Supplier
                </a>
                <a href="{{ route('customers.index') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('customers.*') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('customers.*'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Customer
                </a>
            </div>
        </div>

        <!-- Section: Transaksi -->
        <div class="mt-6 mb-2 px-3 text-[10px] font-bold text-emerald-400/80 uppercase tracking-widest">Transaksi</div>

        <!-- Pembelian -->
        <div x-data="{ open: {{ request()->is('pembelian*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 group relative {{ request()->is('pembelian*') ? 'text-white' : 'text-emerald-100/70 hover:bg-white/5 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 opacity-70 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Pembelian</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-300" :class="{'rotate-90': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="pl-4 mt-1 space-y-1">
                <a href="{{ route('pembelian.transaksi.index') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('pembelian.transaksi.*') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('pembelian.transaksi.*'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Transaksi PO
                </a>
                <a href="{{ route('pembelian.pembayaran.index') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('pembelian.pembayaran.*') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('pembelian.pembayaran.*'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Bayar Hutang
                </a>
            </div>
        </div>

        <!-- Penjualan -->
        <div x-data="{ open: {{ request()->is('penjualan*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 group relative {{ request()->is('penjualan*') ? 'text-white' : 'text-emerald-100/70 hover:bg-white/5 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 opacity-70 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span>Penjualan</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-300" :class="{'rotate-90': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="pl-4 mt-1 space-y-1">
                <a href="{{ route('penjualan.transaksi.index') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('penjualan.transaksi.*') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('penjualan.transaksi.*'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Kasir / POS
                </a>
                <a href="{{ route('penjualan.pembayaran.index') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('penjualan.pembayaran.*') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('penjualan.pembayaran.*'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Bayar Piutang
                </a>
            </div>
        </div>

        <!-- Persediaan -->
        <div x-data="{ open: {{ request()->is('persediaan*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 group relative {{ request()->is('persediaan*') ? 'text-white' : 'text-emerald-100/70 hover:bg-white/5 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 opacity-70 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span>Persediaan</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-300" :class="{'rotate-90': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="pl-4 mt-1 space-y-1">
                <a href="{{ route('persediaan.masuk') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('persediaan.masuk') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('persediaan.masuk'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Item Masuk
                </a>
                <a href="{{ route('persediaan.keluar') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('persediaan.keluar') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('persediaan.keluar'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Item Keluar
                </a>
                <a href="{{ route('persediaan.opname') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('persediaan.opname') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('persediaan.opname'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Stok Opname
                </a>
                <a href="{{ route('persediaan.transfer') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('persediaan.transfer') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('persediaan.transfer'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Transfer Gudang
                </a>
            </div>
        </div>

        <!-- Section: Keuangan -->
        <div class="mt-6 mb-2 px-3 text-[10px] font-bold text-emerald-400/80 uppercase tracking-widest">Keuangan</div>

        <!-- Akuntansi -->
        <div x-data="{ open: {{ request()->is('akuntansi*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 group relative {{ request()->is('akuntansi*') ? 'text-white' : 'text-emerald-100/70 hover:bg-white/5 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 opacity-70 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span>Akuntansi</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-300" :class="{'rotate-90': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="pl-4 mt-1 space-y-1">
                <a href="{{ route('akuntansi.akun.index') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('akuntansi.akun.*') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('akuntansi.akun.*'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Daftar Perkiraan
                </a>
                <a href="{{ route('akuntansi.jurnal.index') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('akuntansi.jurnal.*') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('akuntansi.jurnal.*'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Jurnal Umum
                </a>
                <a href="{{ route('akuntansi.kas_masuk.index') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('akuntansi.kas_masuk.*') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('akuntansi.kas_masuk.*'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Kas Masuk
                </a>
                <a href="{{ route('akuntansi.kas_keluar.index') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('akuntansi.kas_keluar.*') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('akuntansi.kas_keluar.*'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Kas Keluar
                </a>
                <a href="{{ route('akuntansi.kas_transfer.index') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('akuntansi.kas_transfer.*') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('akuntansi.kas_transfer.*'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Kas Transfer
                </a>
                <a href="{{ route('akuntansi.saldo_awal.index') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('akuntansi.saldo_awal.*') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('akuntansi.saldo_awal.*'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Saldo Awal
                </a>
                <a href="{{ route('akuntansi.buku_besar.index') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('akuntansi.buku_besar.*') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('akuntansi.buku_besar.*'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Buku Besar
                </a>
            </div>
        </div>

        <!-- Laporan -->
        <div x-data="{ open: {{ request()->is('laporan*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 group relative {{ request()->is('laporan*') ? 'text-white' : 'text-emerald-100/70 hover:bg-white/5 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 opacity-70 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Laporan</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-300" :class="{'rotate-90': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="pl-4 mt-1 space-y-1">
                <a href="{{ route('laporan.pembelian') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('laporan.pembelian') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('laporan.pembelian'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Lap. Pembelian
                </a>
                <a href="{{ route('laporan.penjualan') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('laporan.penjualan') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('laporan.penjualan'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Lap. Penjualan
                </a>
                <a href="{{ route('laporan.hutang') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('laporan.hutang') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('laporan.hutang'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Lap. Hutang
                </a>
                <a href="{{ route('laporan.piutang') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('laporan.piutang') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('laporan.piutang'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Lap. Piutang
                </a>
                <a href="{{ route('laporan.persediaan') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('laporan.persediaan') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('laporan.persediaan'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Lap. Persediaan
                </a>
                <a href="{{ route('laporan.buku_kas') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('laporan.buku_kas') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('laporan.buku_kas'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Lap. Buku Kas
                </a>
            </div>
        </div>

        <!-- Section: System -->
        <div class="mt-6 mb-2 px-3 text-[10px] font-bold text-emerald-400/80 uppercase tracking-widest">System</div>

        <!-- Pengaturan -->
        <div x-data="{ open: {{ request()->is('pengaturan*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 group relative {{ request()->is('pengaturan*') ? 'text-white' : 'text-emerald-100/70 hover:bg-white/5 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 opacity-70 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Pengaturan</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-300" :class="{'rotate-90': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="pl-4 mt-1 space-y-1">
                <a href="{{ route('pengaturan.monitoring_po') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('pengaturan.monitoring_po') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('pengaturan.monitoring_po'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Monitoring PO
                </a>
                <a href="{{ route('pengaturan.monitoring_transaksi') }}" class="block px-3 py-2 rounded-lg text-sm transition-colors relative {{ request()->routeIs('pengaturan.monitoring_transaksi') ? 'text-white bg-white/10 font-medium' : 'text-emerald-200/60 hover:text-white hover:bg-white/5' }}">
                    @if(request()->routeIs('pengaturan.monitoring_transaksi'))<div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-400 rounded-r-full shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>@endif
                    Monitoring Transaksi
                </a>
            </div>
        </div>
    </div>

    <!-- User Profile -->
    <div class="p-4 border-t border-white/10 bg-black/20">
        <div class="flex items-center gap-3">
            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Admin' }}&background=10b981&color=ffffff" alt="" class="w-10 h-10 rounded-full border-2 border-emerald-500/50">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                <p class="text-xs text-emerald-200/70 truncate">Super Admin</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="p-2 text-emerald-200/70 hover:text-white hover:bg-white/10 rounded-lg transition-colors" title="Logout">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>