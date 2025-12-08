<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[AllowDynamicProperties]
class Finance extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form'); 
        $this->load->model('Finance_model'); // Load Finance Model baru

        // Cek Login dan Role
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        if (!in_array($this->session->userdata('role'), ['finance', 'super_admin'])) {
            show_404(); // Hanya boleh diakses oleh Finance & Super Admin
        }
    }

    // ==========================================
    // BAGIAN 1: FINANCE DASHBOARD
    // ==========================================
    public function index() {
        $data['title'] = "Finance Dashboard";
        $data['user'] = $this->session->userdata();
        
        $data['accounts'] = $this->Finance_model->get_all_accounts();
        $data['saldo'] = $this->Finance_model->get_total_balance();
        $data['donasi'] = $this->Finance_model->get_recent_donations(10);
        $data['pengeluaran'] = $this->Finance_model->get_recent_expenses(20); 
        $data['chart_data'] = $this->Finance_model->get_expense_chart_data();
        
        $data['content'] = 'admin/finance';
        $this->load->view('layout/lay_admin', $data);
    }

    public function add_transaction() {
        $type = $this->input->post('type');
        $amount = $this->input->post('amount');
        $account_id = $this->input->post('account_id');

        if ($type == 'out') {
            $path = FCPATH . 'uploads/expenses/';
            if (!is_dir($path)) mkdir($path, 0777, true);
            $config['upload_path']   = $path;
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
            $config['max_size']      = 5120; 
            $config['encrypt_name']  = TRUE;
            $this->load->library('upload', $config);

            $receipt_url = null;
            $item_url = null;
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
                'amount' => $amount,
                'category' => $this->input->post('category'),
                'transaction_date' => $this->input->post('date'),
                'account_id' => $account_id,
                'receipt_image_url' => $receipt_url,
                'item_image_url' => $item_url,
                'created_by' => $this->session->userdata('user_id')
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
                'verified_at' => date('Y-m-d H:i:s')
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

        $this->Finance_model->update_expense($id, $data, $old_data->amount);
        $this->session->set_flashdata('success', 'Transaksi berhasil diperbarui!');
        redirect('finance');
    }

    public function delete_expense($id) {
        if ($this->Finance_model->delete_expense($id)) {
            $this->session->set_flashdata('success', 'Transaksi dihapus & Saldo dikembalikan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus transaksi.');
        }
        redirect('finance');
    }

    public function get_expense_json($id) {
        if (!$this->session->userdata('logged_in')) exit('No Access');
        $data = $this->Finance_model->get_expense_by_id($id);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}