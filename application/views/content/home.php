<!-- Hero Section -->
<section id="home" class="relative min-h-[100vh] flex items-center justify-center px-6 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1618477461853-5f87b7852ecf?q=80&w=2574&auto=format&fit=crop" class="w-full h-full object-cover opacity-60">
    </div>
    <div class="absolute inset-0 hero-overlay z-10"></div>
    <div class="relative z-20 text-center max-w-4xl mx-auto mt-16">
        <p class="text-brand-green font-bold tracking-[0.2em] mb-4 uppercase">Social Impact Database</p>
        <h1 class="font-heading text-5xl md:text-7xl font-bold uppercase leading-tight mb-6">
            Transparansi <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-green to-teal-500">Nyata</span><br>
            Aksi <span class="text-white">Terdata</span>
        </h1>
        <div class="flex gap-4 justify-center">
            <a href="<?= base_url('lapor') ?>" class="px-8 py-3 bg-brand-green text-black font-bold uppercase hover:bg-white transition-all">Lapor (GIS)</a>
            <a href="<?= base_url('transparansi') ?>" class="px-8 py-3 border border-white text-white font-bold uppercase hover:bg-white/10 transition-all">Cek Data</a>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-20 px-6 bg-brand-black">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="font-heading text-3xl font-bold uppercase text-white mb-6">Misi Kami</h2>
        <p class="text-gray-400 leading-relaxed">
            Membangun platform terintegrasi yang menghubungkan donatur, relawan, dan pemerintah dalam satu ekosistem transparansi. 
            Kami menggunakan data real-time untuk memastikan setiap bantuan tepat sasaran dan setiap sampah terangkut.
        </p>
    </div>
</section>