<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[AllowDynamicProperties]
class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form'); 
        $this->load->model('Admin_model');

        // Cek Login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }

    // Redirect Dashboard sesuai Role
    public function index() {
        $role = $this->session->userdata('role');
        if ($role == 'finance') redirect('admin/finance');
        elseif ($role == 'field_coordinator') redirect('admin/content');
        else redirect('admin/super');
    }

    // ... (Fungsi Finance sama seperti sebelumnya) ...
    public function finance() {
        if (!in_array($this->session->userdata('role'), ['finance', 'super_admin'])) show_404();
        $data['title'] = "Finance Dashboard";
        $data['user'] = $this->session->userdata();
        $data['saldo'] = $this->Admin_model->get_balance();
        $data['donasi'] = $this->Admin_model->get_recent_donations(10);
        $data['pengeluaran'] = $this->Admin_model->get_recent_expenses(10);
        $data['chart_data'] = $this->Admin_model->get_expense_chart_data();
        $data['content'] = 'admin/finance';
        $this->load->view('layout/admin', $data);
    }

    public function add_transaction() {
        $type = $this->input->post('type');
        $amount = $this->input->post('amount');
        $account_id = 1;

        if ($type == 'out') {
            $data = [
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'amount' => $amount,
                'category' => $this->input->post('category'),
                'transaction_date' => $this->input->post('date'),
                'account_id' => $account_id,
                'created_by' => $this->session->userdata('user_id')
            ];
            $this->Admin_model->insert_expense($data);
        } else {
            $data = [
                'donor_name' => 'Manual Input (Admin)',
                'donor_email' => 'admin@local',
                'amount' => $amount,
                'message' => $this->input->post('title'),
                'account_id' => $account_id,
                'status' => 'verified',
                'verified_by' => $this->session->userdata('user_id'),
                'verified_at' => date('Y-m-d H:i:s')
            ];
            $this->Admin_model->insert_income_manual($data);
        }
        $this->session->set_flashdata('success', 'Transaksi berhasil disimpan!');
        redirect('admin/finance');
    }

    // ==========================================
    // BAGIAN 2: CONTENT & LAPORAN (Orang 3 - Yanu)
    // ==========================================
    public function content() {
        if (!in_array($this->session->userdata('role'), ['field_coordinator', 'super_admin'])) show_404();

        $data['title'] = "Content & Volunteer Dashboard";
        $data['user'] = $this->session->userdata();
        
        // Data Reports
        $data['reports'] = $this->Admin_model->get_reports();

        // Data Events (DENGAN JUMLAH PENDAFTAR)
        $events = $this->Admin_model->get_events();
        
        // Loop untuk menghitung pendaftar per event
        foreach ($events as &$ev) {
            // Cek tabel volunteers (cegah error kalau tabel belum dibuat)
            if ($this->db->table_exists('volunteers')) {
                $ev->registered_count = $this->db->where('event_id', $ev->id)->count_all_results('volunteers');
            } else {
                $ev->registered_count = 0; 
            }
        }
        $data['events'] = $events;

        $data['content'] = 'admin/content';
        $this->load->view('layout/admin', $data);
    }

    // --- LOGIC VALIDASI STATUS ---
    public function update_report_status($id, $status) {
        if (!in_array($this->session->userdata('role'), ['field_coordinator', 'super_admin'])) show_404();

        $current_report = $this->db->get_where('waste_reports', ['id' => $id])->row();

        if ($current_report) {
            if ($current_report->status == 'in_progress' && $status == 'rejected') {
                $this->session->set_flashdata('error', 'Gagal! Laporan yang sedang diproses tidak bisa ditolak.');
                redirect('admin/content');
                return;
            }
        }

        $this->Admin_model->update_report_status($id, $status, null);
        $msg = ($status == 'rejected') ? 'Laporan ditolak.' : 'Status laporan diupdate menjadi: ' . $status;
        $this->session->set_flashdata('success', $msg);
        redirect('admin/content');
    }

    // --- LOGIC SELESAIKAN LAPORAN (UPLOAD AFTER) ---
    public function resolve_report() {
        if (!in_array($this->session->userdata('role'), ['field_coordinator', 'super_admin'])) show_404();

        $report_id = $this->input->post('report_id');

        // Config Upload
        $path = FCPATH . 'uploads/reports/';
        if (!is_dir($path)) mkdir($path, 0777, true);

        $config['upload_path']   = $path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']      = 10240; 
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        $image_after = null;

        if ( ! $this->upload->do_upload('image_after')) {
            $error = $this->upload->display_errors();
            $image_after = 'default_after.jpg'; 
            $this->session->set_flashdata('error', 'Upload Warning: ' . $error);
        } else {
            $data = $this->upload->data();
            $image_after = $data['file_name'];
        }

        $this->Admin_model->update_report_status($report_id, 'resolved', $image_after);
        $this->session->set_flashdata('success', 'Laporan selesai! Slider Before-After berhasil dibuat.');
        redirect('admin/content');
    }

    // ==========================================
    // BAGIAN 3: MANAJEMEN EVENT (Orang 4 & 3)
    // ==========================================
    
    // --- FUNGSI ADD EVENT (Dengan Upload) ---
    public function add_event() {
        // Setup Upload Path
        $path = FCPATH . 'uploads/events/';
        if (!is_dir($path)) mkdir($path, 0777, true);

        $banner_image = null;

        // Upload Logic
        if (!empty($_FILES['banner_image']['name'])) {
            $config['upload_path']   = $path;
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']      = 5000;
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

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

        $this->Admin_model->insert_event($data);
        $this->session->set_flashdata('success', 'Event berhasil ditambahkan!');
        
        if($this->session->userdata('role') == 'super_admin') redirect('admin/super');
        else redirect('admin/content');
    }

    // --- FUNGSI EDIT EVENT (FIX 404 NOT FOUND) ---
    public function edit_event() {
        $id = $this->input->post('event_id');
        if(!$id) show_404();

        // 1. Ambil Data Lama (untuk cek foto lama jika tidak upload baru)
        // Note: Sebaiknya buat fungsi get_event_by_id di Model, tapi kita query manual dulu biar cepet
        $old_data = $this->db->get_where('volunteer_events', ['id' => $id])->row();

        // 2. Siapkan Data Update
        $data = [
            'event_name' => $this->input->post('event_name'),
            'description' => $this->input->post('description'),
            'event_date' => $this->input->post('event_date'),
            'location' => $this->input->post('location'),
            'status' => $this->input->post('status')
        ];

        // 3. Cek Upload Foto Baru
        if (!empty($_FILES['banner_image']['name'])) {
            $path = FCPATH . 'uploads/events/';
            if (!is_dir($path)) mkdir($path, 0777, true);

            $config['upload_path']   = $path;
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']      = 5000;
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('banner_image')) {
                $upload_data = $this->upload->data();
                $data['banner_image_url'] = $upload_data['file_name']; // Update nama file
            }
        }

        // 4. Update Database
        $this->db->where('id', $id);
        $this->db->update('volunteer_events', $data);

        $this->session->set_flashdata('success', 'Data Event berhasil diperbarui!');
        
        if($this->session->userdata('role') == 'super_admin') redirect('admin/super');
        else redirect('admin/content');
    }

    public function delete_event($id) {
        $this->Admin_model->delete_event($id);
        $this->session->set_flashdata('success', 'Event dihapus.');
        if($this->session->userdata('role') == 'super_admin') redirect('admin/super');
        else redirect('admin/content');
    }

    // --- (Fungsi Super Admin & Lainnya Tetap Ada di Bawah) ---
    public function super() {
        if ($this->session->userdata('role') !== 'super_admin') show_404();
        $data['title'] = "Super Admin Control Panel";
        $data['user'] = $this->session->userdata();
        $data['admins'] = $this->Admin_model->get_all_admins();
        $data['count_admin'] = count($data['admins']);
        $data['count_event'] = $this->Admin_model->get_count('volunteer_events');
        $data['count_report'] = $this->Admin_model->get_count('waste_reports');
        $data['total_fund'] = $this->Admin_model->get_balance();
        $data['content'] = 'admin/super';
        $this->load->view('layout/admin', $data);
    }

    public function add_admin() {
        $data = [
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'role' => $this->input->post('role'),
            'password_hash' => password_hash($this->input->post('password'), PASSWORD_BCRYPT)
        ];
        $this->Admin_model->insert_admin($data);
        $this->session->set_flashdata('success', 'Admin baru berhasil dibuat!');
        redirect('admin/super');
    }

    public function delete_admin($id) {
        if($id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Tidak bisa menghapus diri sendiri!');
        } else {
            $this->Admin_model->delete_admin($id);
            $this->session->set_flashdata('success', 'Admin dihapus.');
        }
        redirect('admin/super');
    }
}