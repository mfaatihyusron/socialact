<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Admin_seeder
 * @property CI_Loader $load CodeIgniter Loader Class
 * @property CI_DB_query_builder $db CodeIgniter Database Class
 * @property Admin_model $Admin_model Model untuk manajemen admin (admins table)
 */
class praktek extends CI_Controller {
	public function index() 
	{
		$this->load->view('template');
	}

	public function formvalidasi()
	{
		$data['content'] = "formvalidasi";
		$this->load->view('template', $data);
	}
	// test baru
}
