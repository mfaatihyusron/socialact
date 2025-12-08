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
        $data['donasi'] = $this->App_model->get_verified_donations();
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

    // --- FIX UPLOAD LAPOR (PUBLIK) ---
    public function lapor_submit() {
        // Fix Windows Path
        $path = FCPATH . 'uploads/reports/';
        if (!is_dir($path)) mkdir($path, 0777, true);

        $config['upload_path']   = $path; 
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']      = 10240; 
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('image_before')) {
            $file_name = 'default.jpg';
        } else {
            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name'];
        }

        $data = [
            'reporter_name' => $this->input->post('reporter_name'),
            'reporter_contact' => $this->input->post('reporter_contact'),
            'location_address' => $this->input->post('location_address'),
            'latitude' => $this->input->post('latitude'),
            'longitude' => $this->input->post('longitude'),
            'description' => $this->input->post('description'),
            'image_before_url' => $file_name,
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

    // --- FITUR BARU TEMEN LU: SUBMIT DONASI ---
    public function submit_donasi() {
        $path = FCPATH . 'uploads/donations/';
        if (!is_dir($path)) mkdir($path, 0777, true);

        $config['upload_path']   = $path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
        $config['max_size']      = 5120; 
        $config['encrypt_name']  = TRUE; 

        $this->load->library('upload', $config);

        $proof_url = null;
        if ($this->upload->do_upload('transfer_proof')) {
            $upload_data = $this->upload->data();
            $proof_url = $upload_data['file_name'];
        }

        $data = [
            'donor_name' => $this->input->post('donor_name') ?: 'Hamba Allah',
            'donor_email' => $this->input->post('donor_email'),
            'amount' => $this->input->post('amount'),
            'message' => $this->input->post('message'),
            'transfer_proof_url' => $proof_url,
            'account_id' => 1, // Default BCA
            'status' => 'pending'
        ];

        $this->App_model->insert_donation($data);
        $this->session->set_flashdata('success', 'Terima kasih! Donasi Anda sedang diverifikasi.');
        redirect('web/transparansi'); // Redirect ke Transparansi
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
