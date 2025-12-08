<div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-heading font-bold text-white uppercase">Finance Dashboard</h1>
        <p class="text-sm text-gray-400">Kelola arus kas, rekening, dan laporan keuangan.</p>
    </div>
    <div class="flex gap-3">
        <!-- Tombol Tambah Rekening -->
        <button onclick="document.getElementById('accModal').classList.remove('hidden')" class="bg-gray-800 border border-gray-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-gray-700 transition text-sm flex items-center shadow-lg">
            <i class="fas fa-university mr-2 text-blue-400"></i> Tambah Rekening
        </button>
        <!-- Tombol Input Transaksi -->
        <button onclick="openAddModal()" class="bg-brand-green text-black font-bold px-6 py-2 rounded-lg shadow-lg shadow-green-900/50 hover:bg-white transition flex items-center">
            <i class="fas fa-plus mr-2"></i> Input Transaksi
        </button>
    </div>
</div>

<!-- SECTION: KARTU REKENING (Wallet) -->
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-green-900 to-gray-900 p-6 rounded-xl border border-green-800 flex flex-col justify-center relative overflow-hidden group shadow-2xl">
        <div class="absolute -right-4 -top-4 p-4 opacity-10 text-white transform rotate-12 group-hover:rotate-0 transition duration-700"><i class="fas fa-wallet text-8xl"></i></div>
        <p class="text-green-300 text-xs uppercase font-bold mb-1 tracking-wider">Total Aset Keuangan</p>
        <h2 class="text-3xl font-heading font-bold text-white mb-4">Rp <?= number_format($saldo, 0, ',', '.') ?></h2>
        <div class="mt-auto text-[10px] text-gray-400 font-mono flex items-center gap-1">
            <i class="fas fa-sync-alt"></i> Terupdate: <?= date('d M Y H:i') ?>
        </div>
    </div>

    <!-- Scrollable Accounts -->
    <div class="lg:col-span-3 flex gap-4 overflow-x-auto pb-4 scrollbar-hide snap-x">
        <?php foreach($accounts as $acc): ?>
        <div class="min-w-[280px] snap-center bg-gray-800/80 p-5 rounded-xl border border-gray-700 relative group hover:border-brand-green transition-all duration-300 flex flex-col justify-between shadow-lg">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-2">
                    <?php 
                        $icon = 'fa-money-bill-wave'; $color = 'text-green-400';
                        if($acc->account_type == 'bank') { $icon = 'fa-university'; $color = 'text-blue-400'; }
                        if($acc->account_type == 'ewallet') { $icon = 'fa-mobile-alt'; $color = 'text-yellow-400'; }
                    ?>
                    <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center <?= $color ?>"><i class="fas <?= $icon ?>"></i></div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider bg-black/30 px-2 py-1 rounded"><?= $acc->account_type ?></span>
                </div>
                <!-- Edit Account Btn -->
                <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition duration-300 bg-black/50 rounded-lg p-1 backdrop-blur-sm">
                    <button onclick="editAccount(<?= $acc->id ?>)" class="w-7 h-7 flex items-center justify-center rounded hover:bg-yellow-600/20 text-gray-400 hover:text-yellow-500 transition"><i class="fas fa-pencil-alt text-xs"></i></button>
                    <a href="<?= base_url('finance/delete_account/'.$acc->id) ?>" onclick="return confirm('Nonaktifkan rekening ini?')" class="w-7 h-7 flex items-center justify-center rounded hover:bg-red-600/20 text-gray-400 hover:text-red-500 transition"><i class="fas fa-trash text-xs"></i></a>
                </div>
            </div>
            <div>
                <h4 class="text-white font-bold text-lg truncate mb-1"><?= $acc->account_name ?></h4>
                <p class="text-gray-500 text-xs font-mono tracking-wide"><?= $acc->account_number ?> <br> <span class="text-[10px] uppercase text-gray-600"><?= $acc->account_holder_name ?></span></p>
            </div>
            <div class="border-t border-gray-700 pt-3 mt-3 flex justify-between items-end">
                <span class="text-[10px] text-gray-500 block uppercase">Saldo</span>
                <span class="text-white font-bold text-lg">Rp <?= number_format($acc->current_balance, 0, ',', '.') ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-admin-dark p-6 rounded-xl border border-gray-700">
            <p class="text-gray-400 text-xs uppercase font-bold mb-4">Proporsi Pengeluaran</p>
            <div class="h-48 relative"><canvas id="miniChart"></canvas></div>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-8">
        <!-- Tabel Pengeluaran -->
        <div class="bg-admin-dark rounded-xl border border-gray-700 overflow-hidden">
            <div class="p-4 bg-gray-800 border-b border-gray-700 flex justify-between items-center">
                <h3 class="font-bold text-white text-sm uppercase tracking-wide"><i class="fas fa-arrow-up text-red-500 mr-2"></i> Riwayat Pengeluaran</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-400">
                    <thead class="bg-black/30 text-[10px] uppercase text-gray-500 font-bold">
                        <tr>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Detail</th>
                            <th class="p-3 text-right">Nominal</th>
                            <th class="p-3 text-center">Akun</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        <?php foreach($pengeluaran as $e): ?>
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="p-3 text-xs whitespace-nowrap font-mono"><?= $e->transaction_date ?></td>
                            <td class="p-3 text-white min-w-[150px]">
                                <div class="font-bold text-sm"><?= $e->title ?></div>
                                <span class="text-[10px] uppercase px-1.5 py-0.5 rounded bg-gray-700 text-gray-300"><?= $e->category ?></span>
                            </td>
                            <td class="p-3 text-right text-red-400 font-bold whitespace-nowrap">-<?= number_format($e->amount) ?></td>
                            <td class="p-3 text-xs text-center text-gray-500"><?= isset($e->account_name) ? $e->account_name : '-' ?></td>
                            <td class="p-3 text-center whitespace-nowrap">
                                <button onclick="editExpense(<?= $e->id ?>)" class="bg-yellow-600/20 text-yellow-500 hover:bg-yellow-600 hover:text-white p-1.5 rounded transition mr-1"><i class="fas fa-edit text-xs"></i></button>
                                <a href="<?= base_url('finance/delete_expense/'.$e->id) ?>" onclick="return confirm('Hapus pengeluaran ini? Saldo akan dikembalikan ke akun terkait.')" class="bg-red-600/20 text-red-500 hover:bg-red-600 hover:text-white p-1.5 rounded transition"><i class="fas fa-trash text-xs"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- 1. MODAL ADD TRANSAKSI -->
<div id="trxModal" class="fixed inset-0 bg-black/90 hidden flex items-center justify-center p-4 z-50 overflow-y-auto backdrop-blur-sm">
    <div class="bg-admin-dark p-6 rounded-xl w-full max-w-lg border border-gray-600 my-8 shadow-2xl relative">
        <button onclick="document.getElementById('trxModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
        <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2"><i class="fas fa-wallet text-brand-green"></i> Input Transaksi</h3>
        
        <?= form_open_multipart('finance/add_transaction', ['class' => 'space-y-4']) ?>
            <div class="grid grid-cols-2 gap-4">
                <label class="cursor-pointer bg-gray-800 p-3 rounded-lg text-center hover:bg-gray-700 border border-transparent has-[:checked]:border-brand-green has-[:checked]:bg-gray-800/80 transition">
                    <input type="radio" name="type" value="in" required onclick="toggleFields('in')" class="hidden"> 
                    <span class="block text-brand-green font-bold"><i class="fas fa-arrow-down mr-2"></i>Pemasukan</span>
                </label>
                <label class="cursor-pointer bg-gray-800 p-3 rounded-lg text-center hover:bg-gray-700 border border-transparent has-[:checked]:border-red-500 has-[:checked]:bg-gray-800/80 transition">
                    <input type="radio" name="type" value="out" required onclick="toggleFields('out')" class="hidden"> 
                    <span class="block text-red-500 font-bold"><i class="fas fa-arrow-up mr-2"></i>Pengeluaran</span>
                </label>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Sumber Dana / Akun</label>
                <select name="account_id" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-sm focus:border-brand-green outline-none" required>
                    <?php foreach($accounts as $acc): ?>
                        <option value="<?= $acc->id ?>"><?= $acc->account_name ?> (Rp <?= number_format($acc->current_balance) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Judul Transaksi</label>
                <input type="text" name="title" class="w-full bg-black border border-gray-600 p-3 rounded text-white focus:border-brand-green outline-none" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Nominal (Rp)</label>
                    <input type="number" name="amount" class="w-full bg-black border border-gray-600 p-3 rounded text-white focus:border-brand-green outline-none" required>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Tanggal</label>
                    <input type="date" name="date" value="<?= date('Y-m-d') ?>" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-gray-400 focus:border-brand-green outline-none">
                </div>
            </div>
            <!-- Expense Only Fields -->
            <div id="expenseFields" class="hidden space-y-4 border-t border-gray-700 pt-4 bg-gray-900/50 p-4 rounded-lg">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Kategori</label>
                    <select name="category" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-sm">
                        <option value="operational">Operational</option>
                        <option value="equipment">Equipment</option>
                        <option value="logistics">Logistics</option>
                        <option value="admin">Administration</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Deskripsi Lengkap</label>
                    <textarea name="description" rows="2" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-sm"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-xs text-gray-500 mb-1">Foto Struk</label><input type="file" name="receipt_image" class="w-full text-xs text-gray-400 bg-gray-800 rounded"></div>
                    <div><label class="block text-xs text-gray-500 mb-1">Foto Barang</label><input type="file" name="item_image" class="w-full text-xs text-gray-400 bg-gray-800 rounded"></div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="submit" class="bg-brand-green text-black px-6 py-2 rounded font-bold hover:bg-green-400">Simpan</button>
            </div>
        <?= form_close() ?>
    </div>
</div>

<!-- 2. MODAL EDIT PENGELUARAN -->
<div id="editModal" class="fixed inset-0 bg-black/90 hidden flex items-center justify-center p-4 z-50 overflow-y-auto backdrop-blur-sm">
    <div class="bg-admin-dark p-6 rounded-xl w-full max-w-lg border border-gray-600 my-8 shadow-2xl relative">
        <button onclick="document.getElementById('editModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
        <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2 text-yellow-500"><i class="fas fa-edit"></i> Edit Pengeluaran</h3>
        
        <?= form_open_multipart('finance/update_expense', ['class' => 'space-y-4']) ?>
            <input type="hidden" name="expense_id" id="edit_id">
            
            <!-- Sumber Dana (Penting utk logic saldo) -->
            <div>
                <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Sumber Dana / Akun</label>
                <select name="account_id" id="edit_account_id" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-sm focus:border-yellow-500 outline-none">
                    <?php foreach($accounts as $acc): ?>
                        <option value="<?= $acc->id ?>"><?= $acc->account_name ?> (Rp <?= number_format($acc->current_balance) ?>)</option>
                    <?php endforeach; ?>
                </select>
                <p class="text-[10px] text-yellow-600 mt-1 italic">*Jika akun diubah, saldo akan direfund ke akun lama & dipotong dari akun baru.</p>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Judul Transaksi</label>
                <input type="text" name="title" id="edit_title" class="w-full bg-black border border-gray-600 p-3 rounded text-white focus:border-yellow-500 outline-none" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Nominal (Rp)</label>
                    <input type="number" name="amount" id="edit_amount" class="w-full bg-black border border-gray-600 p-3 rounded text-white focus:border-yellow-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Tanggal</label>
                    <input type="date" name="date" id="edit_date" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-gray-400 focus:border-yellow-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Kategori</label>
                <select name="category" id="edit_category" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-sm focus:border-yellow-500 outline-none">
                    <option value="operational">Operational</option>
                    <option value="equipment">Equipment</option>
                    <option value="logistics">Logistics</option>
                    <option value="admin">Administration</option>
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Deskripsi</label>
                <textarea name="description" id="edit_description" rows="2" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-sm focus:border-yellow-500 outline-none"></textarea>
            </div>

            <!-- Bagian Pratinjau Gambar BARU -->
            <div class="grid grid-cols-2 gap-4 bg-gray-800/50 p-3 rounded border border-gray-700">
                <!-- Pratinjau Struk -->
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Pratinjau Struk Lama</label>
                    <div id="receipt_preview_container" class="h-20 bg-gray-900 rounded flex items-center justify-center overflow-hidden border border-gray-700">
                        <img id="edit_receipt_preview" class="h-full w-full object-cover hidden">
                        <span id="receipt_placeholder" class="text-xs text-gray-600">Tidak ada struk</span>
                    </div>
                </div>
                <!-- Pratinjau Barang -->
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Pratinjau Foto Barang Lama</label>
                    <div id="item_preview_container" class="h-20 bg-gray-900 rounded flex items-center justify-center overflow-hidden border border-gray-700">
                        <img id="edit_item_preview" class="h-full w-full object-cover hidden">
                        <span id="item_placeholder" class="text-xs text-gray-600">Tidak ada foto</span>
                    </div>
                </div>
            </div>
            <!-- Akhir Bagian Pratinjau Gambar BARU -->

            <div class="grid grid-cols-2 gap-4 bg-gray-800/50 p-3 rounded border border-gray-700">
                <div><label class="block text-xs text-gray-500 mb-1">Ganti Struk</label><input type="file" name="receipt_image" class="w-full text-xs text-gray-400"></div>
                <div><label class="block text-xs text-gray-500 mb-1">Ganti Foto Barang</label><input type="file" name="item_image" class="w-full text-xs text-gray-400"></div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 text-gray-400 hover:text-white">Batal</button>
                <button type="submit" class="bg-yellow-600 text-white px-6 py-2 rounded font-bold hover:bg-yellow-500 transition shadow-lg">Update & Sesuaikan Saldo</button>
            </div>
        <?= form_close() ?>
    </div>
</div>

<!-- 3. MODAL TAMBAH AKUN -->
<div id="accModal" class="fixed inset-0 bg-black/90 hidden flex items-center justify-center p-4 z-50 backdrop-blur-sm">
    <div class="bg-admin-dark p-6 rounded-xl w-full max-w-md border border-gray-600 shadow-2xl relative">
        <button onclick="document.getElementById('accModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
        <h3 class="text-xl font-bold text-white mb-6">Tambah Rekening</h3>
        <?= form_open('finance/add_account', ['class' => 'space-y-4']) ?>
            <div><input type="text" name="account_name" placeholder="Nama Akun" class="w-full bg-black border border-gray-600 p-3 rounded text-white" required></div>
            <div class="grid grid-cols-2 gap-4">
                <select name="account_type" class="w-full bg-black border border-gray-600 p-3 rounded text-white"><option value="bank">Bank</option><option value="ewallet">E-Wallet</option><option value="cash">Cash</option></select>
                <input type="text" name="account_number" placeholder="No. Rekening" class="w-full bg-black border border-gray-600 p-3 rounded text-white">
            </div>
            <div><input type="text" name="account_holder_name" placeholder="Atas Nama" class="w-full bg-black border border-gray-600 p-3 rounded text-white"></div>
            <div><input type="number" name="initial_balance" placeholder="Saldo Awal" class="w-full bg-black border border-gray-600 p-3 rounded text-white"></div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-bold mt-4">Simpan</button>
        <?= form_close() ?>
    </div>
</div>

<!-- 4. MODAL EDIT AKUN -->
<div id="editAccModal" class="fixed inset-0 bg-black/90 hidden flex items-center justify-center p-4 z-50 backdrop-blur-sm">
    <div class="bg-admin-dark p-6 rounded-xl w-full max-w-md border border-gray-600 shadow-2xl relative">
        <button onclick="document.getElementById('editAccModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
        <h3 class="text-xl font-bold text-white mb-6 text-yellow-500"><i class="fas fa-edit"></i> Edit Rekening</h3>
        
        <?= form_open('finance/update_account_data', ['class' => 'space-y-4']) ?>
            <input type="hidden" name="account_id" id="edit_acc_id">
            
            <div>
                <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Nama Akun / Bank</label>
                <input type="text" name="account_name" id="edit_acc_name" class="w-full bg-black border border-gray-600 p-3 rounded text-white focus:border-yellow-500 outline-none" required>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Tipe</label>
                    <select name="account_type" id="edit_acc_type" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-sm focus:border-yellow-500 outline-none">
                        <option value="bank">Bank</option>
                        <option value="ewallet">E-Wallet</option>
                        <option value="cash">Cash</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">No. Rekening</label>
                    <input type="text" name="account_number" id="edit_acc_number" class="w-full bg-black border border-gray-600 p-3 rounded text-white focus:border-yellow-500 outline-none">
                </div>
            </div>
            
            <div>
                <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Atas Nama</label>
                <input type="text" name="account_holder_name" id="edit_acc_holder" class="w-full bg-black border border-gray-600 p-3 rounded text-white focus:border-yellow-500 outline-none">
            </div>

            <div class="bg-yellow-900/20 p-3 rounded border border-yellow-900/50">
                <p class="text-[10px] text-yellow-500"><i class="fas fa-info-circle"></i> Saldo tidak dapat diedit manual disini. Gunakan Input Transaksi.</p>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="document.getElementById('editAccModal').classList.add('hidden')" class="px-4 py-2 text-gray-400 hover:text-white">Batal</button>
                <button type="submit" class="bg-yellow-600 text-white px-6 py-2 rounded font-bold hover:bg-yellow-500 transition shadow-lg">Update Data</button>
            </div>
        <?= form_close() ?>
    </div>
</div>

<script>
    const baseUrl = '<?= base_url() ?>';
    const uploadPath = baseUrl + 'uploads/expenses/';

    function toggleFields(type) {
        document.getElementById('expenseFields').classList.toggle('hidden', type !== 'out');
    }
    function openAddModal() { document.getElementById('trxModal').classList.remove('hidden'); }
    
    // AJAX Edit Expense
    function editExpense(id) {
        fetch('<?= base_url("finance/get_expense_json/") ?>' + id)
            .then(res => res.json())
            .then(data => {
                if(data) {
                    document.getElementById('edit_id').value = data.id;
                    document.getElementById('edit_title').value = data.title;
                    document.getElementById('edit_amount').value = data.amount;
                    document.getElementById('edit_date').value = data.transaction_date;
                    document.getElementById('edit_category').value = data.category;
                    document.getElementById('edit_description').value = data.description;
                    document.getElementById('edit_account_id').value = data.account_id;
                    document.getElementById('editModal').classList.remove('hidden');

                    // LOGIC BARU: Tampilkan Pratinjau Gambar
                    
                    // Struk
                    const receiptImg = document.getElementById('edit_receipt_preview');
                    const receiptPlaceholder = document.getElementById('receipt_placeholder');
                    if (data.receipt_image_url) {
                        receiptImg.src = uploadPath + data.receipt_image_url;
                        receiptImg.classList.remove('hidden');
                        receiptPlaceholder.classList.add('hidden');
                    } else {
                        receiptImg.classList.add('hidden');
                        receiptPlaceholder.classList.remove('hidden');
                    }

                    // Barang
                    const itemImg = document.getElementById('edit_item_preview');
                    const itemPlaceholder = document.getElementById('item_placeholder');
                    if (data.item_image_url) {
                        itemImg.src = uploadPath + data.item_image_url;
                        itemImg.classList.remove('hidden');
                        itemPlaceholder.classList.add('hidden');
                    } else {
                        itemImg.classList.add('hidden');
                        itemPlaceholder.classList.remove('hidden');
                    }
                }
            });
    }

    // AJAX Edit Account
    function editAccount(id) {
        fetch('<?= base_url("finance/get_account_json/") ?>' + id)
            .then(res => res.json())
            .then(data => {
                if(data) {
                    document.getElementById('edit_acc_id').value = data.id;
                    document.getElementById('edit_acc_name').value = data.account_name;
                    document.getElementById('edit_acc_type').value = data.account_type;
                    document.getElementById('edit_acc_number').value = data.account_number;
                    document.getElementById('edit_acc_holder').value = data.account_holder_name;
                    document.getElementById('editAccModal').classList.remove('hidden');
                }
            });
    }

    // Chart
    new Chart(document.getElementById('miniChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($chart_data, 'category')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($chart_data, 'total')) ?>,
                backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444', '#9ca3af'],
                borderWidth: 0
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right', labels: { color: '#9ca3af', boxWidth: 10 } } }, cutout: '75%' }
    });
</script>

<style>
/* CSS Tambahan untuk Menyembunyikan Scrollbar Browser */

/* Menyembunyikan Scrollbar di Webkit (Chrome, Safari) */
body::-webkit-scrollbar {
    display: none;
}

/* Menyembunyikan Scrollbar di Firefox */
body {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}

/* Scrollbar Sembunyi untuk elemen spesifik (misalnya scrollable accounts) */
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

/* Memastikan elemen utama tidak meluber horizontal */
.h-screen-full {
     height: 100vh;
}
</style>