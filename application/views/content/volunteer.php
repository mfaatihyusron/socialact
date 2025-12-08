<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Aksi Terdekat - Volunteer</title>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <style>
        #mapVolunteer { height: 350px; width: 100%; border-radius: 1rem; z-index: 1; }
        .event-card { transition: all 0.3s ease; }
        .event-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(16, 185, 129, 0.15); }
        .highlight-card { border-color: #10b981; box-shadow: 0 0 20px rgba(16, 185, 129, 0.4); transform: scale(1.02); }
        
        /* Grayscale untuk event selesai */
        .card-completed { filter: grayscale(100%); opacity: 0.7; }
        .card-completed:hover { filter: grayscale(0%); opacity: 1; }
    </style>
</head>
<body class="bg-brand-black text-white min-h-screen">

    <div class="pt-24 pb-12 px-6 max-w-7xl mx-auto">
        
        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-end gap-8 mb-8 border-b border-gray-800 pb-8">
            <div class="flex items-center gap-4">
                <div class="w-2 h-20 bg-brand-green shadow-[0_0_20px_#10b981]"></div>
                <div>
                    <span class="text-brand-green font-bold uppercase tracking-[0.2em] text-xs">Volunteer Hub</span>
                    <h2 class="font-heading text-4xl md:text-6xl font-bold uppercase tracking-wide text-white leading-none mt-2">
                        Aksi <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-green to-teal-500">Nyata</span>
                    </h2>
                </div>
            </div>
            <div class="flex gap-8 bg-[#121212] p-4 rounded-xl border border-gray-800">
                <div class="text-right">
                    <?php $active = count(array_filter($events, function($e){ return $e->status != 'completed'; })); ?>
                    <h3 class="font-heading text-3xl font-bold text-white"><?= $active ?></h3>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider font-bold">Event Aktif</p>
                </div>
                <div class="w-px bg-gray-700"></div>
                <div class="text-right">
                    <?php $completed = count(array_filter($events, function($e){ return $e->status == 'completed'; })); ?>
                    <h3 class="font-heading text-3xl font-bold text-brand-green"><?= $completed ?></h3>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider font-bold">Misi Selesai</p>
                </div>
            </div>
        </div>

        <!-- PETA SEBARAN (Z-INDEX 0 Biar ga nimpa navbar) -->
        <div class="mb-12 relative group z-0">
            <div id="mapVolunteer" class="shadow-2xl border border-gray-800 grayscale hover:grayscale-0 transition duration-700"></div>
            <!-- Legend -->
            <div class="absolute bottom-4 right-4 z-[30] bg-black/80 backdrop-blur px-4 py-3 rounded-lg border border-white/10 shadow-xl">
                <h3 class="text-[10px] font-bold text-gray-400 uppercase mb-2">Status</h3>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-xs text-white"><span class="w-2 h-2 rounded-full bg-green-500"></span> Upcoming</div>
                    <div class="flex items-center gap-2 text-xs text-white"><span class="w-2 h-2 rounded-full bg-yellow-500"></span> Ongoing</div>
                    <div class="flex items-center gap-2 text-xs text-white"><span class="w-2 h-2 rounded-full bg-gray-500"></span> Selesai</div>
                </div>
            </div>
        </div>

        <!-- FILTER -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4 overflow-x-auto pb-2" id="filter-container">
                <button onclick="filterEvents('all')" id="btn-filter-all" class="filter-btn px-4 py-2 bg-brand-green text-black text-xs font-bold rounded-full hover:bg-white transition shadow-lg shadow-brand-green/20">Semua</button>
                <button onclick="filterEvents('week')" id="btn-filter-week" class="filter-btn px-4 py-2 bg-transparent border border-gray-700 text-gray-400 text-xs font-bold rounded-full hover:border-white hover:text-white transition">Minggu Ini</button>
                <button onclick="filterEvents('month')" id="btn-filter-month" class="filter-btn px-4 py-2 bg-transparent border border-gray-700 text-gray-400 text-xs font-bold rounded-full hover:border-white hover:text-white transition">Bulan Depan</button>
            </div>
            <button onclick="resetFilter()" id="btn-reset" class="hidden text-xs text-red-400 font-bold hover:text-white transition flex items-center gap-1"><i class="fas fa-times"></i> Reset</button>
        </div>

        <!-- GRID -->
        <?php if(!empty($events)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="event-grid">
                <?php foreach($events as $ev): 
                    $isCompleted = ($ev->status == 'completed' || $ev->status == 'cancelled');
                    $cardClass = $isCompleted ? 'card-completed' : '';
                    
                    $statusClass = 'bg-blue-600';
                    $statusLabel = 'OPEN RECRUITMENT';
                    
                    if($ev->status == 'ongoing') { $statusClass = 'bg-yellow-500 text-black'; $statusLabel = 'ONGOING'; }
                    elseif($ev->status == 'completed') { $statusClass = 'bg-gray-600'; $statusLabel = 'SELESAI'; }

                    $banner = (!empty($ev->banner_image_url) && file_exists(FCPATH . 'uploads/events/' . $ev->banner_image_url)) 
                        ? base_url('uploads/events/'.$ev->banner_image_url) 
                        : "https://source.unsplash.com/600x400/?volunteer,nature&sig=".$ev->id;
                    
                    $timestamp = strtotime($ev->event_date);
                    $registered_count = isset($ev->registered_count) ? $ev->registered_count : 0;
                ?>
                <div id="event-card-<?= $ev->id ?>" class="event-card bg-[#121212] rounded-2xl overflow-hidden border border-gray-800 group relative flex flex-col h-full <?= $cardClass ?>" data-date="<?= $timestamp ?>" data-id="<?= $ev->id ?>">
                    
                    <div class="relative h-56 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#121212] via-transparent to-transparent z-10"></div>
                        <span class="absolute top-4 right-4 z-20 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-lg <?= $statusClass ?> text-white"><?= $statusLabel ?></span>
                        <div class="absolute top-4 left-4 z-20 flex items-center gap-2 bg-black/60 backdrop-blur px-3 py-1 rounded-full border border-white/10">
                            <span class="text-[10px] font-bold text-white"><?= $registered_count ?> Pendaftar</span>
                        </div>
                        <img src="<?= $banner ?>" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-110 transition duration-700">
                    </div>

                    <div class="p-6 relative z-20 -mt-12 flex-1 flex flex-col">
                        <div class="inline-flex items-center gap-2 bg-black/60 backdrop-blur border border-gray-700 rounded-lg px-3 py-1.5 w-fit mb-3">
                            <i class="fas fa-calendar-alt text-brand-green text-xs"></i>
                            <span class="text-xs font-bold text-gray-200"><?= date('d M Y', strtotime($ev->event_date)) ?></span>
                        </div>

                        <h3 class="font-heading text-2xl font-bold uppercase text-white mb-2 leading-tight group-hover:text-brand-green transition"><?= $ev->event_name ?></h3>
                        
                        <div class="space-y-2 mb-6 flex-1">
                            <div class="flex items-start gap-2 text-xs text-gray-400">
                                <i class="fas fa-clock mt-0.5 text-brand-green"></i> <span><?= date('H:i', strtotime($ev->event_date)) ?> WIB</span>
                            </div>
                            <div class="flex items-start gap-2 text-xs text-gray-400">
                                <i class="fas fa-map-pin mt-0.5 text-brand-green"></i> <span class="uppercase"><?= $ev->location ?></span>
                            </div>
                        </div>

                        <?php if($ev->status == 'upcoming'): ?>
                            <button onclick="openRegisterModal('<?= $ev->id ?>', '<?= addslashes($ev->event_name) ?>')" class="w-full bg-white text-black font-bold py-3 rounded-lg text-sm uppercase tracking-wider hover:bg-brand-green transition text-center">Daftar Sekarang</button>
                        <?php else: ?>
                            <button disabled class="w-full bg-gray-800 text-gray-500 font-bold py-3 rounded-lg text-sm uppercase tracking-wider cursor-not-allowed border border-gray-700">Pendaftaran Ditutup</button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-20 border-2 border-dashed border-gray-800 rounded-3xl bg-[#121212]">
                <h3 class="text-2xl font-bold text-gray-500">Belum Ada Event Aktif</h3>
            </div>
        <?php endif; ?>
    </div>

    <!-- MODAL PENDAFTARAN -->
    <div id="registerModal" class="fixed inset-0 bg-black/90 hidden flex items-center justify-center p-4 z-[9999] backdrop-blur-sm overflow-y-auto">
        <div class="bg-[#121212] rounded-2xl w-full max-w-lg border border-gray-700 shadow-2xl relative my-auto">
            <button onclick="closeRegisterModal()" class="absolute top-4 right-4 text-gray-400 hover:text-white z-10"><i class="fas fa-times text-xl"></i></button>
            <div class="p-8">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-heading font-bold text-white uppercase tracking-wide">Formulir Relawan</h3>
                    <p class="text-sm text-brand-green font-bold mt-1" id="modalEventName">Nama Event</p>
                </div>
                <?= form_open('web/register_volunteer', ['id' => 'registerForm', 'class' => 'space-y-5']) ?>
                    <input type="hidden" name="event_id" id="modalEventId">
                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" name="name" class="bg-black border border-gray-700 p-3 rounded-lg text-sm text-white focus:border-brand-green outline-none" required placeholder="Nama Lengkap">
                        <input type="number" name="age" class="bg-black border border-gray-700 p-3 rounded-lg text-sm text-white focus:border-brand-green outline-none" required placeholder="Usia">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="email" name="email" class="bg-black border border-gray-700 p-3 rounded-lg text-sm text-white focus:border-brand-green outline-none" required placeholder="Email">
                        <input type="text" name="phone" class="bg-black border border-gray-700 p-3 rounded-lg text-sm text-white focus:border-brand-green outline-none" required placeholder="WhatsApp">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <select name="gender" class="bg-black border border-gray-700 p-3 rounded-lg text-sm text-white focus:border-brand-green outline-none"><option value="L">Laki-laki</option><option value="P">Perempuan</option></select>
                        <input type="text" name="domicile" class="bg-black border border-gray-700 p-3 rounded-lg text-sm text-white focus:border-brand-green outline-none" required placeholder="Domisili">
                    </div>
                    <textarea name="experience" class="w-full bg-black border border-gray-700 p-3 rounded-lg text-sm text-white focus:border-brand-green outline-none h-20" placeholder="Pengalaman (Opsional)"></textarea>
                    <textarea name="motivation" class="w-full bg-black border border-gray-700 p-3 rounded-lg text-sm text-white focus:border-brand-green outline-none h-20" placeholder="Motivasi Gabung" required></textarea>
                    <button type="submit" class="w-full bg-brand-green text-black font-bold py-3 rounded-lg uppercase tracking-wider text-sm hover:bg-white transition mt-2">Kirim</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>

    <script>
        const map = L.map('mapVolunteer').setView([-6.2088, 106.8456], 10); 
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; OpenStreetMap', maxZoom: 19 }).addTo(map);

        const events = <?= json_encode($events) ?>;

        events.forEach(ev => {
            const lat = -6.2 + (Math.random() * 0.15 - 0.07);
            const lng = 106.8 + (Math.random() * 0.15 - 0.07);
            let color = '#10b981'; // Green
            if(ev.status == 'ongoing') color = '#eab308';
            if(ev.status == 'completed') color = '#6b7280';

            const customIcon = L.divIcon({ html: `<div style="background-color: ${color}; width: 16px; height: 16px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 15px ${color};"></div>`, className: 'custom-marker-icon' });
            
            const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map)
                .bindPopup(`
                    <div style="color:#333; min-width:180px;">
                        <b style="text-transform:uppercase; font-size:12px;">${ev.event_name}</b><br>
                        <span style="font-size:10px; color:#666;"><i class="fas fa-map-pin"></i> ${ev.location}</span><br>
                        <span style="font-size:10px; font-weight:bold; color:${color}; text-transform:uppercase; margin-top:4px; display:inline-block;">${ev.status}</span>
                        <br>
                        <button onclick="focusEvent(${ev.id})" class="mt-2 bg-blue-600 text-white text-[10px] px-3 py-1 rounded font-bold hover:bg-blue-700 transition w-full">LIHAT DETAIL</button>
                    </div>
                `);
        });

        function focusEvent(id) {
            document.querySelectorAll('.event-card').forEach(card => { card.classList.remove('highlight-card', 'hidden'); });
            const targetCard = document.getElementById('event-card-' + id);
            if(targetCard) {
                targetCard.classList.remove('opacity-50', 'hidden');
                targetCard.classList.add('highlight-card');
                targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                document.getElementById('btn-reset').classList.remove('hidden');
            }
        }
        function resetFilter() {
            document.querySelectorAll('.event-card').forEach(card => { card.classList.remove('highlight-card', 'hidden', 'opacity-50'); });
            document.getElementById('btn-reset').classList.add('hidden');
            filterEvents('all');
        }
        function filterEvents(type) {
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => { btn.classList.remove('bg-brand-green', 'text-black', 'shadow-lg'); btn.classList.add('bg-transparent', 'text-gray-400', 'border-gray-700'); });
            const activeBtn = document.getElementById('btn-filter-' + type);
            if(activeBtn) { activeBtn.classList.remove('bg-transparent', 'text-gray-400', 'border-gray-700'); activeBtn.classList.add('bg-brand-green', 'text-black', 'shadow-lg'); }
            const cards = document.querySelectorAll('.event-card');
            const now = new Date();
            cards.forEach(card => {
                const eventTimestamp = parseInt(card.getAttribute('data-date')) * 1000; 
                const eventDate = new Date(eventTimestamp);
                let show = false;
                if (type === 'all') show = true;
                else if (type === 'week') { const nextWeek = new Date(); nextWeek.setDate(now.getDate() + 7); if (eventDate >= now && eventDate <= nextWeek) show = true; }
                else if (type === 'month') { const nextMonth = new Date(now.getFullYear(), now.getMonth() + 1, 1); const monthAfterNext = new Date(now.getFullYear(), now.getMonth() + 2, 1); if (eventDate >= nextMonth && eventDate < monthAfterNext) show = true; }
                if (show) card.classList.remove('hidden'); else card.classList.add('hidden');
            });
            document.getElementById('btn-reset').classList.add('hidden');
        }
        function openRegisterModal(id, name) { document.getElementById('modalEventId').value = id; document.getElementById('modalEventName').innerText = name; document.getElementById('registerModal').classList.remove('hidden'); }
        function closeRegisterModal() { document.getElementById('registerModal').classList.add('hidden'); }
    </script>
</body>
</html>
