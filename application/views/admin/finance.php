<!-- HEADER AREA -->
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <div>
        <h1 class="text-3xl font-heading font-bold text-white uppercase">Finance & Donasi</h1>
        <p class="text-sm text-gray-400">Kelola arus kas, pengeluaran, dan verifikasi donasi masuk.</p>
    </div>
</div>

<!-- TABS NAVIGATION -->
<div class="flex border-b border-gray-700 mb-8">
    <button onclick="switchTab('dashboard')" id="tab-dashboard" class="tab-btn active px-6 py-3 text-sm font-bold text-white border-b-2 border-brand-green hover:bg-gray-800 transition flex items-center gap-2">
        <i class="fas fa-chart-pie"></i> Dashboard Keuangan
    </button>
    <button onclick="switchTab('approval')" id="tab-approval" class="tab-btn px-6 py-3 text-sm font-bold text-gray-400 border-b-2 border-transparent hover:text-white hover:bg-gray-800 transition flex items-center gap-2 relative">
        <i class="fas fa-check-double"></i> Approval Donasi
        <?php if(count($pending_donations) > 0): ?>
            <span class="absolute top-2 right-2 flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
            </span>
        <?php endif; ?>
    </button>
</div>

<!-- ==============================================
     TAB 1: DASHBOARD KEUANGAN
     ============================================== -->
<div id="content-dashboard" class="tab-content">
    
    <!-- TOP ACTIONS -->
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-white font-bold text-lg">Ringkasan Aset</h3>
        <div class="flex gap-2">
            <button onclick="document.getElementById('accModal').classList.remove('hidden')" class="bg-gray-800 border border-gray-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-gray-700 transition text-xs shadow">
                <i class="fas fa-university mr-2 text-blue-400"></i> Tambah Rekening
            </button>
            <button onclick="openAddModal()" class="bg-brand-green text-black font-bold px-4 py-2 rounded-lg shadow hover:bg-white transition text-xs">
                <i class="fas fa-plus mr-2"></i> Transaksi
            </button>
        </div>
    </div>

    <!-- WALLET & ACCOUNTS -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Balance -->
        <div class="bg-gradient-to-br from-green-900 to-gray-900 p-6 rounded-xl border border-green-800 flex flex-col justify-center relative overflow-hidden group shadow-2xl">
            <div class="absolute -right-4 -top-4 p-4 opacity-10 text-white transform rotate-12 group-hover:rotate-0 transition duration-700"><i class="fas fa-wallet text-8xl"></i></div>
            <p class="text-green-300 text-xs uppercase font-bold mb-1 tracking-wider">Total Aset</p>
            <h2 class="text-3xl font-heading font-bold text-white mb-4">Rp <?= number_format($saldo, 0, ',', '.') ?></h2>
        </div>

        <!-- Scrollable Accounts List -->
        <div class="lg:col-span-3 flex gap-4 overflow-x-auto pb-4 scrollbar-hide snap-x">
            <?php foreach($accounts as $acc): ?>
            <div class="min-w-[260px] snap-center bg-gray-800/80 p-5 rounded-xl border border-gray-700 relative group hover:border-brand-green transition-all duration-300 flex flex-col justify-between shadow-lg">
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
                    <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition duration-300 bg-black/50 rounded-lg p-1">
                        <button onclick="editAccount(<?= $acc->id ?>)" class="w-6 h-6 flex items-center justify-center rounded hover:bg-yellow-600/20 text-gray-400 hover:text-yellow-500"><i class="fas fa-pencil-alt text-[10px]"></i></button>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-bold text-lg truncate mb-1"><?= $acc->account_name ?></h4>
                    <p class="text-gray-500 text-xs font-mono tracking-wide"><?= $acc->account_number ?></p>
                </div>
                <div class="border-t border-gray-700 pt-3 mt-3 flex justify-between items-end">
                    <span class="text-[10px] text-gray-500 block uppercase">Saldo</span>
                    <span class="text-white font-bold text-lg">Rp <?= number_format($acc->current_balance, 0, ',', '.') ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- MAIN CONTENT GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Chart -->
        <div class="lg:col-span-1 bg-admin-dark p-6 rounded-xl border border-gray-700 h-fit">
            <p class="text-gray-400 text-xs uppercase font-bold mb-4">Proporsi Pengeluaran</p>
            <div class="h-48 relative"><canvas id="miniChart"></canvas></div>
        </div>

        <!-- Tabel Pengeluaran (SCROLLABLE & SEARCH) -->
        <div class="lg:col-span-2 bg-admin-dark rounded-xl border border-gray-700 overflow-hidden flex flex-col max-h-[600px]">
            <!-- Header Tabel -->
            <div class="p-4 bg-gray-800 border-b border-gray-700 flex justify-between items-center shrink-0">
                <h3 class="font-bold text-white text-sm uppercase tracking-wide flex items-center gap-2">
                    <i class="fas fa-arrow-up text-red-500"></i> Riwayat Pengeluaran
                    <?php if($filter_active): ?>
                        <span class="text-[10px] bg-blue-900 text-blue-300 px-2 py-0.5 rounded-full ml-2">Filtered</span>
                    <?php endif; ?>
                </h3>
                <button onclick="document.getElementById('searchModal').classList.remove('hidden')" class="bg-gray-700 hover:bg-gray-600 text-white px-3 py-1.5 rounded text-xs transition flex items-center gap-2">
                    <i class="fas fa-search"></i> Filter Tanggal
                </button>
            </div>
            
            <!-- Isi Tabel Scrollable -->
            <div class="overflow-y-auto flex-1 scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-800">
                <table class="w-full text-left text-sm text-gray-400">
                    <thead class="bg-gray-900/90 text-[10px] uppercase text-gray-500 font-bold sticky top-0 z-10 backdrop-blur-sm">
                        <tr>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Detail</th>
                            <th class="p-3 text-right">Nominal</th>
                            <th class="p-3 text-center">Akun</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        <?php if(empty($pengeluaran)): ?>
                            <tr><td colspan="5" class="p-8 text-center text-gray-500 italic">Tidak ada data pengeluaran.</td></tr>
                        <?php endif; ?>
                        <?php foreach($pengeluaran as $e): ?>
                        <tr class="hover:bg-gray-800/50 transition group">
                            <td class="p-3 text-xs whitespace-nowrap font-mono"><?= $e->transaction_date ?></td>
                            <td class="p-3 text-white min-w-[150px]">
                                <div class="font-bold text-sm"><?= $e->title ?></div>
                                <span class="text-[10px] uppercase px-1.5 py-0.5 rounded bg-gray-700 text-gray-300"><?= $e->category ?></span>
                            </td>
                            <td class="p-3 text-right text-red-400 font-bold whitespace-nowrap">-<?= number_format($e->amount) ?></td>
                            <td class="p-3 text-xs text-center text-gray-500"><?= isset($e->account_name) ? $e->account_name : '-' ?></td>
                            <td class="p-3 text-center whitespace-nowrap">
                                <button onclick="editExpense(<?= $e->id ?>)" class="text-yellow-500 hover:text-white px-2 transition"><i class="fas fa-edit"></i></button>
                                <a href="<?= base_url('finance/delete_expense/'.$e->id) ?>" onclick="return confirm('Hapus?')" class="text-red-500 hover:text-white px-2 transition"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- Footer Total (Optional) -->
            <?php if($filter_active): ?>
                <div class="p-2 bg-gray-800 border-t border-gray-700 text-center text-xs text-gray-400 shrink-0">
                    Menampilkan data dari <?= $start_date ?> s/d <?= $end_date ?> (<a href="<?= base_url('finance') ?>" class="text-red-400 hover:underline">Reset</a>)
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ==============================================
     TAB 2: APPROVAL DONASI
     ============================================== -->
<div id="content-approval" class="tab-content hidden">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- KOLOM 1: DAFTAR PENDING (UTAMA) -->
        <div class="lg:col-span-2">
            <div class="bg-yellow-900/10 border border-yellow-900/30 rounded-xl p-6 mb-6">
                <h3 class="text-xl font-bold text-white mb-2"><i class="fas fa-clock text-yellow-500 mr-2"></i> Menunggu Verifikasi</h3>
                <p class="text-sm text-gray-400 mb-6">Periksa bukti transfer sebelum melakukan ACC. Saldo rekening akan bertambah otomatis setelah ACC.</p>
                
                <?php if(empty($pending_donations)): ?>
                    <div class="text-center py-12 border-2 border-dashed border-gray-700 rounded-lg text-gray-500">
                        <i class="fas fa-check-circle text-4xl mb-3 opacity-30"></i>
                        <p>Tidak ada donasi pending saat ini.</p>
                    </div>
                <?php else: ?>
                    <div class="grid gap-4">
                        <?php foreach($pending_donations as $pd): ?>
                        <div class="bg-admin-dark p-4 rounded-xl border border-gray-700 flex flex-col md:flex-row gap-4 items-start md:items-center hover:border-yellow-500/50 transition">
                            <!-- Icon/Img -->
                            <div class="w-12 h-12 rounded-full bg-gray-800 flex items-center justify-center shrink-0 overflow-hidden cursor-pointer border border-gray-600 group" onclick="openVerifyModal(<?= htmlspecialchars(json_encode($pd), ENT_QUOTES, 'UTF-8') ?>)">
                                <?php if($pd->transfer_proof_url): ?>
                                    <img src="<?= base_url('uploads/donations/'.$pd->transfer_proof_url) ?>" class="w-full h-full object-cover opacity-80 group-hover:opacity-100">
                                <?php else: ?>
                                    <i class="fas fa-receipt text-gray-500"></i>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Detail -->
                            <div class="flex-1">
                                <div class="flex justify-between mb-1">
                                    <h4 class="font-bold text-white text-lg"><?= $pd->donor_name ?> <span class="text-xs font-normal text-gray-500 ml-2">(<?= $pd->donor_email ?>)</span></h4>
                                    <span class="text-xs text-gray-400"><?= date('d M Y H:i', strtotime($pd->created_at)) ?></span>
                                </div>
                                <div class="flex items-center gap-3 text-sm">
                                    <span class="text-brand-green font-bold bg-green-900/20 px-2 py-0.5 rounded">Rp <?= number_format($pd->amount) ?></span>
                                    <span class="text-gray-500"><i class="fas fa-arrow-right text-xs mx-1"></i> Ke: <?= $pd->target_account ?? 'Belum Dipilih' ?></span>
                                </div>
                                <?php if($pd->message): ?>
                                    <p class="text-xs text-gray-400 mt-2 italic">"<?= $pd->message ?>"</p>
                                <?php endif; ?>
                            </div>

                            <!-- Action Button -->
                            <button onclick="openVerifyModal(<?= htmlspecialchars(json_encode($pd), ENT_QUOTES, 'UTF-8') ?>)" class="bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-yellow-500 shadow-lg shadow-yellow-900/20 whitespace-nowrap">
                                <i class="fas fa-search mr-1"></i> Review
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- KOLOM 2: RIWAYAT TERAKHIR -->
        <div class="lg:col-span-1">
            <div class="bg-admin-dark rounded-xl border border-gray-700 overflow-hidden">
                <div class="p-4 bg-gray-800 border-b border-gray-700">
                    <h3 class="font-bold text-white text-sm">Riwayat Verifikasi</h3>
                </div>
                <div class="divide-y divide-gray-700 max-h-[500px] overflow-y-auto">
                    <?php foreach($history_donations as $hd): ?>
                    <div class="p-4 hover:bg-gray-800/50">
                        <div class="flex justify-between items-start mb-1">
                            <span class="font-bold text-white text-xs"><?= $hd->donor_name ?></span>
                            <?php if($hd->status == 'verified'): ?>
                                <span class="text-[10px] bg-green-900 text-green-400 px-1.5 rounded">ACC</span>
                            <?php else: ?>
                                <span class="text-[10px] bg-red-900 text-red-400 px-1.5 rounded">REJECT</span>
                            <?php endif; ?>
                        </div>
                        <div class="text-xs text-gray-500 mb-1">Rp <?= number_format($hd->amount) ?></div>
                        <div class="text-[10px] text-gray-600"><?= date('d/m/y H:i', strtotime($hd->verified_at)) ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==============================================
     MODALS
     ============================================== -->

<!-- 1. MODAL SEARCH PENGELUARAN -->
<div id="searchModal" class="fixed inset-0 bg-black/90 hidden flex items-center justify-center p-4 z-50 backdrop-blur-sm">
    <div class="bg-admin-dark p-6 rounded-xl w-full max-w-sm border border-gray-600 shadow-2xl relative">
        <button onclick="document.getElementById('searchModal').classList.add('hidden')" class="absolute top-3 right-3 text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
        <h3 class="text-lg font-bold text-white mb-4"><i class="fas fa-search text-brand-green mr-2"></i> Filter Pengeluaran</h3>
        <form action="<?= base_url('finance') ?>" method="get" class="space-y-4">
            <div>
                <label class="block text-xs text-gray-500 mb-1 uppercase font-bold">Dari Tanggal</label>
                <input type="date" name="start_date" class="w-full bg-black border border-gray-600 p-2 rounded text-white focus:border-brand-green outline-none" required>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1 uppercase font-bold">Sampai Tanggal</label>
                <input type="date" name="end_date" class="w-full bg-black border border-gray-600 p-2 rounded text-white focus:border-brand-green outline-none" required>
            </div>
            <button type="submit" class="w-full bg-brand-green text-black font-bold py-2 rounded hover:bg-white transition">Terapkan Filter</button>
        </form>
    </div>
</div>

<!-- 2. MODAL VERIFIKASI DONASI (ACC) -->
<div id="verifyModal" class="fixed inset-0 bg-black/90 hidden flex items-center justify-center p-4 z-[60] backdrop-blur-sm">
    <div class="bg-admin-dark p-0 rounded-xl w-full max-w-2xl border border-gray-600 shadow-2xl relative flex flex-col md:flex-row overflow-hidden max-h-[90vh]">
        <!-- Kiri: Bukti Transfer (Image) -->
        <div class="w-full md:w-1/2 bg-black flex items-center justify-center p-4 relative border-r border-gray-700">
            <img id="vf_proof_img" src="" class="max-w-full max-h-[300px] md:max-h-[500px] object-contain">
            <div id="vf_no_img" class="text-gray-500 text-center hidden">
                <i class="fas fa-image-slash text-4xl mb-2"></i><br>Tidak ada bukti upload
            </div>
        </div>

        <!-- Kanan: Form Action -->
        <div class="w-full md:w-1/2 p-6 flex flex-col overflow-y-auto">
            <button onclick="document.getElementById('verifyModal').classList.add('hidden')" class="absolute top-2 right-4 text-gray-400 hover:text-white md:hidden"><i class="fas fa-times text-xl"></i></button>
            
            <h3 class="text-xl font-bold text-white mb-1">Verifikasi Donasi</h3>
            <p class="text-xs text-gray-400 mb-6">Pastikan dana sudah masuk ke mutasi bank sebelum ACC.</p>

            <div class="space-y-4 mb-6 flex-1">
                <div class="bg-gray-800 p-3 rounded">
                    <span class="text-xs text-gray-500 block uppercase">Donatur</span>
                    <span class="text-white font-bold" id="vf_name"></span>
                    <div class="text-xs text-gray-400" id="vf_email"></div>
                </div>
                <div class="bg-gray-800 p-3 rounded">
                    <span class="text-xs text-gray-500 block uppercase">Nominal Donasi</span>
                    <span class="text-brand-green font-bold text-xl" id="vf_amount"></span>
                </div>
                <?php if(!empty($pd->message)): ?>
                <div class="bg-gray-800 p-3 rounded">
                    <span class="text-xs text-gray-500 block uppercase">Pesan</span>
                    <p class="text-white text-sm italic" id="vf_msg"></p>
                </div>
                <?php endif; ?>
            </div>

            <?= form_open('finance/verify_donation') ?>
                <input type="hidden" name="donation_id" id="vf_id">
                
                <div class="mb-6">
                    <label class="block text-xs text-gray-500 mb-2 uppercase font-bold">Masuk ke Rekening Mana?</label>
                    <select name="account_id" id="vf_account" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-sm focus:border-brand-green outline-none" required>
                        <option value="" disabled selected>-- Pilih Rekening Tujuan --</option>
                        <?php foreach($accounts as $acc): ?>
                            <option value="<?= $acc->id ?>"><?= $acc->account_name ?> (Rp <?= number_format($acc->current_balance) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-[10px] text-gray-500 mt-1">Saldo rekening ini akan bertambah otomatis.</p>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-auto">
                    <button type="submit" name="action" value="reject" onclick="return confirm('Yakin tolak donasi ini?')" class="bg-transparent border border-red-800 text-red-500 py-3 rounded font-bold hover:bg-red-900/30 transition">
                        <i class="fas fa-times mr-1"></i> Tolak
                    </button>
                    <button type="submit" name="action" value="approve" class="bg-brand-green text-black py-3 rounded font-bold hover:bg-white transition shadow-lg shadow-green-900/50">
                        <i class="fas fa-check mr-1"></i> ACC Donasi
                    </button>
                </div>
            <?= form_close() ?>
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
    // TAB SWITCHER
    function switchTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.getElementById('content-' + tabName).classList.remove('hidden');
        
        document.querySelectorAll('.tab-btn').forEach(btn => { 
            btn.classList.remove('active', 'text-white', 'border-brand-green'); 
            btn.classList.add('text-gray-400', 'border-transparent'); 
        });
        document.getElementById('tab-' + tabName).classList.add('active', 'text-white', 'border-brand-green');
        document.getElementById('tab-' + tabName).classList.remove('text-gray-400', 'border-transparent');
    }

    // MODAL VERIFIKASI DONASI
    function openVerifyModal(data) {
        document.getElementById('vf_id').value = data.id;
        document.getElementById('vf_name').innerText = data.donor_name;
        document.getElementById('vf_email').innerText = data.donor_email;
        document.getElementById('vf_amount').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.amount);
        
        if(data.message) document.getElementById('vf_msg').innerText = '"' + data.message + '"';
        
        // Set Account jika user sudah memilih saat donasi (Optional logic)
        if(data.account_id) {
            document.getElementById('vf_account').value = data.account_id;
        } else {
            document.getElementById('vf_account').selectedIndex = 0;
        }

        // Gambar Bukti
        const img = document.getElementById('vf_proof_img');
        const noImg = document.getElementById('vf_no_img');
        if(data.transfer_proof_url) {
            img.src = '<?= base_url('uploads/donations/') ?>' + data.transfer_proof_url;
            img.classList.remove('hidden');
            noImg.classList.add('hidden');
        } else {
            img.classList.add('hidden');
            noImg.classList.remove('hidden');
        }

        document.getElementById('verifyModal').classList.remove('hidden');
    }

    // Chart JS (Sama seperti sebelumnya)
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
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, cutout: '70%' }
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
.scrollbar-thin::-webkit-scrollbar { width: 6px; }
.scrollbar-thin::-webkit-scrollbar-track { background: #1f2937; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 3px; }
.scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #6b7280; }
</style>