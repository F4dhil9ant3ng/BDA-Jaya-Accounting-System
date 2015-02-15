<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//pegawai login
require_once 'application/controllers/base/base.php';
///////////////////////////// DISPLAY
class login extends base {
	public function index(){
		$data['title'] = 'Login Sistem';
		$this->baseView('all/login',$data);
	}	
	///////////////////////////// PROCESS
	public function pegawaiLogin(){
		$this->load->library('form_validation');//form validation untuk pegawai
		//validasi 
		$this->form_validation->set_rules('input-username', 'username', 'required|trim|xss_clean|callback_validate_credentials');
		$this->form_validation->set_rules('input-password', 'Password', 'required|md5|trim|xss_clean');
		//check valid
		if($this->form_validation->run()) {
			//ambil data
			$username = $this->input->post('input-username');
			$password = $this->input->post('input-password');
			//cek apakah nis dan password sesuai
			$userdata = $this->m_karyawan->can_log_in($username, $password);
			if(!empty($userdata)) { //jika siswa ditemukan
				//set session
				$sessionData = $userdata;
				$sql = "UPDATE pegawai SET login_log = CURTIME() WHERE id_pegawai = ?"; //tambah log time
				$this->db->query($sql,$userdata['id_pegawai']);
				if($sessionData['bagian']=='gudang') {
					$sessionData['gudang_logged_in'] = 1;
					$sessionData['kasir_logged_in'] = 0;
					$sessionData['admin_logged_in'] = 0;
					$redirect = site_url('gudang');
					$bagian = 'Gudang';
				} else if($sessionData['bagian']=='kasir') {
					$sessionData['kasir_logged_in'] = 1;
					$sessionData['gudang_logged_in'] = 0;
					$sessionData['admin_logged_in'] = 0;
					$redirect = site_url('kasir');
					$bagian = 'Kasir';
				}//activate session
				$this->session->set_userdata($sessionData);
				//alert login berhasil
				echo ("<SCRIPT LANGUAGE='JavaScript'>
					window.alert('Anda Login Sebagai ".$bagian."');
					window.location.href='".$redirect."';
				</SCRIPT>");
			} else { //jika siswa tidak ditemukan
				echo ("<SCRIPT LANGUAGE='JavaScript'>
					window.alert('Terjadi kesalahan, silahkan ulangi lagi');
					window.location.href='".site_url()."';
				</SCRIPT>");
			}
		} else { //jika form validasi tidak jalan
			$data['title'] = 'Error Login';
			echo ("<SCRIPT LANGUAGE='JavaScript'>
				window.alert('username dan password tidak cocok');
			</SCRIPT>");
			$this->baseView('all/login',$data);
		}
	}

	//validasi login siswa
	public function validate_credentials(){
		$NIS = $this->input->post('input-username'); 
		$password = md5($this->input->post('input-password'));
		//struktur kendali untuk cek bisa login atau tidak
		if ($this->m_karyawan->can_log_in($NIS, $password)){
			return true;
		} else {
			//memberikan pesan jika login tidak berhasil
			$this->form_validation->set_message('validate_credentials', 'NIS/Password salah');
			return false;
		}
	}
	//logout
	public function logout(){
		$this->session->sess_destroy();//membersihkan session
		redirect('login');//kembali ke halaman login
	}
}
