<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance_model extends CI_Model {

    // --- GET DATA ---
    public function get_all_accounts() {
        return $this->db->get_where('accounts', ['is_active' => 1])->result();
    }

    public function get_total_balance() {
        $this->db->select_sum('current_balance');
        $this->db->where('is_active', 1);
        $query = $this->db->get('accounts')->row();
        return $query->current_balance ?? 0;
    }

    public function get_recent_donations($limit = 5) {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('donations', $limit)->result();
    }

    public function get_recent_expenses($limit = 20) {
        $this->db->select('expenses.*, accounts.account_name');
        $this->db->join('accounts', 'accounts.id = expenses.account_id', 'left');
        $this->db->order_by('transaction_date', 'DESC');
        return $this->db->get('expenses', $limit)->result();
    }

    public function get_expense_by_id($id) {
        return $this->db->get_where('expenses', ['id' => $id])->row();
    }

    public function get_expense_chart_data() {
        $query = $this->db->query("SELECT category, SUM(amount) as total FROM expenses GROUP BY category");
        return $query->result();
    }

    public function get_account_by_id($id) {
        return $this->db->get_where('accounts', ['id' => $id])->row();
    }

    // --- ACCOUNT MANAGEMENT ---
    public function insert_account($data) {
        return $this->db->insert('accounts', $data);
    }

    public function update_account($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('accounts', $data);
    }

    public function delete_account($id) {
        $this->db->set('is_active', 0);
        $this->db->where('id', $id);
        return $this->db->update('accounts');
    }

    // =========================================================
    // FITUR UTAMA: KONEKSI TRANSAKSI KE SALDO REKENING
    // =========================================================

    // 1. TAMBAH PENGELUARAN (Saldo Berkurang)
    public function insert_expense($data) {
        $this->db->trans_start(); // Mulai Transaksi Database

        // A. Simpan Data Pengeluaran
        $this->db->insert('expenses', $data);

        // B. Kurangi Saldo Rekening Terkait
        $this->db->set('current_balance', 'current_balance - ' . $data['amount'], FALSE);
        $this->db->where('id', $data['account_id']);
        $this->db->update('accounts');

        $this->db->trans_complete(); // Selesaikan Transaksi
        return $this->db->trans_status();
    }

    // 2. TAMBAH PEMASUKAN MANUAL (Saldo Bertambah)
    public function insert_income_manual($data) {
        $this->db->trans_start();
        
        $this->db->insert('donations', $data);
        
        $this->db->set('current_balance', 'current_balance + ' . $data['amount'], FALSE);
        $this->db->where('id', $data['account_id']);
        $this->db->update('accounts');

        $this->db->trans_complete();
    }

    // 3. EDIT PENGELUARAN (Refund Lama -> Potong Baru)
    public function update_expense($id, $data) {
        $this->db->trans_start();

        // A. Ambil Data Lama
        $old_expense = $this->get_expense_by_id($id);

        // B. Kembalikan Saldo ke Akun Lama (Refund Full)
        // Ini mengatasi jika user mengganti nominal ATAU mengganti akun
        $this->db->set('current_balance', 'current_balance + ' . $old_expense->amount, FALSE);
        $this->db->where('id', $old_expense->account_id);
        $this->db->update('accounts');

        // C. Update Data Pengeluaran
        $this->db->where('id', $id);
        $this->db->update('expenses', $data);

        // D. Potong Saldo dari Akun Baru (Sesuai Nominal Baru)
        $this->db->set('current_balance', 'current_balance - ' . $data['amount'], FALSE);
        $this->db->where('id', $data['account_id']);
        $this->db->update('accounts');

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // 4. HAPUS PENGELUARAN (Refund Dana ke Rekening)
    public function delete_expense($id) {
        $expense = $this->db->get_where('expenses', ['id' => $id])->row();
        
        if ($expense) {
            $this->db->trans_start();

            // A. Kembalikan Saldo ke Rekening
            $this->db->set('current_balance', 'current_balance + ' . $expense->amount, FALSE);
            $this->db->where('id', $expense->account_id);
            $this->db->update('accounts');

            // B. Hapus Data Transaksi
            $this->db->where('id', $id);
            $this->db->delete('expenses');

            $this->db->trans_complete();
            return $this->db->trans_status();
        }
        return false;
    }
}