<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-heading font-bold text-white uppercase">Content & Laporan</h1>
    <button onclick="document.getElementById('eventModal').classList.remove('hidden')" class="bg-brand-green text-black font-bold px-6 py-2 rounded shadow hover:bg-white transition">
        <i class="fas fa-plus mr-2"></i> Tambah Event
    </button>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Volunteer Events -->
    <div>
        <h3 class="text-xl font-bold text-white mb-4 border-l-4 border-blue-500 pl-3">Daftar Event</h3>
        <div class="space-y-4">
            <?php foreach($events as $ev): ?>
            <div class="bg-admin-dark p-4 rounded-xl border border-gray-700 flex justify-between items-center">
                <div>
                    <h4 class="font-bold text-white"><?= $ev->event_name ?></h4>
                    <p class="text-xs text-gray-400"><i class="fas fa-calendar mr-1"></i> <?= $ev->event_date ?></p>
                    <span class="text-[10px] uppercase bg-gray-800 px-2 py-0.5 rounded text-blue-400"><?= $ev->status ?></span>
                </div>
                <a href="<?= base_url('admin/delete_event/'.$ev->id) ?>" onclick="return confirm('Hapus?')" class="text-red-500 hover:text-white"><i class="fas fa-trash"></i></a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Waste Reports -->
    <div>
        <h3 class="text-xl font-bold text-white mb-4 border-l-4 border-red-500 pl-3">Laporan Warga</h3>
        <div class="space-y-4">
            <?php foreach($reports as $rp): ?>
            <div class="bg-admin-dark p-4 rounded-xl border border-gray-700">
                <div class="flex justify-between items-start mb-2">
                    <h4 class="font-bold text-white text-sm"><?= $rp->location_address ?></h4>
                    <span class="text-[10px] uppercase px-2 py-0.5 rounded <?= $rp->status == 'pending' ? 'bg-red-900 text-red-300' : 'bg-green-900 text-green-300' ?>"><?= $rp->status ?></span>
                </div>
                <p class="text-xs text-gray-400 italic mb-3">"<?= $rp->description ?>"</p>
                <?php if($rp->status != 'resolved'): ?>
                    <div class="flex gap-2">
                        <a href="<?= base_url('admin/verify_report/'.$rp->id.'/resolved') ?>" class="flex-1 bg-green-700 text-white text-center py-1 rounded text-xs hover:bg-green-600">Selesai (Bersih)</a>
                        <a href="<?= base_url('admin/verify_report/'.$rp->id.'/rejected') ?>" class="bg-red-900 text-red-300 px-3 py-1 rounded text-xs hover:bg-red-800">Tolak</a>
                    </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Modal Add Event -->
<div id="eventModal" class="fixed inset-0 bg-black/80 hidden flex items-center justify-center p-4 z-50">
    <div class="bg-admin-dark p-6 rounded-xl w-full max-w-md border border-gray-600">
        <h3 class="text-xl font-bold text-white mb-4">Tambah Event Baru</h3>
        <?= form_open('admin/add_event', ['class' => 'space-y-4']) ?>
            <input type="text" name="event_name" placeholder="Nama Event" class="w-full bg-black border border-gray-600 p-3 rounded text-white" required>
            <input type="text" name="location" placeholder="Lokasi" class="w-full bg-black border border-gray-600 p-3 rounded text-white" required>
            <input type="datetime-local" name="event_date" class="w-full bg-black border border-gray-600 p-3 rounded text-white text-gray-400" required>
            <select name="status" class="w-full bg-black border border-gray-600 p-3 rounded text-white">
                <option value="upcoming">Upcoming</option>
                <option value="ongoing">Ongoing</option>
                <option value="completed">Completed</option>
            </select>
            <div class="flex justify-end gap-3 mt-4">
                <button type="button" onclick="document.getElementById('eventModal').classList.add('hidden')" class="px-4 py-2 text-gray-400">Batal</button>
                <button type="submit" class="bg-brand-green text-black px-4 py-2 rounded font-bold">Simpan</button>
            </div>
        <?= form_close() ?>
    </div>
</div>