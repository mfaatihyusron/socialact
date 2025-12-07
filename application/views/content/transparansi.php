<div class="pt-32 px-6 max-w-7xl mx-auto pb-20">
    <div class="text-center mb-16">
        <span class="text-brand-green font-bold tracking-[0.2em] uppercase text-xs">Data Terbuka</span>
        <h1 class="font-heading text-4xl md:text-6xl font-bold uppercase mt-2 mb-6 text-white">
            Transparansi <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-green to-teal-500">Keuangan</span>
        </h1>
        <p class="text-gray-400 max-w-2xl mx-auto leading-relaxed">
            Setiap rupiah yang Anda donasikan tercatat di sini. Kami menyajikan data langsung (real-time) dari basis data untuk akuntabilitas penuh.
        </p>
    </div>

    <!-- Kartu Ringkasan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <!-- Sisa Saldo -->
        <div class="bg-brand-dark p-8 rounded-2xl border border-gray-800 relative overflow-hidden group hover:border-brand-green/30 transition-all">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity"><i class="fas fa-wallet text-6xl text-white"></i></div>
            <p class="text-gray-500 text-xs font-bold uppercase mb-2 tracking-wider">Sisa Saldo (Live)</p>
            <h2 class="text-4xl font-heading font-bold text-white">Rp <?= number_format($saldo ?? 0, 0, ',', '.') ?></h2>
        </div>
        <!-- Pemasukan -->
        <div class="bg-brand-dark p-8 rounded-2xl border border-gray-800 relative overflow-hidden group hover:border-brand-green/30 transition-all">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity"><i class="fas fa-arrow-down text-6xl text-brand-green"></i></div>
            <p class="text-gray-500 text-xs font-bold uppercase mb-2 tracking-wider">Total Masuk (Verified)</p>
            <h2 class="text-4xl font-heading font-bold text-brand-green">+Rp <?= number_format($total_masuk ?? 0, 0, ',', '.') ?></h2>
        </div>
        <!-- Pengeluaran -->
        <div class="bg-brand-dark p-8 rounded-2xl border border-gray-800 relative overflow-hidden group hover:border-brand-green/30 transition-all">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity"><i class="fas fa-arrow-up text-6xl text-red-500"></i></div>
            <p class="text-gray-500 text-xs font-bold uppercase mb-2 tracking-wider">Total Penggunaan</p>
            <h2 class="text-4xl font-heading font-bold text-red-500">-Rp <?= number_format($total_keluar ?? 0, 0, ',', '.') ?></h2>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Chart Section -->
        <div class="bg-brand-dark p-8 rounded-2xl border border-gray-800">
            <h3 class="font-bold text-lg mb-6 uppercase flex items-center gap-2">
                <i class="fas fa-chart-pie text-brand-green"></i> Alokasi Dana
            </h3>
            <div class="h-64 relative">
                <canvas id="grafikAlokasi"></canvas>
            </div>
        </div>

        <!-- Tables Section -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Tabel Pengeluaran -->
            <div>
                <div class="flex justify-between items-end mb-4">
                    <h3 class="font-bold text-xl uppercase text-white flex items-center gap-2">
                        <span class="w-2 h-8 bg-red-500 rounded-sm"></span> Penggunaan Terakhir
                    </h3>
                </div>
                <div class="bg-brand-dark rounded-xl border border-gray-800 overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-900/50 text-gray-500 uppercase text-xs font-bold">
                            <tr>
                                <th class="p-4">Tanggal</th>
                                <th class="p-4">Keterangan</th>
                                <th class="p-4">Kategori</th>
                                <th class="p-4 text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800 text-gray-300">
                            <?php if(!empty($pengeluaran)): foreach($pengeluaran as $e): ?>
                            <tr>
                                <td class="p-4 font-mono text-xs text-gray-500"><?= date('d M Y', strtotime($e->transaction_date)) ?></td>
                                <td class="p-4 font-bold text-white"><?= $e->title ?></td>
                                <td class="p-4"><span class="bg-gray-800 text-gray-300 px-2 py-1 rounded text-[10px] uppercase"><?= $e->category ?></span></td>
                                <td class="p-4 text-right text-red-500 font-mono">-Rp <?= number_format($e->amount, 0, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="4" class="p-4 text-center text-gray-500">Belum ada data pengeluaran.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS Khusus Halaman Ini (Chart) -->
<script>
    // Data dummy jika variabel PHP kosong (untuk testing view)
    const rawData = <?= !empty($chart_data) ? json_encode($chart_data) : json_encode([
        ['category' => 'Logistik', 'total' => 5000000],
        ['category' => 'Operasional', 'total' => 2500000]
    ]) ?>;
    
    const labels = rawData.map(item => item.category.toUpperCase());
    const dataValues = rawData.map(item => item.total);

    const ctx = document.getElementById('grafikAlokasi').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: dataValues,
                backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#6366f1', '#9ca3af'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right', labels: { color: 'white', font: {family: 'Inter', size: 10} } }
            }
        }
    });
</script>