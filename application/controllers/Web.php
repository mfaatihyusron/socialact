<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[AllowDynamicProperties]
class Web extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('App_model');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->database();
    }

    public function index() {
        $data['title'] = "Home - SocialAct";
        $data['content'] = 'content/home'; 
        $this->load->view('layout/main', $data);
    }

    public function transparansi() {
        $data['title'] = "Transparansi Dana - SocialAct";
        
        $data['saldo'] = $this->App_model->get_saldo();
        $data['total_masuk'] = $this->App_model->get_total_masuk();
        $data['total_keluar'] = $this->App_model->get_total_keluar();
        $data['pengeluaran'] = $this->App_model->get_all_expenses();
        $data['donasi_masuk'] = $this->App_model->get_verified_donations(); 
        
        // REVISI: Menggunakan Murni Data Database (Tanpa Dummy)
        $data['chart_data'] = $this->App_model->get_chart_data();
        
        $data['content'] = 'content/transparansi';
        $this->load->view('layout/main', $data);
    }

    public function lapor() {
        $data['title'] = "Lapor Sampah - SocialAct";
        $data['semua_laporan'] = $this->App_model->get_all_reports(); 
        $data['laporan_selesai'] = $this->App_model->get_resolved_reports(); 
        $data['content'] = 'content/lapor';
        $this->load->view('layout/main', $data);
    }

    // --- FIX UPLOAD LAPOR (MERGED: Logic Teman + Nama Method Lama) ---
    public function submit_laporan() {
        // Menggunakan FCPATH sesuai update teman agar path absolut server terbaca
        $path = FCPATH . 'uploads/reports/';
        if (!is_dir($path)) mkdir($path, 0777, true);

        // Logging untuk debugging (Fitur teman)
        log_message('debug', 'Upload path: ' . $path);

        $config['upload_path']   = $path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']      = 10240; // 10MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ( ! $this->upload->do_upload('image_before')) {
            $error = $this->upload->display_errors();
            log_message('error', 'Upload failed: ' . $error);
            $this->session->set_flashdata('error', 'Upload foto gagal: ' . $error);
            redirect('web/lapor');
        } else {
            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name']; 
            
            $db_path = 'uploads/reports/' . $file_name;

            $data = [
                'reporter_name' => $this->input->post('reporter_name'),
                'reporter_contact' => $this->input->post('reporter_contact'),
                'location_address' => $this->input->post('location_address'),
                'latitude' => $this->input->post('latitude'),
                'longitude' => $this->input->post('longitude'),
                'description' => $this->input->post('description'),
                'image_before_url' => $db_path,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'views' => 0
            ];

            if($this->App_model->insert_report($data)) {
                $this->session->set_flashdata('success', 'Laporan berhasil dikirim! Menunggu verifikasi admin.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan ke database.');
            }
            redirect('web/lapor');
        }
    }

    // --- FITUR DONASI (MERGED: Logic Kita + Upload Teman) ---
    public function donasi() {
        $data['title'] = "Donasi - SocialAct";
        // PENTING: Ambil data akun bank untuk dropdown/pilihan
        $data['accounts'] = $this->App_model->get_active_accounts();
        $data['content'] = 'content/donasi'; 
        $this->load->view('layout/main', $data);
    }

    public function submit_donasi() {
        $path = FCPATH . 'uploads/donations/';
        if (!is_dir($path)) mkdir($path, 0777, true);

        $config['upload_path']   = $path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
        $config['max_size']      = 5120; 
        $config['encrypt_name']  = TRUE; 

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        $proof_filename = null;
        if ($this->upload->do_upload('transfer_proof')) {
            $upload_data = $this->upload->data();
            $proof_filename = $upload_data['file_name']; 
        }

        $data = [
            'donor_name' => $this->input->post('donor_name') ?: 'Hamba Allah',
            'donor_email' => $this->input->post('donor_email'),
            'amount' => $this->input->post('amount'),
            'message' => $this->input->post('message'),
            'account_id' => $this->input->post('account_id'), 
            'is_anonymous' => $this->input->post('is_anonymous') ? 1 : 0,
            'transfer_proof_url' => $proof_filename,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->App_model->insert_donation($data);
        $this->session->set_flashdata('success', 'Terima kasih! Donasi Anda sedang diverifikasi.');
        
        redirect('web/donasi'); 
    }

    public function add_view($report_id) {
        if ($report_id) {
            $this->App_model->increment_views($report_id);
            echo json_encode(['status' => 'success', 'id' => $report_id]);
        }
    }

    public function volunteer() {
        $data['title'] = "Volunteer Hub - SocialAct";
        
        $events = $this->App_model->get_upcoming_events(); 
        foreach ($events as &$ev) {
            if ($this->db->table_exists('volunteers')) {
                $ev->registered_count = $this->db->where('event_id', $ev->id)->count_all_results('volunteers');
            } else {
                $ev->registered_count = 0; 
            }
        }

        $data['events'] = $events;
        $data['content'] = 'content/volunteer';
        $this->load->view('layout/main', $data);
    }

    public function register_volunteer() {
        if (!$this->db->table_exists('volunteers')) {
            $this->session->set_flashdata('error', 'Tabel volunteers belum dibuat.');
            redirect('web/volunteer');
            return;
        }

        $data = [
            'event_id' => $this->input->post('event_id'),
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            'gender' => $this->input->post('gender'),
            'age' => $this->input->post('age'),
            'domicile' => $this->input->post('domicile'),
            'experience' => $this->input->post('experience'),
            'motivation' => $this->input->post('motivation'),
            'registered_at' => date('Y-m-d H:i:s')
        ];

        if ($this->db->insert('volunteers', $data)) {
            $this->session->set_flashdata('success', 'Selamat ' . $data['name'] . '! Anda berhasil terdaftar.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mendaftar. Silakan coba lagi.');
        }
        
        redirect('web/volunteer');
    }
}
