<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/controllers/base/base.php';
class dashboard extends base {
	public function __construct(){
		parent::__construct();
		$this->admin_logged_in();
		$this->load->model('m_admin');
		$this->load->library('cart');
	}
	public function index(){
		$data['title'] = "Admin";
		$data['script'] = "<script> $(document).ready(function(){ document.getElementById('stats').className = 'active';});</script>";
		$data['kasir'] = $this->m_kasir->activityKasir(10,0);
		$data['gudang'] = $this->m_gudang->gudang_activity(10,0);
		$data['login'] = $this->m_karyawan->loginTerakhir();
		$data['today'] = $this->m_kasir->totTransaksiHariIni();
		$data['piutang'] = $this->m_kasir->totPiutang();
		$this->baseView('admin/stats',$data);
	}
	//lihat buku kas
	public function bukukas(){
		$data['title'] = "Pemasukan/Pengeluaran";
		$data['script'] = "<script> $(document).ready(function(){ document.getElementById('bukukas').className = 'active';});</script>";
		if(!empty($this->input->get())){
			$data['tanggal'] = $this->input->get('tgl');
			$data['bulan'] = $this->input->get('bln');
			$data['tahun'] = $this->input->get('thn');
			$params = array($data['tanggal'],$data['bulan'],$data['tahun']);
			$bln_params = array($data['bulan'],$data['tahun']);
		}else{
			$data['tanggal'] = date('d');
			$data['bulan'] = date('m');
			$data['tahun'] = date('Y');
			$params = array(date('d'),date('m'),date('Y'));
			$bln_params = array(date('m'),date('Y'));
		}
		$data['kategori_pemasukan'] = $this->m_admin->show_all_kategoripemasukan();//menampilkan semua kategori pemasukan
		$data['kategori_pengeluaran'] = $this->m_admin->show_all_kategoripengeluaran();//menampilkan semua kategori pengeluaran
		$data['pengeluaran'] = $this->m_pengeluaran->showPengeluaran($params);
		$data['pemasukan'] = $this->m_pemasukan->showPemasukan($params);
		$data['pengeluaran_bln_ini'] = $this->m_pengeluaran->showPengeluaran_blnini($bln_params);//show pengeluaran bulan ini
		$data['pemasukan_bln_ini'] = $this->m_pemasukan->showPemasukan_blnini($bln_params);//show pemasukan bulan ini;
		$this->baseView('admin/bukukas',$data);
	}	
	//management kategori masuk keluar
	public function kategori_kas(){
		$data['title'] = "Manajemen Kategori Pemasukan dan Pengeluaran";
		$data['script'] = "<script> $(document).ready(function(){ document.getElementById('kategorikas').className = 'active';});</script>";
		if(!empty($this->input->get('act'))){ //jika setup get act
			switch ($this->input->get('act')) {
				case 'tambahkategori':
				$kategoriUntuk = $this->input->post('kategori_untuk');
				$inputnama = $this->input->post('input_nama');
					if($kategoriUntuk == 'masuk') { //masukan ke kategori pemasukan
						$data = array('det_kat_masuk'=>$inputnama);
						$this->db->insert('kategori_pemasukan',$data);
						redirect(site_url('dashboard/kategori_kas'));
					} else if ($kategoriUntuk == 'keluar'){ //masukan ke kategori pengeluaran
						$data = array('det_kat_pengeluaran'=>$inputnama);
						$this->db->insert('kategori_pengeluaran',$data);
						redirect(site_url('dashboard/kategori_kas'));
					}
					break;
				case 'hapusmasuk': //hapus kategori pemasukan
				$id = $this->input->get('id');
				$this->db->delete('kategori_pemasukan',array('id_kat_masuk'=>$id));
				redirect(site_url('dashboard/kategori_kas'));
				break;
				case 'hapuskeluar': //hapus kategori pengeluaran
				$id = $this->input->get('id');
				$this->db->delete('kategori_pengeluaran',array('id_kat_pengeluaran'=>$id));
				redirect(site_url('dashboard/kategori_kas'));
				break;
				case 'editmasuk'://edit kategori masuk
				$id = $this->input->get('id');
				$data['edit'] = $this->m_admin->get_kategori_masuk_by_id($id);
				$data['action'] = site_url('dashboard/kategori_kas?act=proseseditmasuk');
				$data['katMasuk'] = $this->m_admin->show_all_kategoripemasukan();
				$data['katKeluar'] = $this->m_admin->show_all_kategoripengeluaran();
				$this->baseView('admin/kategori_kas',$data);
				break;
				case 'editkeluar'://edit kategori keluar
				$id = $this->input->get('id');
				$data['edit'] = $this->m_admin->get_kategori_keluar_by_id($id);
				$data['action'] = site_url('dashboard/kategori_kas?act=proseseditkeluar');
				$data['katMasuk'] = $this->m_admin->show_all_kategoripemasukan();
				$data['katKeluar'] = $this->m_admin->show_all_kategoripengeluaran();
				$this->baseView('admin/kategori_kas',$data);
				break;
				case 'proseseditmasuk':
				$newname = $this->input->post('nama');
				$id = $this->input->post('id');
				$data = array('det_kat_masuk'=>$newname);
				$this->db->where('id_kat_masuk',$id);
				$this->db->update('kategori_pemasukan',$data);
				redirect(site_url('dashboard/kategori_kas'));
				break;
				case 'proseseditkeluar':
				$newname = $this->input->post('nama');
				$id = $this->input->post('id');
				$data = array('det_kat_pengeluaran'=>$newname);
				$this->db->where('id_kat_pengeluaran',$id);
				$this->db->update('kategori_pengeluaran',$data);
				redirect(site_url('dashboard/kategori_kas'));
				break;
				default:
				echo 'Menu salah';
				break;
			}
		} else { //hanya lihat kategori saja
			$data['katMasuk'] = $this->m_admin->show_all_kategoripemasukan();
			$data['katKeluar'] = $this->m_admin->show_all_kategoripengeluaran();
			$this->baseView('admin/kategori_kas',$data);
		}
	}
	//////////////////////////////////////////////
	////////////ALL ABOUT GUDANG /////////////////
	//////////////////////////////////////////////
	//semua barang digudang
	public function barang(){
		$data['script'] = "<script> $(document).ready(function(){ document.getElementById('barang').className = 'active';});</script>";
		$data['title'] = 'Gudang';
		$data['kategori'] = $this->m_barang->semua_kat_barang();
		$data['pemasok'] = $this->m_gudang->semuapemasok();
		//pagination setup
		$this->load->library('pagination');
		$config['per_page']= 20;
		$config['num_link']=2;
		$config['page_query_string'] = TRUE;
		$this->pagination->initialize($config); 
		$data['page'] = $this->pagination->create_links();		
		if(isset($_GET['per_page'])) {
			if($_GET['per_page'] == '') { 
				$uri = 0;
			} else {
				$uri = $_GET['per_page'];
			}
		} else {
			$uri = 0;
		}
		if(!empty($this->input->get('q'))) { //jika melakukan pencarian
			$keyword = $this->input->get('q');//keyword pencarian
			$config['base_url'] = site_url('gudang/barang?p=on&q='.$keyword);
			$this->db->where('id_barang',$keyword);			
			$config['total_rows'] = $this->db->count_all('barang');			
			$data['barang'] = $this->m_barang->searchBarang($config['per_page'],$uri,$keyword);
		} else { //jika tidak melakukan pencarian
			$config['base_url'] = site_url('gudang/barang?p=on');
			$config['total_rows'] = $this->db->count_all('barang');			
			$data['barang'] = $this->m_barang->semuaBarang($config['per_page'],$uri);
		}		
		$this->baseView('admin/barang',$data);
	}
	//semua kategori barang
	public function kategori_barang(){
		$data['title'] = 'Kategori Barang';
		$data['script'] = "<script> $(document).ready(function(){ document.getElementById('kategori').className = 'active';});</script>";
		$data['kategori'] = $this->m_barang->semua_kat_barang();
		$this->baseView('admin/kategori',$data);
	}
	//semua pemasok
	public function pemasok(){
		$data['title'] = 'Pemasok';
		$data['script'] = "<script> $(document).ready(function(){ document.getElementById('pemasok').className = 'active';});</script>";
		$data['pemasok'] = $this->m_gudang->semuaPemasok();
		$this->baseView('admin/pemasok',$data);
	}

	//////////////////////////////////////////////
	///////////// ALL ABOUT KASIR ////////////////
	//////////////////////////////////////////////
	//menampilkan semua transaksi
	public function transaksi(){
		$data['title'] = 'Semua Transaksi';
		$data['script'] = "<script> $(document).ready(function(){ document.getElementById('transaksi').className = 'active';});</script>";
		$this->load->library('pagination');
		$config['per_page']= 20;
		$config['num_link']=2;
		$config['page_query_string'] = TRUE;
		$this->pagination->initialize($config); 
		$data['page'] = $this->pagination->create_links();		
		if(isset($_GET['per_page'])) {
			if($_GET['per_page'] == '') { 
				$uri = 0;
			} else {
				$uri = $_GET['per_page'];
			}
		} else {
			$uri = 0;
		}
		if(!empty($this->input->get())){ //jika set get
			if(!empty($this->input->get('status'))) {
				switch ($this->input->get('status')) {
					case 'lunas':
					$config['base_url'] = site_url('kasir/transaksi?p=on&status=lunas');
					$this->db->where('status','lunas');
					$config['total_rows'] = $this->db->count_all('transaksi');
					$data['transaksi'] = $this->m_kasir->showTransaksiByStatus($config['per_page'],$uri,'lunas');	
					break;

					case 'piutang':
					$config['base_url'] = site_url('kasir/transaksi?p=on&status=piutang');
					$this->db->where('status','piutang');
					$config['total_rows'] = $this->db->count_all('transaksi');	
					$data['transaksi'] = $this->m_kasir->showTransaksiByStatus($config['per_page'],$uri,'piutang');
					break;

					default:
					echo "ERROR 404 : Halaman Tidak Ditemukan";
					break;
				}
			}else{
				switch ($this->input->get('q')) {
					default:
					$keyword = $this->input->get('q');//keyword
					$config['base_url'] = site_url('kasir/transaksi?p=on&q='.$keyword);
					$this->db->where('id_transaksi',$keyword);
					$config['total_rows'] = $this->db->count_all('transaksi');	
					$data['transaksi'] = $this->m_kasir->showTransaksiByKeyword($config['per_page'],$uri,$keyword);
					break;
				}
			}		
			
		} else { //menampilkan semua transaksi		
			$config['base_url'] = site_url('kasir/transaksi?p=on');
			$config['total_rows'] = $this->db->count_all('transaksi');			
			$data['transaksi'] = $this->m_kasir->showTransaksi($config['per_page'],$uri);
		}		
		$this->baseView('admin/transaksi',$data);
	}
	//////////////////////////////////////////////
	///////////// ALL ABOUT KARYAWAN /////////////
	//////////////////////////////////////////////
	public function karyawan(){
		$data['title'] = 'Karyawan';
		$data['script'] = "<script> $(document).ready(function(){ document.getElementById('karyawan').className = 'active';});</script>";
		if(!empty($this->input->get('act'))){
			switch ($_GET['act']) {
				case 'addkaryawan': //proses menambah karyawan baru
				$nama = $this->input->post('addnama');
				$bagian = $this->input->post('addbagian');
				$telepon = $this->input->post('addtelepon');
				$alamat = $this->input->post('addalamat');
				$username = $this->input->post('addusername');
				$password = md5($this->input->post('addpassword'));
				$data = array(
					'nama'=>$nama,
					'bagian'=>$bagian,
					'telp'=>$telepon,
					'alamat'=>$alamat,
					'username'=>$username,
					'password'=>$password
					);
				$this->db->insert('pegawai',$data);
				redirect('dashboard/karyawan');//kemabali ke halaman dashboard
				break;
				case 'delkaryawan':
				$id = $_GET['id'];
				$this->db->delete('pegawai',array('id_pegawai'=>$id));
				redirect('dashboard/karyawan');//kemabali ke halaman dashboard
				break;
				case 'editkaryawan':
				$id = $_GET['id'];
				$data['karyawan'] = $this->m_admin->pegawai_by_id($id);
				$data['pegawai'] = $this->m_admin->show_all_pegawai();
				$this->baseView('admin/karyawan',$data);
				break;
				case 'proceditkaryawan':
				$id = $this->input->post('addid');
				$nama = $this->input->post('addnama');
				$bagian = $this->input->post('addbagian');
				$telepon = $this->input->post('addtelepon');
				$alamat = $this->input->post('addalamat');
				$username = $this->input->post('addusername');
				$password = $this->input->post('addpassword');
				if(empty($password)){
					$password = $this->input->post('recentpassword');	
				}else{
					$password = md5($password);
				}
				$data = array(
					'nama'=>$nama,
					'bagian'=>$bagian,
					'telp'=>$telepon,
					'alamat'=>$alamat,
					'username'=>$username,
					'password'=>$password,
					);
				$this->db->where('id_pegawai',$id);
				$this->db->update('pegawai',$data);
				redirect('dashboard/karyawan');//kemabali ke halaman dashboard
				break;
				default:
				echo 'menu yang anda pilih salah';
				break;
			}			
		} else {
			$data['pegawai'] = $this->m_admin->show_all_pegawai();
			$this->baseView('admin/karyawan',$data);
		}
	}

	///////////////////////////////
	//// ALL ABOUT PENJURNALAN ////
	///////////////////////////////

	//cek penjurnalan
	public function jurnal(){
		$data['title'] = "Penjurnalan";
		$data['script'] = "<script> $(document).ready(function(){ document.getElementById('jurnal').className = 'active';});</script>";
		if(!empty($this->input->get())){
			$data['tanggal'] = $this->input->get('tgl');
			$data['bulan'] = $this->input->get('bln');
			$data['tahun'] = $this->input->get('thn');
			$params = array($data['tanggal'],$data['bulan'],$data['tahun']);
			$bln_params = array($data['bulan'],$data['tahun']);
		}else{
			$data['tanggal'] = date('d');
			$data['bulan'] = date('m');
			$data['tahun'] = date('Y');
			$params = array(date('d'),date('m'),date('Y'));
			$bln_params = array(date('m'),date('Y'));
		}
		//bln_params_lalu
		if($data['bulan'] == 1){
			$bulanlalu = 12;
			$tahunlalu = $data['tahun']-1;
		}else{
			$bulanlalu = $data['bulan']-1;
			$tahunlalu = $data['tahun'];
		}
		$blnlalu_params = array($bulanlalu,$tahunlalu);//parameter untuk bulan lalu
		// print_r($blnlalu_params);
		//selesai mendapatkan modal
		$data['modallalu'] = $this->get_modal($bulanlalu,$tahunlalu);
		$data['total_gaji'] = $this->m_karyawan->total_gaji_bln_ini($bln_params);
		//jumlah pemasukan dan pengeluaran bulan ini;
		$data['pengeluaran_bln_ini'] = $this->m_pengeluaran->showPengeluaran_blnini($bln_params);//show pengeluaran bulan ini
		$data['pemasukan_bln_ini'] = $this->m_pemasukan->showPemasukan_blnini($bln_params);//show pemasukan bulan ini;
		$data['all_kategori_keluar'] = $this->m_pengeluaran->show_all_kategori_keluar();
		$data['all_kategori_masuk'] = $this->m_pemasukan->show_all_kategori_masuk();
		//jumlah pemasukan dan pengeluaran bulan sebelumnya
		$data['pengeluaran_bln_lalu'] = $this->m_pengeluaran->showPengeluaran_blnini($blnlalu_params);;
		$data['pemasukan_bln_lalu'] = $this->m_pengeluaran->showPengeluaran_blnini($blnlalu_params);;
		$this->baseView('admin/jurnal',$data);
	}

	//cek buku besar
	public function buku_besar(){
		$data['title'] = 'Buku Besar ';
		$data['script'] = "<script> $(document).ready(function(){ document.getElementById('bukubesar').className = 'active';});</script>";
		if(!empty($this->input->get())){
			$data['tanggal'] = $this->input->get('tgl');
			$data['bulan'] = $this->input->get('bln');
			$data['tahun'] = $this->input->get('thn');
			$params = array($data['tanggal'],$data['bulan'],$data['tahun']);
			$bln_params = array($data['bulan'],$data['tahun']);
		}else{
			$data['tanggal'] = date('d');
			$data['bulan'] = date('m');
			$data['tahun'] = date('Y');
			$params = array(date('d'),date('m'),date('Y'));
			$bln_params = array(date('m'),date('Y'));
		}
		//bln_params_lalu
		if($data['bulan'] == 1){
			$bulanlalu = 12;
			$tahunlalu = $data['tahun']-1;
		}else{
			$bulanlalu = $data['bulan']-1;
			$tahunlalu = $data['tahun'];
		}
		$blnlalu_params = array($bulanlalu,$tahunlalu);//parameter untuk bulan lalu
		$data['modallalu'] = $this->get_modal($bulanlalu,$tahunlalu);
		$data['total_gaji'] = $this->m_karyawan->total_gaji_bln_ini($bln_params);
		$data['pengeluaran_bln_ini'] = $this->m_pengeluaran->showPengeluaran_blnini($bln_params);//show pengeluaran bulan ini
		$data['pemasukan_bln_ini'] = $this->m_pemasukan->showPemasukan_blnini($bln_params);//show pemasukan bulan ini;
		$data['all_kategori_keluar'] = $this->m_pengeluaran->show_all_kategori_keluar();
		$data['all_kategori_masuk'] = $this->m_pemasukan->show_all_kategori_masuk();
		$this->baseView('admin/bukubesar',$data);
	}

	//cek neraca
	public function neraca(){
		$data['bulan'] = $this->input->get('bln');
		$data['tahun'] = $this->input->get('thn');
		$data['title'] = 'Neraca';;
		$data['script'] = "<script> $(document).ready(function(){ document.getElementById('neraca').className = 'active';});</script>";
		$this->baseView('admin/neraca',$data);
	}

	//lapora raba rugi
	public function raba_rugi(){
		$data['bulan'] = $this->input->get('bln');
		$data['tahun'] = $this->input->get('thn');
		$data['title'] = 'Laporan Data Rugi | ';
		$data['script'] = "<script> $(document).ready(function(){ document.getElementById('neraca').className = 'active';});</script>";
		$this->baseView('admin/raba_rugi',$data);	
	}
	//cetak raba rugi
	public function cetakRabaRugi(){
		$data['bulan'] = $this->input->get('bln');
		$data['tahun'] = $this->input->get('thn');
		$data['title'] = 'Cetak Laporan Data Rugi | ';
		$data['script'] = "<script> $(document).ready(function(){ document.getElementById('neraca').className = 'active';});</script>";
		$this->load->view('admin/cetak_raba_rugi',$data);		
		$html = $this->output->get_output();
			//echo $html;
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->render();
		$this->dompdf->stream('Laporan Data Rugi'.".pdf");//pdf file name	
	}
	//laporan perubahan modal
	public function perubahan_modal(){
		$data['title'] = 'Laporan Perubahan Modal | ';
		$data['script'] = "<script> $(document).ready(function(){ document.getElementById('neraca').className = 'active';});</script>";
		$this->baseView('admin/perubahan_modal',$data);
	}
}

