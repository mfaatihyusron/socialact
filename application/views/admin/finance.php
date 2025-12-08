<div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-heading font-bold text-white uppercase">Finance Dashboard</h1>
        <p class="text-sm text-gray-400">Kelola arus kas, rekening, dan laporan keuangan.</p>
    </div>
    <div class="flex gap-3">
        <!-- Tombol Tambah Rekening -->
        <button onclick="document.getElementById('accModal').classList.remove('hidden')" class="bg-gray-800 border border-gray-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-gray-700 transition text-sm flex items-center shadow-lg">
            <i class="fas fa-university mr-2 text-blue-400"></i> Kelola Rekening
        </button>
        <!-- Tombol Input Transaksi -->
        <button onclick="openAddModal()" class="bg-brand-green text-black font-bold px-6 py-2 rounded-lg shadow-lg shadow-green-900/50 hover:bg-white transition flex items-center">
            <i class="fas fa-plus mr-2"></i> Input Transaksi
        </button>
    </div>
</div>

<!-- SECTION: KARTU REKENING & OVERVIEW -->
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Kartu Total Aset (Utama) -->
    <div class="bg-gradient-to-br from-green-900 to-gray-900 p-6 rounded-xl border border-green-800 flex flex-col justify-center relative overflow-hidden group shadow-2xl">
        <div class="absolute -right-4 -top-4 p-4 opacity-10 text-white transform rotate-12 group-hover:rotate-0 transition duration-700"><i class="fas fa-wallet text-8xl"></i></div>
        <p class="text-green-300 text-xs uppercase font-bold mb-1 tracking-wider">Total Aset Keuangan</p>
        <h2 class="text-3xl font-heading font-bold text-white mb-4">Rp <?= number_format($saldo, 0, ',', '.') ?></h2>
        <div class="mt-auto text-[10px] text-gray-400 font-mono flex items-center gap-1">
            <i class="fas fa-sync-alt"></i> Terupdate: <?= date('d M Y H:i') ?>
        </div>
    </div>

    <!-- List Rekening (Scroll Horizontal) -->
    <div class="lg:col-span-3 flex gap-4 overflow-x-auto pb-4 scrollbar-hide snap-x">
        <?php foreach($accounts as $acc): ?>
        <div class="min-w-[280px] snap-center bg-gray-800/80 p-5 rounded-xl border border-gray-700 relative group hover:border-brand-green transition-all duration-300 flex flex-col justify-between shadow-lg">
            
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-2">
                    <?php if($acc->account_type == 'bank'): ?>
                        <div class="w-8 h-8 rounded-full bg-blue-900/30 flex items-center justify-center text-blue-400"><i class="fas fa-university"></i></div>
                    <?php elseif($acc->account_type == 'ewallet'): ?>
                        <div class="w-8 h-8 rounded-full bg-yellow-900/30 flex items-center justify-center text-yellow-400"><i class="fas fa-mobile-alt"></i></div>
                    <?php else: ?>
                        <div class="w-8 h-8 rounded-full bg-green-900/30 flex items-center justify-center text-green-400"><i class="fas fa-money-bill-wave"></i></div>
                    <?php endif; ?>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider bg-black/30 px-2 py-1 rounded"><?= $acc->account_type ?></span>
                </div>
                <!-- Action Menu -->
                <div class="flex gap-1 opacity-100 lg:opacity-0 group-hover:opacity-100 transition duration-300 bg-black/50 rounded-lg p-1 backdrop-blur-sm">
                    <button onclick="editAccount(<?= $acc->id ?>)" class="w-7 h-7 flex items-center justify-center rounded hover:bg-yellow-600/20 text-gray-400 hover:text-yellow-500 transition"><i class="fas fa-pencil-alt text-xs"></i></button>
                    <a href="<?= base_url('finance/delete_account/'.$acc->id) ?>" onclick="return confirm('Nonaktifkan rekening ini? Saldo tidak akan hilang dari laporan.')" class="w-7 h-7 flex items-center justify-center rounded hover:bg-red-600/20 text-gray-400 hover:text-red-500 transition"><i class="fas fa-trash text-xs"></i></a>
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
        
        <!-- Add Card Button -->
        <button onclick="document.getElementById('accModal').classList.remove('hidden')" class="min-w-[100px] bg-gray-900/50 rounded-xl border-2 border-dashed border-gray-700 flex flex-col items-center justify-center text-gray-500 hover:text-brand-green hover:border-brand-green hover:bg-gray-800 transition cursor-pointer group snap-center">
            <div class="w-10 h-10 rounded-full bg-gray-800 group-hover:bg-brand-green group-hover:text-black flex items-center justify-center mb-2 transition">
                <i class="fas fa-plus text-lg"></i>
            </div>
            <span class="text-xs font-bold uppercase">Tambah</span>
        </button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Charts & Stats -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-admin-dark p-6 rounded-xl border border-gray-700">
            <p class="text-gray-400 text-xs uppercase font-bold mb-4">Proporsi Pengeluaran</p>
            <div class="h-48 relative">
                <canvas id="miniChart"></canvas>
            </div>
        </div>
        
        <!-- Summary Text -->
        <div class="bg-blue-900/10 p-5 rounded-xl border border-blue-900/30">
            <h4 class="text-blue-400 font-bold mb-2 text-sm"><i class="fas fa-info-circle mr-1"></i> Info Keuangan</h4>
            <p class="text-xs text-gray-400 leading-relaxed">
                Total pemasukan bulan ini didominasi oleh donasi via Transfer Bank. Pastikan untuk selalu mengupload bukti struk pada setiap pengeluaran operasional.
            </p>
        </div>
    </div>

    <!-- Tabel Riwayat Transaksi -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Tabel Pemasukan -->
        <div class="bg-admin-dark rounded-xl border border-gray-700 overflow-hidden">
            <div class="p-4 bg-gray-800 border-b border-gray-700 flex justify-between items-center">
                <h3 class="font-bold text-white text-sm uppercase tracking-wide"><i class="fas fa-arrow-down text-brand-green mr-2"></i> Pemasukan Terakhir</h3>
            </div>
            <table class="w-full text-left text-sm text-gray-400">
                <tbody class="divide-y divide-gray-700">
                    <?php foreach($donasi as $d): ?>
                    <tr class="hover:bg-gray-800/50 transition">
                        <td class="p-3 text-xs font-mono text-gray-500 w-24"><?= substr($d->created_at, 0, 10) ?></td>
                        <td class="p-3 text-white">
                            <div class="font-bold text-sm"><?= $d->donor_name ?></div>
                            <div class="text-[10px] text-gray-500"><?= $d->message ?: 'Tanpa pesan' ?></div>
                        </td>
                        <td class="p-3 text-right text-brand-green font-bold">+Rp <?= number_format($d->amount) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Tabel Pengeluaran -->
        <div class="bg-admin-dark rounded-xl border border-gray-700 overflow-hidden">
            <div class="p-4 bg-gray-800 border-b border-gray-700 flex justify-between items-center">
                <h3 class="font-bold text-white text-sm uppercase tracking-wide"><i class="fas fa-arrow-up text-red-500 mr-2"></i> Pengeluaran Terakhir</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-400">
                    <thead class="bg-black/30 text-[10px] uppercase text-gray-500 font-bold">
                        <tr>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Keterangan</th>
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
                            <td class="p-3 text-xs text-center text-gray-500">
                                <?= isset($e->account_name) ? $e->account_name : '-' ?>
                            </td>
                            <td class="p-3 text-center whitespace-nowrap">
                                <button onclick="editExpense(<?= $e->id ?>)" class="bg-yellow-600/20 text-yellow-500 hover:bg-yellow-600 hover:text-white p-1.5 rounded transition mr-1"><i class="fas fa-edit text-xs"></i></button>
                                <a href="<?= base_url('finance/delete_expense/'.$e->id) ?>" onclick="return confirm('Hapus transaksi ini? Saldo akan dikembalikan.')" class="bg-red-600/20 text-red-500 hover:bg-red-600 hover:text-white p-1.5 rounded transition"><i class="fas fa-trash text-xs"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ============================================= -->
<!-- MODAL SECTION -->
<!-- ============================================= -->

<!-- 1. MODAL INPUT TRANSAKSI -->
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

            <!-- PILIH AKUN / BANK -->
            <div>
                <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Sumber Dana / Akun</label>
                <select name="account_id" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-sm focus:border-brand-green focus:outline-none" required>
                    <?php foreach($accounts as $acc): ?>
                        <option value="<?= $acc->id ?>"><?= $acc->account_name ?> (Rp <?= number_format($acc->current_balance) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Judul Transaksi</label>
                <input type="text" name="title" placeholder="Contoh: Beli Pupuk / Donasi Hamba Allah" class="w-full bg-black border border-gray-600 p-3 rounded text-white focus:border-brand-green focus:outline-none" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Nominal (Rp)</label>
                    <input type="number" name="amount" placeholder="0" class="w-full bg-black border border-gray-600 p-3 rounded text-white focus:border-brand-green focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Tanggal</label>
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

<!-- 2. MODAL TAMBAH REKENING (NEW) -->
<div id="accModal" class="fixed inset-0 bg-black/90 hidden flex items-center justify-center p-4 z-50 backdrop-blur-sm">
    <div class="bg-admin-dark p-6 rounded-xl w-full max-w-md border border-gray-600 shadow-2xl relative animate-fade-in-up">
        <button onclick="document.getElementById('accModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
        <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2"><i class="fas fa-university text-blue-400"></i> Tambah Rekening</h3>
        
        <?= form_open('finance/add_account', ['class' => 'space-y-4']) ?>
            <div>
                <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Nama Akun / Bank</label>
                <input type="text" name="account_name" placeholder="Contoh: BCA, GoPay, Kas Tunai" class="w-full bg-black border border-gray-600 p-3 rounded text-white focus:border-brand-green outline-none transition" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Tipe</label>
                    <select name="account_type" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-sm focus:border-brand-green outline-none">
                        <option value="bank">Bank Transfer</option>
                        <option value="ewallet">E-Wallet</option>
                        <option value="cash">Cash / Tunai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">No. Rekening</label>
                    <input type="text" name="account_number" placeholder="123xxx" class="w-full bg-black border border-gray-600 p-3 rounded text-white focus:border-brand-green outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Atas Nama</label>
                <input type="text" name="account_holder_name" placeholder="Nama Pemilik" class="w-full bg-black border border-gray-600 p-3 rounded text-white focus:border-brand-green outline-none">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Saldo Awal (Rp)</label>
                <input type="number" name="initial_balance" value="0" class="w-full bg-black border border-gray-600 p-3 rounded text-white focus:border-brand-green outline-none">
                <p class="text-[10px] text-gray-500 mt-1">*Hanya diisi saat pertama kali dibuat.</p>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-700">
                <button type="button" onclick="document.getElementById('accModal').classList.add('hidden')" class="px-4 py-2 text-gray-400 hover:text-white">Batal</button>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-500 transition shadow-lg">Simpan Akun</button>
            </div>
        <?= form_close() ?>
    </div>
</div>

<!-- 3. MODAL EDIT REKENING (NEW) -->
<div id="editAccModal" class="fixed inset-0 bg-black/90 hidden flex items-center justify-center p-4 z-50 backdrop-blur-sm">
    <div class="bg-admin-dark p-6 rounded-xl w-full max-w-md border border-gray-600 shadow-2xl relative">
        <button onclick="document.getElementById('editAccModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
        <h3 class="text-xl font-bold text-white mb-6 text-yellow-500 flex items-center gap-2"><i class="fas fa-edit"></i> Edit Rekening</h3>
        
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
                        <option value="bank">Bank Transfer</option>
                        <option value="ewallet">E-Wallet</option>
                        <option value="cash">Cash / Tunai</option>
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
            <div class="bg-yellow-900/10 p-3 rounded border border-yellow-900/30 flex gap-2">
                <i class="fas fa-exclamation-triangle text-yellow-500 mt-1"></i>
                <p class="text-[10px] text-yellow-500 leading-tight">Saldo tidak dapat diedit manual disini demi keamanan data transaksi. Gunakan menu Input Transaksi jika ada selisih.</p>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-700">
                <button type="button" onclick="document.getElementById('editAccModal').classList.add('hidden')" class="px-4 py-2 text-gray-400 hover:text-white">Batal</button>
                <button type="submit" class="bg-yellow-600 text-white px-6 py-2 rounded font-bold hover:bg-yellow-500 transition shadow-lg">Update Data</button>
            </div>
        <?= form_close() ?>
    </div>
</div>

<!-- 4. MODAL EDIT PENGELUARAN (EXISTING) -->
<div id="editModal" class="fixed inset-0 bg-black/90 hidden flex items-center justify-center p-4 z-50 overflow-y-auto backdrop-blur-sm">
    <div class="bg-admin-dark p-6 rounded-xl w-full max-w-lg border border-gray-600 my-8 shadow-2xl relative">
        <button onclick="document.getElementById('editModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
        <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2 text-yellow-500"><i class="fas fa-edit"></i> Edit Pengeluaran</h3>
        
        <?= form_open_multipart('finance/update_expense', ['class' => 'space-y-4']) ?>
            <input type="hidden" name="expense_id" id="edit_id">
            
            <div>
                <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Sumber Dana / Akun</label>
                <select name="account_id" id="edit_account_id" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-sm focus:border-yellow-500 focus:outline-none">
                    <?php foreach($accounts as $acc): ?>
                        <option value="<?= $acc->id ?>"><?= $acc->account_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Judul Transaksi</label>
                <input type="text" name="title" id="edit_title" class="w-full bg-black border border-gray-600 p-3 rounded text-white focus:border-yellow-500 focus:outline-none" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Nominal (Rp)</label>
                    <input type="number" name="amount" id="edit_amount" class="w-full bg-black border border-gray-600 p-3 rounded text-white focus:border-yellow-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Tanggal</label>
                    <input type="date" name="date" id="edit_date" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-gray-400 focus:border-yellow-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Kategori</label>
                <select name="category" id="edit_category" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-sm focus:border-yellow-500 focus:outline-none">
                    <option value="operational">Operational</option>
                    <option value="equipment">Equipment</option>
                    <option value="logistics">Logistics</option>
                    <option value="admin">Administration</option>
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-500 mb-1 font-bold uppercase">Deskripsi</label>
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
    
    // AJAX for Edit Expense
    function editExpense(id) {
        fetch('<?= base_url("finance/get_expense_json/") ?>' + id)
            .then(response => response.json())
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
                } else {
                    alert('Data tidak ditemukan');
                }
            });
    }

    // AJAX for Edit Account
    function editAccount(id) {
        fetch('<?= base_url("finance/get_account_json/") ?>' + id)
            .then(response => response.json())
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
            plugins: { legend: { position: 'right', labels: { color: '#9ca3af' } } },
            cutout: '70%'
        }
    });
</script>