<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
*/

// 1. HALAMAN PUBLIK (Landing Page & Fitur Utama)
// Mengarah ke application/controllers/Web.php
$route['default_controller'] = 'web';
$route['transparansi']       = 'web/transparansi';
$route['lapor']              = 'web/lapor';
$route['volunteer']          = 'web/volunteer';
$route['donasi']             = 'web/donasi';

// 2. RUTE AKSI FORM (Handling Submit)
// Agar form action 'lapor/submit' di view tetap bekerja meski controller aslinya bernama 'Web'
$route['lapor/submit']       = 'web/submit_lapor';   // Pastikan buat fungsi submit_lapor di Web.php nanti
$route['donasi/submit']      = 'web/submit_donasi';  // Pastikan buat fungsi submit_donasi di Web.php nanti

// 3. AUTENTIKASI (Login/Logout)
// Mengarah ke application/controllers/Auth.php (Perlu dibuat terpisah)
$route['login']              = 'auth/login';
$route['register']           = 'auth/register';
$route['logout']             = 'auth/logout';

// 4. ADMIN DASHBOARD (Sesuai Role di Tugas SIM)
// Mengarah ke application/controllers/Admin.php (Perlu dibuat terpisah)
$route['admin']              = 'admin/index';        // Dashboard Utama / Login Redirect
$route['admin/finance']      = 'admin/finance';      // Dashboard Orang 1 (Finance)
$route['admin/verifikasi']   = 'admin/verifikasi';   // Dashboard Orang 2 (Verifikasi)
$route['admin/content']      = 'admin/content';      // Dashboard Orang 3 (Konten/Aduan)
$route['admin/super']        = 'admin/super';        // Dashboard Orang 4 (Super Admin)

// 5. STANDARD CI ROUTES
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Route Auth
$route['login'] = 'auth/index';
$route['auth/process'] = 'auth/process';
$route['logout'] = 'auth/logout';

// Route Admin (Secure)
$route['admin'] = 'admin/index'; // Auto redirect sesuai role
$route['admin/dashboard'] = 'admin/dashboard';
$route['admin/finance'] = 'admin/finance';
$route['admin/content'] = 'admin/content';
$route['admin/super'] = 'admin/super';
