<div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-heading font-bold text-white uppercase">Content & Laporan</h1>
        <p class="text-sm text-gray-400">Pilih menu di bawah untuk mengelola data.</p>
    </div>
    
    <!-- HEADER STATS -->
    <div class="flex gap-6 bg-admin-dark px-6 py-2 rounded-full border border-gray-700">
        <div class="text-center">
            <span class="text-[10px] text-gray-500 uppercase font-bold block">Laporan</span>
            <span class="text-xl font-heading font-bold text-white"><?= count($reports) ?></span>
        </div>
        <div class="w-px bg-gray-700"></div>
        <div class="text-center">
            <span class="text-[10px] text-gray-500 uppercase font-bold block">Event</span>
            <span class="text-xl font-heading font-bold text-blue-400"><?= count($events) ?></span>
        </div>
    </div>
</div>

<!-- MAIN TABS -->
<div class="flex border-b border-gray-700 mb-8">
    <button onclick="switchMainTab('laporan')" id="tab-btn-laporan" class="main-tab-btn active px-6 py-3 text-sm font-bold text-white border-b-2 border-brand-green hover:bg-gray-800 transition flex items-center gap-2">
        <i class="fas fa-exclamation-circle"></i> Manajemen Laporan
    </button>
    <button onclick="switchMainTab('event')" id="tab-btn-event" class="main-tab-btn px-6 py-3 text-sm font-bold text-gray-400 border-b-2 border-transparent hover:text-white hover:bg-gray-800 transition flex items-center gap-2">
        <i class="fas fa-calendar-alt"></i> Event Volunteer
    </button>
</div>

<!-- ==========================
     KONTEN TAB 1: LAPORAN (SAMA SEPERTI SEBELUMNYA)
     ========================== -->
<div id="content-laporan" class="tab-content">
    
    <!-- MILESTONE FILTER LAPORAN -->
    <div class="mb-3 flex items-center gap-2">
        <span class="text-[10px] uppercase font-bold text-gray-500 bg-gray-800 px-2 py-1 rounded">Filter Status:</span>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-8">
        <?php 
            $rep_pending = count(array_filter($reports, function($r){ return $r->status == 'pending'; }));
            $rep_process = count(array_filter($reports, function($r){ return $r->status == 'in_progress'; }));
            $rep_resolved = count(array_filter($reports, function($r){ return $r->status == 'resolved'; }));
            $rep_rejected = count(array_filter($reports, function($r){ return $r->status == 'rejected'; }));
            $rep_total = count($reports);
        ?>
        <button onclick="filterReports('all')" id="btn-all" class="filter-btn active group flex flex-col items-center justify-center p-4 rounded-xl border-2 border-gray-800 bg-admin-dark hover:border-brand-green/50 hover:bg-gray-800 transition-all duration-300 transform hover:-translate-y-1">
            <span class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1 group-hover:text-white transition">Semua</span>
            <h2 class="text-2xl font-heading font-bold text-white"><?= $rep_total ?></h2>
        </button>
        <button onclick="filterReports('pending')" id="btn-pending" class="filter-btn group flex flex-col items-center justify-center p-4 rounded-xl border-2 border-gray-800 bg-admin-dark hover:border-red-500/50 hover:bg-gray-800 transition-all duration-300 transform hover:-translate-y-1">
            <span class="text-[10px] text-gray-500 uppercase font-bold tracking-wider group-hover:text-red-400 transition">Pending</span>
            <h2 class="text-2xl font-heading font-bold text-red-500 mt-1"><?= $rep_pending ?></h2>
        </button>
        <button onclick="filterReports('in_progress')" id="btn-in_progress" class="filter-btn group flex flex-col items-center justify-center p-4 rounded-xl border-2 border-gray-800 bg-admin-dark hover:border-yellow-500/50 hover:bg-gray-800 transition-all duration-300 transform hover:-translate-y-1">
            <span class="text-[10px] text-gray-500 uppercase font-bold tracking-wider group-hover:text-yellow-400 transition">Proses</span>
            <h2 class="text-2xl font-heading font-bold text-yellow-500 mt-1"><?= $rep_process ?></h2>
        </button>
        <button onclick="filterReports('resolved')" id="btn-resolved" class="filter-btn group flex flex-col items-center justify-center p-4 rounded-xl border-2 border-gray-800 bg-admin-dark hover:border-green-500/50 hover:bg-gray-800 transition-all duration-300 transform hover:-translate-y-1">
            <span class="text-[10px] text-gray-500 uppercase font-bold tracking-wider group-hover:text-green-400 transition">Selesai</span>
            <h2 class="text-2xl font-heading font-bold text-green-500 mt-1"><?= $rep_resolved ?></h2>
        </button>
        <button onclick="filterReports('rejected')" id="btn-rejected" class="filter-btn group flex flex-col items-center justify-center p-4 rounded-xl border-2 border-gray-800 bg-admin-dark hover:border-gray-500/50 hover:bg-gray-800 transition-all duration-300 transform hover:-translate-y-1">
            <span class="text-[10px] text-gray-500 uppercase font-bold tracking-wider group-hover:text-gray-300 transition">Ditolak</span>
            <h2 class="text-2xl font-heading font-bold text-gray-400 mt-1"><?= $rep_rejected ?></h2>
        </button>
    </div>

    <!-- LIST LAPORAN -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="report-list">
        <?php foreach($reports as $rp): ?>
        <div class="bg-admin-dark p-5 rounded-xl border border-gray-700 hover:border-gray-500 transition-all duration-300 hover:shadow-2xl report-item shadow-lg flex flex-col h-full" data-status="<?= $rp->status ?>">
            
            <div class="flex justify-between items-start mb-4 border-b border-gray-700/50 pb-3">
                <div class="flex gap-3 items-center">
                    <div class="bg-gray-800 p-2 rounded text-gray-400"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <h4 class="font-bold text-white text-md capitalize"><?= substr($rp->location_address, 0, 30) ?>...</h4>
                        <span class="text-[10px] text-gray-500 font-mono">#<?= $rp->id ?> • <?= date('d/m/Y', strtotime($rp->created_at)) ?></span>
                    </div>
                </div>
                <!-- Badge Status -->
                <?php 
                    $badgeClass = 'bg-gray-800 text-gray-400';
                    $statusIcon = 'fa-circle';
                    if($rp->status == 'pending') { $badgeClass = 'bg-red-900/20 text-red-400 border border-red-900'; $statusIcon = 'fa-exclamation-circle'; }
                    if($rp->status == 'in_progress') { $badgeClass = 'bg-yellow-900/20 text-yellow-400 border border-yellow-900'; $statusIcon = 'fa-sync fa-spin'; }
                    if($rp->status == 'resolved') { $badgeClass = 'bg-green-900/20 text-green-400 border border-green-900'; $statusIcon = 'fa-check-circle'; }
                    if($rp->status == 'rejected') { $badgeClass = 'bg-gray-700 text-gray-400 border border-gray-600 line-through'; $statusIcon = 'fa-ban'; }
                ?>
                <span class="px-2 py-1 rounded text-[10px] uppercase font-bold <?= $badgeClass ?>">
                    <i class="fas <?= $statusIcon ?> mr-1"></i> <?= str_replace('_', ' ', $rp->status) ?>
                </span>
            </div>
            
            <div class="flex gap-4 mb-4 flex-1">
                <?php if($rp->image_before_url): ?>
                    <div class="w-24 h-24 rounded-lg overflow-hidden flex-shrink-0 border border-gray-600 bg-black group relative cursor-pointer" onclick="window.open('<?= base_url('uploads/reports/'.$rp->image_before_url) ?>', '_blank')">
                        <img src="<?= base_url('uploads/reports/'.$rp->image_before_url) ?>" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition">
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 bg-black/50 transition">
                            <i class="fas fa-search-plus text-white"></i>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="flex-1">
                    <p class="text-xs text-gray-500 font-bold uppercase mb-1">Pelapor: <span class="text-gray-300"><?= $rp->reporter_name ?></span></p>
                    <div class="bg-black/20 p-2 rounded border border-white/5 h-20 overflow-y-auto">
                        <p class="text-xs text-gray-300 italic">"<?= $rp->description ?: '-' ?>"</p>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-gray-700 mt-auto">
                <?php if($rp->status == 'pending'): ?>
                    <div class="flex gap-2">
                        <a href="<?= base_url('content/update_report_status/'.$rp->id.'/in_progress') ?>" class="flex-1 bg-yellow-600 hover:bg-yellow-500 text-white py-2 rounded text-xs font-bold text-center transition hover:shadow-lg">
                            <i class="fas fa-tools mr-1"></i> Proses
                        </a>
                        <a href="<?= base_url('content/update_report_status/'.$rp->id.'/rejected') ?>" onclick="return confirm('Yakin tolak?')" class="px-4 py-2 bg-transparent border border-red-800 text-red-400 rounded text-xs font-bold hover:bg-red-900/30 transition">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                <?php elseif($rp->status == 'in_progress'): ?>
                    <button onclick="openResolveModal(<?= $rp->id ?>)" class="w-full bg-brand-green text-black py-2 rounded text-xs font-bold hover:bg-white transition shadow-lg shadow-green-900/10">
                        <i class="fas fa-camera mr-1"></i> Selesaikan (Upload Bukti)
                    </button>
                <?php elseif($rp->status == 'resolved'): ?>
                    <div class="flex justify-between items-center text-xs text-green-400 bg-green-900/10 px-3 py-2 rounded border border-green-900/30">
                        <span class="font-bold"><i class="fas fa-check-double mr-1"></i> Selesai</span>
                        <?php if($rp->image_after_url): ?>
                            <a href="<?= base_url('uploads/reports/'.$rp->image_after_url) ?>" target="_blank" class="underline hover:text-white">Lihat Bukti</a>
                        <?php endif; ?>
                    </div>
                <?php elseif($rp->status == 'rejected'): ?>
                    <div class="text-center text-xs text-gray-500 font-bold bg-gray-800 py-2 rounded border border-gray-700">
                        <i class="fas fa-ban mr-1"></i> Ditolak
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if(empty($reports)): ?>
            <div class="col-span-1 md:col-span-2 text-center py-16 border-2 border-dashed border-gray-700 rounded-xl text-gray-500">
                <i class="fas fa-inbox text-4xl mb-3 opacity-50"></i>
                <p>Belum ada laporan masuk.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ==========================
     KONTEN TAB 2: EVENT
     ========================== -->
<div id="content-event" class="tab-content hidden">
    <!-- Header Event & Tombol -->
    <div class="flex justify-between items-center mb-6 bg-blue-900/10 p-6 rounded-xl border border-blue-900/30">
        <div>
            <h3 class="text-xl font-bold text-white mb-1">Manajemen Event Volunteer</h3>
            <p class="text-sm text-blue-300">Jadwal aksi sosial untuk relawan.</p>
        </div>
        <button onclick="openAddEventModal()" class="bg-blue-600 text-white font-bold px-6 py-3 rounded-lg hover:bg-blue-500 transition text-sm flex items-center shadow-lg shadow-blue-900/30 transform hover:-translate-y-1">
            <i class="fas fa-plus mr-2"></i> Buat Event Baru
        </button>
    </div>

    <!-- PETA SEBARAN EVENT -->
    <div class="mb-8 relative group z-0">
        <div class="absolute top-4 left-4 z-10 bg-black/70 backdrop-blur px-3 py-1 rounded border border-white/10">
            <h3 class="text-xs font-bold text-white uppercase"><i class="fas fa-map-marked-alt mr-2 text-blue-400"></i> Peta Lokasi Aksi</h3>
        </div>
        <div id="eventMap" class="w-full h-64 bg-gray-900 rounded-xl border border-gray-700 overflow-hidden shadow-inner z-0"></div>
    </div>

    <!-- MILESTONE FILTER EVENT -->
    <div class="mb-3 flex items-center gap-2">
        <span class="text-[10px] uppercase font-bold text-gray-500 bg-gray-800 px-2 py-1 rounded">Filter Event:</span>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
        <?php 
            $ev_upcoming = count(array_filter($events, function($e){ return $e->status == 'upcoming'; }));
            $ev_ongoing = count(array_filter($events, function($e){ return $e->status == 'ongoing'; }));
            $ev_completed = count(array_filter($events, function($e){ 
                $time = strtotime($e->event_date);
                return $e->status == 'completed' || ($time < (time() - 86400) && $e->status != 'cancelled');
            }));
            $ev_total = count($events);
        ?>

        <button onclick="filterEvents('all')" id="ev-btn-all" class="ev-filter active group flex flex-col items-center justify-center p-4 rounded-xl border-2 border-gray-800 bg-admin-dark hover:border-blue-500/50 hover:bg-gray-800 transition-all duration-300 transform hover:-translate-y-1">
            <span class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1 group-hover:text-white transition">Semua Event</span>
            <h2 class="text-2xl font-heading font-bold text-white"><?= $ev_total ?></h2>
        </button>
        <button onclick="filterEvents('upcoming')" id="ev-btn-upcoming" class="ev-filter group flex flex-col items-center justify-center p-4 rounded-xl border-2 border-gray-800 bg-admin-dark hover:border-blue-400/50 hover:bg-gray-800 transition-all duration-300 transform hover:-translate-y-1">
            <span class="text-[10px] text-gray-500 uppercase font-bold tracking-wider group-hover:text-blue-400 transition">Upcoming</span>
            <h2 class="text-2xl font-heading font-bold text-blue-400 mt-1"><?= $ev_upcoming ?></h2>
        </button>
        <button onclick="filterEvents('ongoing')" id="ev-btn-ongoing" class="ev-filter group flex flex-col items-center justify-center p-4 rounded-xl border-2 border-gray-800 bg-admin-dark hover:border-yellow-500/50 hover:bg-gray-800 transition-all duration-300 transform hover:-translate-y-1">
            <span class="text-[10px] text-gray-500 uppercase font-bold tracking-wider group-hover:text-yellow-400 transition">Ongoing</span>
            <h2 class="text-2xl font-heading font-bold text-yellow-500 mt-1"><?= $ev_ongoing ?></h2>
        </button>
        <button onclick="filterEvents('completed')" id="ev-btn-completed" class="ev-filter group flex flex-col items-center justify-center p-4 rounded-xl border-2 border-gray-800 bg-admin-dark hover:border-green-500/50 hover:bg-gray-800 transition-all duration-300 transform hover:-translate-y-1">
            <span class="text-[10px] text-gray-500 uppercase font-bold tracking-wider group-hover:text-green-400 transition">Selesai</span>
            <h2 class="text-2xl font-heading font-bold text-green-500 mt-1"><?= $ev_completed ?></h2>
        </button>
    </div>

    <!-- GRID EVENT -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach($events as $ev): 
            $now = time();
            $evtTime = strtotime($ev->event_date);
            $realStatus = $ev->status;
            if ($evtTime < ($now - 86400) && $ev->status != 'cancelled') $realStatus = 'completed';
            $evDataJson = htmlspecialchars(json_encode($ev), ENT_QUOTES, 'UTF-8');
            
            // --- FITUR BARU: AMBIL JUMLAH PENDAFTAR REAL ---
            // Cek apakah properti registered_count ada (dari Controller)
            $reg_count = isset($ev->registered_count) ? $ev->registered_count : 0;
        ?>
        <div class="bg-admin-dark p-0 rounded-xl border border-gray-700 hover:border-blue-500/50 transition duration-300 transform hover:-translate-y-2 hover:shadow-2xl overflow-hidden group event-item flex flex-col" data-status="<?= $realStatus ?>">
            <!-- Banner Image -->
            <div class="h-40 bg-gray-800 relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 to-transparent z-10"></div>
                <?php 
                    $banner = (!empty($ev->banner_image_url) && file_exists(FCPATH . 'uploads/events/' . $ev->banner_image_url)) 
                        ? base_url('uploads/events/'.$ev->banner_image_url) 
                        : "https://source.unsplash.com/400x200/?nature,volunteer&sig=".$ev->id; 
                ?>
                <img src="<?= $banner ?>" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition group-hover:scale-110 duration-700">
                
                <span class="absolute top-3 right-3 z-20 text-[10px] uppercase font-bold px-2 py-1 rounded bg-black/50 text-white border border-white/20 backdrop-blur-sm shadow">
                    <?= $realStatus ?>
                </span>

                <!-- OVERLAY JUMLAH PENDAFTAR -->
                <div class="absolute bottom-3 left-3 z-20 flex items-center gap-1.5 bg-black/60 backdrop-blur px-2 py-1 rounded-full border border-white/10">
                    <i class="fas fa-users text-blue-400 text-xs"></i>
                    <span class="text-[10px] font-bold text-white"><?= $reg_count ?> Relawan</span>
                </div>
            </div>

            <div class="p-5 flex-1 flex flex-col">
                <h4 class="font-bold text-white text-lg leading-tight mb-2 group-hover:text-blue-400 transition"><?= $ev->event_name ?></h4>
                
                <div class="space-y-3 mb-6 flex-1">
                    <p class="text-xs text-gray-400 flex items-center">
                        <i class="fas fa-calendar w-5 text-center text-blue-500 mr-2"></i> 
                        <?= date('d F Y', strtotime($ev->event_date)) ?>
                    </p>
                    <p class="text-xs text-gray-400 flex items-center">
                        <i class="fas fa-clock w-5 text-center text-blue-500 mr-2"></i> 
                        <?= date('H:i', strtotime($ev->event_date)) ?> WIB
                    </p>
                    <p class="text-xs text-gray-400 flex items-center">
                        <i class="fas fa-map-pin w-5 text-center text-blue-500 mr-2"></i> 
                        <?= $ev->location ?>
                    </p>
                </div>

                <div class="flex gap-2 pt-4 border-t border-gray-700 mt-auto">
                    <!-- Tombol LIHAT PENDAFTAR -->
                    <button onclick="openVolunteerList(<?= $ev->id ?>, '<?= addslashes($ev->event_name) ?>')" class="bg-blue-900/30 text-blue-400 py-2 px-3 rounded text-xs font-bold hover:bg-blue-900/50 transition border border-blue-900/50" title="Lihat Data Relawan">
                        <i class="fas fa-clipboard-list"></i>
                    </button>

                    <button onclick="openEditEventModal(<?= $evDataJson ?>)" class="flex-1 bg-gray-800 text-gray-300 py-2 rounded text-xs font-bold text-center hover:bg-gray-700 hover:text-white transition">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </button>
                    <a href="<?= base_url('content/delete_event/'.$ev->id) ?>" onclick="return confirm('Hapus event ini?')" class="flex-1 bg-red-900/20 text-red-400 border border-red-900/50 py-2 rounded text-xs font-bold text-center hover:bg-red-900/40 transition">
                        <i class="fas fa-trash mr-1"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if(empty($events)): ?>
            <div class="col-span-full text-center py-16 border-2 border-dashed border-gray-700 rounded-xl text-gray-500">
                <i class="fas fa-calendar-times text-4xl mb-3 opacity-50"></i>
                <p>Belum ada event aktif.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL RESOLVE (LAPORAN) -->
<div id="resolveModal" class="fixed inset-0 bg-black/90 hidden flex items-center justify-center p-4 z-[60] backdrop-blur-sm">
    <div class="bg-admin-dark p-6 rounded-xl w-full max-w-md border border-gray-600 shadow-2xl transform transition-all scale-100">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-brand-green/20 text-brand-green rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-camera text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white">Bukti Penyelesaian</h3>
            <p class="text-xs text-gray-400">Upload foto kondisi setelah dibersihkan (After).</p>
        </div>
        
        <?= form_open_multipart('content/resolve_report') ?>
            <input type="hidden" name="report_id" id="modal_report_id">
            
            <div class="mb-6">
                <div class="border-2 border-dashed border-gray-600 rounded-lg p-8 text-center hover:bg-gray-800 transition cursor-pointer relative group bg-black/30">
                    <input type="file" name="image_after" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required onchange="previewFile(event, 'preview-resolve')">
                    <div id="upload-placeholder-resolve">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-500 mb-2 group-hover:text-brand-green transition"></i>
                        <p class="text-xs text-gray-400 group-hover:text-white">Klik atau Tarik Foto ke sini</p>
                    </div>
                    <img id="preview-resolve" class="hidden w-full h-32 object-cover rounded mt-2 border border-gray-600">
                </div>
            </div>

            <div class="flex justify-between gap-3">
                <button type="button" onclick="document.getElementById('resolveModal').classList.add('hidden')" class="px-4 py-2 text-gray-400 hover:text-white text-sm font-bold">Batal</button>
                <button type="submit" class="flex-1 bg-brand-green text-black px-4 py-2.5 rounded font-bold text-sm hover:bg-white transition shadow-lg shadow-green-900/20">
                    <i class="fas fa-check-circle mr-2"></i> Simpan & Selesai
                </button>
            </div>
        <?= form_close() ?>
    </div>
</div>

<!-- MODAL EVENT (ADD / EDIT) -->
<div id="eventModal" class="fixed inset-0 bg-black/90 hidden flex items-center justify-center p-4 z-[9999] backdrop-blur-sm overflow-y-auto">
    <div class="bg-admin-dark p-0 rounded-2xl w-full max-w-4xl border border-gray-700 shadow-2xl relative overflow-hidden my-auto">
        <div class="p-6 border-b border-gray-700 bg-gray-800/50 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-1 bg-blue-500 h-6 rounded-full"></div>
                <h3 class="text-xl font-heading font-bold text-white" id="eventModalTitle">Edit Event</h3>
            </div>
            <button type="button" onclick="closeEventModal()" class="text-gray-400 hover:text-white"><i class="fas fa-times text-xl"></i></button>
        </div>

        <?= form_open_multipart('content/add_event', ['id' => 'eventForm']) ?>
            <input type="hidden" name="event_id" id="evt_id"> 
            <input type="hidden" name="latitude" id="evt_lat">
            <input type="hidden" name="longitude" id="evt_lng">

            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs text-gray-500 font-bold mb-2 uppercase tracking-wide">Nama Kegiatan</label>
                            <input type="text" name="event_name" id="evt_name" class="w-full bg-black border border-gray-700 p-3 rounded-lg text-sm text-white focus:border-blue-500 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-bold mb-2 uppercase tracking-wide">Waktu Pelaksanaan</label>
                            <input type="datetime-local" name="event_date" id="evt_date" class="w-full bg-black border border-gray-700 p-3 rounded-lg text-sm text-white focus:border-blue-500 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-bold mb-2 uppercase tracking-wide">Status Event</label>
                            <select name="status" id="evt_status" class="w-full bg-black border border-gray-700 p-3 rounded-lg text-sm text-white focus:border-blue-500 outline-none">
                                <option value="upcoming">Upcoming</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-bold mb-2 uppercase tracking-wide">Deskripsi</label>
                            <textarea name="description" id="evt_desc" rows="4" class="w-full bg-black border border-gray-700 p-3 rounded-lg text-sm text-white focus:border-blue-500 outline-none"></textarea>
                        </div>
                    </div>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs text-gray-500 font-bold mb-2 uppercase tracking-wide">Pilih Lokasi (Klik Peta)</label>
                            <div class="relative rounded-lg border border-gray-700 overflow-hidden shadow-inner group">
                                <div id="inputEventMap" class="w-full h-48 bg-gray-900 z-0"></div>
                            </div>
                            <input type="text" name="location" id="evt_loc" placeholder="Lokasi Teks" class="w-full bg-black border border-gray-700 p-3 mt-3 rounded-lg text-sm text-white focus:border-blue-500 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 font-bold mb-2 uppercase tracking-wide">Banner Foto</label>
                            <div class="border-2 border-dashed border-gray-700 rounded-lg p-6 text-center hover:bg-gray-800 transition cursor-pointer relative group bg-black/20">
                                <input type="file" name="banner_image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewFile(event, 'preview-event')">
                                <div id="upload-placeholder-event" class="flex flex-col items-center">
                                    <i class="fas fa-image text-3xl text-gray-600 mb-2 group-hover:text-blue-500"></i> 
                                    <span class="text-xs text-gray-500 group-hover:text-white">Upload Banner</span>
                                </div>
                                <img id="preview-event" class="hidden absolute inset-0 w-full h-full object-cover rounded-lg">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6 bg-gray-800/50 border-t border-gray-700 flex justify-end gap-3">
                <button type="button" onclick="closeEventModal()" class="px-6 py-2.5 text-gray-400 font-bold hover:text-white">Batal</button>
                <button type="submit" class="bg-blue-600 text-white px-8 py-2.5 rounded-lg font-bold hover:bg-blue-500 transition">Simpan Event</button>
            </div>
        <?= form_close() ?>
    </div>
</div>

<!-- MODAL LIST VOLUNTEER (Z-INDEX 9999) -->
<div id="volunteerListModal" class="fixed inset-0 bg-black/95 hidden flex items-center justify-center p-4 z-[9999] backdrop-blur-sm">
    <div class="bg-admin-dark p-0 rounded-xl w-full max-w-3xl border border-gray-700 shadow-2xl relative flex flex-col max-h-[80vh]">
        <div class="p-5 border-b border-gray-700 bg-gray-800/50 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-white uppercase"><i class="fas fa-users mr-2 text-blue-400"></i> Data Relawan</h3>
                <p class="text-xs text-gray-400 mt-1" id="vl-event-name">Loading...</p>
            </div>
            <button onclick="document.getElementById('volunteerListModal').classList.add('hidden')" class="text-gray-400 hover:text-white"><i class="fas fa-times text-xl"></i></button>
        </div>
        
        <div class="overflow-auto p-0 flex-1">
            <table class="w-full text-left text-sm text-gray-400">
                <thead class="bg-black/50 uppercase text-xs font-bold text-gray-500 sticky top-0">
                    <tr>
                        <th class="p-4">Nama</th>
                        <th class="p-4">Kontak</th>
                        <th class="p-4">Domisili</th>
                        <th class="p-4">Usia/JK</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="volunteer-table-body" class="divide-y divide-gray-800"></tbody>
            </table>
            <div id="vl-loading" class="p-10 text-center text-gray-500 hidden"><i class="fas fa-spinner fa-spin text-2xl mb-2"></i><br>Mengambil data...</div>
            <div id="vl-empty" class="p-10 text-center text-gray-500 hidden">Belum ada yang mendaftar.</div>
        </div>
    </div>
</div>

<!-- LEAFLET JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const db_events = <?= json_encode($events) ?>;

    function switchMainTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.getElementById('content-' + tabName).classList.remove('hidden');
        document.querySelectorAll('.main-tab-btn').forEach(btn => { btn.classList.remove('active', 'text-white', 'border-brand-green'); btn.classList.add('text-gray-400', 'border-transparent'); });
        document.getElementById('tab-btn-' + tabName).classList.add('active', 'text-white', 'border-brand-green');
        document.getElementById('tab-btn-' + tabName).classList.remove('text-gray-400', 'border-transparent');
        if(tabName === 'event') initEventMap();
    }

    function filterReports(status) {
        document.querySelectorAll('.filter-btn').forEach(btn => { btn.classList.remove('border-brand-green', 'border-red-500', 'border-yellow-500', 'border-green-500', 'border-gray-400'); btn.classList.add('border-gray-800'); });
        const activeBtn = document.getElementById('btn-' + status);
        if(activeBtn) { activeBtn.classList.remove('border-gray-800'); if(status == 'all') activeBtn.classList.add('border-brand-green'); if(status == 'pending') activeBtn.classList.add('border-red-500'); if(status == 'in_progress') activeBtn.classList.add('border-yellow-500'); if(status == 'resolved') activeBtn.classList.add('border-green-500'); if(status == 'rejected') activeBtn.classList.add('border-gray-400'); }
        const items = document.querySelectorAll('.report-item');
        items.forEach(item => { if(status === 'all' || item.dataset.status === status) { item.classList.remove('hidden'); item.classList.add('flex'); } else { item.classList.add('hidden'); item.classList.remove('flex'); } });
    }

    function filterEvents(status) {
        document.querySelectorAll('.ev-filter').forEach(btn => { btn.classList.remove('border-blue-500', 'border-yellow-500', 'border-green-500', 'border-brand-green'); btn.classList.add('border-gray-800'); });
        const activeBtn = document.getElementById('ev-btn-' + status);
        if(activeBtn) { activeBtn.classList.remove('border-gray-800'); if(status == 'all') activeBtn.classList.add('border-brand-green'); if(status == 'upcoming') activeBtn.classList.add('border-blue-500'); if(status == 'ongoing') activeBtn.classList.add('border-yellow-500'); if(status == 'completed') activeBtn.classList.add('border-green-500'); }
        const items = document.querySelectorAll('.event-item');
        items.forEach(item => { if(status === 'all' || item.dataset.status === status) { item.classList.remove('hidden'); } else { item.classList.add('hidden'); } });
    }

    let eventMap = null;
    function initEventMap() {
        if(eventMap) return; 
        setTimeout(() => { 
            eventMap = L.map('eventMap').setView([-6.2088, 106.8456], 10);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; OpenStreetMap' }).addTo(eventMap);
            db_events.forEach(ev => {
                let lat = -6.2 + (Math.random() * 0.1 - 0.05); let lng = 106.8 + (Math.random() * 0.1 - 0.05);
                L.marker([lat, lng]).addTo(eventMap).bindPopup(`<b style="color:black">${ev.event_name}</b><br>${ev.location}`);
            });
        }, 300);
    }

    let inputMap = null;
    let inputMarker = null;

    function openAddEventModal() {
        document.getElementById('eventModalTitle').innerText = "Buat Event Baru";
        document.getElementById('eventForm').action = "<?= base_url('content/add_event') ?>";
        document.getElementById('eventForm').reset();
        document.getElementById('evt_id').value = ""; 
        document.getElementById('upload-placeholder-event').classList.remove('hidden');
        document.getElementById('preview-event').classList.add('hidden');
        document.getElementById('eventModal').classList.remove('hidden');
        initInputMap();
    }

    function openEditEventModal(data) {
        document.getElementById('eventModalTitle').innerText = "Edit Event";
        document.getElementById('eventForm').action = "<?= base_url('content/edit_event') ?>";
        document.getElementById('evt_id').value = data.id;
        document.getElementById('evt_name').value = data.event_name;
        document.getElementById('evt_date').value = data.event_date.replace(' ', 'T');
        document.getElementById('evt_status').value = data.status;
        document.getElementById('evt_loc').value = data.location;
        document.getElementById('evt_desc').value = data.description || '';
        if(data.banner_image_url) {
            document.getElementById('upload-placeholder-event').classList.add('hidden');
            document.getElementById('preview-event').classList.remove('hidden');
            document.getElementById('preview-event').src = "<?= base_url('uploads/events/') ?>" + data.banner_image_url;
        } else {
            document.getElementById('upload-placeholder-event').classList.remove('hidden');
            document.getElementById('preview-event').classList.add('hidden');
        }
        document.getElementById('eventModal').classList.remove('hidden');
        initInputMap();
    }

    function closeEventModal() { document.getElementById('eventModal').classList.add('hidden'); }

    function initInputMap() {
        setTimeout(() => {
            if(!inputMap) {
                inputMap = L.map('inputEventMap').setView([-6.2088, 106.8456], 11);
                L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png').addTo(inputMap);
                inputMap.on('click', function(e) {
                    const lat = e.latlng.lat; const lng = e.latlng.lng;
                    if(inputMarker) inputMarker.setLatLng([lat, lng]); else inputMarker = L.marker([lat, lng]).addTo(inputMap);
                });
            }
            inputMap.invalidateSize(); 
        }, 300);
    }

    // LIST PENDAFTAR LOGIC
    function openVolunteerList(eventId, eventName) {
        const modal = document.getElementById('volunteerListModal');
        const title = document.getElementById('vl-event-name');
        const tbody = document.getElementById('volunteer-table-body');
        const loading = document.getElementById('vl-loading');
        const empty = document.getElementById('vl-empty');

        title.innerText = eventName;
        modal.classList.remove('hidden');
        tbody.innerHTML = '';
        loading.classList.remove('hidden');
        empty.classList.add('hidden');

        fetch('<?= base_url('content/get_event_volunteers/') ?>' + eventId)
            .then(response => response.json())
            .then(data => {
                loading.classList.add('hidden');
                if(data.length === 0) {
                    empty.classList.remove('hidden');
                } else {
                    data.forEach(v => {
                        tbody.innerHTML += `
                            <tr class="hover:bg-gray-800/50 transition">
                                <td class="p-4 font-bold text-white">${v.name}</td>
                                <td class="p-4"><div class="text-xs text-gray-300">${v.email}</div><div class="text-xs text-brand-green">${v.phone}</div></td>
                                <td class="p-4">${v.domicile}</td>
                                <td class="p-4 text-xs">${v.age} Thn / ${v.gender}</td>
                                <td class="p-4 text-right"><a href="https://wa.me/${v.phone.replace(/^0/, '62')}" target="_blank" class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-500"><i class="fab fa-whatsapp"></i> Chat</a></td>
                            </tr>
                        `;
                    });
                }
            })
            .catch(err => { loading.classList.add('hidden'); alert('Gagal mengambil data.'); });
    }

    function openResolveModal(id) { document.getElementById('modal_report_id').value = id; document.getElementById('resolveModal').classList.remove('hidden'); }
    function previewFile(event, previewId) {
        const input = event.target;
        const preview = document.getElementById(previewId);
        const placeholder = input.previousElementSibling; 
        if(input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) { preview.src = e.target.result; preview.classList.remove('hidden'); if(placeholder) placeholder.classList.add('hidden'); }
            reader.readAsDataURL(input.files[0]);
        }
    }
    document.addEventListener('DOMContentLoaded', () => { filterReports('all'); });
</script>
