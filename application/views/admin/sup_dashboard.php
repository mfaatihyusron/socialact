<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control Panel - Super Admin</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
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
                        'admin-panel': '#111827', // Gray-900
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

    <!-- SIDEBAR -->
    <aside class="w-64 bg-admin-panel border-r border-gray-800 flex-col hidden md:flex h-full">
        <!-- MODIFIED HEADER SECTION -->
        <div class="p-6 border-b border-gray-800 flex items-center gap-3">
            <!-- New Logo Icon -->
            <div class="w-10 h-10 bg-brand-green text-black flex items-center justify-center font-bold rounded-lg shadow-[0_0_15px_rgba(16,185,129,0.4)]">
                <i class="fas fa-sliders-h text-xl"></i>
            </div>
            <!-- New Text Control Panel -->
            <div>
                <span class="font-heading text-xl font-bold uppercase text-white tracking-wide leading-none block">Control</span>
                <span class="font-heading text-xl font-bold uppercase text-brand-green tracking-wide leading-none block">Panel</span>
            </div>
        </div>
        <!-- END HEADER -->

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <p class="px-4 text-xs font-bold text-gray-600 uppercase mb-2 mt-2">Main Menu</p>
            
            <button onclick="switchTab('dashboard')" class="sidebar-link active w-full text-left px-4 py-3 rounded-l text-sm hover:bg-gray-800 hover:text-white flex items-center gap-3 transition-all">
                <i class="fas fa-tachometer-alt w-5 text-center"></i> 
                <span>Dashboard Overview</span>
            </button>

            <button onclick="switchTab('events')" class="sidebar-link w-full text-left px-4 py-3 rounded-l text-sm hover:bg-gray-800 hover:text-white flex items-center gap-3 transition-all">
                <i class="fas fa-calendar-check w-5 text-center"></i> 
                <span>Kelola Volunteer</span>
            </button>

            <p class="px-4 text-xs font-bold text-gray-600 uppercase mb-2 mt-6">System Control</p>

            <button onclick="switchTab('users')" class="sidebar-link w-full text-left px-4 py-3 rounded-l text-sm hover:bg-gray-800 hover:text-white flex items-center gap-3 transition-all">
                <i class="fas fa-users-cog w-5 text-center"></i> 
                <span>Manajemen Admin</span>
            </button>

            <button onclick="alert('Fitur Logs akan segera hadir')" class="sidebar-link w-full text-left px-4 py-3 rounded-l text-sm hover:bg-gray-800 hover:text-white flex items-center gap-3 transition-all">
                <i class="fas fa-terminal w-5 text-center"></i> 
                <span>System Logs</span>
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
            <i class="fas fa-sliders-h text-brand-green"></i>
            <span class="font-heading font-bold text-white">CONTROL PANEL</span>
        </div>
        <button class="text-white"><i class="fas fa-bars"></i></button>
    </div>

    <!-- MAIN CONTENT -->
    <main class="flex-1 h-full overflow-y-auto bg-brand-black p-6 md:p-10 pt-20 md:pt-10 relative">
        
        <!-- HEADER SECTION -->
        <header class="flex justify-between items-center mb-8 pb-6 border-b border-gray-800">
            <div>
                <h1 id="page-title" class="text-2xl md:text-3xl font-heading font-bold text-white uppercase">Dashboard Overview</h1>
                <p class="text-sm text-gray-500 mt-1">Selamat datang di Panel Kontrol Utama.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-white">Super Admin</p>
                    <p class="text-xs text-brand-green">online</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-gray-700 border-2 border-brand-green flex items-center justify-center overflow-hidden shadow-[0_0_10px_rgba(16,185,129,0.3)]">
                    <i class="fas fa-user-astronaut text-gray-300"></i>
                </div>
            </div>
        </header>

        <!-- 1. DASHBOARD OVERVIEW -->
        <div id="tab-dashboard" class="admin-tab active">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-admin-panel p-6 rounded-lg border border-gray-800 hover:border-brand-green/50 transition-colors group">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-gray-500 text-[10px] uppercase font-bold tracking-widest">Total Admins</p>
                            <h3 class="text-3xl font-heading font-bold text-white mt-1 group-hover:text-brand-green transition-colors" id="stat-admins">0</h3>
                        </div>
                        <div class="p-2 bg-blue-900/20 text-blue-400 rounded-lg"><i class="fas fa-user-shield"></i></div>
                    </div>
                </div>

                <div class="bg-admin-panel p-6 rounded-lg border border-gray-800 hover:border-brand-green/50 transition-colors group">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-gray-500 text-[10px] uppercase font-bold tracking-widest">Active Events</p>
                            <h3 class="text-3xl font-heading font-bold text-white mt-1 group-hover:text-brand-green transition-colors" id="stat-events">0</h3>
                        </div>
                        <div class="p-2 bg-brand-green/20 text-brand-green rounded-lg"><i class="fas fa-calendar-alt"></i></div>
                    </div>
                </div>

                <div class="bg-admin-panel p-6 rounded-lg border border-gray-800 hover:border-brand-green/50 transition-colors group">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-gray-500 text-[10px] uppercase font-bold tracking-widest">Total Volunteers</p>
                            <h3 class="text-3xl font-heading font-bold text-white mt-1 group-hover:text-brand-green transition-colors">1,240</h3>
                        </div>
                        <div class="p-2 bg-yellow-900/20 text-yellow-500 rounded-lg"><i class="fas fa-users"></i></div>
                    </div>
                </div>

                <div class="bg-admin-panel p-6 rounded-lg border border-gray-800 hover:border-brand-green/50 transition-colors group">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-gray-500 text-[10px] uppercase font-bold tracking-widest">System Health</p>
                            <h3 class="text-3xl font-heading font-bold text-green-400 mt-1">98%</h3>
                        </div>
                        <div class="p-2 bg-green-900/20 text-green-400 rounded-lg"><i class="fas fa-server"></i></div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Log (Mockup) -->
            <div class="bg-admin-panel rounded-lg border border-gray-800 p-6">
                <h3 class="font-heading text-lg font-bold text-white mb-6 border-b border-gray-800 pb-2">Recent System Logs</h3>
                <div class="space-y-4">
                    <div class="flex gap-4 items-start">
                        <div class="w-8 flex flex-col items-center">
                            <div class="w-2 h-2 rounded-full bg-blue-500 mb-1"></div>
                            <div class="w-0.5 h-full bg-gray-800"></div>
                        </div>
                        <div>
                            <p class="text-sm text-white font-bold">New Admin Created</p>
                            <p class="text-xs text-gray-500">Super Admin created user <b>finance_02</b> with role Finance.</p>
                            <p class="text-[10px] text-gray-600 mt-1">Today, 10:42 AM</p>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start">
                        <div class="w-8 flex flex-col items-center">
                            <div class="w-2 h-2 rounded-full bg-brand-green mb-1"></div>
                            <div class="w-0.5 h-full bg-gray-800"></div>
                        </div>
                        <div>
                            <p class="text-sm text-white font-bold">Event Status Updated</p>
                            <p class="text-xs text-gray-500">Event <b>Bersih Ciliwung</b> marked as Completed.</p>
                            <p class="text-[10px] text-gray-600 mt-1">Yesterday, 04:30 PM</p>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start">
                        <div class="w-8 flex flex-col items-center">
                            <div class="w-2 h-2 rounded-full bg-red-500 mb-1"></div>
                        </div>
                        <div>
                            <p class="text-sm text-white font-bold">Login Attempt Failed</p>
                            <p class="text-xs text-gray-500">Failed login attempt for user admin_01 from IP 192.168.1.5</p>
                            <p class="text-[10px] text-gray-600 mt-1">Yesterday, 09:15 AM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. KELOLA EVENT (Table: volunteer_events) -->
        <div id="tab-events" class="admin-tab">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-500 text-xs"></i>
                    <input type="text" placeholder="Cari event..." class="bg-admin-panel border border-gray-700 rounded-full pl-10 pr-4 py-2 text-sm text-white focus:border-brand-green outline-none w-64">
                </div>
                <button onclick="toggleModal('addEventModal')" class="bg-brand-green hover:bg-white text-black font-bold px-6 py-2 rounded shadow-lg shadow-green-900/20 transition-all flex items-center gap-2 text-sm uppercase">
                    <i class="fas fa-plus"></i> Tambah Event
                </button>
            </div>

            <div class="bg-admin-panel rounded-lg border border-gray-800 overflow-hidden shadow-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-400">
                        <thead class="bg-gray-900/50 text-gray-200 uppercase text-[11px] font-bold tracking-wider">
                            <tr>
                                <th class="p-5">Event Name</th>
                                <th class="p-5">Date & Time</th>
                                <th class="p-5">Location</th>
                                <th class="p-5">Status</th>
                                <th class="p-5 text-center">Volunteers</th>
                                <th class="p-5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody id="event-table-body" class="divide-y divide-gray-800">
                            <!-- Injected by JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 3. MANAJEMEN ADMIN (Table: admins) -->
        <div id="tab-users" class="admin-tab">
            <div class="bg-yellow-900/10 border border-yellow-900/30 p-4 rounded-lg mb-6 flex items-start gap-3">
                <i class="fas fa-exclamation-triangle text-yellow-500 mt-1"></i>
                <div>
                    <p class="text-sm text-yellow-500 font-bold">Hati-hati!</p>
                    <p class="text-xs text-yellow-200/70">Menambahkan atau menghapus admin akan mempengaruhi akses ke sistem Finance, Verification, dan Content.</p>
                </div>
            </div>

            <div class="flex justify-end mb-6">
                <button onclick="toggleModal('addUserModal')" class="bg-white hover:bg-gray-200 text-black font-bold px-6 py-2 rounded shadow-lg transition-all flex items-center gap-2 text-sm uppercase">
                    <i class="fas fa-user-plus"></i> Tambah Admin Baru
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="user-grid">
                <!-- User Cards Injected by JS -->
            </div>
        </div>

    </main>

    <!-- === MODALS === -->

    <!-- Modal Add Event -->
    <div id="addEventModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
        <div class="bg-admin-panel border border-gray-700 p-8 max-w-lg w-full rounded-xl shadow-2xl relative">
            <button onclick="toggleModal('addEventModal')" class="absolute top-4 right-4 text-gray-500 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
            <h3 class="font-heading text-2xl font-bold text-white mb-6 border-l-4 border-brand-green pl-3 uppercase">Buat Event Baru</h3>
            
            <form onsubmit="handleAddEvent(event)" class="space-y-5">
                <div>
                    <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Nama Kegiatan</label>
                    <input type="text" id="evtName" placeholder="Contoh: Bersih Sungai Citarum" class="w-full bg-brand-black border border-gray-700 p-3 rounded text-sm text-white focus:border-brand-green outline-none transition-colors" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Tanggal & Waktu</label>
                        <input type="datetime-local" id="evtDate" class="w-full bg-brand-black border border-gray-700 p-3 rounded text-sm text-white focus:border-brand-green outline-none text-gray-400" required>
                    </div>
                    <div>
                        <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Status Awal</label>
                        <select id="evtStatus" class="w-full bg-brand-black border border-gray-700 p-3 rounded text-sm text-white focus:border-brand-green outline-none">
                            <option value="upcoming">Upcoming</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Lokasi</label>
                    <input type="text" id="evtLoc" placeholder="Nama Tempat / Koordinat" class="w-full bg-brand-black border border-gray-700 p-3 rounded text-sm text-white focus:border-brand-green outline-none" required>
                </div>
                <div>
                    <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Deskripsi Singkat</label>
                    <textarea class="w-full bg-brand-black border border-gray-700 p-3 rounded text-sm text-white focus:border-brand-green outline-none h-20"></textarea>
                </div>
                
                <div class="pt-4">
                    <button type="submit" class="w-full bg-brand-green hover:bg-white text-black font-bold py-3 rounded uppercase tracking-wider transition-all shadow-lg shadow-green-900/20">
                        Publish Event
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Add User -->
    <div id="addUserModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
        <div class="bg-admin-panel border border-gray-700 p-8 max-w-md w-full rounded-xl shadow-2xl relative">
            <button onclick="toggleModal('addUserModal')" class="absolute top-4 right-4 text-gray-500 hover:text-white transition-colors"><i class="fas fa-times text-xl"></i></button>
            <h3 class="font-heading text-2xl font-bold text-white mb-6 border-l-4 border-blue-500 pl-3 uppercase">Tambah Admin</h3>
            
            <form onsubmit="handleAddUser(event)" class="space-y-5">
                <div>
                    <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Username</label>
                    <input type="text" id="admUsername" placeholder="finance_01" class="w-full bg-brand-black border border-gray-700 p-3 rounded text-sm text-white focus:border-blue-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Email Official</label>
                    <input type="email" id="admEmail" placeholder="staff@social.org" class="w-full bg-brand-black border border-gray-700 p-3 rounded text-sm text-white focus:border-blue-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Role Access</label>
                    <select id="admRole" class="w-full bg-brand-black border border-gray-700 p-3 rounded text-sm text-white focus:border-blue-500 outline-none">
                        <option value="finance">Finance (Orang 1)</option>
                        <option value="field_coordinator">Field Coordinator (Orang 3)</option>
                        <option value="super_admin">Super Admin (Orang 4)</option>
                    </select>
                    <p class="text-[10px] text-gray-500 mt-1">*Menentukan akses ke dashboard terkait.</p>
                </div>
                <div>
                    <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Password Awal</label>
                    <input type="password" value="Password123" class="w-full bg-brand-black border border-gray-700 p-3 rounded text-sm text-white focus:border-blue-500 outline-none">
                </div>
                
                <div class="pt-4">
                    <button type="submit" class="w-full bg-white hover:bg-gray-200 text-black font-bold py-3 rounded uppercase tracking-wider transition-all">
                        Buat User Akses
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // --- 1. DATA STATE (MOCKUP DATABASE) ---
        let db_volunteer_events = [
            { id: 1, event_name: "Bersih Pantai Indah", event_date: "2023-11-12 08:00", location: "Pantai Indah Kapuk 2", status: 'upcoming', volunteers: 120 },
            { id: 2, event_name: "Operasi Semut Sungai", event_date: "2023-11-15 07:00", location: "Bantaran Ciliwung", status: 'upcoming', volunteers: 45 },
            { id: 3, event_name: "Mangrove Planting", event_date: "2023-10-20 09:00", location: "Muara Angke", status: 'completed', volunteers: 200 }
        ];

        let db_admins = [
            { id: 1, username: 'superadmin', email: 'admin@social.org', role: 'super_admin' },
            { id: 2, username: 'finance_lead', email: 'money@social.org', role: 'finance' },
            { id: 3, username: 'content_creator', email: 'media@social.org', role: 'field_coordinator' }
        ];

        // --- 2. INIT & RENDER ---
        function init() {
            renderEvents();
            renderUsers();
            updateStats();
        }

        function renderEvents() {
            const tbody = document.getElementById('event-table-body');
            tbody.innerHTML = '';
            
            db_volunteer_events.forEach(ev => {
                let badgeClass = '';
                let statusIcon = '';
                
                if(ev.status === 'upcoming') {
                    badgeClass = 'bg-blue-900/30 text-blue-400 border border-blue-900';
                    statusIcon = '<i class="fas fa-clock mr-1"></i>';
                } else if(ev.status === 'completed') {
                    badgeClass = 'bg-green-900/30 text-green-400 border border-green-900';
                    statusIcon = '<i class="fas fa-check-circle mr-1"></i>';
                } else {
                    badgeClass = 'bg-yellow-900/30 text-yellow-500 border border-yellow-900';
                    statusIcon = '<i class="fas fa-spinner fa-spin mr-1"></i>';
                }

                tbody.innerHTML += `
                    <tr class="hover:bg-gray-800/50 transition-colors group">
                        <td class="p-5">
                            <p class="font-bold text-white text-sm">${ev.event_name}</p>
                            <p class="text-[10px] text-gray-500 mt-0.5 group-hover:text-brand-green transition-colors">ID: EVT-${ev.id}</p>
                        </td>
                        <td class="p-5 text-sm">
                            <span class="block text-gray-300">${ev.event_date.split(' ')[0]}</span>
                            <span class="text-xs text-gray-500">${ev.event_date.split(' ')[1]} WIB</span>
                        </td>
                        <td class="p-5 text-sm text-gray-400">
                            <i class="fas fa-map-marker-alt text-gray-600 mr-1"></i> ${ev.location}
                        </td>
                        <td class="p-5">
                            <span class="px-2.5 py-1 rounded text-[10px] uppercase font-bold tracking-wide ${badgeClass}">
                                ${statusIcon} ${ev.status}
                            </span>
                        </td>
                        <td class="p-5 text-center">
                            <span class="font-mono text-white font-bold">${ev.volunteers}</span>
                        </td>
                        <td class="p-5 text-right">
                            <button onclick="deleteEvent(${ev.id})" class="text-gray-500 hover:text-red-400 transition-colors p-2 rounded hover:bg-gray-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        }

        function renderUsers() {
            const grid = document.getElementById('user-grid');
            grid.innerHTML = '';

            db_admins.forEach(user => {
                let roleColor = user.role === 'super_admin' ? 'text-brand-green' : (user.role === 'finance' ? 'text-blue-400' : 'text-purple-400');
                let roleIcon = user.role === 'super_admin' ? 'fa-crown' : (user.role === 'finance' ? 'fa-wallet' : 'fa-camera');
                
                grid.innerHTML += `
                    <div class="bg-admin-panel border border-gray-800 p-5 rounded-lg flex items-center gap-4 hover:border-gray-600 transition-all group relative">
                        ${user.role !== 'super_admin' ? `<button onclick="deleteUser(${user.id})" class="absolute top-2 right-2 text-gray-600 hover:text-red-500 p-2"><i class="fas fa-times"></i></button>` : ''}
                        
                        <div class="w-12 h-12 rounded-full bg-gray-800 flex items-center justify-center text-xl text-gray-500 group-hover:text-white transition-colors">
                            <i class="fas ${roleIcon}"></i>
                        </div>
                        <div>
                            <p class="font-bold text-white text-sm mb-0.5">${user.username}</p>
                            <p class="text-xs text-gray-500 mb-2">${user.email}</p>
                            <span class="text-[10px] uppercase font-bold border border-gray-700 px-2 py-0.5 rounded ${roleColor}">
                                ${user.role.replace('_', ' ')}
                            </span>
                        </div>
                    </div>
                `;
            });
        }

        function updateStats() {
            document.getElementById('stat-admins').innerText = db_admins.length;
            document.getElementById('stat-events').innerText = db_volunteer_events.filter(e => e.status !== 'completed').length;
        }

        // --- 3. HANDLERS ---
        function switchTab(tabId) {
            // Hide all tabs
            document.querySelectorAll('.admin-tab').forEach(el => el.classList.remove('active'));
            // Show selected tab
            document.getElementById('tab-' + tabId).classList.add('active');
            
            // Update Sidebar Active State
            document.querySelectorAll('.sidebar-link').forEach(el => el.classList.remove('active'));
            event.currentTarget.classList.add('active');

            // Update Page Title
            const titles = {
                'dashboard': 'Dashboard Overview',
                'events': 'Kelola Volunteer & Events',
                'users': 'Manajemen Admin System'
            };
            document.getElementById('page-title').innerText = titles[tabId];
        }

        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }

        function handleAddEvent(e) {
            e.preventDefault();
            const name = document.getElementById('evtName').value;
            const date = document.getElementById('evtDate').value;
            const loc = document.getElementById('evtLoc').value;
            const status = document.getElementById('evtStatus').value;

            db_volunteer_events.unshift({
                id: Date.now(),
                event_name: name,
                event_date: date.replace('T', ' '),
                location: loc,
                status: status,
                volunteers: 0
            });

            renderEvents();
            updateStats();
            toggleModal('addEventModal');
            e.target.reset();
            // Show toast/alert manually nicely
            alert(`Event "${name}" berhasil ditambahkan!`);
        }

        function handleAddUser(e) {
            e.preventDefault();
            const user = document.getElementById('admUsername').value;
            const email = document.getElementById('admEmail').value;
            const role = document.getElementById('admRole').value;

            db_admins.push({
                id: Date.now(),
                username: user,
                email: email,
                role: role
            });

            renderUsers();
            updateStats();
            toggleModal('addUserModal');
            e.target.reset();
        }

        function deleteEvent(id) {
            if(confirm('Hapus event ini secara permanen?')) {
                db_volunteer_events = db_volunteer_events.filter(e => e.id !== id);
                renderEvents();
                updateStats();
            }
        }

        function deleteUser(id) {
            if(confirm('Hapus akses user ini?')) {
                db_admins = db_admins.filter(u => u.id !== id);
                renderUsers();
                updateStats();
            }
        }

        // Start
        window.onload = init;
    </script>
</body>
</html>