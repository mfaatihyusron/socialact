<div class="pt-32 px-6 max-w-4xl mx-auto pb-20">
    <!-- Header Section -->
    <div class="text-center mb-12">
        <span class="text-brand-green font-bold tracking-[0.2em] uppercase text-xs">Dukung Gerakan</span>
        <h1 class="font-heading text-4xl md:text-5xl font-bold uppercase mt-2 mb-4 text-white">
            Formulir <span class="text-brand-green">Donasi</span>
        </h1>
        <p class="text-gray-400 max-w-lg mx-auto">
            Setiap kontribusi Anda membantu operasional pembersihan sungai dan lingkungan. Transparan & Amanah.
        </p>
    </div>

    <!-- Kartu Informasi Rekening -->
    <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-2xl p-8 mb-10 text-center relative overflow-hidden shadow-2xl">
        <!-- Dekorasi Background -->
        <div class="absolute -top-10 -left-10 w-32 h-32 bg-brand-green/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl"></div>
        
        <div class="relative z-10">
            <p class="text-gray-400 text-sm uppercase tracking-wide mb-3 flex items-center justify-center gap-2">
                <i class="fas fa-university"></i> Salurkan Bantuan ke:
            </p>
            <h2 class="text-4xl md:text-5xl font-bold text-white font-mono mb-2 tracking-wider">888-222-111</h2>
            <div class="inline-block bg-white text-black px-4 py-1 rounded font-bold text-sm uppercase mb-6">
                BCA - Green Earth Foundation
            </div>
            <p class="text-xs text-gray-500 bg-black/30 inline-block px-3 py-2 rounded-lg border border-white/5">
                <i class="fas fa-info-circle mr-1"></i> Mohon simpan bukti transfer untuk verifikasi admin.
            </p>
        </div>
    </div>

    <!-- Notifikasi Flashdata (Sukses/Gagal) -->
    <?php if($this->session->flashdata('success')): ?>
        <div class="mb-8 bg-green-900/30 border border-green-500/50 text-green-200 p-4 rounded-lg text-center flex items-center justify-center gap-2 animate-pulse">
            <i class="fas fa-check-circle text-xl"></i> 
            <span><?= $this->session->flashdata('success'); ?></span>
        </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
        <div class="mb-8 bg-red-900/30 border border-red-500/50 text-red-200 p-4 rounded-lg text-center flex items-center justify-center gap-2">
            <i class="fas fa-exclamation-circle text-xl"></i> 
            <span><?= $this->session->flashdata('error'); ?></span>
        </div>
    <?php endif; ?>

    <!-- Formulir Donasi -->
    <!-- Pastikan route 'web/submit_donasi' sesuai dengan Controller Anda -->
    <?= form_open_multipart('web/submit_donasi', ['class' => 'bg-[#121212] p-8 md:p-10 rounded-2xl border border-gray-800 shadow-xl relative']) ?>
        
        <div class="space-y-8">
            <!-- Grid Nama & Email -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase text-gray-500 font-bold mb-2 ml-1">Nama Donatur</label>
                    <div class="relative">
                        <span class="absolute left-4 top-4 text-gray-600"><i class="fas fa-user"></i></span>
                        <input type="text" name="donor_name" placeholder="Nama Lengkap / Hamba Allah" class="w-full bg-black border border-gray-700 p-4 pl-10 rounded-lg focus:border-brand-green focus:ring-1 focus:ring-brand-green outline-none text-white transition-all placeholder-gray-700">
                    </div>
                </div>
                <div>
                    <label class="block text-xs uppercase text-gray-500 font-bold mb-2 ml-1">Email <span class="text-brand-green">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-4 text-gray-600"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="donor_email" placeholder="email@anda.com" class="w-full bg-black border border-gray-700 p-4 pl-10 rounded-lg focus:border-brand-green focus:ring-1 focus:ring-brand-green outline-none text-white transition-all placeholder-gray-700" required>
                    </div>
                    <p class="text-[10px] text-gray-600 mt-1 ml-1">Digunakan untuk mengirim notifikasi verifikasi.</p>
                </div>
            </div>

            <!-- Input Jumlah Donasi -->
            <div>
                <label class="block text-xs uppercase text-gray-500 font-bold mb-2 ml-1">Jumlah Donasi (Rp) <span class="text-brand-green">*</span></label>
                <div class="relative group">
                    <span class="absolute left-4 top-4 text-brand-green font-bold">Rp</span>
                    <input type="number" name="amount" placeholder="0" class="w-full bg-black border border-gray-700 p-4 pl-12 rounded-lg focus:border-brand-green focus:ring-1 focus:ring-brand-green outline-none text-white transition-all font-mono text-lg placeholder-gray-700 group-hover:border-gray-600" required>
                </div>
                <!-- Pilihan Cepat (Opsional JS bisa ditambahkan untuk mengisi input) -->
                <div class="flex gap-2 mt-3 overflow-x-auto pb-1">
                    <button type="button" onclick="document.querySelector('[name=amount]').value=50000" class="text-xs border border-gray-700 px-3 py-1 rounded-full text-gray-400 hover:bg-gray-800 hover:text-white transition">Rp 50.000</button>
                    <button type="button" onclick="document.querySelector('[name=amount]').value=100000" class="text-xs border border-gray-700 px-3 py-1 rounded-full text-gray-400 hover:bg-gray-800 hover:text-white transition">Rp 100.000</button>
                    <button type="button" onclick="document.querySelector('[name=amount]').value=500000" class="text-xs border border-gray-700 px-3 py-1 rounded-full text-gray-400 hover:bg-gray-800 hover:text-white transition">Rp 500.000</button>
                </div>
            </div>

            <!-- Input Pesan -->
            <div>
                <label class="block text-xs uppercase text-gray-500 font-bold mb-2 ml-1">Pesan / Doa (Opsional)</label>
                <textarea name="message" placeholder="Tuliskan pesan dukungan atau doa Anda..." class="w-full bg-black border border-gray-700 p-4 rounded-lg h-32 focus:border-brand-green focus:ring-1 focus:ring-brand-green outline-none text-white transition-all placeholder-gray-700 resize-none"></textarea>
            </div>

            <!-- Input File Bukti Transfer -->
            <div class="border-t border-dashed border-gray-800 pt-6">
                <label class="block text-xs uppercase text-brand-green font-bold mb-3 ml-1">Upload Bukti Transfer <span class="text-red-500">*</span></label>
                <div class="relative border-2 border-dashed border-gray-700 rounded-lg p-6 hover:bg-gray-900/50 transition-colors text-center group">
                    <input type="file" name="transfer_proof" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                    <div class="space-y-2">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-500 group-hover:text-brand-green transition-colors"></i>
                        <p class="text-sm text-gray-300 font-bold">Klik untuk upload bukti transfer</p>
                        <p class="text-xs text-gray-600">Format: JPG, PNG, PDF. Maksimal 5MB.</p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-brand-green text-black font-bold py-4 rounded-lg uppercase tracking-widest hover:bg-white hover:scale-[1.01] transition-all shadow-lg hover:shadow-brand-green/20 mt-4 flex justify-center items-center gap-2">
                <span>Konfirmasi Donasi</span>
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>

    <?= form_close() ?>
    
    <!-- Security Note -->
    <div class="text-center mt-8 text-gray-600 text-xs">
        <i class="fas fa-lock mr-1"></i> Data Anda diamankan dan tidak akan dipublikasikan kecuali Nama & Pesan.
    </div>
</div>

<!-- Script Sederhana untuk Highlight Nominal -->
<script>
    const amountInput = document.querySelector('[name=amount]');
    const chipButtons = document.querySelectorAll('button[type=button]');

    chipButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Reset style semua tombol
            chipButtons.forEach(b => {
                b.classList.remove('bg-brand-green', 'text-black', 'border-transparent');
                b.classList.add('text-gray-400', 'border-gray-700');
            });
            // Highlight tombol aktif
            btn.classList.remove('text-gray-400', 'border-gray-700');
            btn.classList.add('bg-brand-green', 'text-black', 'border-transparent');
        });
    });

    // Reset highlight jika user ketik manual
    amountInput.addEventListener('input', () => {
        chipButtons.forEach(b => {
            b.classList.remove('bg-brand-green', 'text-black', 'border-transparent');
            b.classList.add('text-gray-400', 'border-gray-700');
        });
    });
</script>
