<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Social Act Platform' ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Oswald:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Config Tailwind -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-black': '#0a0a0a',
                        'brand-dark': '#121212',
                        'brand-green': '#10b981', 
                        'brand-red': '#ef4444',
                        'brand-yellow': '#f59e0b',
                        'admin-dark': '#1f2937',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Oswald', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Custom CSS -->
    <style>
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0a0a0a; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #10b981; }
        .hero-overlay { background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(10,10,10,1) 100%); }
        
        /* Map & Slider Styles */
        #peta, #map { height: 100%; width: 100%; z-index: 1; min-height: 400px; }
        .ba-slider { position: relative; width: 100%; height: 300px; overflow: hidden; cursor: col-resize; border-radius: 8px; }
        .ba-slider img { width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; }
        .ba-before { z-index: 2; clip-path: inset(0 50% 0 0); width: 100%; }
        .ba-handle { position: absolute; top: 0; bottom: 0; left: 50%; width: 4px; background: #fff; z-index: 10; display: flex; align-items: center; justify-content: center; }
        .ba-circle { width: 30px; height: 30px; background: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; border: 2px solid white; }
    </style>
</head>
<body class="bg-brand-black text-white font-sans antialiased selection:bg-brand-green selection:text-black">

    <!-- NAVBAR MASTER -->
    <nav id="navbar" class="fixed w-full z-50 transition-all duration-300 py-4 px-6 md:px-12 flex justify-between items-center backdrop-blur-sm bg-black/70 border-b border-white/10">
        <a href="<?= base_url() ?>" class="flex items-center gap-2 cursor-pointer">
            <span class="font-heading text-xl tracking-wider font-bold uppercase">Social<span class="text-brand-green">Act</span></span>
        </a>
        
        <!-- BAGIAN MENU YANG ANDA MINTA -->
        <div class="hidden md:flex gap-6 text-sm font-semibold tracking-wide uppercase text-gray-300">

            <a href="<?= base_url('transparansi') ?>" class="hover:text-brand-green transition-colors <?= $this->uri->segment(1) == 'transparansi' ? 'text-brand-green' : '' ?>">Transparansi</a>
            <a href="<?= base_url('lapor') ?>" class="hover:text-brand-green transition-colors <?= $this->uri->segment(1) == 'lapor' ? 'text-brand-green' : '' ?>">Pengaduan</a>          
            <!-- Link Admin (Opsional, saya taruh di ujung kanan sebagai icon) -->
            <a href="<?= base_url('volunteer') ?>" class="hover:text-brand-green transition-colors <?= $this->uri->segment(1) == 'volunteer' ? 'text-brand-green' : '' ?>">Volunteer</a>

            <!-- LOGIC TOMBOL LOGIN / DASHBOARD -->
            <?php if($this->session->userdata('logged_in')): ?>
                <!-- Jika Sudah Login: Tampilkan Tombol ke Dashboard & Logout -->
                <div class="ml-4 flex items-center gap-2">
                    <a href="<?= base_url('admin') ?>" class="bg-brand-green text-black px-3 py-1 rounded text-xs font-bold hover:bg-white transition-all">
                        DASHBOARD
                    </a>
                    <a href="<?= base_url('logout') ?>" class="border border-red-500 text-red-500 px-3 py-1 rounded text-xs font-bold hover:bg-red-500 hover:text-white transition-all">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            <?php else: ?>
                <!-- Jika Belum Login (Guest): Tampilkan Tombol Gembok -->
                <a href="<?= base_url('login') ?>" class="ml-4 border border-gray-600 px-3 py-1 rounded hover:bg-brand-green hover:text-black hover:border-brand-green transition-all" title="Admin Login">
                    <i class="fas fa-lock"></i>
                </a>
            <?php endif; ?>
        </div>
        
        <button class="md:hidden text-2xl"><i class="fas fa-bars"></i></button>
    </nav>

    <!-- CONTENT INJECTION -->
    <main>
        <?php $this->load->view($content); ?>
    </main>

    <!-- FOOTER -->
    <footer class="border-t border-gray-800 bg-brand-dark py-8 mt-12">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-gray-500 text-sm">&copy; <?= date('Y') ?> Social Act Platform. Clean Action Movement.</p>
        </div>
    </footer>

    <!-- Global Scripts -->
    <script>
        // Efek Navbar berubah warna saat scroll
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 50) {
                nav.classList.add('bg-black', 'shadow-lg');
                nav.classList.remove('bg-black/70');
            } else {
                nav.classList.remove('bg-black', 'shadow-lg');
                nav.classList.add('bg-black/70');
            }
        });
    </script>
</body>
</html>
