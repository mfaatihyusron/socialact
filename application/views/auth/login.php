<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SocialAct</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-900 flex items-center justify-center h-screen">

    <div class="bg-gray-800 p-8 rounded-xl shadow-2xl w-full max-w-md border border-gray-700">
        <div class="text-center mb-8">
            <h1 class="font-[Oswald] text-3xl text-white uppercase tracking-wider">Admin <span class="text-green-500">Panel</span></h1>
            <p class="text-gray-400 text-sm mt-2">Silakan masuk untuk mengelola sistem.</p>
        </div>

        <?php if($this->session->flashdata('error')): ?>
            <div class="bg-red-900/50 text-red-200 p-3 rounded mb-4 text-sm text-center border border-red-800">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/process') ?>" method="POST" class="space-y-6">
            <div>
                <label class="block text-gray-400 text-xs font-bold uppercase mb-2">Email Official</label>
                <input type="email" name="email" class="w-full bg-gray-900 border border-gray-700 text-white rounded p-3 focus:outline-none focus:border-green-500 transition-colors" placeholder="nama@social.org" required>
            </div>

            <div>
                <label class="block text-gray-400 text-xs font-bold uppercase mb-2">Password</label>
                <input type="password" name="password" class="w-full bg-gray-900 border border-gray-700 text-white rounded p-3 focus:outline-none focus:border-green-500 transition-colors" placeholder="••••••••" required>
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded uppercase tracking-widest transition-all shadow-lg shadow-green-900/20">
                Masuk Sistem
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <a href="<?= base_url() ?>" class="text-gray-500 text-xs hover:text-white transition-colors">Kembali ke Website Utama</a>
        </div>
    </div>

</body>
</html>