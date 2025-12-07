<div class="relative h-[60vh] w-full pt-20">
    <div id="peta" class="bg-gray-900"></div>
    
    <!-- Legend Overlay -->
    <div class="absolute bottom-8 left-8 z-[500] bg-black/90 p-5 rounded-lg border border-gray-700 backdrop-blur-md shadow-2xl max-w-xs">
        <h3 class="font-heading text-lg uppercase mb-3 text-white border-b border-gray-700 pb-2">Status Area</h3>
        <div class="space-y-2">
            <div class="flex items-center gap-3 text-sm text-gray-300">
                <span class="w-3 h-3 rounded-full bg-red-500 shadow-[0_0_8px_red]"></span> Menunggu (Kotor)
            </div>
            <div class="flex items-center gap-3 text-sm text-gray-300">
                <span class="w-3 h-3 rounded-full bg-yellow-500 shadow-[0_0_8px_orange]"></span> Proses Pembersihan
            </div>
            <div class="flex items-center gap-3 text-sm text-gray-300">
                <span class="w-3 h-3 rounded-full bg-green-500 shadow-[0_0_8px_lime]"></span> Selesai (Bersih)
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-16 grid grid-cols-1 lg:grid-cols-2 gap-16">
    <!-- Form Report -->
    <div>
        <div class="mb-8">
            <span class="text-brand-green font-bold uppercase tracking-wider text-xs">Partisipasi Publik</span>
            <h2 class="font-heading text-4xl font-bold uppercase text-white mt-1">Lapor Titik Sampah</h2>
            <p class="text-gray-400 mt-4">Punya informasi lokasi penumpukan sampah liar? Bantu kami memetakan dan membersihkannya.</p>
            
            <?php if($this->session->flashdata('success')): ?>
                <div class="mt-4 bg-green-900/50 border border-green-500 text-green-200 p-4 rounded text-sm">
                    <?= $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>
        </div>

        <?= form_open_multipart('lapor/submit', ['class' => 'space-y-5 bg-[#121212] p-8 rounded-2xl border border-gray-800 shadow-xl']) ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Nama Pelapor</label>
                    <input type="text" name="reporter_name" placeholder="Nama Anda" class="w-full bg-black border border-gray-700 p-4 rounded focus:border-brand-green outline-none text-white transition-colors">
                </div>
                <div>
                    <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Kontak (WA)</label>
                    <input type="text" name="reporter_contact" placeholder="08xx-xxxx-xxxx" class="w-full bg-black border border-gray-700 p-4 rounded focus:border-brand-green outline-none text-white transition-colors">
                </div>
            </div>
            
            <div>
                <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Lokasi / Patokan</label>
                <input type="text" name="location_address" placeholder="Contoh: Pinggir Jembatan Merah..." class="w-full bg-black border border-gray-700 p-4 rounded focus:border-brand-green outline-none text-white transition-colors" required>
            </div>

            <div class="bg-black p-4 rounded border border-gray-700 flex items-center gap-4">
                <div class="flex-1">
                    <label class="block text-[10px] uppercase text-gray-500 font-bold">Koordinat GPS</label>
                    <div class="flex gap-2 text-sm text-brand-green font-mono">
                        <input type="text" id="lat" name="latitude" class="bg-transparent w-24 outline-none" readonly placeholder="Latitude" required>
                        <input type="text" id="lng" name="longitude" class="bg-transparent w-24 outline-none" readonly placeholder="Longitude" required>
                    </div>
                </div>
                <button type="button" onclick="getLocation()" class="bg-white text-black px-4 py-2 rounded font-bold text-xs uppercase hover:bg-gray-200 transition">
                    <i class="fas fa-crosshairs mr-1"></i> Ambil GPS
                </button>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-brand-red to-red-700 text-white font-bold py-4 rounded uppercase tracking-widest hover:shadow-lg transition-all">
                Kirim Laporan
            </button>
        <?= form_close() ?>
    </div>

    <!-- Before After Gallery -->
    <div class="flex flex-col justify-center">
        <div class="mb-8">
            <span class="text-brand-green font-bold uppercase tracking-wider text-xs">Hasil Kerja</span>
            <h2 class="font-heading text-4xl font-bold uppercase text-white mt-1">Galeri Eksekusi</h2>
        </div>
        
        <div class="space-y-10">
            <!-- Contoh Slider Statis untuk Demo -->
            <div>
                <div class="flex justify-between items-end mb-3">
                    <div>
                        <h4 class="font-bold uppercase text-white"><i class="fas fa-map-marker-alt text-brand-green mr-2"></i> Sungai Ciliwung Sektor 4</h4>
                        <p class="text-xs text-gray-500">Status: <span class="text-green-500">BERSIH</span></p>
                    </div>
                </div>
                <div class="ba-slider border border-gray-700 shadow-2xl" onmousemove="geserSlider(event, this)" ontouchmove="geserSlider(event, this)">
                    <img src="https://images.unsplash.com/photo-1618477461853-5f87b7852ecf?q=80&w=800" alt="Sesudah" draggable="false">
                    <div class="ba-before">
                        <img src="https://images.unsplash.com/photo-1621451537084-482c73073a0f?q=80&w=800&grayscale" alt="Sebelum" draggable="false">
                    </div>
                    <div class="ba-handle"><div class="ba-circle"><i class="fas fa-arrows-left-right text-xs"></i></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS Map Logic -->
<script>
    // Inisialisasi Peta
    const map = L.map('peta').setView([-6.200, 106.816], 12); 
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap',
        maxZoom: 19
    }).addTo(map);

    // Dummy Markers (Nanti diganti data dari Controller)
    const points = [
        {lat: -6.200, lng: 106.816, status: 'pending'},
        {lat: -6.220, lng: 106.850, status: 'resolved'}
    ];

    points.forEach(p => {
        let color = p.status === 'pending' ? '#ef4444' : '#10b981';
        const iconHtml = `<div style="background-color: ${color}; width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 15px ${color};"></div>`;
        const customIcon = L.divIcon({ html: iconHtml, className: 'custom-marker-icon' });
        L.marker([p.lat, p.lng], { icon: customIcon }).addTo(map);
    });

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                document.getElementById('lat').value = lat;
                document.getElementById('lng').value = lng;
                map.setView([lat, lng], 15);
                L.marker([lat, lng]).addTo(map).bindPopup("Lokasi Anda").openPopup();
            }, () => { alert("Gagal mengambil lokasi. Pastikan GPS aktif."); });
        } else { alert("Browser tidak mendukung Geolocation."); }
    }

    function geserSlider(e, element) {
        const rect = element.getBoundingClientRect();
        const x = (e.touches ? e.touches[0].clientX : e.clientX) - rect.left;
        let percent = Math.max(0, Math.min(100, (x / rect.width) * 100));
        element.querySelector('.ba-before').style.clipPath = `inset(0 ${100 - percent}% 0 0)`;
        element.querySelector('.ba-handle').style.left = `${percent}%`;
    }
</script>