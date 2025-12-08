<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance_model extends CI_Model {

    // --- ACCOUNTS ---
    public function get_all_accounts() {
        return $this->db->get_where('accounts', ['is_active' => 1])->result();
    }

    public function get_account_by_id($id) {
        return $this->db->get_where('accounts', ['id' => $id])->row();
    }

    public function get_total_balance() {
        $this->db->select_sum('current_balance');
        $this->db->where('is_active', 1);
        $query = $this->db->get('accounts')->row();
        return $query->current_balance ?? 0;
    }

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

    // --- EXPENSES (PENGELUARAN) ---
    
    // Updated: Support Filter Tanggal
    public function get_expenses_filtered($start_date = null, $end_date = null) {
        $this->db->select('expenses.*, accounts.account_name');
        $this->db->join('accounts', 'accounts.id = expenses.account_id', 'left');
        
        if ($start_date && $end_date) {
            $this->db->where('transaction_date >=', $start_date);
            $this->db->where('transaction_date <=', $end_date);
        }
        
        $this->db->order_by('transaction_date', 'DESC');
        // Limit dihapus atau diperbesar jika difilter, tapi default kita limit 100 biar tidak berat
        $this->db->limit(100); 
        return $this->db->get('expenses')->result();
    }

    public function get_expense_by_id($id) {
        return $this->db->get_where('expenses', ['id' => $id])->row();
    }

    public function get_expense_chart_data() {
        $query = $this->db->query("SELECT category, SUM(amount) as total FROM expenses GROUP BY category");
        return $query->result();
    }

    public function insert_expense($data) {
        $this->db->trans_start();
        $this->db->insert('expenses', $data);
        $this->db->set('current_balance', 'current_balance - ' . $data['amount'], FALSE);
        $this->db->where('id', $data['account_id']);
        $this->db->update('accounts');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function update_expense($id, $data) {
        $this->db->trans_start();
        $old_expense = $this->get_expense_by_id($id);
        
        // Refund ke akun lama
        $this->db->set('current_balance', 'current_balance + ' . $old_expense->amount, FALSE);
        $this->db->where('id', $old_expense->account_id);
        $this->db->update('accounts');

        // Update Data
        $this->db->where('id', $id);
        $this->db->update('expenses', $data);

        // Potong dari akun baru
        $this->db->set('current_balance', 'current_balance - ' . $data['amount'], FALSE);
        $this->db->where('id', $data['account_id']);
        $this->db->update('accounts');
        $this->db->trans_complete();
    }

    public function delete_expense($id) {
        $expense = $this->db->get_where('expenses', ['id' => $id])->row();
        if ($expense) {
            $this->db->trans_start();
            $this->db->set('current_balance', 'current_balance + ' . $expense->amount, FALSE);
            $this->db->where('id', $expense->account_id);
            $this->db->update('accounts');
            $this->db->where('id', $id);
            $this->db->delete('expenses');
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
        return false;
    }

    public function insert_income_manual($data) {
        $this->db->trans_start();
        $this->db->insert('donations', $data);
        $this->db->set('current_balance', 'current_balance + ' . $data['amount'], FALSE);
        $this->db->where('id', $data['account_id']);
        $this->db->update('accounts');
        $this->db->trans_complete();
    }

    // --- DONATION MANAGEMENT (BARU) ---

    // Ambil semua donasi pending untuk di-ACC
    public function get_pending_donations() {
        $this->db->select('donations.*, accounts.account_name as target_account');
        $this->db->join('accounts', 'accounts.id = donations.account_id', 'left');
        $this->db->where('donations.status', 'pending');
        $this->db->order_by('created_at', 'ASC');
        return $this->db->get('donations')->result();
    }

    public function get_recent_donations_history($limit = 10) {
        $this->db->where('status !=', 'pending');
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('donations', $limit)->result();
    }

    // ACC Donasi: Update Status + Tambah Saldo Rekening
    public function approve_donation($id, $admin_id, $account_id) {
        $donation = $this->db->get_where('donations', ['id' => $id])->row();
        
        if($donation && $donation->status == 'pending') {
            $this->db->trans_start();

            // 1. Update Status Donasi
            $data = [
                'status' => 'verified',
                'verified_by' => $admin_id,
                'verified_at' => date('Y-m-d H:i:s'),
                'account_id' => $account_id // Pastikan account_id ter-set sesuai pilihan admin
            ];
            $this->db->where('id', $id);
            $this->db->update('donations', $data);

            // 2. Tambah Saldo Rekening
            $this->db->set('current_balance', 'current_balance + ' . $donation->amount, FALSE);
            $this->db->where('id', $account_id);
            $this->db->update('accounts');

            $this->db->trans_complete();
            return $this->db->trans_status();
        }
        return false;
    }

    // Reject Donasi: Hanya Update Status (Saldo tidak berubah)
    public function reject_donation($id, $admin_id) {
        $data = [
            'status' => 'rejected',
            'verified_by' => $admin_id,
            'verified_at' => date('Y-m-d H:i:s')
        ];
        $this->db->where('id', $id);
        return $this->db->update('donations', $data);
    }
}