<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//base for all
class base extends CI_Controller {
	//constructor
	public function __construct(){
		parent::__construct();
		//auto load model	
		$this->load->model('m_gudang');
		$this->load->model('m_karyawan');
		$this->load->model('m_barang');
		$this->load->model('m_pengeluaran');
		$this->load->model('m_pemasukan');
		$this->load->model('m_kasir');
		$this->load->model('m_transaksi');
		$this->load->model('m_pelanggan');
		//hidden notice
		error_reporting();
	}

	public function error_403(){//default 403 error
		$note = '<h1><center>ERROR 403 : FORBIDDEN ACCESS</center</h1>';
		return $note;
	}
	public function index(){
		echo $this->error_403();
	}
	//////////////////////// BASE VIEW
	//base configuration for users
	public function baseView($view='',$data=''){
		$data['view'] = $view;
		$this->load->view('base/gudang_view',$data);
	}

	//////////////////////// LOGIN CHECK
	public function gudang_logged_in(){
		if($this->session->userdata['gudang_logged_in'] == 0 && $this->session->userdata['admin_logged_in'] == 0){
			redirect(site_url('login'));
			// echo $this->session->userdata['gudang_logged_in'];
		}
	}
	public function kasir_logged_in(){
		if($this->session->userdata['kasir_logged_in'] == 0 && $this->session->userdata['admin_logged_in']==0){
			// redirect(site_url('login'));
		}
	}
	public function admin_logged_in(){
		if($this->session->userdata['admin_logged_in']==0){
			redirect(site_url('admin'));
		}
	}
	//admin function
	//mencari modal
	public function get_modal(){

	}
	
}