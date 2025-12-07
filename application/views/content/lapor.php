<!-- Leaflet & Swiper (Modern Carousel) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    /* Map Cursor */
    #peta { cursor: crosshair; }
    
    /* Before-After Slider di Modal */
    .ba-slider { position: relative; width: 100%; height: 400px; overflow: hidden; cursor: col-resize; border-radius: 12px; }
    .ba-slider img { width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; }
    .ba-before { z-index: 2; clip-path: inset(0 50% 0 0); width: 100%; } 
    .ba-handle { position: absolute; top: 0; bottom: 0; left: 50%; width: 4px; background: #fff; z-index: 10; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(0,0,0,0.5); }
    .ba-circle { width: 40px; height: 40px; background: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; border: 3px solid white; font-size: 14px; }

    /* Animasi Card */
    .report-card { transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
    .report-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px -10px rgba(16, 185, 129, 0.3); }
    
    /* Animasi Fade In */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
</style>

<!-- HEADER MAP (FULL WIDTH) -->
<div class="relative h-[60vh] w-full pt-20 group">
    <div id="peta" class="bg-gray-900 h-full w-full z-0"></div>
    
    <!-- Floating Info Panel -->
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
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        
        <!-- KOLOM KIRI: FORM LAPOR (Sticky) - Lebar 4/12 -->
        <div class="lg:col-span-4">
            <div class="sticky top-28">
                <div class="bg-brand-dark border border-gray-800 rounded-3xl p-6 md:p-8 shadow-2xl relative overflow-hidden">
                    <!-- Hiasan Background -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-brand-green/10 rounded-full blur-3xl -mr-10 -mt-10"></div>
                    
                    <h2 class="font-heading text-3xl font-bold uppercase text-white mb-2 relative z-10">Lapor <span class="text-brand-green">Sampah</span></h2>
                    <p class="text-sm text-gray-400 mb-6 relative z-10">Temukan, Foto, dan Laporkan. Kami akan menindaklanjutinya.</p>

                    <!-- Alert Messages -->
                    <?php if($this->session->flashdata('success')): ?>
                        <div class="mb-6 bg-green-900/30 border border-green-500/50 text-green-300 p-3 rounded-xl text-xs flex items-center gap-2 animate-pulse">
                            <i class="fas fa-check-circle text-lg"></i> <?= $this->session->flashdata('success'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="mb-6 bg-red-900/30 border border-red-500/50 text-red-300 p-3 rounded-xl text-xs flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle text-lg"></i> <?= $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>

                    <?= form_open_multipart('web/lapor_submit', ['class' => 'space-y-4 relative z-10']) ?>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="text-[10px] uppercase font-bold text-gray-500">Nama</label>
                                <input type="text" name="reporter_name" placeholder="Anonim" class="w-full bg-black/50 border border-gray-700 p-3 rounded-lg text-sm text-white focus:border-brand-green focus:ring-1 focus:ring-brand-green outline-none transition">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] uppercase font-bold text-gray-500">Kontak</label>
                                <input type="text" name="reporter_contact" placeholder="08xx.." class="w-full bg-black/50 border border-gray-700 p-3 rounded-lg text-sm text-white focus:border-brand-green focus:ring-1 focus:ring-brand-green outline-none transition">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] uppercase font-bold text-gray-500">Lokasi / Patokan</label>
                            <input type="text" name="location_address" placeholder="Contoh: Jembatan Merah..." class="w-full bg-black/50 border border-gray-700 p-3 rounded-lg text-sm text-white focus:border-brand-green focus:ring-1 focus:ring-brand-green outline-none transition" required>
                        </div>

                        <!-- GPS Input -->
                        <div class="bg-black/50 p-3 rounded-lg border border-gray-700 space-y-2">
                            <div class="flex justify-between items-center">
                                <label class="text-[10px] uppercase font-bold text-gray-500">Koordinat</label>
                                <button type="button" onclick="getLocation()" class="text-[10px] bg-white text-black px-3 py-1 rounded-md font-bold hover:bg-brand-green transition flex items-center gap-1 shadow-lg">
                                    <i class="fas fa-location-arrow"></i> GPS Saya
                                </button>
                            </div>
                            <div class="flex gap-2 font-mono text-xs text-brand-green">
                                <input type="text" id="lat" name="latitude" class="bg-transparent w-full outline-none" readonly placeholder="Lat..." required>
                                <input type="text" id="lng" name="longitude" class="bg-transparent w-full outline-none" readonly placeholder="Lng..." required>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] uppercase font-bold text-gray-500">Foto Bukti</label>
                            <div class="relative group cursor-pointer">
                                <input type="file" name="image_before" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" required>
                                <div class="bg-black/50 border border-dashed border-gray-600 rounded-lg p-3 text-center group-hover:border-brand-green transition">
                                    <span class="text-xs text-gray-400 group-hover:text-white"><i class="fas fa-camera mr-1"></i> Klik Upload Foto</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] uppercase font-bold text-gray-500">Keterangan</label>
                            <textarea name="description" class="w-full bg-black/50 border border-gray-700 p-3 rounded-lg text-sm text-white focus:border-brand-green focus:ring-1 focus:ring-brand-green outline-none transition h-20" placeholder="Deskripsi kondisi..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-brand-green text-black font-bold py-3 rounded-lg uppercase tracking-wider text-sm hover:bg-white hover:shadow-lg hover:shadow-brand-green/20 transition-all transform hover:-translate-y-1">
                            Kirim Laporan
                        </button>
                    <?= form_close() ?>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: GALERI MODERN (Grid Card) - Lebar 8/12 -->
        <div class="lg:col-span-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
                <div>
                    <span class="text-brand-green font-bold uppercase tracking-widest text-xs">Transformasi Nyata</span>
                    <h2 class="font-heading text-4xl font-bold uppercase text-white mt-1">Galeri Eksekusi</h2>
                </div>
                <!-- Filter Tabs JS Sort -->
                <div class="flex bg-brand-dark p-1 rounded-lg border border-gray-800">
                    <button onclick="sortGallery('latest')" id="btn-latest" class="px-4 py-1.5 text-xs font-bold bg-gray-800 text-white rounded shadow-sm hover:bg-gray-700 transition">Terbaru</button>
                    <button onclick="sortGallery('popular')" id="btn-popular" class="px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-white hover:bg-gray-800 rounded transition">Populer</button>
                </div>
            </div>

            <?php if(!empty($laporan_selesai)): ?>
                <!-- GRID CARD MODERN -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="gallery-grid">
                    <?php 
                        $count = 0;
                        foreach($laporan_selesai as $ls): 
                            $count++;
                            $hiddenClass = ($count > 6) ? 'hidden hidden-item' : ''; // Tampil 6 awal
                            
                            // Image Sources
                            $img_after = ($ls->image_after_url && $ls->image_after_url != 'default_after.jpg') 
                                ? base_url('uploads/reports/'.$ls->image_after_url) 
                                : "https://source.unsplash.com/400x300/?nature,clean&sig=".$ls->id;
                            
                            $img_before = ($ls->image_before_url && $ls->image_before_url != 'default.jpg')
                                ? base_url('uploads/reports/'.$ls->image_before_url)
                                : "https://source.unsplash.com/400x300/?trash,dirty&sig=".$ls->id;
                            
                            // DATA REAL DARI DATABASE
                            $views = isset($ls->views) ? $ls->views : 0; 
                            $timestamp = $ls->cleaned_at ? strtotime($ls->cleaned_at) : time();

                            // JSON Data untuk Modal
                            $modalData = htmlspecialchars(json_encode([
                                'id' => $ls->id, // Kirim ID buat update views
                                'before' => $img_before,
                                'after' => $img_after,
                                'loc' => $ls->location_address,
                                'date' => $ls->cleaned_at ? date('d M Y', strtotime($ls->cleaned_at)) : '-'
                            ]), ENT_QUOTES, 'UTF-8');
                    ?>
                        <!-- Card Item -->
                        <div class="report-card bg-brand-dark rounded-xl overflow-hidden border border-gray-800 group cursor-pointer <?= $hiddenClass ?>" 
                             onclick="openGalleryModal(<?= $modalData ?>)"
                             data-date="<?= $timestamp ?>" 
                             data-views="<?= $views ?>">
                             
                            <div class="relative h-48 overflow-hidden">
                                <!-- Badge -->
                                <div class="absolute top-3 right-3 z-10 bg-green-500 text-black text-[10px] font-bold px-2 py-1 rounded shadow-lg">
                                    SELESAI
                                </div>
                                <!-- View Count Overlay (REAL DATA) -->
                                <div class="absolute bottom-3 left-3 z-10 flex items-center gap-1 bg-black/60 backdrop-blur px-2 py-1 rounded text-[10px] text-white">
                                    <i class="fas fa-eye text-brand-green"></i> <span id="view-count-<?= $ls->id ?>"><?= number_format($views) ?></span>
                                </div>

                                <img src="<?= $img_after ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur text-white flex items-center justify-center border border-white/50">
                                        <i class="fas fa-expand-arrows-alt"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4">
                                <h4 class="font-bold text-white text-sm truncate uppercase mb-1"><?= $ls->location_address ?></h4>
                                <div class="flex justify-between items-center text-xs text-gray-500">
                                    <span><i class="fas fa-calendar-check mr-1 text-brand-green"></i> <?= $ls->cleaned_at ? date('d M Y', strtotime($ls->cleaned_at)) : '-' ?></span>
                                    <span class="text-brand-green font-bold text-[10px]">LIHAT DETAIL <i class="fas fa-arrow-right ml-1"></i></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Load More Button -->
                <?php if(count($laporan_selesai) > 6): ?>
                <div class="mt-10 text-center" id="load-more-btn">
                    <button onclick="showAllGallery()" class="group px-8 py-3 bg-brand-dark border border-gray-700 rounded-full text-white text-sm font-bold hover:bg-white hover:text-black transition shadow-lg">
                        Muat Lebih Banyak <i class="fas fa-chevron-down ml-2 group-hover:translate-y-1 transition-transform"></i>
                    </button>
                </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="text-center py-20 border-2 border-dashed border-gray-800 rounded-3xl bg-brand-dark/50">
                    <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-600 text-3xl">
                        <i class="fas fa-images"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Belum Ada Dokumentasi</h3>
                    <p class="text-sm text-gray-500 mt-2">Data aksi bersih-bersih akan muncul di sini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- MODAL BEFORE-AFTER (POP-UP KEREN) -->
<div id="galleryModal" class="fixed inset-0 bg-black/95 z-[9999] hidden flex items-center justify-center p-4 backdrop-blur-xl opacity-0 transition-opacity duration-300">
    <div class="w-full max-w-4xl bg-brand-dark rounded-2xl border border-gray-700 shadow-2xl overflow-hidden relative transform scale-95 transition-transform duration-300" id="modalContent">
        
        <!-- Header Modal -->
        <div class="p-6 border-b border-gray-800 flex justify-between items-center bg-black/30">
            <div>
                <h3 class="text-2xl font-heading font-bold text-white uppercase" id="modalTitle">Lokasi</h3>
                <p class="text-sm text-brand-green flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> Dibersihkan pada: <span id="modalDate" class="text-white"></span>
                </p>
            </div>
            <button onclick="closeGalleryModal()" class="w-10 h-10 rounded-full bg-gray-800 text-gray-400 hover:text-white hover:bg-red-500/20 hover:border-red-500 border border-transparent transition flex items-center justify-center">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Slider Content -->
        <div class="p-6 bg-black relative">
            <div class="ba-slider border-2 border-gray-800 shadow-2xl" 
                 onmousemove="geserSlider(event, this)" 
                 ontouchmove="geserSlider(event, this)">
                <img id="modalImgAfter" src="" alt="Sesudah" draggable="false">
                <div class="ba-before">
                    <img id="modalImgBefore" src="" alt="Sebelum" draggable="false">
                </div>
                
                <!-- Label Before/After -->
                <div class="absolute top-4 left-4 z-20 bg-black/60 text-white text-[10px] font-bold px-3 py-1 rounded backdrop-blur border border-white/10">BEFORE</div>
                <div class="absolute top-4 right-4 z-20 bg-brand-green/80 text-black text-[10px] font-bold px-3 py-1 rounded backdrop-blur border border-white/10">AFTER</div>

                <div class="ba-handle">
                    <div class="ba-circle"><i class="fas fa-arrows-left-right"></i></div>
                </div>
            </div>
            <p class="text-center text-xs text-gray-500 mt-4"><i class="fas fa-info-circle mr-1"></i> Geser garis putih untuk melihat perbandingan</p>
        </div>
    </div>
</div>

<script>
    // 1. Inisialisasi Peta
    const map = L.map('peta').setView([-6.2088, 106.8456], 11); 
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);

    let userMarker = null;

    // Fungsi Marker & Input
    function updateInputCoords(lat, lng) {
        document.getElementById('lat').value = lat.toFixed(6);
        document.getElementById('lng').value = lng.toFixed(6);
    }

    function placeMarker(lat, lng) {
        if (userMarker) {
            userMarker.setLatLng([lat, lng]);
        } else {
            userMarker = L.marker([lat, lng], { draggable: true }).addTo(map);
            userMarker.on('dragend', function(e) {
                const pos = e.target.getLatLng();
                updateInputCoords(pos.lat, pos.lng);
                map.panTo(pos); 
            });
            userMarker.bindPopup("<b>Lokasi Laporan</b><br>Geser untuk sesuaikan.").openPopup();
        }
        updateInputCoords(lat, lng);
        map.panTo([lat, lng]);
    }

    map.on('click', function(e) { placeMarker(e.latlng.lat, e.latlng.lng); });

    // Load Data Marker DB
    const db_laporan = <?= json_encode($semua_laporan ?? []) ?>;
    db_laporan.forEach(r => {
        let warna = r.status === 'pending' ? '#ef4444' : (r.status === 'in_progress' ? '#f59e0b' : '#10b981');
        const customIcon = L.divIcon({ html: `<div style="background-color: ${warna}; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 10px ${warna};"></div>`, className: 'custom-marker-icon' });
        L.marker([r.latitude, r.longitude], { icon: customIcon }).addTo(map)
            .bindPopup(`<div style="color:#333; font-family:sans-serif; min-width:150px;"><b style="text-transform:uppercase; font-size:11px;">${r.location_address}</b><br><span style="font-size:10px; font-weight:bold; color:${warna}; text-transform:uppercase;">${r.status.replace('_', ' ')}</span></div>`);
    });

    // Fungsi GPS
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                placeMarker(lat, lng); 
                map.setView([lat, lng], 15);
            }, () => { alert("Gagal mengambil lokasi. Pastikan GPS aktif."); });
        } else { alert("Browser tidak mendukung Geolocation."); }
    }

    // --- UPDATE LOGIC SORTING (BISA KLIK POPULER) ---
    function sortGallery(type) {
        const container = document.getElementById('gallery-grid');
        const items = Array.from(container.children);
        
        // Update Button Styles
        const btnLatest = document.getElementById('btn-latest');
        const btnPopular = document.getElementById('btn-popular');

        // Reset Styles
        btnLatest.className = "px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-white hover:bg-gray-800 rounded transition";
        btnPopular.className = "px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-white hover:bg-gray-800 rounded transition";

        if (type === 'latest') {
            btnLatest.className = "px-4 py-1.5 text-xs font-bold bg-gray-800 text-white rounded shadow-sm hover:bg-gray-700 transition";
            // Sort by Date (Descending)
            items.sort((a, b) => parseInt(b.getAttribute('data-date')) - parseInt(a.getAttribute('data-date')));
        } else {
            btnPopular.className = "px-4 py-1.5 text-xs font-bold bg-gray-800 text-white rounded shadow-sm hover:bg-gray-700 transition";
            // Sort by Views (Descending)
            items.sort((a, b) => parseInt(b.getAttribute('data-views')) - parseInt(a.getAttribute('data-views')));
        }

        // Re-render & Reset Visibility
        items.forEach(item => container.appendChild(item));
        
        const limit = 6;
        items.forEach((item, index) => {
            if (index < limit) {
                item.classList.remove('hidden', 'hidden-item');
                item.classList.add('animate-pulse'); 
                setTimeout(() => item.classList.remove('animate-pulse'), 500);
            } else {
                item.classList.add('hidden', 'hidden-item');
            }
        });
        
        const btnLoadMore = document.getElementById('load-more-btn');
        if (btnLoadMore) {
            btnLoadMore.style.display = (items.length > limit) ? 'block' : 'none';
        }
    }

    // --- UPDATE MODAL: NAMBAH VIEW COUNT (AJAX) ---
    function openGalleryModal(data) {
        document.getElementById('modalTitle').innerText = data.loc;
        document.getElementById('modalDate').innerText = data.date;
        document.getElementById('modalImgBefore').src = data.before;
        document.getElementById('modalImgAfter').src = data.after;
        
        // AJAX Call ke Controller buat nambah view count
        fetch('<?= base_url('web/add_view/') ?>' + data.id)
            .then(response => response.json())
            .then(res => {
                console.log('View added:', res);
                const viewBadge = document.getElementById('view-count-' + data.id);
                if(viewBadge) {
                    let current = parseInt(viewBadge.innerText.replace(/,/g, ''));
                    viewBadge.innerText = (current + 1).toLocaleString();
                    const card = viewBadge.closest('.report-card');
                    card.setAttribute('data-views', current + 1);
                }
            })
            .catch(err => console.error('Error adding view:', err));

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

    function geserSlider(e, element) {
        const rect = element.getBoundingClientRect();
        const x = (e.touches ? e.touches[0].clientX : e.clientX) - rect.left;
        let percent = Math.max(0, Math.min(100, (x / rect.width) * 100));
        element.querySelector('.ba-before').style.clipPath = `inset(0 ${100 - percent}% 0 0)`;
        element.querySelector('.ba-handle').style.left = `${percent}%`;
    }

    function showAllGallery() {
        document.querySelectorAll('.hidden-item').forEach(el => {
            el.classList.remove('hidden');
            el.classList.add('animate-fade-in-up');
        });
        document.getElementById('load-more-btn').style.display = 'none';
    }
</script>