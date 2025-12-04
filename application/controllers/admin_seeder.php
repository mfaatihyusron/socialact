<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_seeder extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Load model yang kita buat
        $this->load->model('Admin_model');
        // Load library database
        $this->load->database();
        // Load helper URL untuk fungsi base_url()
        $this->load->helper('url');
    }

    /**
     * Fungsi untuk membuat satu user admin default.
     * Dapat diakses langsung melalui URL untuk seeding awal.
     */
    public function create_default_admin()
    {
        // Detail User yang diminta
        $email = 'admin@gmail.com';
        $plain_password = 'admin';
        $username = 'default_admin'; // Username bebas, karena email sudah unik
        $role = 'super_admin'; // Berdasarkan enum di DB

        // --- Hashing Password (Penting untuk Keamanan) ---
        // Gunakan password_hash() untuk menyimpan hash yang aman
        $password_hash = password_hash($plain_password, PASSWORD_BCRYPT);

        // Data yang akan dimasukkan ke model
        $admin_data = array(
            'username'      => $username,
            'email'         => $email,
            'password_hash' => $password_hash,
            'role'          => $role,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s')
        );

        // Panggil model untuk menyimpan data
        $result = $this->Admin_model->create_admin($admin_data);

        if ($result) {
            $response = array(
                'status' => 'success',
                'message' => 'User Admin Default berhasil dibuat.',
                'details' => [
                    'email' => $email,
                    'password' => $plain_password,
                    'catatan' => 'Password disimpan dalam bentuk hash yang aman.'
                ]
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Gagal membuat User Admin Default. Kemungkinan email sudah terdaftar atau terjadi kesalahan database. Silakan cek file log di application/logs/.'
            );
        }

        // Tampilkan respons (bisa dalam bentuk JSON atau teks biasa)
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($response));
    }

}

/* End of file Admin_seeder.php */
/* Location: ./application/controllers/Admin_seeder.php */