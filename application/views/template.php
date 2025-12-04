  <?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

    <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: Arial, sans-serif; background: #f5f7fb; color: #222; }

    /* Navbar */
    header {
      background: #fff;
      border-bottom: 1px solid #ddd;
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .navbar {
      max-width: 1000px;
      margin: auto;
      padding: 12px 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .logo {
      font-size: 1.2rem;
      font-weight: bold;
      color: #0b2546;
    }
    nav a {
      text-decoration: none;
      color: #333;
      margin-left: 20px;
      font-size: 0.95rem;
    }
    nav a:hover {
      color: #0057ff;
    }

    /* Konten utama */
    main {
      max-width: 1000px;
      margin: 30px auto;
      padding: 0 16px;
    }
    .content {
      background: #fff;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    /* Responsif */
    @media (max-width: 600px) {
      nav a { margin-left: 10px; font-size: 0.9rem; }
      .navbar { flex-direction: column; align-items: flex-start; }
    }
    </style>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #cffff9ff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
		text-decoration: none;
	}

	a:hover {
		color: #97310e;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
		min-height: 96px;
	}

	p {
		margin: 0 0 10px;
		padding:0;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
</head>
<body>

    <header>
        <div class="navbar">
            <div class="Logo">Belajar pemrograman Web</div>
            <nav>
                <a href="<?php echo site_url('praktek/latihan1'); ?>">Login</a>
                <a href="<?php echo site_url('praktek/formvalidasi'); ?>">Validasi</a>
            </nav>
        </div>        
    </header>

    <main>
        <div class="content">
            <?php 
            if (isset($content) && $content !== '') {
                $this->load->view($content);
            } else {
                echo "<h2>Selamat Datang di Pemrograman Web</h2>
                <p>Ini adalah area konten belajar pemrograman web.</p>";
            }
            ?>
        </div>
    </main>

</body>
</html>
