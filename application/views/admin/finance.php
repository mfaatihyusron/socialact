<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-heading font-bold text-white uppercase">Finance Dashboard</h1>
    <button onclick="document.getElementById('trxModal').classList.remove('hidden')" class="bg-brand-green text-black font-bold px-6 py-2 rounded shadow hover:bg-white transition">
        <i class="fas fa-plus mr-2"></i> Input Transaksi
    </button>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-admin-dark p-6 rounded-xl border border-gray-700">
        <p class="text-gray-400 text-xs uppercase font-bold mb-2">Total Saldo (Live)</p>
        <h2 class="text-4xl font-heading font-bold text-white">Rp <?= number_format($saldo, 0, ',', '.') ?></h2>
    </div>
    <div class="bg-admin-dark p-6 rounded-xl border border-gray-700">
        <p class="text-gray-400 text-xs uppercase font-bold mb-2">Chart Pengeluaran</p>
        <div class="h-24"><canvas id="miniChart"></canvas></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Tabel Pemasukan -->
    <div class="bg-admin-dark rounded-xl border border-gray-700 overflow-hidden">
        <div class="p-4 bg-gray-800 border-b border-gray-700"><h3 class="font-bold text-white">Pemasukan Terakhir</h3></div>
        <table class="w-full text-left text-sm text-gray-400">
            <thead class="bg-gray-900 text-xs uppercase">
                <tr><th class="p-3">Tanggal</th><th class="p-3">Ket</th><th class="p-3 text-right">Jumlah</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                <?php foreach($donasi as $d): ?>
                <tr>
                    <td class="p-3 text-xs"><?= substr($d->created_at, 0, 10) ?></td>
                    <td class="p-3 text-white"><?= $d->donor_name ?></td>
                    <td class="p-3 text-right text-brand-green">+<?= number_format($d->amount) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Tabel Pengeluaran -->
    <div class="bg-admin-dark rounded-xl border border-gray-700 overflow-hidden">
        <div class="p-4 bg-gray-800 border-b border-gray-700"><h3 class="font-bold text-white">Pengeluaran Terakhir</h3></div>
        <table class="w-full text-left text-sm text-gray-400">
            <thead class="bg-gray-900 text-xs uppercase">
                <tr><th class="p-3">Tanggal</th><th class="p-3">Ket</th><th class="p-3 text-right">Jumlah</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                <?php foreach($pengeluaran as $e): ?>
                <tr>
                    <td class="p-3 text-xs"><?= $e->transaction_date ?></td>
                    <td class="p-3 text-white"><?= $e->title ?></td>
                    <td class="p-3 text-right text-red-400">-<?= number_format($e->amount) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Input Transaksi -->
<div id="trxModal" class="fixed inset-0 bg-black/80 hidden flex items-center justify-center p-4 z-50">
    <div class="bg-admin-dark p-6 rounded-xl w-full max-w-md border border-gray-600">
        <h3 class="text-xl font-bold text-white mb-4">Input Transaksi Baru</h3>
        <?= form_open('admin/add_transaction', ['class' => 'space-y-4']) ?>
            <div class="grid grid-cols-2 gap-4">
                <label class="cursor-pointer bg-gray-800 p-3 rounded text-center hover:bg-gray-700">
                    <input type="radio" name="type" value="in" required> <span class="block text-brand-green font-bold">Pemasukan</span>
                </label>
                <label class="cursor-pointer bg-gray-800 p-3 rounded text-center hover:bg-gray-700">
                    <input type="radio" name="type" value="out" required> <span class="block text-red-500 font-bold">Pengeluaran</span>
                </label>
            </div>
            <input type="text" name="title" placeholder="Keterangan / Nama Donatur" class="w-full bg-black border border-gray-600 p-3 rounded text-white" required>
            <input type="number" name="amount" placeholder="Nominal (Rp)" class="w-full bg-black border border-gray-600 p-3 rounded text-white" required>
            
            <!-- Field Khusus Pengeluaran (Opsional) -->
            <select name="category" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-sm">
                <option value="others">Kategori (Jika Pengeluaran)</option>
                <option value="operational">Operasional</option>
                <option value="logistics">Logistik</option>
            </select>
            <input type="date" name="date" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-gray-400">

            <div class="flex justify-end gap-3 mt-4">
                <button type="button" onclick="document.getElementById('trxModal').classList.add('hidden')" class="px-4 py-2 text-gray-400">Batal</button>
                <button type="submit" class="bg-brand-green text-black px-4 py-2 rounded font-bold">Simpan</button>
            </div>
        <?= form_close() ?>
    </div>
</div>

<script>
    // Simple Chart
    const ctx = document.getElementById('miniChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($chart_data, 'category')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($chart_data, 'total')) ?>,
                backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444', '#9ca3af'],
                borderWidth: 0
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
    });
</script>