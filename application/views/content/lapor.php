<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    /* Map Cursor */
    #peta { cursor: crosshair; }
    
    /* --- PERBAIKAN CSS MODAL BEFORE-AFTER --- */
    .ba-container { 
        position: relative; 
        width: 100%; 
        
        /* UBAH DI SINI: */
        /* Jangan gunakan height fix pixel yang besar. Gunakan max-height atau vh */
        height: 50vh;           /* Tinggi menyesuaikan 50% layar user */
        min-height: 300px;      /* Minimal tinggi agar tidak kekecilan di HP */
        max-height: 500px;      /* Maksimal tinggi agar tidak kebesaran di Laptop */
        
        overflow: hidden; 
        border-radius: 12px; 
        background-color: #000; /* Background hitam murni agar sisa ruang terlihat rapi */
        border: 1px solid #333;
    }
    
    .ba-container img { 
        width: 100% !important; 
        height: 100% !important; 
        
        /* UBAH DI SINI: */
        /* 'contain' memastikan SELURUH gambar terlihat, tidak ada yang terpotong. */
        /* Sisa ruang akan berwarna hitam (letterbox) */
        object-fit: contain;  
        
        position: absolute; 
        top: 0; 
        left: 0; 
        transition: opacity 0.4s ease-in-out; 
        opacity: 0; 
        z-index: 1;
    }
    
    /* Class untuk menampilkan gambar aktif */
    .ba-container img.active-view { 
        opacity: 1 !important; 
        z-index: 10;
        display: block !important;
    }

    /* Tombol Navigasi Modal */
    .ba-buttons { display: flex; gap: 8px; margin-top: 16px; justify-content: center; }
    .ba-btn { 
        padding: 8px 24px; 
        border: none; 
        border-radius: 8px; 
        font-size: 12px; 
        font-weight: bold; 
        cursor: pointer; 
        transition: all 0.2s ease; 
        letter-spacing: 1px;
    }
    .ba-btn.active { background: #10b981; color: white; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4); }
    .ba-btn:not(.active) { background: #374151; color: #9ca3af; }
    .ba-btn:hover { transform: translateY(-2px); }

    /* Animasi Card */
    .report-card { transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
    .report-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px -10px rgba(16, 185, 129, 0.3); }
    
    /* Animasi Fade In */
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
</style>

<div class="relative h-[60vh] w-full pt-20 group mb-16 z-0">
    <div id="peta" class="bg-gray-900 h-full w-full z-0"></div>
    
    <div class="absolute top-28 left-6 md:left-12 z-[400] bg-black/80 backdrop-blur-md p-6 rounded-2xl border border-white/10 shadow-2xl max-w-sm transform transition-all hover:scale-105 duration-300">
        <h3 class="font-heading text-xl uppercase mb-3 text-white flex items-center gap-2">
            <i class="fas fa-map-marked-alt text-brand-green"></i> Peta Sebaran
        </h3>
        <p class="text-xs text-gray-400 mb-4 leading-relaxed">
            Pantau titik tumpukan sampah yang dilaporkan warga. Klik peta untuk menandai lokasi baru secara manual.
        </p>
        <div class="flex gap-2">
            <span class="px-2 py-1 rounded bg-red-500/20 text-red-400 text-[10px] font-bold border border-red-500/50">Menunggu</span>
            <span class="px-2 py-1 rounded bg-yellow-500/20 text-yellow-400 text-[10px] font-bold border border-yellow-500/50">Proses</span>
            <span class="px-2 py-1 rounded bg-green-500/20 text-green-400 text-[10px] font-bold border border-green-500/50">Selesai</span>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
        
        <div class="lg:col-span-4">
            <div class="sticky top-28">
                <div class="bg-brand-dark border border-gray-800 rounded-3xl p-6 md:p-8 shadow-2xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-brand-green/10 rounded-full blur-3xl -mr-10 -mt-10"></div>
                    <h2 class="font-heading text-3xl font-bold uppercase text-white mb-2 relative z-10">Lapor <span class="text-brand-green">Sampah</span></h2>
                    <p class="text-sm text-gray-400 mb-6 relative z-10">Temukan, Foto, dan Laporkan. Kami akan menindaklanjutinya.</p>

                    <?php if($this->session->flashdata('success')): ?>
                        <div class="mb-6 bg-green-900/30 border border-green-500/50 text-green-300 p-3 rounded-xl text-xs flex items-center gap-2 animate-pulse"><i class="fas fa-check-circle text-lg"></i> <?= $this->session->flashdata('success'); ?></div>
                    <?php endif; ?>
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="mb-6 bg-red-900/30 border border-red-500/50 text-red-300 p-3 rounded-xl text-xs flex items-center gap-2"><i class="fas fa-exclamation-triangle text-lg"></i> <?= $this->session->flashdata('error'); ?></div>
                    <?php endif; ?>

                    <?= form_open_multipart('web/submit_laporan', ['class' => 'space-y-4 relative z-10']) ?>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1"><label class="text-[10px] uppercase font-bold text-gray-500">Nama</label><input type="text" name="reporter_name" placeholder="Anonim" class="w-full bg-black/50 border border-gray-700 p-3 rounded-lg text-sm text-white focus:border-brand-green outline-none transition"></div>
                            <div class="space-y-1"><label class="text-[10px] uppercase font-bold text-gray-500">Kontak</label><input type="text" name="reporter_contact" placeholder="08xx.." class="w-full bg-black/50 border border-gray-700 p-3 rounded-lg text-sm text-white focus:border-brand-green outline-none transition"></div>
                        </div>
                        <div class="space-y-1"><label class="text-[10px] uppercase font-bold text-gray-500">Lokasi / Patokan</label><input type="text" name="location_address" placeholder="Contoh: Jembatan Merah..." class="w-full bg-black/50 border border-gray-700 p-3 rounded-lg text-sm text-white focus:border-brand-green outline-none transition" required></div>
                        <div class="bg-black/50 p-3 rounded-lg border border-gray-700 space-y-2">
                            <div class="flex justify-between items-center"><label class="text-[10px] uppercase font-bold text-gray-500">Koordinat</label><button type="button" onclick="getLocation()" class="text-[10px] bg-white text-black px-3 py-1 rounded-md font-bold hover:bg-brand-green transition flex items-center gap-1 shadow-lg"><i class="fas fa-location-arrow"></i> GPS Saya</button></div>
                            <div class="flex gap-2 font-mono text-xs text-brand-green"><input type="text" id="lat" name="latitude" class="bg-transparent w-full outline-none" readonly placeholder="Lat..." required><input type="text" id="lng" name="longitude" class="bg-transparent w-full outline-none" readonly placeholder="Lng..." required></div>
                        </div>
                        <div class="space-y-1"><label class="text-[10px] uppercase font-bold text-gray-500">Foto Bukti</label><div class="relative group cursor-pointer"><input type="file" name="image_before" id="image_before" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" required onchange="previewImage(this)"><div class="bg-black/50 border border-dashed border-gray-600 rounded-lg p-3 text-center group-hover:border-brand-green transition"><span class="text-xs text-gray-400 group-hover:text-white"><i class="fas fa-camera mr-1"></i> Klik Upload Foto</span></div></div><div id="image-preview" class="mt-2 hidden"><img id="preview-img" class="w-full h-32 object-cover rounded-lg border border-gray-600" alt="Preview"></div></div>
                        <div class="space-y-1"><label class="text-[10px] uppercase font-bold text-gray-500">Keterangan</label><textarea name="description" class="w-full bg-black/50 border border-gray-700 p-3 rounded-lg text-sm text-white focus:border-brand-green outline-none transition h-20" placeholder="Deskripsi kondisi..."></textarea></div>
                        <button type="submit" class="w-full bg-brand-green text-black font-bold py-3 rounded-lg uppercase tracking-wider text-sm hover:bg-white transition-all transform hover:-translate-y-1">Kirim Laporan</button>
                    <?= form_close() ?>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
                <div><span class="text-brand-green font-bold uppercase tracking-widest text-xs">Transformasi Nyata</span><h2 class="font-heading text-4xl font-bold uppercase text-white mt-1">Galeri Eksekusi</h2></div>
                <div class="flex bg-brand-dark p-1 rounded-lg border border-gray-800">
                    <button onclick="sortGallery('latest')" id="btn-latest" class="px-4 py-1.5 text-xs font-bold bg-gray-800 text-white rounded shadow-sm hover:bg-gray-700 transition">Terbaru</button>
                    <button onclick="sortGallery('popular')" id="btn-popular" class="px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-white hover:bg-gray-800 rounded transition">Populer</button>
                </div>
            </div>

            <?php if(!empty($laporan_selesai)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="gallery-grid">
                    <?php 
                        $count = 0;
                        foreach($laporan_selesai as $ls): 
                            $count++;
                            $hiddenClass = ($count > 6) ? 'hidden hidden-item' : ''; 
                            $img_after = ($ls->image_after_url && $ls->image_after_url != 'default_after.jpg') ? base_url('uploads/reports/'.$ls->image_after_url) : "https://source.unsplash.com/400x300/?nature,clean&sig=".$ls->id;
                            $img_before = ($ls->image_before_url && $ls->image_before_url != 'default.jpg') ? base_url('uploads/reports/'.$ls->image_before_url) : "https://source.unsplash.com/400x300/?trash,dirty&sig=".$ls->id;
                            $views = isset($ls->views) ? $ls->views : 0; 
                            $timestamp = $ls->cleaned_at ? strtotime($ls->cleaned_at) : time();
                            // Data JSON untuk Modal
                            $modalData = htmlspecialchars(json_encode(['id' => $ls->id, 'before' => $img_before, 'after' => $img_after, 'loc' => $ls->location_address, 'date' => $ls->cleaned_at ? date('d M Y', strtotime($ls->cleaned_at)) : '-']), ENT_QUOTES, 'UTF-8');
                    ?>
                        <div class="report-card bg-brand-dark rounded-xl overflow-hidden border border-gray-800 group cursor-pointer <?= $hiddenClass ?>" onclick="openGalleryModal(<?= $modalData ?>)" data-date="<?= $timestamp ?>" data-views="<?= $views ?>">
                            <div class="relative h-48 overflow-hidden">
                                <div class="absolute top-3 right-3 z-10 bg-green-500 text-black text-[10px] font-bold px-2 py-1 rounded shadow-lg">SELESAI</div>
                                <div class="absolute bottom-3 left-3 z-10 flex items-center gap-1 bg-black/60 backdrop-blur px-2 py-1 rounded text-[10px] text-white"><i class="fas fa-eye text-brand-green"></i> <span id="view-count-<?= $ls->id ?>"><?= number_format($views) ?></span></div>
                                <img src="<?= $img_after ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            </div>
                            <div class="p-4">
                                <h4 class="font-bold text-white text-sm truncate uppercase mb-1"><?= $ls->location_address ?></h4>
                                <div class="flex justify-between items-center text-xs text-gray-500"><span><i class="fas fa-calendar-check mr-1 text-brand-green"></i> <?= $ls->cleaned_at ? date('d M Y', strtotime($ls->cleaned_at)) : '-' ?></span><span class="text-brand-green font-bold text-[10px]">LIHAT DETAIL <i class="fas fa-arrow-right ml-1"></i></span></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if(count($laporan_selesai) > 6): ?>
                <div class="mt-10 text-center" id="load-more-btn"><button onclick="showAllGallery()" class="group px-8 py-3 bg-brand-dark border border-gray-700 rounded-full text-white text-sm font-bold hover:bg-white hover:text-black transition shadow-lg">Muat Lebih Banyak <i class="fas fa-chevron-down ml-2 group-hover:translate-y-1 transition-transform"></i></button></div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-20 border-2 border-dashed border-gray-800 rounded-3xl bg-brand-dark/50"><i class="fas fa-camera text-gray-600 text-3xl mb-4"></i><h3 class="text-xl font-bold text-white">Belum Ada Dokumentasi</h3></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="galleryModal" class="fixed inset-0 bg-black/95 z-[9999] hidden flex items-center justify-center p-4 backdrop-blur-xl opacity-0 transition-opacity duration-300">
    <div class="w-full max-w-4xl bg-brand-dark rounded-2xl border border-gray-700 shadow-2xl overflow-hidden relative transform scale-95 transition-transform duration-300" id="modalContent">
        <div class="p-6 border-b border-gray-800 flex justify-between items-center bg-black/30">
            <div>
                <h3 class="text-2xl font-heading font-bold text-white uppercase" id="modalTitle"></h3>
                <p class="text-sm text-brand-green flex items-center gap-2"><i class="fas fa-check-circle"></i> Dibersihkan pada: <span id="modalDate" class="text-white"></span></p>
            </div>
            <button onclick="closeGalleryModal()" class="w-10 h-10 rounded-full bg-gray-800 text-gray-400 hover:text-white transition flex items-center justify-center"><i class="fas fa-times text-lg"></i></button>
        </div>
        
        <div class="p-6 bg-black relative">
            <div class="ba-container shadow-2xl">
                <img id="modalImgBefore" src="" alt="Sebelum" class="object-cover">
                <img id="modalImgAfter" src="" alt="Sesudah" class="object-cover">
            </div>
            
            <div class="ba-buttons">
                <button id="btn-before" class="ba-btn" onclick="switchToBefore()">KONDISI AWAL</button>
                <button id="btn-after" class="ba-btn active" onclick="switchToAfter()">SETELAH DIBERSIHKAN</button>
            </div>
            <p class="text-center text-xs text-gray-500 mt-4"><i class="fas fa-info-circle mr-1"></i> Klik tombol di atas untuk melihat perbandingan</p>
        </div>
    </div>
</div>

<script>
    // 1. INISIALISASI MAP
    const map = L.map('peta').setView([-6.2088, 106.8456], 11); 
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);

    let userMarker = null;
    function updateInputCoords(lat, lng) { document.getElementById('lat').value = lat.toFixed(6); document.getElementById('lng').value = lng.toFixed(6); }
    function placeMarker(lat, lng) { 
        if (userMarker) { userMarker.setLatLng([lat, lng]); } else { userMarker = L.marker([lat, lng], { draggable: true }).addTo(map); userMarker.on('dragend', function(e) { const pos = e.target.getLatLng(); updateInputCoords(pos.lat, pos.lng); map.panTo(pos); }); userMarker.bindPopup("<b>Lokasi Laporan</b><br>Geser untuk sesuaikan.").openPopup(); } 
        updateInputCoords(lat, lng); 
        map.panTo([lat, lng]); 
    }
    map.on('click', function(e) { placeMarker(e.latlng.lat, e.latlng.lng); });
    function getLocation() { if (navigator.geolocation) { navigator.geolocation.getCurrentPosition((position) => { const lat = position.coords.latitude; const lng = position.coords.longitude; placeMarker(lat, lng); map.setView([lat, lng], 15); }, () => { alert("Gagal mengambil lokasi."); }); } else { alert("Browser tidak mendukung Geolocation."); } }

    const db_laporan = <?= json_encode($semua_laporan ?? []) ?>;
    db_laporan.forEach(r => { 
        let warna = r.status === 'pending' ? '#ef4444' : (r.status === 'in_progress' ? '#f59e0b' : '#10b981'); 
        const customIcon = L.divIcon({ html: `<div style="background-color: ${warna}; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 10px ${warna};"></div>`, className: 'custom-marker-icon' }); 
        L.marker([r.latitude, r.longitude], { icon: customIcon }).addTo(map).bindPopup(`<div style="color:#333; font-family:sans-serif; min-width:150px;"><b style="text-transform:uppercase; font-size:11px;">${r.location_address}</b><br><span style="font-size:10px; font-weight:bold; color:${warna}; text-transform:uppercase;">${r.status.replace('_', ' ')}</span></div>`); 
    });

    // 2. FUNGSI GAMBAR BEFORE-AFTER (LOGIKA BARU)
    function switchToBefore() {
        document.getElementById('modalImgBefore').classList.add('active-view');
        document.getElementById('modalImgAfter').classList.remove('active-view');
        
        document.getElementById('btn-before').classList.add('active');
        document.getElementById('btn-after').classList.remove('active');
    }

    function switchToAfter() {
        document.getElementById('modalImgAfter').classList.add('active-view');
        document.getElementById('modalImgBefore').classList.remove('active-view');
        
        document.getElementById('btn-after').classList.add('active');
        document.getElementById('btn-before').classList.remove('active');
    }

    // 3. FUNGSI MODAL
    function openGalleryModal(data) {
        document.getElementById('modalTitle').innerText = data.loc;
        document.getElementById('modalDate').innerText = data.date;
        document.getElementById('modalImgBefore').src = data.before;
        document.getElementById('modalImgAfter').src = data.after;

        // Reset ke After setiap kali dibuka
        switchToAfter();

        // Counter Views
        fetch('<?= base_url('web/add_view/') ?>' + data.id).then(r=>r.json()).then(res=>{ const v=document.getElementById('view-count-'+data.id); if(v){let c=parseInt(v.innerText.replace(/,/g,''))+1;v.innerText=c.toLocaleString();v.closest('.report-card').setAttribute('data-views',c);} });

        const modal = document.getElementById('galleryModal');
        const content = document.getElementById('modalContent');
        
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.remove('opacity-0'); content.classList.remove('scale-95'); content.classList.add('scale-100'); }, 10);
    }

    function closeGalleryModal() { 
        const modal = document.getElementById('galleryModal'); 
        const content = document.getElementById('modalContent'); 
        modal.classList.add('opacity-0'); content.classList.remove('scale-100'); content.classList.add('scale-95'); 
        setTimeout(() => { modal.classList.add('hidden'); }, 300); 
    }

    // 4. PREVIEW IMAGE UPLOAD
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (file.type.startsWith('image/')) {
                const url = URL.createObjectURL(file);
                previewImg.src = url;
                preview.classList.remove('hidden');
            }
        }
    }

    // 5. SORTING & LOAD MORE
    function sortGallery(type) {
        const container = document.getElementById('gallery-grid');
        const items = Array.from(container.children);
        const btnLatest = document.getElementById('btn-latest');
        const btnPopular = document.getElementById('btn-popular');

        if (type === 'latest') {
            btnLatest.classList.replace('text-gray-500', 'text-white'); btnLatest.classList.add('bg-gray-800');
            btnPopular.classList.replace('text-white', 'text-gray-500'); btnPopular.classList.remove('bg-gray-800');
            items.sort((a, b) => parseInt(b.getAttribute('data-date')) - parseInt(a.getAttribute('data-date')));
        } else {
            btnPopular.classList.replace('text-gray-500', 'text-white'); btnPopular.classList.add('bg-gray-800');
            btnLatest.classList.replace('text-white', 'text-gray-500'); btnLatest.classList.remove('bg-gray-800');
            items.sort((a, b) => parseInt(b.getAttribute('data-views')) - parseInt(a.getAttribute('data-views')));
        }
        
        items.forEach(item => container.appendChild(item));
        
        // Reset Visibility
        const limit = 6;
        items.forEach((item, index) => {
            if (index < limit) { item.classList.remove('hidden', 'hidden-item'); item.classList.add('animate-fade-in-up'); } 
            else { item.classList.add('hidden', 'hidden-item'); item.classList.remove('animate-fade-in-up'); }
        });
        const btnLoadMore = document.getElementById('load-more-btn');
        if(btnLoadMore) btnLoadMore.style.display = (items.length > limit) ? 'block' : 'none';
    }

    function showAllGallery() {
        document.querySelectorAll('.hidden-item').forEach(el => { el.classList.remove('hidden'); el.classList.add('animate-fade-in-up'); });
        document.getElementById('load-more-btn').style.display = 'none';
    }
</script>