<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance_model extends CI_Model {

    // --- FINANCE READ ---
    public function get_all_accounts() {
        return $this->db->get_where('accounts', ['is_active' => 1])->result();
    }

    public function get_total_balance() {
        $this->db->select_sum('current_balance');
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

    // --- FINANCE CREATE ---
    public function insert_expense($data) {
        $this->db->insert('expenses', $data);
        $this->db->set('current_balance', 'current_balance - ' . $data['amount'], FALSE);
        $this->db->where('id', $data['account_id']);
        $this->db->update('accounts');
    }

    public function insert_income_manual($data) {
        $this->db->insert('donations', $data);
        $this->db->set('current_balance', 'current_balance + ' . $data['amount'], FALSE);
        $this->db->where('id', $data['account_id']);
        $this->db->update('accounts');
    }

    // --- FINANCE UPDATE ---
    public function update_expense($id, $data, $old_amount) {
        $old_expense = $this->get_expense_by_id($id);
        
        $this->db->where('id', $id);
        $this->db->update('expenses', $data);

        // 1. Kembalikan saldo ke akun lama
        $this->db->set('current_balance', 'current_balance + ' . $old_amount, FALSE);
        $this->db->where('id', $old_expense->account_id);
        $this->db->update('accounts');

        // 2. Potong saldo dari akun baru
        $this->db->set('current_balance', 'current_balance - ' . $data['amount'], FALSE);
        $this->db->where('id', $data['account_id']);
        $this->db->update('accounts');
    }

    // --- FINANCE DELETE ---
    public function delete_expense($id) {
        $expense = $this->db->get_where('expenses', ['id' => $id])->row();
        
        if ($expense) {
            // Kembalikan Saldo
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
}