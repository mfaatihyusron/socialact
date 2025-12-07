<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App_model extends CI_Model {

    // --- BAGIAN KEUANGAN & DONASI ---
    
    public function get_saldo() {
        // Hitung Saldo: Total Masuk (Verified) - Total Keluar
        $masuk = $this->db->select_sum('amount')->where('status', 'verified')->get('donations')->row()->amount;
        $keluar = $this->db->select_sum('amount')->get('expenses')->row()->amount;
        return $masuk - $keluar;
    }

    public function get_verified_donations() {
        return $this->db->where('status', 'verified')->order_by('created_at', 'DESC')->get('donations')->result();
    }

    public function get_total_masuk() {
        return $this->db->select_sum('amount')->where('status', 'verified')->get('donations')->row()->amount;
    }

    public function insert_donation($data) {
        return $this->db->insert('donations', $data);
    }

    public function get_all_expenses() {
        return $this->db->order_by('transaction_date', 'DESC')->get('expenses')->result();
    }

    public function get_total_keluar() {
        return $this->db->select_sum('amount')->get('expenses')->row()->amount;
    }

    public function get_chart_data() {
        $this->db->select('category, SUM(amount) as total');
        $this->db->group_by('category');
        return $this->db->get('expenses')->result_array();
    }

    public function insert_expense($data) {
        return $this->db->insert('expenses', $data);
    }

    // --- BAGIAN LAPORAN SAMPAH (GIS) ---

    public function get_all_reports() {
        return $this->db->order_by('created_at', 'DESC')->get('waste_reports')->result();
    }

    public function insert_report($data) {
        return $this->db->insert('waste_reports', $data);
    }

// --- FITUR BARU: VIEW COUNT ---
    
    // Fungsi nambah views +1
    public function increment_views($report_id) {
        $this->db->set('views', 'views+1', FALSE); // FALSE biar ga dianggap string
        $this->db->where('id', $report_id);
        $this->db->update('waste_reports');
    }

    // Update fungsi get_resolved_reports biar ngambil kolom views juga
    // (Sebenarnya get() udah otomatis ambil semua kolom (*), jadi aman)
    public function get_resolved_reports() {
        return $this->db->where('status', 'resolved')
                        ->where('image_after_url !=', NULL)
                        ->order_by('cleaned_at', 'DESC') 
                        ->get('waste_reports')->result();
    }

    // --- BAGIAN VOLUNTEER & EVENTS ---

    public function get_all_events() {
        return $this->db->order_by('event_date', 'ASC')->get('volunteer_events')->result();
    }

    public function get_upcoming_events() {
        return $this->db->where('status', 'upcoming')->order_by('event_date', 'ASC')->get('volunteer_events')->result();
    }
}