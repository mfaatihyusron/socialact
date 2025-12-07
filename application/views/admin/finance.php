<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-heading font-bold text-white uppercase">Finance Dashboard</h1>
    <button onclick="openAddModal()" class="bg-brand-green text-black font-bold px-6 py-2 rounded shadow hover:bg-white transition">
        <i class="fas fa-plus mr-2"></i> Input Transaksi
    </button>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-admin-dark p-6 rounded-xl border border-gray-700">
        <p class="text-gray-400 text-xs uppercase font-bold mb-2">Total Semua Saldo</p>
        <h2 class="text-4xl font-heading font-bold text-white">Rp <?= number_format($saldo, 0, ',', '.') ?></h2>
    </div>
    <div class="bg-admin-dark p-6 rounded-xl border border-gray-700">
        <p class="text-gray-400 text-xs uppercase font-bold mb-2">Chart Pengeluaran</p>
        <div class="h-24"><canvas id="miniChart"></canvas></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Tabel Pemasukan -->
    <div class="bg-admin-dark rounded-xl border border-gray-700 overflow-hidden h-fit">
        <div class="p-4 bg-gray-800 border-b border-gray-700"><h3 class="font-bold text-white">Pemasukan (Donasi)</h3></div>
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
        <div class="p-4 bg-gray-800 border-b border-gray-700"><h3 class="font-bold text-white">Riwayat Pengeluaran</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-400">
                <thead class="bg-gray-900 text-xs uppercase">
                    <tr>
                        <th class="p-3">Tgl</th>
                        <th class="p-3">Rincian</th>
                        <th class="p-3 text-right">Jml</th>
                        <th class="p-3 text-center">Akun</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php foreach($pengeluaran as $e): ?>
                    <tr class="hover:bg-gray-800/50 transition">
                        <td class="p-3 text-xs whitespace-nowrap"><?= $e->transaction_date ?></td>
                        <td class="p-3 text-white min-w-[150px]">
                            <div class="font-bold"><?= $e->title ?></div>
                            <div class="text-xs text-gray-500 uppercase"><?= $e->category ?></div>
                        </td>
                        <td class="p-3 text-right text-red-400 whitespace-nowrap">-<?= number_format($e->amount) ?></td>
                        <td class="p-3 text-xs text-center text-gray-500">
                            <?= isset($e->account_name) ? $e->account_name : '-' ?>
                        </td>
                        <td class="p-3 text-center whitespace-nowrap">
                            <button onclick="editExpense(<?= $e->id ?>)" class="bg-yellow-600/20 text-yellow-500 hover:bg-yellow-600 hover:text-white p-1.5 rounded transition mr-1"><i class="fas fa-edit"></i></button>
                            <a href="<?= base_url('admin/delete_expense/'.$e->id) ?>" onclick="return confirm('Hapus transaksi ini? Saldo akan dikembalikan.')" class="bg-red-600/20 text-red-500 hover:bg-red-600 hover:text-white p-1.5 rounded transition"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL 1: INPUT TRANSAKSI -->
<div id="trxModal" class="fixed inset-0 bg-black/80 hidden flex items-center justify-center p-4 z-50 overflow-y-auto backdrop-blur-sm">
    <div class="bg-admin-dark p-6 rounded-xl w-full max-w-lg border border-gray-600 my-8 shadow-2xl relative">
        <button onclick="document.getElementById('trxModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
        <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2"><i class="fas fa-wallet text-brand-green"></i> Input Transaksi</h3>
        
        <?= form_open_multipart('admin/add_transaction', ['class' => 'space-y-4']) ?>
            
            <div class="grid grid-cols-2 gap-4">
                <label class="cursor-pointer bg-gray-800 p-3 rounded-lg text-center hover:bg-gray-700 border border-transparent has-[:checked]:border-brand-green has-[:checked]:bg-gray-800/80">
                    <input type="radio" name="type" value="in" required onclick="toggleFields('in')" class="hidden"> 
                    <span class="block text-brand-green font-bold"><i class="fas fa-arrow-down mr-2"></i>Pemasukan</span>
                </label>
                <label class="cursor-pointer bg-gray-800 p-3 rounded-lg text-center hover:bg-gray-700 border border-transparent has-[:checked]:border-red-500 has-[:checked]:bg-gray-800/80">
                    <input type="radio" name="type" value="out" required onclick="toggleFields('out')" class="hidden"> 
                    <span class="block text-red-500 font-bold"><i class="fas fa-arrow-up mr-2"></i>Pengeluaran</span>
                </label>
            </div>

            <!-- PILIH AKUN / BANK -->
            <div>
                <label class="block text-xs text-gray-500 mb-1">Sumber Dana / Akun</label>
                <select name="account_id" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-sm focus:border-brand-green focus:outline-none" required>
                    <?php foreach($accounts as $acc): ?>
                        <option value="<?= $acc->id ?>"><?= $acc->account_name ?> (Rp <?= number_format($acc->current_balance) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Judul / Nama Transaksi</label>
                <input type="text" name="title" placeholder="Contoh: Beli Pupuk / Donasi Hamba Allah" class="w-full bg-black border border-gray-600 p-3 rounded text-white focus:border-brand-green focus:outline-none" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Nominal (Rp)</label>
                    <input type="number" name="amount" placeholder="0" class="w-full bg-black border border-gray-600 p-3 rounded text-white focus:border-brand-green focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Tanggal</label>
                    <input type="date" name="date" value="<?= date('Y-m-d') ?>" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-gray-400 focus:border-brand-green focus:outline-none">
                </div>
            </div>
            
            <!-- Expense Fields -->
            <div id="expenseFields" class="hidden space-y-4 border-t border-gray-700 pt-4 bg-gray-900/50 p-4 rounded-lg">
                <div class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2">Detail Pengeluaran</div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Kategori</label>
                    <select name="category" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-sm focus:border-brand-green focus:outline-none">
                        <option value="operational">Operational</option>
                        <option value="equipment">Equipment</option>
                        <option value="logistics">Logistics</option>
                        <option value="admin">Administration</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Deskripsi Lengkap</label>
                    <textarea name="description" rows="2" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-sm focus:border-brand-green focus:outline-none"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1"><i class="fas fa-file-invoice mr-1"></i> Foto Struk</label>
                        <input type="file" name="receipt_image" class="w-full text-xs text-gray-400 bg-gray-800 rounded border border-gray-700">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1"><i class="fas fa-box mr-1"></i> Foto Barang</label>
                        <input type="file" name="item_image" class="w-full text-xs text-gray-400 bg-gray-800 rounded border border-gray-700">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('trxModal').classList.add('hidden')" class="px-4 py-2 text-gray-400 hover:text-white">Batal</button>
                <button type="submit" class="bg-brand-green text-black px-6 py-2 rounded font-bold hover:bg-green-400 shadow-lg shadow-green-900/50 transition">Simpan</button>
            </div>
        <?= form_close() ?>
    </div>
</div>

<!-- MODAL 2: EDIT PENGELUARAN -->
<div id="editModal" class="fixed inset-0 bg-black/80 hidden flex items-center justify-center p-4 z-50 overflow-y-auto backdrop-blur-sm">
    <div class="bg-admin-dark p-6 rounded-xl w-full max-w-lg border border-gray-600 my-8 shadow-2xl relative">
        <button onclick="document.getElementById('editModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
        <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2 text-yellow-500"><i class="fas fa-edit"></i> Edit Pengeluaran</h3>
        
        <?= form_open_multipart('admin/update_expense', ['class' => 'space-y-4']) ?>
            <input type="hidden" name="expense_id" id="edit_id">
            
            <!-- Tambah Select Akun di Edit -->
            <div>
                <label class="block text-xs text-gray-500 mb-1">Sumber Dana / Akun</label>
                <select name="account_id" id="edit_account_id" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-sm focus:border-yellow-500 focus:outline-none">
                    <?php foreach($accounts as $acc): ?>
                        <option value="<?= $acc->id ?>"><?= $acc->account_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Judul Transaksi</label>
                <input type="text" name="title" id="edit_title" class="w-full bg-black border border-gray-600 p-3 rounded text-white focus:border-yellow-500 focus:outline-none" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Nominal (Rp)</label>
                    <input type="number" name="amount" id="edit_amount" class="w-full bg-black border border-gray-600 p-3 rounded text-white focus:border-yellow-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Tanggal</label>
                    <input type="date" name="date" id="edit_date" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-gray-400 focus:border-yellow-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Kategori</label>
                <select name="category" id="edit_category" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-sm focus:border-yellow-500 focus:outline-none">
                    <option value="operational">Operational</option>
                    <option value="equipment">Equipment</option>
                    <option value="logistics">Logistics</option>
                    <option value="admin">Administration</option>
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1">Deskripsi</label>
                <textarea name="description" id="edit_description" rows="2" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-sm focus:border-yellow-500 focus:outline-none"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 bg-gray-800/50 p-3 rounded border border-gray-700">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Ganti Struk (Opsional)</label>
                    <input type="file" name="receipt_image" class="w-full text-xs text-gray-400 file:bg-gray-700 file:border-0 file:text-white file:py-1 file:px-2 file:mr-2 file:text-xs">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Ganti Foto Barang (Opsional)</label>
                    <input type="file" name="item_image" class="w-full text-xs text-gray-400 file:bg-gray-700 file:border-0 file:text-white file:py-1 file:px-2 file:mr-2 file:text-xs">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 text-gray-400 hover:text-white">Batal</button>
                <button type="submit" class="bg-yellow-600 text-white px-6 py-2 rounded font-bold hover:bg-yellow-500 transition shadow-lg shadow-yellow-900/30">Update Data</button>
            </div>
        <?= form_close() ?>
    </div>
</div>

<script>
    function toggleFields(type) {
        const expenseFields = document.getElementById('expenseFields');
        if (type === 'out') {
            expenseFields.classList.remove('hidden');
        } else {
            expenseFields.classList.add('hidden');
        }
    }
    function openAddModal() {
        document.getElementById('trxModal').classList.remove('hidden');
    }
    function editExpense(id) {
        fetch('<?= base_url("admin/get_expense_json/") ?>' + id)
            .then(response => response.json())
            .then(data => {
                if(data) {
                    document.getElementById('edit_id').value = data.id;
                    document.getElementById('edit_title').value = data.title;
                    document.getElementById('edit_amount').value = data.amount;
                    document.getElementById('edit_date').value = data.transaction_date;
                    document.getElementById('edit_category').value = data.category;
                    document.getElementById('edit_description').value = data.description;
                    document.getElementById('edit_account_id').value = data.account_id; // Set Akun yang dipilih
                    document.getElementById('editModal').classList.remove('hidden');
                } else {
                    alert('Data tidak ditemukan');
                }
            });
    }

    const ctx = document.getElementById('miniChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($chart_data, 'category')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($chart_data, 'total')) ?>,
                backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444', '#9ca3af'],
                borderColor: '#1f2937',
                borderWidth: 2
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false, 
            plugins: { legend: { display: false } },
            cutout: '70%'
        }
    });
</script>