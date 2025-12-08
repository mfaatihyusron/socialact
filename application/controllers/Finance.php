<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[AllowDynamicProperties]
class Finance extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form'); 
        $this->load->model('Finance_model');

        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        if (!in_array($this->session->userdata('role'), ['finance', 'super_admin'])) {
            show_404(); 
        }
    }

    public function index() {
        $data['title'] = "Finance Dashboard";
        $data['user'] = $this->session->userdata();
        
        // Filter Tanggal Pengeluaran
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $data['filter_active'] = ($start_date && $end_date);
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        // Data Utama
        $data['accounts'] = $this->Finance_model->get_all_accounts();
        $data['saldo'] = $this->Finance_model->get_total_balance();
        $data['chart_data'] = $this->Finance_model->get_expense_chart_data();

        // Data Tab 1: Pengeluaran (Filtered)
        $data['pengeluaran'] = $this->Finance_model->get_expenses_filtered($start_date, $end_date);

        // Data Tab 2: Donasi
        $data['pending_donations'] = $this->Finance_model->get_pending_donations();
        $data['history_donations'] = $this->Finance_model->get_recent_donations_history();
        
        $data['content'] = 'admin/finance';
        $this->load->view('layout/lay_admin', $data);
    }

    // --- MANAJEMEN DONASI (ACC/REJECT) ---
    public function verify_donation() {
        $action = $this->input->post('action'); // 'approve' or 'reject'
        $donation_id = $this->input->post('donation_id');
        $account_id = $this->input->post('account_id'); // Rekening tujuan
        $admin_id = $this->session->userdata('user_id');

        if ($action == 'approve') {
            if ($this->Finance_model->approve_donation($donation_id, $admin_id, $account_id)) {
                $this->session->set_flashdata('success', 'Donasi berhasil di-ACC dan saldo ditambahkan.');
            } else {
                $this->session->set_flashdata('error', 'Gagal memproses donasi.');
            }
        } elseif ($action == 'reject') {
            $this->Finance_model->reject_donation($donation_id, $admin_id);
            $this->session->set_flashdata('success', 'Donasi telah ditolak.');
        }

        redirect('finance');
    }

    // --- AKUN ---
    public function add_account() {
        $data = [
            'account_name' => $this->input->post('account_name'),
            'account_type' => $this->input->post('account_type'),
            'account_number' => $this->input->post('account_number'),
            'account_holder_name' => $this->input->post('account_holder_name'),
            'current_balance' => $this->input->post('initial_balance'),
            'is_active' => 1
        ];
        $this->Finance_model->insert_account($data);
        $this->session->set_flashdata('success', 'Rekening berhasil ditambahkan');
        redirect('finance');
    }

    public function update_account_data() {
        $id = $this->input->post('account_id');
        $data = [
            'account_name' => $this->input->post('account_name'),
            'account_type' => $this->input->post('account_type'),
            'account_number' => $this->input->post('account_number'),
            'account_holder_name' => $this->input->post('account_holder_name')
        ];
        $this->Finance_model->update_account($id, $data);
        $this->session->set_flashdata('success', 'Rekening berhasil diupdate');
        redirect('finance');
    }

    public function delete_account($id) {
        $this->Finance_model->delete_account($id);
        $this->session->set_flashdata('success', 'Rekening dinonaktifkan');
        redirect('finance');
    }

    public function get_account_json($id) {
        echo json_encode($this->Finance_model->get_account_by_id($id));
    }

    // --- TRANSAKSI ---
    public function add_transaction() {
        $type = $this->input->post('type');
        $amount = $this->input->post('amount');
        $account_id = $this->input->post('account_id');

        // Config Upload
        $path = FCPATH . 'uploads/expenses/';
        if (!is_dir($path)) mkdir($path, 0777, true);
        $config['upload_path']   = $path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
        $config['max_size']      = 5120;
        $config['encrypt_name']  = TRUE;
        $this->load->library('upload', $config);

        if ($type == 'out') {
            $receipt_url = null; $item_url = null;
            if (!empty($_FILES['receipt_image']['name'])) {
                if ($this->upload->do_upload('receipt_image')) $receipt_url = $this->upload->data('file_name');
            }
            $this->upload->initialize($config);
            if (!empty($_FILES['item_image']['name'])) {
                if ($this->upload->do_upload('item_image')) $item_url = $this->upload->data('file_name');
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
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->Finance_model->insert_expense($data);
        } else {
            $data = [
                'donor_name' => 'Manual Input (Admin)',
                'donor_email' => 'admin@local',
                'amount' => $amount,
                'message' => $this->input->post('title'),
                'account_id' => $account_id,
                'status' => 'verified',
                'verified_by' => $this->session->userdata('user_id'),
                'verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->Finance_model->insert_income_manual($data);
        }
        $this->session->set_flashdata('success', 'Transaksi berhasil disimpan!');
        redirect('finance');
    }

    public function update_expense() {
        $id = $this->input->post('expense_id');
        $old_data = $this->Finance_model->get_expense_by_id($id);
        if(!$old_data) show_404();

        $path = FCPATH . 'uploads/expenses/';
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
        $config['max_size'] = 5120;
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);

        $receipt_url = $old_data->receipt_image_url;
        $item_url = $old_data->item_image_url;

        if (!empty($_FILES['receipt_image']['name'])) {
            if ($this->upload->do_upload('receipt_image')) {
                $receipt_url = $this->upload->data('file_name');
                if($old_data->receipt_image_url && file_exists($path.$old_data->receipt_image_url)) unlink($path.$old_data->receipt_image_url);
            }
        }
        $this->upload->initialize($config);
        if (!empty($_FILES['item_image']['name'])) {
            if ($this->upload->do_upload('item_image')) {
                $item_url = $this->upload->data('file_name');
                if($old_data->item_image_url && file_exists($path.$old_data->item_image_url)) unlink($path.$old_data->item_image_url);
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
        $this->Finance_model->update_expense($id, $data);
        $this->session->set_flashdata('success', 'Transaksi diperbarui, saldo disesuaikan.');
        redirect('finance');
    }

    public function delete_expense($id) {
        if ($this->Finance_model->delete_expense($id)) {
            $this->session->set_flashdata('success', 'Transaksi dihapus & dana dikembalikan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus transaksi.');
        }
        redirect('finance');
    }

    public function get_expense_json($id) {
        $data = $this->Finance_model->get_expense_by_id($id);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}