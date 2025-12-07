<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Web extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // $this->load->model('Main_model'); // Load model jika ada
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('session');
    }

    // 1. HALAMAN HOME
    public function index() {
        $data['title'] = "Home - SocialAct";
        // Tentukan file konten yang akan dimuat
        $data['content'] = 'content/home'; 
        // Load layout utama, kirim variabel $content
        $this->load->view('layout/main', $data);
    }

    // 2. HALAMAN TRANSPARANSI
    public function transparansi() {
        $data['title'] = "Transparansi Dana - SocialAct";
        
        // Contoh Data Dummy (Nanti diganti dari Database)
        $data['saldo'] = 25000000;
        $data['total_masuk'] = 45000000;
        $data['total_keluar'] = 20000000;
        $data['pengeluaran'] = []; // Isi array object dari DB
        $data['chart_data'] = [
            ['category' => 'Logistik', 'total' => 10000000],
            ['category' => 'Operasional', 'total' => 5000000]
        ];

        $data['content'] = 'content/transparansi';
        $this->load->view('layout/main', $data);
    }

    // 3. HALAMAN LAPOR
    public function lapor() {
        $data['title'] = "Lapor Sampah - SocialAct";
        $data['content'] = 'content/lapor';
        $this->load->view('layout/main', $data);
    }

    // 4. HALAMAN VOLUNTEER
    public function volunteer() {
        $data['title'] = "Volunteer Hub - SocialAct";
        $data['content'] = 'content/volunteer';
        $this->load->view('layout/main', $data);
    }
}