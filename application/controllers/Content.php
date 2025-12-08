<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[AllowDynamicProperties]
class Content extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form'); 
        $this->load->model('Content_model'); // Load Content Model baru

        // Cek Login dan Role
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        if (!in_array($this->session->userdata('role'), ['field_coordinator', 'super_admin'])) {
            show_404(); // Hanya boleh diakses oleh Content & Super Admin
        }
    }

    // ==========================================
    // BAGIAN 1: CONTENT & LAPORAN DASHBOARD
    // ==========================================
    public function index() {
        $data['title'] = "Content & Volunteer Dashboard";
        $data['user'] = $this->session->userdata();
        $data['reports'] = $this->Content_model->get_reports();

        $events = $this->Content_model->get_events();
        foreach ($events as &$ev) {
            if ($this->db->table_exists('volunteers')) {
                // Gunakan DB dari CI instance (Model tidak dipakai di sini)
                $ev->registered_count = $this->db->where('event_id', $ev->id)->count_all_results('volunteers');
            } else {
                $ev->registered_count = 0; 
            }
        }
        $data['events'] = $events;

        $data['content'] = 'admin/content';
        $this->load->view('layout/lay_admin', $data);
    }

    public function update_report_status($id, $status) {
        $current_report = $this->db->get_where('waste_reports', ['id' => $id])->row();
        if ($current_report && $current_report->status == 'in_progress' && $status == 'rejected') {
            $this->session->set_flashdata('error', 'Gagal! Laporan yang sedang diproses tidak bisa ditolak.');
            redirect('content');
            return;
        }

        $this->Content_model->update_report_status($id, $status, null);
        $this->session->set_flashdata('success', 'Status laporan diperbarui.');
        redirect('content');
    }

    public function resolve_report() {
        $report_id = $this->input->post('report_id');
        
        $path = FCPATH . 'uploads/reports/';
        if (!is_dir($path)) mkdir($path, 0777, true);
        
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = 10240;
        $config['encrypt_name'] = TRUE;
        
        $this->load->library('upload', $config);
        
        $image_after = null; // Ubah default menjadi null
        if ($this->upload->do_upload('image_after')) {
            $data = $this->upload->data();
            $image_after = $data['file_name'];
            
            $this->Content_model->update_report_status($report_id, 'resolved', $image_after);
            $this->session->set_flashdata('success', 'Laporan selesai! Foto berhasil diupload.');
        } else {
            $error = $this->upload->display_errors();
            // Jika upload gagal, status tetap resolved, tapi foto after null (atau gunakan default jika ada)
            $this->Content_model->update_report_status($report_id, 'resolved', $image_after); 
            $this->session->set_flashdata('warning', 'Laporan selesai, tapi upload foto gagal: ' . $error);
        }

        redirect('content');
    }

    // ==========================================
    // BAGIAN 2: MANAJEMEN EVENT
    // ==========================================
    public function add_event() {
        $path = FCPATH . 'uploads/events/';
        if (!is_dir($path)) mkdir($path, 0777, true);
        $banner_image = null;

        if (!empty($_FILES['banner_image']['name'])) {
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 5000;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if ($this->upload->do_upload('banner_image')) {
                $upload_data = $this->upload->data();
                $banner_image = $upload_data['file_name'];
            }
        }

        $data = [
            'event_name' => $this->input->post('event_name'),
            'description' => $this->input->post('description'),
            'event_date' => $this->input->post('event_date'),
            'location' => $this->input->post('location'),
            'status' => $this->input->post('status'),
            'banner_image_url' => $banner_image
        ];

        $this->Content_model->insert_event($data);
        $this->session->set_flashdata('success', 'Event berhasil ditambahkan!');
        redirect('content');
    }

    public function edit_event() {
        $id = $this->input->post('event_id');
        if(!$id) show_404();

        $data = [
            'event_name' => $this->input->post('event_name'),
            'description' => $this->input->post('description'),
            'event_date' => $this->input->post('event_date'),
            'location' => $this->input->post('location'),
            'status' => $this->input->post('status')
        ];

        if (!empty($_FILES['banner_image']['name'])) {
            $path = FCPATH . 'uploads/events/';
            if (!is_dir($path)) mkdir($path, 0777, true);
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 5000;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if ($this->upload->do_upload('banner_image')) {
                $upload_data = $this->upload->data();
                $data['banner_image_url'] = $upload_data['file_name'];
            }
        }

        $this->Content_model->update_event($id, $data);
        $this->session->set_flashdata('success', 'Data Event berhasil diperbarui!');
        redirect('content');
    }

    public function delete_event($id) {
        $this->Content_model->delete_event($id);
        $this->session->set_flashdata('success', 'Event dihapus.');
        redirect('content');
    }

    public function get_event_volunteers($event_id) {
        if (!$this->session->userdata('logged_in')) return;
        $data = $this->Content_model->get_event_volunteers($event_id);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}