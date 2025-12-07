<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    // --- GENERAL STATS ---
    public function get_count($table) {
        return $this->db->count_all($table);
    }

    // --- FINANCE READ ---
    // Update: Ambil semua akun aktif untuk dropdown
    public function get_all_accounts() {
        return $this->db->get_where('accounts', ['is_active' => 1])->result();
    }

    public function get_balance() {
        // Menjumlahkan total saldo dari SEMUA akun
        $this->db->select_sum('current_balance');
        $query = $this->db->get('accounts')->row();
        return $query->current_balance ?? 0;
    }

    public function get_recent_donations($limit = 5) {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('donations', $limit)->result();
    }

    public function get_recent_expenses($limit = 20) {
        // Join ke tabel accounts agar tahu sumber dana (opsional, untuk display nama akun)
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

    // --- FINANCE CREATE ---
    public function insert_expense($data) {
        // 1. Catat Pengeluaran
        $this->db->insert('expenses', $data);
        
        // 2. Kurangi Saldo Akun yang DIPILIH (Sesuai account_id)
        $this->db->set('current_balance', 'current_balance - ' . $data['amount'], FALSE);
        $this->db->where('id', $data['account_id']);
        $this->db->update('accounts');
    }

    public function insert_income_manual($data) {
        $this->db->insert('donations', $data);
        
        // 3. Tambah Saldo Akun yang DIPILIH
        $this->db->set('current_balance', 'current_balance + ' . $data['amount'], FALSE);
        $this->db->where('id', $data['account_id']);
        $this->db->update('accounts');
    }

    // --- FINANCE UPDATE ---
    public function update_expense($id, $data, $old_amount) {
        // Ambil data lama untuk tahu akun mana yang dulu dipotong
        $old_expense = $this->get_expense_by_id($id);
        
        $this->db->where('id', $id);
        $this->db->update('expenses', $data);

        // Koreksi Saldo:
        // 1. Kembalikan saldo ke akun lama
        $this->db->set('current_balance', 'current_balance + ' . $old_amount, FALSE);
        $this->db->where('id', $old_expense->account_id);
        $this->db->update('accounts');

        // 2. Potong saldo dari akun baru (bisa jadi akunnya berubah atau sama)
        $this->db->set('current_balance', 'current_balance - ' . $data['amount'], FALSE);
        $this->db->where('id', $data['account_id']); // Gunakan account_id dari data baru
        $this->db->update('accounts');
    }

    // --- FINANCE DELETE ---
    public function delete_expense($id) {
        $expense = $this->db->get_where('expenses', ['id' => $id])->row();
        
        if ($expense) {
            // Kembalikan Saldo ke akun yang sesuai
            $this->db->set('current_balance', 'current_balance + ' . $expense->amount, FALSE);
            $this->db->where('id', $expense->account_id);
            $this->db->update('accounts');

            // Hapus Data
            $this->db->where('id', $id);
            $this->db->delete('expenses');
            return true;
        }
        return false;
    }

    // --- CONTENT & SUPER ADMIN UTILS ---
    public function get_events() {
        $this->db->order_by('event_date', 'ASC');
        return $this->db->get('volunteer_events')->result();
    }
    public function insert_event($data) { return $this->db->insert('volunteer_events', $data); }
    public function delete_event($id) { $this->db->where('id', $id); return $this->db->delete('volunteer_events'); }
    public function get_reports() { $this->db->order_by('created_at', 'DESC'); return $this->db->get('waste_reports')->result(); }
    public function update_report_status($id, $status, $img_url = null) {
        $data = ['status' => $status];
        if($img_url) $data['image_after_url'] = $img_url;
        if($status == 'resolved') $data['cleaned_at'] = date('Y-m-d');
        $this->db->where('id', $id);
        return $this->db->update('waste_reports', $data);
    }
    public function get_all_admins() { return $this->db->get('admins')->result(); }
    public function insert_admin($data) { return $this->db->insert('admins', $data); }
    public function delete_admin($id) { $this->db->where('id', $id); return $this->db->delete('admins'); }
}