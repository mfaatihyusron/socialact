<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor Sampah GIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" xintegrity="sha512-Zcn6bjR/8RZbLEpLIeOwNtzREBAJnUKESxces60Mpoj+2okopSA8GVYO5AvBFI5lsFScotJ1+rsR606FCPVLIg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js" xintegrity="sha512-BwHfrr4c9kmRkLw6iXFdzcdWV/PGkVgiIyIWLLlTSXzWQzxuSg4DiQUCpauz/EWjgk5TYQqX/nyHgjlPEEZTpw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: { colors: { 'brand-black': '#0a0a0a', 'brand-green': '#10b981', 'brand-red': '#ef4444' }, fontFamily: { heading: ['Oswald', 'sans-serif'] } }
            }
        }
    </script>
    <style>
        #peta { height: 100%; width: 100%; z-index: 1; }
        .ba-slider { position: relative; width: 100%; height: 300px; overflow: hidden; cursor: col-resize; border-radius: 8px; }
        .ba-slider img { width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; }
        .ba-before { z-index: 2; clip-path: inset(0 50% 0 0); width: 100%; }
        .ba-handle { position: absolute; top: 0; bottom: 0; left: 50%; width: 4px; background: #fff; z-index: 10; display: flex; align-items: center; justify-content: center; }
        .ba-circle { width: 30px; height: 30px; background: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; border: 2px solid white; }
    </style>
</head>
<body class="bg-brand-black text-white font-sans">

    <!-- Navbar -->
    <nav class="fixed w-full z-40 p-6 px-6 md:px-12 bg-black/80 backdrop-blur border-b border-gray-800 flex justify-between items-center">
        <a href="<?= base_url('transparansi') ?>" class="flex items-center gap-2 cursor-pointer hover:text-brand-green transition">
            <i class="fas fa-arrow-left text-brand-green"></i>
            <span class="font-heading font-bold uppercase">Kembali ke Transparansi</span>
        </a>
        <button class="bg-brand-red px-6 py-2 rounded font-bold uppercase text-xs hover:bg-red-600 transition shadow-[0_0_15px_rgba(239,68,68,0.4)]">
            Darurat
        </button>
    </nav>

    <!-- Map Full Width -->
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
                <p class="text-gray-400 mt-4">Punya informasi lokasi penumpukan sampah liar? Bantu kami memetakan dan membersihkannya. Identitas pelapor aman.</p>
                
                <!-- Flashdata Notifikasi -->
                <?php if($this->session->flashdata('success')): ?>
                    <div class="mt-4 bg-green-900/50 border border-green-500 text-green-200 p-4 rounded text-sm">
                        <?= $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Menggunakan form_open_multipart untuk upload gambar -->
            <?= form_open_multipart('lapor/submit', ['class' => 'space-y-5 bg-[#121212] p-8 rounded-2xl border border-gray-800 shadow-xl']) ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Nama Pelapor</label>
                        <input type="text" name="reporter_name" placeholder="Hamba Allah" class="w-full bg-black border border-gray-700 p-4 rounded focus:border-brand-green outline-none text-white transition-colors">
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
                            <!-- Input Hidden untuk dikirim ke DB -->
                            <input type="text" id="lat" name="latitude" class="bg-transparent w-24 outline-none" readonly placeholder="Latitude" required>
                            <input type="text" id="lng" name="longitude" class="bg-transparent w-24 outline-none" readonly placeholder="Longitude" required>
                        </div>
                    </div>
                    <button type="button" onclick="getLocation()" class="bg-white text-black px-4 py-2 rounded font-bold text-xs uppercase hover:bg-gray-200 transition">
                        <i class="fas fa-crosshairs mr-1"></i> Ambil GPS
                    </button>
                </div>

                <div>
                    <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Unggah Foto (Kondisi Awal)</label>
                    <div class="relative border-2 border-dashed border-gray-700 rounded-lg p-8 text-center hover:bg-gray-900 transition-colors group">
                        <input type="file" name="image_before" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-500 group-hover:text-brand-green mb-2 transition-colors"></i>
                        <p class="text-xs text-gray-400">Klik area ini untuk pilih foto</p>
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Deskripsi Tambahan</label>
                    <textarea name="description" class="w-full bg-black border border-gray-700 p-4 rounded h-24 focus:border-brand-green outline-none text-white transition-colors"></textarea>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-brand-red to-red-700 text-white font-bold py-4 rounded uppercase tracking-widest hover:shadow-lg hover:shadow-red-900/40 transition-all transform hover:-translate-y-1">
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
                <?php if(!empty($laporan_selesai)): ?>
                    <?php foreach($laporan_selesai as $ls): ?>
                    <div>
                        <div class="flex justify-between items-end mb-3">
                            <div>
                                <h4 class="font-bold uppercase text-white"><i class="fas fa-map-marker-alt text-brand-green mr-2"></i> <?= $ls->location_address ?></h4>
                                <p class="text-xs text-gray-500">Dibersihkan pada: <?= $ls->cleaned_at ?></p>
                            </div>
                        </div>
                        <div class="ba-slider border border-gray-700 shadow-2xl" onmousemove="geserSlider(event, this)" ontouchmove="geserSlider(event, this)">
                            <img src="<?= base_url($ls->image_after_url) ?>" alt="Sesudah" draggable="false">
                            <div class="ba-before">
                                <img src="<?= base_url($ls->image_before_url) ?>" alt="Sebelum" draggable="false">
                            </div>
                            <div class="ba-handle"><div class="ba-circle"><i class="fas fa-arrows-left-right text-xs"></i></div></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500">Belum ada data pembersihan yang selesai.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Data marker dari Controller (PHP -> JSON -> JS)
        const db_laporan = <?= json_encode($semua_laporan) ?>;
        
        // Init Map
        const map = L.map('peta').setView([-6.200, 106.816], 10); // Default Jakarta
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap',
            maxZoom: 19
        }).addTo(map);

        // Render Marker
        db_laporan.forEach(r => {
            let warna = r.status === 'pending' ? '#ef4444' : (r.status === 'in_progress' ? '#f59e0b' : '#10b981');
            const iconHtml = `<div style="background-color: ${warna}; width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 15px ${warna};"></div>`;
            const customIcon = L.divIcon({ html: iconHtml, className: 'custom-marker-icon' });
            
            L.marker([r.latitude, r.longitude], { icon: customIcon }).addTo(map)
                .bindPopup(`<div style="color:black; font-family:sans-serif">
                    <b style="text-transform:uppercase">${r.location_address}</b><br>
                    <span style="font-size:10px; font-weight:bold; color:${warna}">${r.status.toUpperCase()}</span>
                </div>`);
        });

        // Fungsi GPS Browser
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    document.getElementById('lat').value = lat;
                    document.getElementById('lng').value = lng;
                    
                    // Pindahkan map ke lokasi user
                    map.setView([lat, lng], 15);
                    L.marker([lat, lng]).addTo(map).bindPopup("Lokasi Anda").openPopup();
                    
                }, () => { alert("Gagal mengambil lokasi. Pastikan GPS aktif."); });
            } else { alert("Browser tidak mendukung Geolocation."); }
        }

        // Logic Slider Before/After
        function geserSlider(e, element) {
            const rect = element.getBoundingClientRect();
            const x = (e.touches ? e.touches[0].clientX : e.clientX) - rect.left;
            let percent = Math.max(0, Math.min(100, (x / rect.width) * 100));
            element.querySelector('.ba-before').style.clipPath = `inset(0 ${100 - percent}% 0 0)`;
            element.querySelector('.ba-handle').style.left = `${percent}%`;
        }
    </script>
</body>
</html>
