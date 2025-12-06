<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Admin - Pandawara Style</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Oswald:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-black': '#0a0a0a',
                        'brand-dark': '#121212',
                        'brand-green': '#10b981', 
                        'brand-red': '#ef4444',
                        'admin-dark': '#1f2937',
                        'admin-panel': '#111827',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Oswald', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #111827; }
        ::-webkit-scrollbar-thumb { background: #374151; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #10b981; }
        
        .admin-tab { display: none; }
        .admin-tab.active { display: block; animation: fadeIn 0.3s ease-in-out; }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .sidebar-link.active {
            background-color: #1f2937;
            color: #10b981;
            border-right: 3px solid #10b981;
        }
    </style>
</head>
<body class="bg-brand-black text-gray-300 font-sans antialiased overflow-hidden h-screen flex">

    <!-- SIDEBAR (Sama seperti Super Admin) -->
    <aside class="w-64 bg-admin-panel border-r border-gray-800 flex-col hidden md:flex h-full">
        <!-- HEADER SIDEBAR -->
        <div class="p-6 border-b border-gray-800 flex items-center gap-3">
            <div class="w-10 h-10 bg-brand-green text-black flex items-center justify-center font-bold rounded-lg shadow-[0_0_15px_rgba(16,185,129,0.4)]">
                <i class="fas fa-wallet text-xl"></i>
            </div>
            <div>
                <span class="font-heading text-xl font-bold uppercase text-white tracking-wide leading-none block">Finance</span>
                <span class="font-heading text-xl font-bold uppercase text-brand-green tracking-wide leading-none block">Panel</span>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <p class="px-4 text-xs font-bold text-gray-600 uppercase mb-2 mt-2">Main Menu</p>
            
            <button onclick="switchTab('overview')" class="sidebar-link active w-full text-left px-4 py-3 rounded-l text-sm hover:bg-gray-800 hover:text-white flex items-center gap-3 transition-all">
                <i class="fas fa-tachometer-alt w-5 text-center"></i> 
                <span>Dashboard Overview</span>
            </button>

            <button onclick="switchTab('finance')" class="sidebar-link w-full text-left px-4 py-3 rounded-l text-sm hover:bg-gray-800 hover:text-white flex items-center gap-3 transition-all">
                <i class="fas fa-money-bill-wave w-5 text-center"></i> 
                <span>Kelola Keuangan</span>
            </button>

            <p class="px-4 text-xs font-bold text-gray-600 uppercase mb-2 mt-6">Reports</p>

            <button onclick="alert('Fitur Export PDF/Excel akan segera hadir')" class="sidebar-link w-full text-left px-4 py-3 rounded-l text-sm hover:bg-gray-800 hover:text-white flex items-center gap-3 transition-all">
                <i class="fas fa-file-export w-5 text-center"></i> 
                <span>Laporan Bulanan</span>
            </button>
        </nav>

        <div class="p-4 border-t border-gray-800 bg-black/20">
            <button class="w-full flex items-center gap-2 text-red-400 hover:text-red-300 text-sm px-2 py-2 rounded hover:bg-red-900/20 transition-colors">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </div>
    </aside>

    <!-- MOBILE HEADER -->
    <div class="fixed top-0 left-0 w-full bg-admin-panel border-b border-gray-800 z-50 md:hidden flex justify-between items-center p-4">
        <div class="flex items-center gap-2">
            <i class="fas fa-wallet text-brand-green"></i>
            <span class="font-heading font-bold text-white">FINANCE PANEL</span>
        </div>
        <button class="text-white"><i class="fas fa-bars"></i></button>
    </div>

    <!-- MAIN CONTENT -->
    <main class="flex-1 h-full overflow-y-auto bg-brand-black p-6 md:p-10 pt-20 md:pt-10 relative">
        
        <!-- HEADER SECTION -->
        <header class="flex justify-between items-center mb-8 pb-6 border-b border-gray-800">
            <div>
                <h1 id="page-title" class="text-2xl md:text-3xl font-heading font-bold text-white uppercase">Dashboard Overview</h1>
                <p class="text-sm text-gray-500 mt-1">Pantau transparansi dan arus kas organisasi.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-white">Admin Finance</p>
                    <p class="text-xs text-brand-green">online</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-gray-700 border-2 border-brand-green flex items-center justify-center overflow-hidden">
                    <i class="fas fa-user-tie text-gray-300"></i>
                </div>
            </div>
        </header>

        <!-- === TAB 1: DASHBOARD OVERVIEW (Konten dari donasi.html) === -->
        <div id="tab-overview" class="admin-tab active">
            
            <!-- Kartu Ringkasan (Dari donasi.html) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Sisa Saldo -->
                <div class="bg-brand-dark p-8 rounded-2xl border border-gray-800 relative overflow-hidden group hover:border-brand-green/30 transition-all">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity"><i class="fas fa-wallet text-6xl text-white"></i></div>
                    <p class="text-gray-500 text-xs font-bold uppercase mb-2 tracking-wider">Sisa Saldo (Live)</p>
                    <h2 class="text-4xl font-heading font-bold text-white" id="display-saldo">Rp 25.000.000</h2>
                </div>
                <!-- Pemasukan -->
                <div class="bg-brand-dark p-8 rounded-2xl border border-gray-800 relative overflow-hidden group hover:border-brand-green/30 transition-all">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity"><i class="fas fa-arrow-down text-6xl text-brand-green"></i></div>
                    <p class="text-gray-500 text-xs font-bold uppercase mb-2 tracking-wider">Total Masuk</p>
                    <h2 class="text-4xl font-heading font-bold text-brand-green" id="display-masuk">+Rp 45.000.000</h2>
                </div>
                <!-- Pengeluaran -->
                <div class="bg-brand-dark p-8 rounded-2xl border border-gray-800 relative overflow-hidden group hover:border-brand-green/30 transition-all">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity"><i class="fas fa-arrow-up text-6xl text-red-500"></i></div>
                    <p class="text-gray-500 text-xs font-bold uppercase mb-2 tracking-wider">Total Penggunaan</p>
                    <h2 class="text-4xl font-heading font-bold text-red-500" id="display-keluar">-Rp 20.000.000</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Chart Section (Dari donasi.html) -->
                <div class="bg-brand-dark p-8 rounded-2xl border border-gray-800">
                    <h3 class="font-bold text-lg mb-6 uppercase flex items-center gap-2 text-white">
                        <i class="fas fa-chart-pie text-brand-green"></i> Alokasi Dana
                    </h3>
                    <div class="h-64 relative">
                        <canvas id="grafikAlokasi"></canvas>
                    </div>
                </div>

                <!-- Tables Section (Dari donasi.html) -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Tabel Donasi -->
                    <div>
                        <div class="flex justify-between items-end mb-4">
                            <h3 class="font-bold text-xl uppercase text-white flex items-center gap-2">
                                <span class="w-2 h-8 bg-brand-green rounded-sm"></span> Donasi Terbaru
                            </h3>
                        </div>
                        <div class="bg-brand-dark rounded-xl border border-gray-800 overflow-hidden">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-900/50 text-gray-500 uppercase text-xs font-bold">
                                    <tr>
                                        <th class="p-4">Tanggal</th>
                                        <th class="p-4">Donatur</th>
                                        <th class="p-4 text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-800 text-gray-300">
                                    <tr>
                                        <td class="p-4 font-mono text-xs text-gray-500">01 Dec 2023</td>
                                        <td class="p-4 font-bold text-white">Hamba Allah</td>
                                        <td class="p-4 text-right text-brand-green font-mono">+Rp 500.000</td>
                                    </tr>
                                    <tr>
                                        <td class="p-4 font-mono text-xs text-gray-500">30 Nov 2023</td>
                                        <td class="p-4 font-bold text-white">Budi Santoso</td>
                                        <td class="p-4 text-right text-brand-green font-mono">+Rp 1.000.000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tabel Pengeluaran -->
                    <div>
                        <div class="flex justify-between items-end mb-4">
                            <h3 class="font-bold text-xl uppercase text-white flex items-center gap-2">
                                <span class="w-2 h-8 bg-red-500 rounded-sm"></span> Penggunaan Terakhir
                            </h3>
                        </div>
                        <div class="bg-brand-dark rounded-xl border border-gray-800 overflow-hidden">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-900/50 text-gray-500 uppercase text-xs font-bold">
                                    <tr>
                                        <th class="p-4">Tanggal</th>
                                        <th class="p-4">Keterangan</th>
                                        <th class="p-4 text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-800 text-gray-300">
                                    <tr>
                                        <td class="p-4 font-mono text-xs text-gray-500">28 Nov 2023</td>
                                        <td class="p-4 text-white">Beli Kantong Sampah (100 pack)</td>
                                        <td class="p-4 text-right text-red-500 font-mono">-Rp 2.500.000</td>
                                    </tr>
                                    <tr>
                                        <td class="p-4 font-mono text-xs text-gray-500">27 Nov 2023</td>
                                        <td class="p-4 text-white">Sewa Truk Pengangkut</td>
                                        <td class="p-4 text-right text-red-500 font-mono">-Rp 1.500.000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- === TAB 2: KELOLA KEUANGAN === -->
        <div id="tab-finance" class="admin-tab">
            
            <!-- A. Persebaran Uang (Bank Accounts) -->
            <h3 class="font-heading text-lg text-white mb-4 border-l-4 border-brand-green pl-3 uppercase">Posisi Saldo Rekening</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <!-- Card BCA -->
                <div class="bg-admin-panel p-5 rounded-lg border border-gray-800 flex flex-col justify-between h-32 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 text-blue-900 opacity-20"><i class="fas fa-university text-9xl"></i></div>
                    <div class="flex justify-between items-start z-10">
                        <span class="text-gray-400 text-xs font-bold uppercase">BCA Corporate</span>
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/1200px-Bank_Central_Asia.svg.png" class="h-4 opacity-50 bg-white px-1 rounded">
                    </div>
                    <div class="z-10">
                        <p class="text-2xl font-mono font-bold text-white">Rp 15.000.000</p>
                        <p class="text-[10px] text-gray-500 mt-1">Acct: 8830-xxxx-xxxx</p>
                    </div>
                </div>

                <!-- Card Mandiri -->
                <div class="bg-admin-panel p-5 rounded-lg border border-gray-800 flex flex-col justify-between h-32 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 text-yellow-900 opacity-20"><i class="fas fa-university text-9xl"></i></div>
                    <div class="flex justify-between items-start z-10">
                        <span class="text-gray-400 text-xs font-bold uppercase">Mandiri</span>
                        <span class="text-xs font-bold text-yellow-500">MANDIRI</span>
                    </div>
                    <div class="z-10">
                        <p class="text-2xl font-mono font-bold text-white">Rp 8.500.000</p>
                        <p class="text-[10px] text-gray-500 mt-1">Acct: 133-00-xxxx</p>
                    </div>
                </div>

                <!-- Card Petty Cash -->
                <div class="bg-admin-panel p-5 rounded-lg border border-gray-800 flex flex-col justify-between h-32 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 text-green-900 opacity-20"><i class="fas fa-money-bill-wave text-9xl"></i></div>
                    <div class="flex justify-between items-start z-10">
                        <span class="text-gray-400 text-xs font-bold uppercase">Petty Cash</span>
                        <i class="fas fa-coins text-gray-500"></i>
                    </div>
                    <div class="z-10">
                        <p class="text-2xl font-mono font-bold text-white">Rp 1.500.000</p>
                        <p class="text-[10px] text-gray-500 mt-1">Pegangan Bendahara</p>
                    </div>
                </div>

                <!-- Card Total -->
                <div class="bg-brand-green p-5 rounded-lg border border-brand-green flex flex-col justify-between h-32 text-black shadow-lg shadow-green-900/20">
                    <div class="flex justify-between items-start">
                        <span class="text-black/60 text-xs font-bold uppercase">Total Aset</span>
                        <i class="fas fa-check-circle text-black/40"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-heading font-bold">Rp 25.000.000</p>
                        <p class="text-[10px] text-black/70 mt-1 font-bold uppercase">Last Update: Just Now</p>
                    </div>
                </div>
            </div>

            <!-- B. Rincian Keluar Masuk & Tombol Aksi -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-4 gap-4">
                <h3 class="font-heading text-lg text-white border-l-4 border-blue-500 pl-3 uppercase">Buku Besar Transaksi</h3>
                
                <button onclick="toggleModal('addTransactionModal')" class="bg-brand-green hover:bg-white text-black font-bold px-6 py-2 rounded shadow-lg shadow-green-900/20 transition-all flex items-center gap-2 text-sm uppercase">
                    <i class="fas fa-plus-circle"></i> Tambah Transaksi
                </button>
            </div>

            <div class="bg-admin-panel rounded-lg border border-gray-800 overflow-hidden shadow-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-400">
                        <thead class="bg-gray-900/50 text-gray-200 uppercase text-[11px] font-bold tracking-wider">
                            <tr>
                                <th class="p-4">Tanggal</th>
                                <th class="p-4">ID</th>
                                <th class="p-4">Keterangan</th>
                                <th class="p-4">Kategori</th>
                                <th class="p-4">Sumber Dana</th>
                                <th class="p-4 text-right">Nominal</th>
                                <th class="p-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody id="finance-table-body" class="divide-y divide-gray-800">
                            <!-- Injected by JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <!-- === MODALS === -->

    <!-- Modal Tambah Pemasukan/Pengeluaran -->
    <div id="addTransactionModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
        <div class="bg-admin-panel border border-gray-700 p-8 max-w-lg w-full rounded-xl shadow-2xl relative">
            <button onclick="toggleModal('addTransactionModal')" class="absolute top-4 right-4 text-gray-500 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
            <h3 class="font-heading text-2xl font-bold text-white mb-6 border-l-4 border-brand-green pl-3 uppercase">Catat Transaksi</h3>
            
            <form onsubmit="handleTransactionSubmit(event)" class="space-y-4">
                <!-- Tipe Transaksi -->
                <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="in" class="peer sr-only" checked>
                        <div class="bg-gray-800 text-gray-400 peer-checked:bg-brand-green peer-checked:text-black p-3 rounded text-center font-bold text-sm transition-all border border-gray-700">
                            <i class="fas fa-arrow-down mr-1"></i> PEMASUKAN
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="out" class="peer sr-only">
                        <div class="bg-gray-800 text-gray-400 peer-checked:bg-red-500 peer-checked:text-white p-3 rounded text-center font-bold text-sm transition-all border border-gray-700">
                            <i class="fas fa-arrow-up mr-1"></i> PENGELUARAN
                        </div>
                    </label>
                </div>

                <div>
                    <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Tanggal</label>
                    <input type="date" id="trxDate" class="w-full bg-brand-black border border-gray-700 p-3 rounded text-sm text-white focus:border-brand-green outline-none" required>
                </div>

                <div>
                    <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Keterangan / Judul</label>
                    <input type="text" id="trxTitle" placeholder="Contoh: Donasi dari Bpk. X atau Beli Peralatan" class="w-full bg-brand-black border border-gray-700 p-3 rounded text-sm text-white focus:border-brand-green outline-none" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Kategori</label>
                        <select id="trxCategory" class="w-full bg-brand-black border border-gray-700 p-3 rounded text-sm text-white focus:border-brand-green outline-none">
                            <option value="Donasi">Donasi Umum</option>
                            <option value="Sponsorship">Sponsorship</option>
                            <option value="Logistik">Logistik & Alat</option>
                            <option value="Operasional">Operasional</option>
                            <option value="Konsumsi">Konsumsi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Bank / Akun</label>
                        <select id="trxBank" class="w-full bg-brand-black border border-gray-700 p-3 rounded text-sm text-white focus:border-brand-green outline-none">
                            <option value="BCA">BCA Corporate</option>
                            <option value="Mandiri">Mandiri</option>
                            <option value="Cash">Petty Cash</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Jumlah (Rp)</label>
                    <input type="number" id="trxAmount" placeholder="0" class="w-full bg-brand-black border border-gray-700 p-3 rounded text-sm text-white focus:border-brand-green outline-none font-mono text-lg" required>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-white hover:bg-gray-200 text-black font-bold py-3 rounded uppercase tracking-wider transition-all">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // --- 1. MOCK DATA ---
        let transactions = [
            { id: 'TRX-001', date: '2023-12-01', title: 'Donasi Umum via Web', type: 'in', category: 'Donasi', bank: 'BCA', amount: 500000, status: 'Verified' },
            { id: 'TRX-002', date: '2023-11-30', title: 'Donasi Bpk. Budi Santoso', type: 'in', category: 'Donasi', bank: 'Mandiri', amount: 1000000, status: 'Verified' },
            { id: 'TRX-003', date: '2023-11-28', title: 'Beli Kantong Sampah (100 pack)', type: 'out', category: 'Logistik', bank: 'Cash', amount: 250000, status: 'Completed' },
            { id: 'TRX-004', date: '2023-11-27', title: 'Sewa Truk Pengangkut', type: 'out', category: 'Operasional', bank: 'BCA', amount: 1500000, status: 'Completed' },
            { id: 'TRX-005', date: '2023-11-25', title: 'Sponsorship PT. Maju Jaya', type: 'in', category: 'Sponsorship', bank: 'BCA', amount: 10000000, status: 'Verified' }
        ];

        // --- 2. INIT FUNCTION ---
        window.onload = function() {
            renderTable();
            renderChart();
            // Set default date input to today
            document.getElementById('trxDate').valueAsDate = new Date();
        };

        // --- 3. RENDERING ---
        function renderTable() {
            const tbody = document.getElementById('finance-table-body');
            tbody.innerHTML = '';

            transactions.forEach(trx => {
                const isIncome = trx.type === 'in';
                const colorClass = isIncome ? 'text-brand-green' : 'text-red-500';
                const sign = isIncome ? '+' : '-';
                const badge = isIncome 
                    ? '<span class="px-2 py-0.5 rounded text-[10px] bg-green-900/30 text-green-400 border border-green-900">MASUK</span>' 
                    : '<span class="px-2 py-0.5 rounded text-[10px] bg-red-900/30 text-red-400 border border-red-900">KELUAR</span>';

                tbody.innerHTML += `
                    <tr class="hover:bg-gray-800/50 transition-colors">
                        <td class="p-4 font-mono text-xs text-gray-500">${trx.date}</td>
                        <td class="p-4 font-mono text-xs text-gray-500">${trx.id}</td>
                        <td class="p-4">
                            <p class="font-bold text-white text-sm">${trx.title}</p>
                        </td>
                        <td class="p-4"><span class="bg-gray-800 text-gray-400 px-2 py-1 rounded text-[10px] uppercase">${trx.category}</span></td>
                        <td class="p-4 text-xs font-bold text-gray-400 uppercase">${trx.bank}</td>
                        <td class="p-4 text-right font-mono font-bold ${colorClass}">
                            ${sign}Rp ${new Intl.NumberFormat('id-ID').format(trx.amount)}
                        </td>
                        <td class="p-4 text-center text-xs text-gray-500"><i class="fas fa-check-circle text-brand-green"></i></td>
                    </tr>
                `;
            });
        }

        function renderChart() {
            const ctx = document.getElementById('grafikAlokasi').getContext('2d');
            
            // Simple logic to sum categories for chart (Demo purpose)
            const categories = {};
            transactions.forEach(t => {
                if(t.type === 'out') {
                    categories[t.category] = (categories[t.category] || 0) + t.amount;
                }
            });
            // Add dummy data if empty so chart shows something
            if(Object.keys(categories).length === 0) {
                categories['Logistik'] = 5000000;
                categories['Operasional'] = 3000000;
                categories['Lainnya'] = 1000000;
            }

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(categories),
                    datasets: [{
                        data: Object.values(categories),
                        backgroundColor: ['#10b981', '#ef4444', '#f59e0b', '#3b82f6', '#9ca3af'],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { color: 'white', font: {family: 'Inter', size: 12} } }
                    }
                }
            });
        }

        // --- 4. INTERACTION HANDLERS ---
        function switchTab(tabId) {
            document.querySelectorAll('.admin-tab').forEach(el => el.classList.remove('active'));
            document.getElementById('tab-' + tabId).classList.add('active');
            
            document.querySelectorAll('.sidebar-link').forEach(el => el.classList.remove('active'));
            event.currentTarget.classList.add('active');

            const titles = { 'overview': 'Dashboard Overview', 'finance': 'Kelola Keuangan' };
            document.getElementById('page-title').innerText = titles[tabId];
        }

        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }

        function handleTransactionSubmit(e) {
            e.preventDefault();
            
            // Get Values
            const type = document.querySelector('input[name="type"]:checked').value;
            const date = document.getElementById('trxDate').value;
            const title = document.getElementById('trxTitle').value;
            const category = document.getElementById('trxCategory').value;
            const bank = document.getElementById('trxBank').value;
            const amount = parseInt(document.getElementById('trxAmount').value);

            // Add to Array
            const newTrx = {
                id: 'TRX-' + Math.floor(Math.random() * 1000),
                date: date,
                title: title,
                type: type,
                category: category,
                bank: bank,
                amount: amount,
                status: 'Verified'
            };

            transactions.unshift(newTrx); // Add to top

            // Re-render & Close
            renderTable();
            toggleModal('addTransactionModal');
            e.target.reset();
            document.getElementById('trxDate').valueAsDate = new Date();
            
            alert('Transaksi berhasil dicatat!');
        }
    </script>
</body>
</html>