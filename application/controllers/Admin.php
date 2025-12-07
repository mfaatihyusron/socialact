<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[AllowDynamicProperties]
class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
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

    // ==========================================
    // 1. ADMIN FINANCE DASHBOARD
    // ==========================================
    public function finance() {
        // Cek Akses
        if (!in_array($this->session->userdata('role'), ['finance', 'super_admin'])) show_404();

        $data['title'] = "Finance Dashboard";
        $data['user'] = $this->session->userdata();
        
        // Data DB
        $data['saldo'] = $this->Admin_model->get_balance();
        $data['donasi'] = $this->Admin_model->get_recent_donations(10);
        $data['pengeluaran'] = $this->Admin_model->get_recent_expenses(10);
        $data['chart_data'] = $this->Admin_model->get_expense_chart_data();

        // Load View
        $data['content'] = 'admin/finance';
        $this->load->view('layout/admin', $data);
    }

    public function add_transaction() {
        $type = $this->input->post('type'); // 'in' or 'out'
        $amount = $this->input->post('amount');
        $account_id = 1; // Default Account Utama

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
    // 2. ADMIN CONTENT DASHBOARD
    // ==========================================
    public function content() {
        if (!in_array($this->session->userdata('role'), ['field_coordinator', 'super_admin'])) show_404();

        $data['title'] = "Content & Volunteer Dashboard";
        $data['user'] = $this->session->userdata();
        
        $data['events'] = $this->Admin_model->get_events();
        $data['reports'] = $this->Admin_model->get_reports();

        $data['content'] = 'admin/content';
        $this->load->view('layout/admin', $data);
    }

    public function add_event() {
        $data = [
            'event_name' => $this->input->post('event_name'),
            'event_date' => $this->input->post('event_date'),
            'location' => $this->input->post('location'),
            'status' => $this->input->post('status')
        ];
        $this->Admin_model->insert_event($data);
        $this->session->set_flashdata('success', 'Event berhasil ditambahkan!');
        redirect('admin/content');
    }

    public function delete_event($id) {
        $this->Admin_model->delete_event($id);
        redirect('admin/content');
    }

    public function verify_report($id, $status) {
        // Simulasi update status laporan
        // Pada aplikasi nyata, Anda mungkin perlu upload foto "After" di sini
        $this->Admin_model->update_report_status($id, $status);
        redirect('admin/content');
    }

    // ==========================================
    // 3. SUPER ADMIN DASHBOARD
    // ==========================================
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