<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Praktik - CodeIgniter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* CSS untuk footer agar selalu di bawah */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .content-wrapper {
            flex: 1; /* Konten mengambil sisa ruang yang tersedia */
            padding-top: 20px; /* Tambahkan sedikit padding agar tidak terlalu mepet navbar */
            padding-bottom: 20px; /* Tambahkan sedikit padding agar tidak terlalu mepet footer */
        }
        .footer {
            flex-shrink: 0; /* Mencegah footer menyusut */
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo base_url(); ?>">Aplikasi Praktik CI</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?php echo base_url(); ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('praktek/formvalidasi'); ?>">Form Validasi</a>
                    </li>
                    </ul>
            </div>
        </div>
    </nav>
    <div class="container content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <?php
                // Logika untuk memuat konten
                // Jika $content ada (misalnya 'formvalidasi'), maka akan memuat view tersebut.
                // Jika tidak ada (misalnya pada fungsi index), maka akan menampilkan konten default/kosong.
                if (isset($content)) {
                    $this->load->view($content);
                } else {
                    echo "<h1>Tidak ditemukan content!</h1>";
                }
                ?>
            </div>
        </div>
    </div>
    <footer class="footer mt-auto py-3 bg-dark text-white">
        <div class="container text-center">
            <span>&copy; <?php echo date('Y'); ?> Aplikasi Praktik. Dibuat dengan CodeIgniter.</span>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>