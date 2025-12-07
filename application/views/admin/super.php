<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-heading font-bold text-white uppercase">Super Admin Control</h1>
    <button onclick="document.getElementById('userModal').classList.remove('hidden')" class="bg-white text-black font-bold px-6 py-2 rounded shadow hover:bg-gray-200 transition">
        <i class="fas fa-user-plus mr-2"></i> Tambah Admin
    </button>
</div>

<!-- Stats Ringkas -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-admin-dark p-4 rounded border border-gray-700">
        <span class="text-gray-500 text-xs uppercase">Total Admin</span>
        <h3 class="text-2xl font-bold text-white"><?= $count_admin ?></h3>
    </div>
    <div class="bg-admin-dark p-4 rounded border border-gray-700">
        <span class="text-gray-500 text-xs uppercase">Total Event</span>
        <h3 class="text-2xl font-bold text-blue-400"><?= $count_event ?></h3>
    </div>
    <div class="bg-admin-dark p-4 rounded border border-gray-700">
        <span class="text-gray-500 text-xs uppercase">Laporan Masuk</span>
        <h3 class="text-2xl font-bold text-red-400"><?= $count_report ?></h3>
    </div>
    <div class="bg-admin-dark p-4 rounded border border-gray-700">
        <span class="text-gray-500 text-xs uppercase">Total Dana</span>
        <h3 class="text-lg font-bold text-brand-green">Rp <?= number_format($total_fund) ?></h3>
    </div>
</div>

<div class="bg-admin-dark rounded-xl border border-gray-700 overflow-hidden">
    <div class="p-4 bg-gray-800 border-b border-gray-700"><h3 class="font-bold text-white">Manajemen User Admin</h3></div>
    <table class="w-full text-left text-sm text-gray-400">
        <thead class="bg-gray-900 text-xs uppercase">
            <tr><th class="p-4">Username</th><th class="p-4">Email</th><th class="p-4">Role</th><th class="p-4 text-right">Aksi</th></tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
            <?php foreach($admins as $u): ?>
            <tr>
                <td class="p-4 font-bold text-white"><?= $u->username ?></td>
                <td class="p-4"><?= $u->email ?></td>
                <td class="p-4"><span class="bg-gray-800 px-2 py-1 rounded text-xs uppercase"><?= $u->role ?></span></td>
                <td class="p-4 text-right">
                    <?php if($u->id != $user['user_id']): ?>
                        <a href="<?= base_url('admin/delete_admin/'.$u->id) ?>" onclick="return confirm('Hapus akses user ini?')" class="text-red-500 hover:text-white px-3 py-1 border border-red-900 rounded bg-red-900/20">Hapus</a>
                    <?php else: ?>
                        <span class="text-gray-600 italic">Current</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Add Admin -->
<div id="userModal" class="fixed inset-0 bg-black/80 hidden flex items-center justify-center p-4 z-50">
    <div class="bg-admin-dark p-6 rounded-xl w-full max-w-md border border-gray-600">
        <h3 class="text-xl font-bold text-white mb-4">Tambah Admin Baru</h3>
        <?= form_open('admin/add_admin', ['class' => 'space-y-4']) ?>
            <input type="text" name="username" placeholder="Username" class="w-full bg-black border border-gray-600 p-3 rounded text-white" required>
            <input type="email" name="email" placeholder="Email" class="w-full bg-black border border-gray-600 p-3 rounded text-white" required>
            <input type="password" name="password" placeholder="Password" class="w-full bg-black border border-gray-600 p-3 rounded text-white" required>
            <select name="role" class="w-full bg-black border border-gray-600 p-3 rounded text-white">
                <option value="finance">Finance Admin</option>
                <option value="field_coordinator">Content / Field Coord</option>
                <option value="super_admin">Super Admin</option>
            </select>
            <div class="flex justify-end gap-3 mt-4">
                <button type="button" onclick="document.getElementById('userModal').classList.add('hidden')" class="px-4 py-2 text-gray-400">Batal</button>
                <button type="submit" class="bg-brand-green text-black px-4 py-2 rounded font-bold">Buat User</button>
            </div>
        <?= form_close() ?>
    </div>
</div>