<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[AllowDynamicProperties]
class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
    }

    // Tampilkan Halaman Login
    public function index() {
        if ($this->session->userdata('logged_in')) {
            // Jika sudah login, cek role dan lempar langsung
            $this->_redirect_by_role();
        } else {
            $this->load->view('auth/login');
        }
    }

    // Proses Login
    public function process() {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        // Cari user berdasarkan email
        $user = $this->db->get_where('admins', ['email' => $email])->row();

        if ($user) {
            // Cek Password (Verify Hash)
            if (password_verify($password, $user->password_hash)) {
                // Set Session
                $session_data = [
                    'user_id'   => $user->id,
                    'username'  => $user->username,
                    'role'      => $user->role, 
                    'logged_in' => TRUE
                ];
                $this->session->set_userdata($session_data);

                // UPDATE: Langsung redirect sesuai Role (Tanpa mampir ke admin/dashboard)
                $this->_redirect_by_role();

            } else {
                $this->session->set_flashdata('error', 'Password salah!');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('error', 'Email tidak terdaftar!');
            redirect('auth');
        }
    }

    // Fungsi Bantuan untuk Redirect
    private function _redirect_by_role() {
        $role = $this->session->userdata('role');

        if ($role === 'finance') {
            redirect('admin/finance');
        } elseif ($role === 'field_coordinator') {
            redirect('admin/content');
        } else {
            // Default ke Super Admin jika role super_admin atau tidak dikenali
            redirect('admin/super');
        }
    }

    // Logout
    public function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }
}