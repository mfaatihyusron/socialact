<div class="pt-32 px-6 max-w-4xl mx-auto pb-20">
    <div class="text-center mb-12">
        <span class="text-brand-green font-bold tracking-[0.2em] uppercase text-xs">Dukung Gerakan</span>
        <h1 class="font-heading text-4xl md:text-5xl font-bold uppercase mt-2 mb-4 text-white">
            Formulir <span class="text-brand-green">Donasi</span>
        </h1>
        <p class="text-gray-400 max-w-lg mx-auto">
            Setiap kontribusi Anda membantu operasional pembersihan sungai dan lingkungan. Transparan & Amanah.
        </p>
    </div>

    <!-- Informasi Rekening (Looping dari Database) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <?php if(!empty($accounts)): foreach($accounts as $acc): ?>
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 border border-gray-700 rounded-2xl p-6 relative overflow-hidden group hover:border-brand-green/30 transition-all">
            <div class="absolute -top-10 -left-10 w-20 h-20 bg-brand-green/10 rounded-full blur-2xl group-hover:bg-brand-green/20 transition-all"></div>
            
            <div class="flex justify-between items-start mb-4">
                <div class="bg-white/10 p-2 rounded-lg">
                    <i class="fas fa-university text-brand-green text-xl"></i>
                </div>
                <span class="text-[10px] uppercase font-bold text-gray-500 border border-gray-600 px-2 py-1 rounded"><?= $acc->account_type ?></span>
            </div>
            
            <p class="text-gray-400 text-xs uppercase tracking-wide mb-1"><?= $acc->account_name ?></p>
            <h2 class="text-2xl font-bold text-white font-mono mb-1 tracking-wider select-all"><?= $acc->account_number ?></h2>
            <p class="text-sm text-brand-green font-bold">a.n <?= $acc->account_holder_name ?></p>
        </div>
        <?php endforeach; else: ?>
            <div class="col-span-2 text-center text-gray-500 italic p-4">Belum ada rekening aktif yang tersedia.</div>
        <?php endif; ?>
    </div>

    <!-- Notifikasi -->
    <?php if($this->session->flashdata('success')): ?>
        <div class="mb-8 bg-green-900/30 border border-green-500/50 text-green-200 p-4 rounded-lg text-center flex items-center justify-center gap-2">
            <i class="fas fa-check-circle text-xl"></i> 
            <span><?= $this->session->flashdata('success'); ?></span>
        </div>
    <?php endif; ?>

    <!-- Formulir Donasi -->
    <?= form_open_multipart('web/submit_donasi', ['class' => 'bg-[#121212] p-8 md:p-10 rounded-2xl border border-gray-800 shadow-xl relative']) ?>
        
        <div class="space-y-8">
            <!-- Pilihan Rekening Tujuan (Wajib) -->
            <div>
                <label class="block text-xs uppercase text-gray-500 font-bold mb-3 ml-1">Transfer ke Rekening Mana? <span class="text-brand-green">*</span></label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php if(!empty($accounts)): foreach($accounts as $acc): ?>
                    <label class="cursor-pointer relative">
                        <input type="radio" name="account_id" value="<?= $acc->id ?>" class="peer sr-only" required>
                        <div class="p-4 rounded-lg border border-gray-700 bg-black hover:bg-gray-900 peer-checked:border-brand-green peer-checked:bg-brand-green/10 transition-all flex items-center gap-3">
                            <div class="w-4 h-4 rounded-full border border-gray-500 peer-checked:bg-brand-green peer-checked:border-brand-green"></div>
                            <div>
                                <p class="text-sm font-bold text-white"><?= $acc->account_name ?></p>
                                <p class="text-xs text-gray-500"><?= $acc->account_number ?></p>
                            </div>
                        </div>
                    </label>
                    <?php endforeach; endif; ?>
                </div>
            </div>

            <!-- Grid Nama & Email -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs uppercase text-gray-500 font-bold mb-2 ml-1">Nama Donatur</label>
                    <div class="relative">
                        <span class="absolute left-4 top-4 text-gray-600"><i class="fas fa-user"></i></span>
                        <input type="text" name="donor_name" placeholder="Nama Lengkap" class="w-full bg-black border border-gray-700 p-4 pl-10 rounded-lg focus:border-brand-green focus:ring-1 focus:ring-brand-green outline-none text-white transition-all placeholder-gray-700">
                    </div>
                    <!-- Opsi Anonim -->
                    <div class="mt-3 flex items-center gap-2 ml-1">
                        <input type="checkbox" name="is_anonymous" id="anon" value="1" class="w-4 h-4 rounded bg-black border-gray-600 text-brand-green focus:ring-brand-green">
                        <label for="anon" class="text-xs text-gray-400 select-none cursor-pointer">Sembunyikan nama saya (Hamba Allah)</label>
                    </div>
                </div>
                <div>
                    <label class="block text-xs uppercase text-gray-500 font-bold mb-2 ml-1">Email <span class="text-brand-green">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-4 text-gray-600"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="donor_email" placeholder="email@anda.com" class="w-full bg-black border border-gray-700 p-4 pl-10 rounded-lg focus:border-brand-green focus:ring-1 focus:ring-brand-green outline-none text-white transition-all placeholder-gray-700" required>
                    </div>
                    <p class="text-[10px] text-gray-600 mt-1 ml-1">Untuk konfirmasi verifikasi donasi.</p>
                </div>
            </div>

            <!-- Input Jumlah Donasi -->
            <div>
                <label class="block text-xs uppercase text-gray-500 font-bold mb-2 ml-1">Jumlah Donasi (Rp) <span class="text-brand-green">*</span></label>
                <div class="relative group">
                    <span class="absolute left-4 top-4 text-brand-green font-bold">Rp</span>
                    <input type="number" name="amount" placeholder="0" class="w-full bg-black border border-gray-700 p-4 pl-12 rounded-lg focus:border-brand-green focus:ring-1 focus:ring-brand-green outline-none text-white transition-all font-mono text-lg placeholder-gray-700 group-hover:border-gray-600" required>
                </div>
                <div class="flex gap-2 mt-3 overflow-x-auto pb-1">
                    <button type="button" onclick="document.querySelector('[name=amount]').value=50000" class="text-xs border border-gray-700 px-3 py-1 rounded-full text-gray-400 hover:bg-gray-800 hover:text-white transition">Rp 50.000</button>
                    <button type="button" onclick="document.querySelector('[name=amount]').value=100000" class="text-xs border border-gray-700 px-3 py-1 rounded-full text-gray-400 hover:bg-gray-800 hover:text-white transition">Rp 100.000</button>
                    <button type="button" onclick="document.querySelector('[name=amount]').value=500000" class="text-xs border border-gray-700 px-3 py-1 rounded-full text-gray-400 hover:bg-gray-800 hover:text-white transition">Rp 500.000</button>
                </div>
            </div>

            <!-- Input Pesan -->
            <div>
                <label class="block text-xs uppercase text-gray-500 font-bold mb-2 ml-1">Pesan / Doa (Opsional)</label>
                <textarea name="message" placeholder="Tuliskan pesan dukungan..." class="w-full bg-black border border-gray-700 p-4 rounded-lg h-24 focus:border-brand-green focus:ring-1 focus:ring-brand-green outline-none text-white transition-all placeholder-gray-700 resize-none"></textarea>
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
    
    <div class="text-center mt-8 text-gray-600 text-xs">
        <i class="fas fa-lock mr-1"></i> Data Anda diamankan dan tidak akan dipublikasikan secara lengkap.
    </div>
</div>
