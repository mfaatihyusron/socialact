<div class="pt-32 px-6 max-w-7xl mx-auto pb-20">
    <div class="text-center mb-16">
        <span class="text-brand-green font-bold tracking-[0.2em] uppercase text-xs">Data Terbuka</span>
        <h1 class="font-heading text-4xl md:text-6xl font-bold uppercase mt-2 mb-6 text-white">
            Transparansi <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-green to-teal-500">Keuangan</span>
        </h1>
        <p class="text-gray-400 max-w-2xl mx-auto leading-relaxed">
            Data ini diambil secara real-time dari database. <br>
            <span class="text-xs text-gray-500">*Data donasi yang ditampilkan adalah yang sudah berstatus VERIFIED.</span>
        </p>
    </div>

    <!-- Kartu Ringkasan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-brand-dark p-8 rounded-2xl border border-gray-800 relative overflow-hidden group hover:border-brand-green/30 transition-all">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity"><i class="fas fa-wallet text-6xl text-white"></i></div>
            <p class="text-gray-500 text-xs font-bold uppercase mb-2 tracking-wider">Total Saldo Rekening (Live)</p>
            <h2 class="text-4xl font-heading font-bold text-white">Rp <?= number_format($saldo ?? 0, 0, ',', '.') ?></h2>
            <p class="text-[10px] text-gray-500 mt-1">*Total saldo dari seluruh akun bank terdaftar</p>
        </div>
        <div class="bg-brand-dark p-8 rounded-2xl border border-gray-800 relative overflow-hidden group hover:border-brand-green/30 transition-all">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity"><i class="fas fa-arrow-down text-6xl text-brand-green"></i></div>
            <p class="text-gray-500 text-xs font-bold uppercase mb-2 tracking-wider">Total Donasi Masuk (Verified)</p>
            <h2 class="text-4xl font-heading font-bold text-brand-green">+Rp <?= number_format($total_masuk ?? 0, 0, ',', '.') ?></h2>
        </div>
        <div class="bg-brand-dark p-8 rounded-2xl border border-gray-800 relative overflow-hidden group hover:border-brand-green/30 transition-all">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity"><i class="fas fa-arrow-up text-6xl text-red-500"></i></div>
            <p class="text-gray-500 text-xs font-bold uppercase mb-2 tracking-wider">Total Penggunaan Dana</p>
            <h2 class="text-4xl font-heading font-bold text-red-500">-Rp <?= number_format($total_keluar ?? 0, 0, ',', '.') ?></h2>
        </div>
    </div>

    <!-- Grafik Alokasi -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <div class="bg-brand-dark p-8 rounded-2xl border border-gray-800">
            <h3 class="font-bold text-lg mb-6 uppercase flex items-center gap-2">
                <i class="fas fa-chart-pie text-brand-green"></i> Alokasi Dana
            </h3>
            <div class="h-64 relative">
                <canvas id="grafikAlokasi"></canvas>
            </div>
        </div>
        
        <!-- Tabel Pengeluaran (Expense) -->
        <div class="lg:col-span-2 bg-brand-dark rounded-2xl border border-gray-800 overflow-hidden">
            <div class="p-6 border-b border-gray-700 flex justify-between items-center">
                <h3 class="font-bold text-xl uppercase text-white"><span class="text-red-500">OUT</span> - Penggunaan Dana</h3>
            </div>
            <div class="overflow-x-auto">
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
                        <tr class="hover:bg-gray-800/50">
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

    <!-- Tabel Pemasukan (Donasi) -->
    <div class="bg-brand-dark rounded-2xl border border-gray-800 overflow-hidden mb-16">
        <div class="p-6 border-b border-gray-700 flex justify-between items-center">
            <h3 class="font-bold text-xl uppercase text-white"><span class="text-brand-green">IN</span> - Daftar Si Dermawan </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-900/50 text-gray-500 uppercase text-xs font-bold">
                    <tr>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4">Donatur</th>
                        <th class="p-4">Pesan</th>
                        <th class="p-4 text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-gray-300">
                    <?php if(!empty($donasi_masuk)): foreach($donasi_masuk as $d): ?>
                    <tr class="hover:bg-gray-800/50">
                        <td class="p-4 font-mono text-xs text-gray-500"><?= date('d M Y', strtotime($d->created_at)) ?></td>
                        <!-- Logic Anonim -->
                        <td class="p-4 font-bold text-white"><?= $d->is_anonymous == 1 ? 'Hamba Allah' : $d->donor_name ?></td>
                        <td class="p-4 text-xs italic text-gray-500">"<?= $d->message ?? '-' ?>"</td>
                        <td class="p-4 text-right text-brand-green font-mono">+Rp <?= number_format($d->amount, 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="4" class="p-4 text-center text-gray-500">Belum ada data donasi masuk.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- SECTION AJAKAN DONASI (HIGHLIGHTED) -->
    <div class="relative rounded-2xl overflow-hidden p-10 md:p-14 text-center border border-brand-green/30 bg-[#0f1510] shadow-[0_0_50px_rgba(16,185,129,0.1)]">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-brand-green to-transparent"></div>
        <div class="absolute -left-20 top-1/2 w-60 h-60 bg-brand-green/10 rounded-full blur-3xl"></div>
        <div class="absolute -right-20 top-1/2 w-60 h-60 bg-brand-green/10 rounded-full blur-3xl"></div>

        <div class="relative z-10 max-w-3xl mx-auto">
            <span class="inline-block py-1 px-3 rounded-full bg-brand-green/10 border border-brand-green/30 text-brand-green text-xs font-bold uppercase tracking-widest mb-4">
                Bergabunglah Bersama Kami
            </span>
            <h2 class="font-heading text-3xl md:text-5xl font-bold uppercase text-white mb-6 leading-tight">
                Jadilah Bagian dari <span class="text-brand-green">Perubahan Nyata</span>
            </h2>
            <p class="text-gray-400 mb-10 text-lg">
                Dana yang terkumpul akan digunakan sepenuhnya untuk operasional pembersihan sungai, peralatan relawan, dan logistik. Transparansi adalah janji kami.
            </p>
            
            <a href="<?= base_url('donasi') ?>" class="group inline-flex items-center gap-3 px-10 py-5 bg-brand-green text-black font-bold text-xl uppercase tracking-widest rounded hover:bg-white hover:scale-105 transition-all shadow-[0_0_30px_rgba(16,185,129,0.5)] hover:shadow-[0_0_50px_rgba(255,255,255,0.6)]">
                <span>Donasi Sekarang</span>
                <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition-transform"></i>
            </a>
            
            <p class="mt-6 text-xs text-gray-500 font-mono">
                <i class="fas fa-lock mr-1"></i> Pembayaran Aman & Terverifikasi
            </p>
        </div>
    </div>
</div>

<script>
    const rawData = <?= !empty($chart_data) ? json_encode($chart_data) : '[]' ?>;
    const labels = rawData.length ? rawData.map(item => item.category.toUpperCase()) : ['NO DATA'];
    const dataValues = rawData.length ? rawData.map(item => item.total) : [1];
    const bgColors = rawData.length ? ['#f59e0b', '#3b82f6', '#10b981', '#6366f1', '#9ca3af'] : ['#333'];

    const ctx = document.getElementById('grafikAlokasi').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: dataValues,
                backgroundColor: bgColors,
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
