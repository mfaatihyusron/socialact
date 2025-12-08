<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Super_model extends CI_Model {

    // --- GENERAL STATS ---
    public function get_count($table) {
        return $this->db->count_all($table);
    }

    public function get_total_balance() {
        // Menjumlahkan total saldo dari SEMUA akun
        $this->db->select_sum('current_balance');
        $query = $this->db->get('accounts')->row();
        return $query->current_balance ?? 0;
    }

    // --- ADMIN MANAGEMENT ---
    public function get_all_admins() { 
        return $this->db->get('admins')->result(); 
    }
    public function insert_admin($data) { 
        return $this->db->insert('admins', $data); 
    }
    public function delete_admin($id) { 
        $this->db->where('id', $id); 
        return $this->db->delete('admins'); 
    }
}