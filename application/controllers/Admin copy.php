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

    public function index() {
        $role = $this->session->userdata('role');
        if ($role == 'finance') redirect('admin/finance');
        elseif ($role == 'field_coordinator') redirect('admin/content');
        else redirect('admin/super');
    }

    // ==========================================
    // BAGIAN 1: FINANCE DASHBOARD (GABUNGAN)
    // ==========================================
    public function finance() {
        if (!in_array($this->session->userdata('role'), ['finance', 'super_admin'])) show_404();

        $data['title'] = "Finance Dashboard";
        $data['user'] = $this->session->userdata();
        
        // Data Akun (Dari Temen Lu)
        $data['accounts'] = $this->Admin_model->get_all_accounts();

        $data['saldo'] = $this->Admin_model->get_balance();
        $data['donasi'] = $this->Admin_model->get_recent_donations(10);
        $data['pengeluaran'] = $this->Admin_model->get_recent_expenses(20); 
        $data['chart_data'] = $this->Admin_model->get_expense_chart_data();
        
        $data['content'] = 'admin/finance';
        $this->load->view('layout/lay_admin', $data);
    }

    public function add_transaction() {
        $type = $this->input->post('type');
        $amount = $this->input->post('amount');
        $account_id = $this->input->post('account_id'); // Ambil ID Akun

        if ($type == 'out') {
            // --- FIX UPLOAD FINANCE (ANTI MACET) ---
            $path = FCPATH . 'uploads/expenses/';
            if (!is_dir($path)) mkdir($path, 0777, true);

            $config['upload_path']   = $path;
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
            $config['max_size']      = 5120; 
            $config['encrypt_name']  = TRUE;
            
            $this->load->library('upload', $config);

            $receipt_url = null;
            $item_url = null;

            // Upload Struk
            if (!empty($_FILES['receipt_image']['name'])) {
                if ($this->upload->do_upload('receipt_image')) {
                    $up = $this->upload->data();
                    $receipt_url = $up['file_name'];
                }
            }

            // Upload Barang (Reset Config)
            $this->upload->initialize($config); 
            if (!empty($_FILES['item_image']['name'])) {
                if ($this->upload->do_upload('item_image')) {
                    $up = $this->upload->data();
                    $item_url = $up['file_name'];
                }
            }

            $data = [
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'amount' => $amount,
                'category' => $this->input->post('category'),
                'transaction_date' => $this->input->post('date'),
                'account_id' => $account_id,
                'receipt_image_url' => $receipt_url,
                'item_image_url' => $item_url,
                'created_by' => $this->session->userdata('user_id')
            ];
            
            $this->Admin_model->insert_expense($data);

        } else {
            // Pemasukan Manual
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

    // --- FITUR BARU TEMEN LU: UPDATE EXPENSE ---
    public function update_expense() {
        $id = $this->input->post('expense_id');
        $old_data = $this->Admin_model->get_expense_by_id($id);
        if(!$old_data) show_404();

        // Fix Upload Edit
        $path = FCPATH . 'uploads/expenses/';
        if (!is_dir($path)) mkdir($path, 0777, true);
        
        $config['upload_path']   = $path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
        $config['max_size']      = 5120;
        $config['encrypt_name']  = TRUE;
        $this->load->library('upload', $config);

        $receipt_url = $old_data->receipt_image_url;
        $item_url = $old_data->item_image_url;

        if (!empty($_FILES['receipt_image']['name'])) {
            if ($this->upload->do_upload('receipt_image')) {
                $up = $this->upload->data();
                $receipt_url = $up['file_name'];
            }
        }

        $this->upload->initialize($config);
        if (!empty($_FILES['item_image']['name'])) {
            if ($this->upload->do_upload('item_image')) {
                $up = $this->upload->data();
                $item_url = $up['file_name'];
            }
        }

        $data = [
            'title' => $this->input->post('title'),
            'description' => $this->input->post('description'),
            'amount' => $this->input->post('amount'),
            'category' => $this->input->post('category'),
            'transaction_date' => $this->input->post('date'),
            'account_id' => $this->input->post('account_id'),
            'receipt_image_url' => $receipt_url,
            'item_image_url' => $item_url,
        ];

        $this->Admin_model->update_expense($id, $data, $old_data->amount);
        $this->session->set_flashdata('success', 'Transaksi berhasil diperbarui!');
        redirect('admin/finance');
    }

    // --- FITUR BARU TEMEN LU: DELETE EXPENSE ---
    public function delete_expense($id) {
        if ($this->Admin_model->delete_expense($id)) {
            $this->session->set_flashdata('success', 'Transaksi dihapus & Saldo dikembalikan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus transaksi.');
        }
        redirect('admin/finance');
    }

    public function get_expense_json($id) {
        if (!$this->session->userdata('logged_in')) exit('No Access');
        $data = $this->Admin_model->get_expense_by_id($id);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    // ==========================================
    // BAGIAN 2: CONTENT & LAPORAN
    // ==========================================
    public function content() {
        if (!in_array($this->session->userdata('role'), ['field_coordinator', 'super_admin'])) show_404();

        $data['title'] = "Content & Volunteer Dashboard";
        $data['user'] = $this->session->userdata();
        $data['reports'] = $this->Admin_model->get_reports();

        $events = $this->Admin_model->get_events();
        foreach ($events as &$ev) {
            if ($this->db->table_exists('volunteers')) {
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
        if (!in_array($this->session->userdata('role'), ['field_coordinator', 'super_admin'])) show_404();
        
        $current_report = $this->db->get_where('waste_reports', ['id' => $id])->row();
        if ($current_report && $current_report->status == 'in_progress' && $status == 'rejected') {
            $this->session->set_flashdata('error', 'Gagal! Laporan yang sedang diproses tidak bisa ditolak.');
            redirect('admin/content');
            return;
        }

        $this->Admin_model->update_report_status($id, $status, null);
        $this->session->set_flashdata('success', 'Status laporan diperbarui.');
        redirect('admin/content');
    }

    public function resolve_report() {
        if (!in_array($this->session->userdata('role'), ['field_coordinator', 'super_admin'])) show_404();
        
        $report_id = $this->input->post('report_id');
        
        // Fix Upload Windows
        $path = FCPATH . 'uploads/reports/';
        if (!is_dir($path)) mkdir($path, 0777, true);
        
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = 10240;
        $config['encrypt_name'] = TRUE;
        
        $this->load->library('upload', $config);
        
        $image_after = 'default_after.jpg';
        if ($this->upload->do_upload('image_after')) {
            $data = $this->upload->data();
            $image_after = $data['file_name'];
            
            $this->Admin_model->update_report_status($report_id, 'resolved', $image_after);
            $this->session->set_flashdata('success', 'Laporan selesai! Foto berhasil diupload.');
        } else {
            $error = $this->upload->display_errors();
            $this->Admin_model->update_report_status($report_id, 'resolved', $image_after);
            $this->session->set_flashdata('warning', 'Laporan selesai, tapi upload foto gagal: ' . $error);
        }

        redirect('admin/content');
    }

    // ==========================================
    // BAGIAN 3: MANAJEMEN EVENT
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

        $this->Admin_model->insert_event($data);
        $this->session->set_flashdata('success', 'Event berhasil ditambahkan!');
        
        if($this->session->userdata('role') == 'super_admin') redirect('admin/super');
        else redirect('admin/content');
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

        $this->db->where('id', $id);
        $this->db->update('volunteer_events', $data);

        $this->session->set_flashdata('success', 'Data Event berhasil diperbarui!');
        
        if($this->session->userdata('role') == 'super_admin') redirect('admin/super');
        else redirect('admin/content');
    }

    public function delete_event($id) {
        $this->Admin_model->delete_event($id);
        $this->session->set_flashdata('success', 'Event dihapus.');
        redirect('admin/content');
    }

    public function get_event_volunteers($event_id) {
        if (!$this->session->userdata('logged_in')) return;
        $this->db->where('event_id', $event_id);
        $this->db->order_by('registered_at', 'DESC');
        $data = $this->db->get('volunteers')->result();
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    
    // --- SUPER ADMIN ---
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
        $this->load->view('layout/lay_admin', $data);
    }

    public function add_admin() {
        if ($this->session->userdata('role') !== 'super_admin') redirect('admin');
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
        if ($this->session->userdata('role') !== 'super_admin') redirect('admin');
        if($id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Tidak bisa menghapus diri sendiri!');
        } else {
            $this->Admin_model->delete_admin($id);
            $this->session->set_flashdata('success', 'Admin dihapus.');
        }
        redirect('admin/super');
    }
}
