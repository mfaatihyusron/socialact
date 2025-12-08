<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[AllowDynamicProperties]
class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form'); 
        $this->load->model('Super_model'); // Load Super Model baru

        // Cek Login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }

    public function index() {
        $role = $this->session->userdata('role');
        if ($role == 'finance') redirect('finance'); // Arahkan ke controller baru
        elseif ($role == 'field_coordinator') redirect('content'); // Arahkan ke controller baru
        else redirect('admin/super');
    }

    // ==========================================
    // BAGIAN 1: SUPER ADMIN CONTROL
    // ==========================================
    public function super() {
        if ($this->session->userdata('role') !== 'super_admin') show_404();

        $data['title'] = "Super Admin Control Panel";
        $data['user'] = $this->session->userdata();
        
        // Data dari Super_model
        $data['admins'] = $this->Super_model->get_all_admins();
        $data['count_admin'] = count($data['admins']);
        $data['count_event'] = $this->Super_model->get_count('volunteer_events');
        $data['count_report'] = $this->Super_model->get_count('waste_reports');
        $data['total_fund'] = $this->Super_model->get_total_balance(); // Ambil dari Super_model
        
        $data['content'] = 'admin/super';
        $this->load->view('layout/lay_admin', $data);
    }

    public function add_admin() {
        if ($this->session->userdata('role') !== 'super_admin') redirect('admin');
        $data = [
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'role' => $this->input->post('role'),
            'password_hash' => password_hash($this->input->post('password'), PASSWORD_BCRYPT)
        ];
        $this->Super_model->insert_admin($data);
        $this->session->set_flashdata('success', 'Admin baru berhasil dibuat!');
        redirect('admin/super');
    }

    public function delete_admin($id) {
        if ($this->session->userdata('role') !== 'super_admin') redirect('admin');
        if($id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Tidak bisa menghapus diri sendiri!');
        } else {
            $this->Super_model->delete_admin($id);
            $this->session->set_flashdata('success', 'Admin dihapus.');
        }
        redirect('admin/super');
    }
}