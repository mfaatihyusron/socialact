<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    // --- GENERAL STATS ---
    public function get_count($table) {
        return $this->db->count_all($table);
    }

    // --- FINANCE ---
    public function get_balance() {
        // Ambil saldo dari akun utama (ID 1)
        $this->db->select('current_balance');
        $query = $this->db->get_where('accounts', ['id' => 1])->row();
        return $query ? $query->current_balance : 0;
    }

    public function get_recent_donations($limit = 5) {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('donations', $limit)->result();
    }

    public function get_recent_expenses($limit = 5) {
        $this->db->order_by('transaction_date', 'DESC');
        return $this->db->get('expenses', $limit)->result();
    }

    public function get_expense_chart_data() {
        $query = $this->db->query("SELECT category, SUM(amount) as total FROM expenses GROUP BY category");
        return $query->result();
    }

    public function insert_expense($data) {
        // 1. Catat Pengeluaran
        $this->db->insert('expenses', $data);
        
        // 2. Kurangi Saldo Akun (Trigger sederhana via PHP)
        $this->db->set('current_balance', 'current_balance - ' . $data['amount'], FALSE);
        $this->db->where('id', $data['account_id']);
        $this->db->update('accounts');
    }

    public function insert_income_manual($data) {
        // Untuk input donasi manual / pemasukan lain via admin
        $this->db->insert('donations', $data);
        
        // Tambah Saldo
        $this->db->set('current_balance', 'current_balance + ' . $data['amount'], FALSE);
        $this->db->where('id', $data['account_id']);
        $this->db->update('accounts');
    }

    // --- CONTENT (EVENTS & REPORTS) ---
    public function get_events() {
        $this->db->order_by('event_date', 'ASC');
        return $this->db->get('volunteer_events')->result();
    }

    public function insert_event($data) {
        return $this->db->insert('volunteer_events', $data);
    }

    public function delete_event($id) {
        $this->db->where('id', $id);
        return $this->db->delete('volunteer_events');
    }

    public function get_reports() {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('waste_reports')->result();
    }

    public function update_report_status($id, $status, $img_url = null) {
        $data = ['status' => $status];
        if($img_url) $data['image_after_url'] = $img_url;
        if($status == 'resolved') $data['cleaned_at'] = date('Y-m-d');
        
        $this->db->where('id', $id);
        return $this->db->update('waste_reports', $data);
    }

    // --- SUPER ADMIN (USERS) ---
    public function get_all_admins() {
        return $this->db->get('admins')->result();
    }

    public function insert_admin($data) {
        return $this->db->insert('admins', $data);
    }

    public function delete_admin($id) {
        // Cegah hapus diri sendiri (logic di controller)
        $this->db->where('id', $id);
        return $this->db->delete('admins');
    }
}