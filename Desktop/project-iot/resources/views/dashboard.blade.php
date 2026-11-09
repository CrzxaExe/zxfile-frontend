<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Fashion Inventory System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">

    <!-- Top Navigation -->
    <header class="bg-slate-900 border-b-4 border-amber-100 sticky top-0 z-50 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center font-black text-amber-100 shadow-md border-2 border-amber-100">
                    SF
                </div>
                <div>
                    <h1 class="text-base font-black text-white leading-tight">Smart Fashion Inventory</h1>
                    <p class="text-xs font-bold text-slate-400">Conveyor PID & Sorting System</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-black bg-emerald-500/10 text-emerald-400 border-2 border-amber-100">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                    System Online
                </span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <!-- Stat Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Produksi -->
            <div class="bg-slate-900 border-4 border-amber-100 rounded-2xl p-6 shadow-lg flex items-center justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-wider text-amber-100/80">Total Produksi</p>
                    <h3 class="text-4xl font-black text-white mt-2">{{ $product->total_produksi ?? 0 }}</h3>
                    <p class="text-xs font-bold text-slate-400 mt-1">Item terdeteksi IR Sensor</p>
                </div>
                <div class="p-3.5 bg-slate-800 text-indigo-400 rounded-xl border-2 border-amber-100">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>

            <!-- Stok Tersedia -->
            <div class="bg-slate-900 border-4 border-amber-100 rounded-2xl p-6 shadow-lg flex items-center justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-wider text-amber-100/80">Stok Tersedia</p>
                    <h3 class="text-4xl font-black text-emerald-400 mt-2">{{ $product->stok_tersedia ?? 0 }}</h3>
                    <p class="text-xs font-bold text-slate-400 mt-1">Siap disalurkan di rak</p>
                </div>
                <div class="p-3.5 bg-slate-800 text-emerald-400 rounded-xl border-2 border-amber-100">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>

            <!-- Barang Terjual -->
            <div class="bg-slate-900 border-4 border-amber-100 rounded-2xl p-6 shadow-lg flex items-center justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-wider text-amber-100/80">Barang Terjual</p>
                    <h3 class="text-4xl font-black text-amber-400 mt-2">{{ $product->total_terjual ?? 0 }}</h3>
                    <p class="text-xs font-bold text-slate-400 mt-1">Keluar dari gudang</p>
                </div>
                <div class="p-3.5 bg-slate-800 text-amber-400 rounded-xl border-2 border-amber-100">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Detail Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- QR Code Box -->
            <div class="bg-slate-900 border-4 border-amber-100 rounded-2xl p-6 shadow-lg flex flex-col items-center justify-center text-center">
                <h2 class="text-xs font-black text-amber-100 uppercase tracking-wider mb-4">QR Code Produk Unique</h2>
                <div class="p-4 bg-white rounded-2xl border-4 border-amber-200 shadow-inner">
                    @if(isset($product) && $product->qr_code_path)
                        <img src="{{ str_starts_with($product->qr_code_path, 'http') ? $product->qr_code_path : asset('storage/' . $product->qr_code_path) }}" alt="QR Code" class="w-44 h-44 object-contain">
                    @else
                        <div class="w-44 h-44 bg-slate-100 flex items-center justify-center text-slate-500 text-xs font-bold rounded-xl border-2 border-dashed border-slate-400">
                            Menunggu Data ESP32...
                        </div>
                    @endif
                </div>
                <p class="mt-4 text-xs font-mono text-slate-200 bg-slate-950 px-4 py-2 rounded-xl border-2 border-amber-100 font-bold">
                    SKU: <span class="text-indigo-400 font-black">{{ $product->sku ?? 'N/A' }}</span>
                </p>
            </div>

            <!-- Table Riwayat -->
            <div class="lg:col-span-2 bg-slate-900 border-4 border-amber-100 rounded-2xl p-6 shadow-lg flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-3 border-b-4 border-amber-100">
                        <h2 class="text-xs font-black text-slate-200 uppercase tracking-wider">Monitoring Konveyor Real-Time</h2>
                        <span class="text-xs font-black text-amber-100 bg-slate-800 px-3 py-1 rounded-lg border-2 border-amber-100">10 Log Terakhir</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-200">
                            <thead class="text-xs font-black text-amber-100 uppercase bg-slate-950 border-b-4 border-amber-100">
                                <tr>
                                    <th class="py-3.5 px-4 border-r-2 border-amber-100">Waktu Log</th>
                                    <th class="py-3.5 px-4 border-r-2 border-amber-100">Alokasi Rak</th>
                                    <th class="py-3.5 px-4 text-right">Kecepatan PID (RPM)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y-2 divide-slate-800 font-bold">
                                @forelse($logs as $log)
                                    <tr class="hover:bg-slate-800/80 transition-colors">
                                        <td class="py-3.5 px-4 font-mono text-xs text-slate-300 border-r-2 border-slate-800">
                                            {{ $log->created_at->format('H:i:s - d M Y') }}
                                        </td>
                                        <td class="py-3.5 px-4 border-r-2 border-slate-800">
                                            @php
                                                $rakCode = $log->rak->kode_rak ?? '-';
                                                $badgeColor = match($rakCode) {
                                                    'RAK-A' => 'bg-indigo-500/20 text-indigo-300 border-amber-100',
                                                    'RAK-B' => 'bg-purple-500/20 text-purple-300 border-amber-100',
                                                    'RAK-C' => 'bg-pink-500/20 text-pink-300 border-amber-100',
                                                    default => 'bg-slate-500/20 text-slate-400 border-amber-100',
                                                };
                                            @endphp
                                            <span class="px-3 py-1 rounded-lg text-xs font-black border-2 {{ $badgeColor }}">
                                                {{ $rakCode }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-right font-mono font-black text-emerald-400">
                                            {{ number_format($log->kecepatan_rpm, 1) }} RPM
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-10 text-center text-slate-500 text-xs font-bold">
                                            Belum ada aktivitas konveyor tercatat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>

</body>
</html>