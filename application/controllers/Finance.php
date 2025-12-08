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

        // Security Check
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
        
        $data['accounts'] = $this->Finance_model->get_all_accounts();
        $data['saldo'] = $this->Finance_model->get_total_balance();
        $data['donasi'] = $this->Finance_model->get_recent_donations(10);
        $data['pengeluaran'] = $this->Finance_model->get_recent_expenses(20); 
        $data['chart_data'] = $this->Finance_model->get_expense_chart_data();
        
        $data['content'] = 'admin/finance';
        $this->load->view('layout/lay_admin', $data);
    }

    // --- FITUR REKENING ---
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

    // --- FITUR TRANSAKSI (ADD, EDIT, DELETE) ---

    // 1. TAMBAH TRANSAKSI
    public function add_transaction() {
        $type = $this->input->post('type');
        $amount = $this->input->post('amount');
        $account_id = $this->input->post('account_id');

        // Config Upload
        $path = FCPATH . 'uploads/expenses/';
        if (!is_dir($path)) mkdir($path, 0777, true);
        $config['upload_path']   = $path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
        $config['max_size']      = 5120; // 5MB
        $config['encrypt_name']  = TRUE;
        $this->load->library('upload', $config);

        if ($type == 'out') {
            // Logic Pengeluaran
            $receipt_url = null;
            $item_url = null;

            if (!empty($_FILES['receipt_image']['name'])) {
                if ($this->upload->do_upload('receipt_image')) {
                    $receipt_url = $this->upload->data('file_name');
                }
            }
            // Reset upload lib untuk file kedua
            $this->upload->initialize($config);
            if (!empty($_FILES['item_image']['name'])) {
                if ($this->upload->do_upload('item_image')) {
                    $item_url = $this->upload->data('file_name');
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
                'created_by' => $this->session->userdata('user_id'),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->Finance_model->insert_expense($data);

        } else {
            // Logic Pemasukan (Manual)
            $data = [
                'donor_name' => 'Manual Input (Admin)',
                'donor_email' => $this->session->userdata('email') ?: 'admin@sistem',
                'amount' => $amount,
                'message' => $this->input->post('title'), // Judul jadi pesan
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

    // 2. EDIT PENGELUARAN
    public function update_expense() {
        $id = $this->input->post('expense_id');
        $old_data = $this->Finance_model->get_expense_by_id($id);
        
        if(!$old_data) {
            show_404();
            return;
        }

        // Config Upload sama seperti add
        $path = FCPATH . 'uploads/expenses/';
        $config['upload_path']   = $path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
        $config['max_size']      = 5120;
        $config['encrypt_name']  = TRUE;
        $this->load->library('upload', $config);

        // Pakai gambar lama dulu
        $receipt_url = $old_data->receipt_image_url;
        $item_url = $old_data->item_image_url;

        // Cek upload baru
        if (!empty($_FILES['receipt_image']['name'])) {
            if ($this->upload->do_upload('receipt_image')) {
                $receipt_url = $this->upload->data('file_name');
                // Hapus file lama jika ada (opsional)
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

        // Panggil Model (Otomatis handle saldo)
        $this->Finance_model->update_expense($id, $data);
        
        $this->session->set_flashdata('success', 'Transaksi diperbarui, saldo rekening disesuaikan.');
        redirect('finance');
    }

    // 3. HAPUS PENGELUARAN
    public function delete_expense($id) {
        if ($this->Finance_model->delete_expense($id)) {
            $this->session->set_flashdata('success', 'Transaksi dihapus & dana dikembalikan ke rekening.');
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