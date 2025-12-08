<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { 'brand-black': '#0a0a0a', 'brand-green': '#10b981', 'admin-dark': '#1f2937' },
                    fontFamily: { heading: ['Oswald', 'sans-serif'] }
                }
            }
        }
    </script>
</head>
<body class="bg-brand-black text-gray-300 font-sans flex h-screen overflow-hidden">

    <!-- SIDEBAR DINAMIS -->
    <aside class="w-64 bg-[#111827] border-r border-gray-800 flex flex-col hidden md:flex">
        <div class="p-6 border-b border-gray-800 flex items-center gap-3">
            <div class="w-8 h-8 bg-brand-green text-black flex items-center justify-center font-bold rounded">A</div>
            <div>
                <span class="block text-white font-bold uppercase text-sm">Panel Admin</span>
                <span class="block text-xs text-brand-green uppercase"><?= $this->session->userdata('role') ?></span>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-2">
            <!-- MENU UNTUK SUPER ADMIN -->
            <?php if($this->session->userdata('role') == 'super_admin'): ?>
                <a href="<?= base_url('admin/super') ?>" class="flex items-center gap-3 px-4 py-3 rounded text-sm hover:bg-gray-800 <?= $this->uri->segment(2) == 'super' ? 'bg-gray-800 text-brand-green' : '' ?>">
                    <i class="fas fa-crown w-5"></i> Super Control
                </a>
            <?php endif; ?>

            <!-- MENU UNTUK FINANCE & SUPER -->
            <?php if(in_array($this->session->userdata('role'), ['finance', 'super_admin'])): ?>
                <a href="<?= base_url('finance') ?>" class="flex items-center gap-3 px-4 py-3 rounded text-sm hover:bg-gray-800 <?= $this->uri->segment(2) == 'finance' ? 'bg-gray-800 text-brand-green' : '' ?>">
                    <i class="fas fa-wallet w-5"></i> Keuangan
                </a>
            <?php endif; ?>

            <!-- MENU UNTUK CONTENT & SUPER -->
            <?php if(in_array($this->session->userdata('role'), ['field_coordinator', 'super_admin'])): ?>
                <a href="<?= base_url('content') ?>" class="flex items-center gap-3 px-4 py-3 rounded text-sm hover:bg-gray-800 <?= $this->uri->segment(2) == 'content' ? 'bg-gray-800 text-brand-green' : '' ?>">
                    <i class="fas fa-map-marked-alt w-5"></i> Content & Laporan
                </a>
            <?php endif; ?>

             <!-- LINK BALIK KE WEB UTAMA -->
             <div class="pt-4 mt-4 border-t border-gray-700">
                <a href="<?= base_url() ?>" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white text-sm">
                    <i class="fas fa-globe w-5"></i> Lihat Website
                </a>
             </div>
        </nav>

        <div class="p-4 border-t border-gray-800">
            <a href="<?= base_url('auth/logout') ?>" class="flex items-center gap-2 text-red-400 hover:text-red-300 text-sm px-2 py-2 w-full rounded hover:bg-red-900/20">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 flex flex-col h-full relative overflow-hidden">
        <!-- HEADER MOBILE -->
        <div class="md:hidden bg-[#111827] p-4 flex justify-between items-center border-b border-gray-800">
            <span class="font-bold text-white">ADMIN PANEL</span>
            <a href="<?= base_url('auth/logout') ?>" class="text-red-400"><i class="fas fa-sign-out-alt"></i></a>
        </div>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto p-6 md:p-10">
            <?php if($this->session->flashdata('success')): ?>
                <div class="bg-green-900/50 text-green-300 p-4 rounded mb-6 border border-green-800 flex justify-between items-center">
                    <span><?= $this->session->flashdata('success') ?></span>
                    <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-white">x</button>
                </div>
            <?php endif; ?>

            <!-- INJECT VIEW -->
            <?php $this->load->view($content); ?>
        </div>
    </main>

</body>
</html>