<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/controllers/base/base.php';
class gudang extends base {
	public function __construct(){
		parent::__construct();
		$this->gudang_logged_in();
	}

	public function index(){
		$data['script'] = "<script> $(document).ready(function(){ document.getElementById('stats').className = 'active';});</script>";
		$data['title'] = 'Dashboard';
		$data['activity'] = $this->m_gudang->gudang_activity(10,0);
		$data['log'] = $this->m_karyawan->loginTerakhir();
		$this->baseView('gudang/stats',$data);
	}
	//cek barang digudang
	public function cekBarang(){
		$kodebarang = $this->input->get('kode');
		// echo $kodebarang;
		$query = $this->db->get_where('barang',array('no_seri'=>$kodebarang));
		if($query->num_rows()>0){
			$result= $query->row_array();
			$response = $result['stok'].' | '.$result['nama'];
		} else {
			$response = 'Barang tidak ditemukan';
		}
		echo $response;
	}
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
		$this->baseView('gudang/barang',$data);
	}

	public function kategori(){
		$data['title'] = 'Kategori Barang';
		$data['script'] = "<script> $(document).ready(function(){ document.getElementById('kategori').className = 'active';});</script>";
		$data['kategori'] = $this->m_barang->semua_kat_barang();
		$this->baseView('gudang/kategori',$data);
	}

	public function pemasok(){
		$data['title'] = 'Pemasok';
		$data['script'] = "<script> $(document).ready(function(){ document.getElementById('pemasok').className = 'active';});</script>";
		$data['pemasok'] = $this->m_gudang->semuaPemasok();
		$this->baseView('gudang/pemasok',$data);
	}

	//////////////ALL ABOUT PROCESS
	public function tambahBarang(){
		$data['title'] = 'Memproses....';
		$post = $this->input->post();//get post request
		$by = $this->session->userdata('id_pegawai');
		//jika yang login admin
		if($this->session->userdata('admin_logged_in')){
			$by = 1;
		}
		$noseri = $post['inputSeri'];
		$nama = $post['inputNama'];
		$kategori = $post['inputKategori'];
		$hargabeli = 0;
		$stok = 0;
		$hargajual = 0;//set harga jual
		$status = 'lunas';
		$pemasok = null;
		//cek apakah nomor seri sudah ada
		$this->load->library('form_validation');
		$this->form_validation->set_rules('inputSeri','Nomor Seri','is_unique[barang.no_seri]');//no seri adalah unik
		if($this->form_validation->run()){ //data sesuai rule
			$params = array( //parameter untuk tambah barang
				'no_seri'=>$noseri,
				'nama'=>$nama,
				'kategori'=>$kategori,
				'oleh'=>$by,
				'harga_jual'=>$hargajual,
				'harga_beli'=>$hargabeli,
				'stok'=>$stok
				);
			if($this->m_barang->tambahBarang($params)) { //insert barang ke database
				$rp = $hargabeli * $stok;
				$keterangan = "Tambah Barang ".$nama." ke Gudang";
				$kategorikeluar = 6;//kategori pembelian barang
				$sql = "SELECT id_barang FROM barang WHERE no_seri = ?";
				$query = $this->db->query($sql,$noseri);
				$query = $query->row_array();
				$id_barang = $query['id_barang'];//lihat id barang yang baru ditambahkan
				//parameter untuk tambah ke tabel pengeluaran
				$params = array(
					'oleh' =>$by,
					'keterangan' => $keterangan,
					'rp'=>$rp,
					'kategori'=>$kategorikeluar,
					//'id_pemasok'=>$pemasok,
					'id_barang'=>$id_barang,
					'status'=>$status
					);				
				if($this->m_pengeluaran->tambahBarangGudang($params)){ //memasukan ke buku kas
					$sql = "SELECT nama FROM pemasok WHERE id_pemasok = ?";
					$query = $this->db->query($sql,$pemasok);
					$querypemasok = $query->row_array();
					$namapemasok = null;
					$activity = "Tambah barang baru dengan nomor seri ".$noseri." dari ".$namapemasok;
					$params = array(
						'id_barang' => $id_barang,
						'id_pegawai' =>$by,
						'id_kategori'=>$kategori,
						'activity'=>$activity
						);
					if($this->m_gudang->tambahActivity($params)){
						echo ("<SCRIPT LANGUAGE='JavaScript'>
							window.alert('Data Berhasil Dimasukan');
							window.location.href='".site_url('gudang/barang')."';
						</SCRIPT>");
					}//else untuk gagal tambah activity
				} //else gagal memasukan pengeluaran ke buku kas
			} //else gagal memasukan barang ke database
		} else { //tidak sesuai rule
			echo ("<SCRIPT LANGUAGE='JavaScript'>
				window.alert('Gagal Tambah Barang, Nomor Seri Sudah Ada !');
				window.location.href='".site_url($this->agent->referrer())."';
			</SCRIPT>");	
		}
	}
	//hapus barang
	public function hapusBarang(){
		$idbarang = $_GET['id'];
		$this->db->where('id_barang',$idbarang);
		$this->db->delete('barang');
		redirect(site_url('gudang/barang'));
	}
	//edit barang
	public function editbarang(){
		$id = $this->uri->segment(3);//id barang
		$data = array(
			'no_seri'=>$_POST['inputSeri'],
			'nama'=>$_POST['inputNama'],
			'kategori'=>$_POST['inputKategori'],
			'stok'=>$_POST['inputStok'],
			'harga_beli'=>$_POST['inputHargaBeli'],
			'harga_jual'=>$_POST['inputHargaJual'],
			);
		$this->db->where('id_barang',$id);
		$this->db->update('barang',$data);
		redirect(site_url('gudang/barang'));
	}
	//cek seri barang
	public function cekSeri(){
		$seri = $this->input->get('seri');
		$query = $this->db->get_where('barang', array('no_seri' => $seri),1,0);		
		if($query->num_rows()>0){
			$query = $query->row_array();
			$nama = $query['nama'];
			echo '<p style="color:green">Ditemukan : '.$nama.'</p>';
		} else {
			echo '<p style="color:red">nomor seri tidak ditemukan</p>';
		}
	}
	//tambah stok barang
	public function tambahStok(){
		//get post data
		$noseri = $this->input->post('inputSeri');
		$pemasok = $this->input->post('inputPemasok');
		$stoktambah = $this->input->post('inputStok');
		$hargabeli = $this->input->post('inputHargaBeli');
		$hargajual = $hargabeli + ($hargabeli * 0.01);
		$status = $this->input->post('statusTransaksi');
		$hargabelitotal = $hargabeli * $stoktambah;//total harga beli
		//get id barang
		$querybarang = "SELECT id_barang FROM barang WHERE no_seri = ?";
		$barang = $this->db->query($querybarang,$noseri);
		$barang = $barang->row_array();
		$idbarang = $barang['id_barang'];
		//edit data barang
		$sqlupdatebarang = "UPDATE barang SET stok = stok + ".$stoktambah.",harga_beli = ".$hargabeli.",
		harga_jual = ".$hargajual." WHERE no_seri = '".$noseri."'";
		if($this->db->query($sqlupdatebarang)){//berhasil tambah stok di database
			//proses memasukan data pengeluaran
			$datapengeluaran = array(
				'oleh'=>$this->session->userdata('id_pegawai'),
				'keterangan'=>'tambah stok untuk barang dengan nomor seri : '.$noseri,
				'rp'=>$hargabelitotal,
				'kategori'=>6,
				'id_barang'=> $idbarang,
				'id_pemasok'=>$pemasok,
				'status'=>'lunas',//pasti lunas
				);
			if($this->db->insert('pengeluaran',$datapengeluaran)){ //ekse untuk tabel pengeluaran
				//memasukan data ke tabel activity
				$dataactivity = array(
					'id_pegawai'=>$this->session->userdata('id_pegawai'),
					'id_barang'=>$idbarang,
					'id_pemasok'=>$pemasok,
					'activity'=>'Menambah stok barang dengan nomor seri : '.$noseri,
					);
				if($this->db->insert('gudang_activity',$dataactivity)){
					echo 'Stok barang telah ditambahkan <a href="'.site_url('gudang/barang').'">Kembali</a>';
				}else{
					echo "Gagal memasukan ke tabel gudang_activity";
				}
			} else {
				echo "Gagal memasukan ke tabel pengeluaran";
			}
		} else {
			echo 'Gagal update tabel barang';
		}
	}
	//tambah kategori
	public function tambahKategori(){
		$kategori = $this->input->post('inputKategori');
		$params = array('des_kat_barang'=>$kategori);
		$this->db->insert('kategori_barang',$params);
		redirect($this->agent->referrer());
	}
	//hapus kategori
	public function hapuskategori(){
		$id = $this->input->get('id');
		$this->db->delete('kategori_barang',array('id_kat_barang'=>$id));
		redirect(site_url('gudang/kategori'));
	}
	//tambah pemasok
	public function tambahPemasok(){
		$pemasok = $this->input->post('inputPemasok');
		$alamat = $this->input->post('inputAlamat');
		$kontak = $this->input->post('inputKontak');
		$params = array('nama'=>$pemasok,'alamat'=>$alamat,'kontak'=>$kontak);
		$this->db->insert('pemasok',$params);
		redirect($this->agent->referrer());
	}
	//hapus pemasok
	public function hapusPemasok(){
		$id = $this->input->get('id');
		$this->db->delete('pemasok',array('id_pemasok'=>$id));
		redirect($this->agent->referrer());
	}
	//edit data kategori barang / pemasok
	public function editdata(){
		$act = $this->input->get('act');
		$id = $this->input->get('id');
		$data['act'] = $act;
		switch ($act) {
			case 'kategori':
			$data['kategori'] = true;
			$this->db->where('id_kat_barang',$id);
			$query = $this->db->get('kategori_barang');
			$data['item'] = $query->row_array();
			break;

			case 'pemasok':
			$data['pemasok'] = true;
			$this->db->where('id_pemasok',$id);
			$query = $this->db->get('pemasok');
			$data['item'] = $query->row_array();
			break;
			
			default:
			echo 'Error : action tidak tersedia';
			break;
		}
		$this->baseView('gudang/editdata',$data);
	}
	//proses edit data kategori barang / pemasok
	public function proses_editdata(){
		$id = $this->input->post('id');
		if(isset($_POST['btn_kategori'])){
			$data = array(
				'des_kat_barang' => $this->input->post('nama')
				);
			$this->db->where('id_kat_barang',$id);
			$this->db->update('kategori_barang',$data);
			redirect(site_url('gudang/kategori'));
		} else if(isset($_POST['btn_pemasok'])){
			$data = array(
				'nama' => $this->input->post('nama'),
				'alamat' => $this->input->post('alamat'),
				'kontak'=>$this->input->post('kontak')
				);
			$this->db->where('id_pemasok',$id);
			$this->db->update('pemasok',$data);
			redirect(site_url('gudang/pemasok'));
		}
	}
	//tambah pasokan
	public function tambah_pasokan(){
		$this->load->library('cart');
		$data['title'] = 'Tambah Pasokan';
		$data['pemasok'] = $this->m_gudang->semuaPemasok();
		if(!empty($this->input->post())){ //jika tambah ke cart
			$kodebarang = $this->input->post('inputKode');//kodebarang
			$jumlah = $this->input->post('inputJumlah');//input jumlah barang
			$hargabeli = $this->input->post('inputHargaBeli');//input harga beli
			$barang = $this->m_barang->showBarangByKode($kodebarang);//detail barang
			//insert ke cart
			$insert = array(
				'id'=>$kodebarang,
				'qty'=>$jumlah,
				'price'=>$hargabeli,
				'name'=>$barang['nama']
				);
			$this->cart->insert($insert);//memasukan data ke cart
		}
		$this->baseView('gudang/tambahpasokan',$data);
	}
	//hapus cart
	public function hapusCart(){
		$this->load->library('cart');
		$id= $this->input->get('id');
		$data = array('rowid'=>$id,'qty'=>0);//kembali ke 0
		$this->cart->update($data);
		redirect(site_url('gudang/tambah_pasokan'));
	}
	//pasokan
	public function pasokan(){
		$data['title'] = 'pasokan';
		$data['script'] = "<script> $(document).ready(function(){ document.getElementById('pasokan').className = 'active';});</script>";
		$data['pasokan'] = $this->m_gudang->semua_pasokan();
		$this->baseView('gudang/pasokan',$data);
	}
	public function cetakpasokan(){
		// print_r($_GET);
		//jika tidak atur tanggal atau bulan
		// if(empty($_GET['tgl']) || empty($_GET['bln'])){
		// 	redirect('gudang/pasokan');
		// }
		$data['pasokan'] = $this->m_gudang->cetakPasokan($_GET['tgl'],$_GET['bln'],$_GET['thn']);
		if(empty($data['pasokan'])){// print_r($pasokan);
			echo 'tidak ada data ditemukan';
		}else{
			$this->load->view('gudang/cetakpasokan',$data);
		// Load library
			$html = $this->output->get_output();
		//echo $html;
			$this->load->library('dompdf_gen');
			$this->dompdf->load_html($html);
			$this->dompdf->render();
			$this->dompdf->stream('laporan pasokan'.".pdf");//pdf file name
		}
}
	//proses tambah stok
public function proses_tambah_stok(){
	$this->load->library('cart');
	$data['title'] = "Memproses...";
		// set status pembayaran
		if(isset($_POST['btn_bayar'])) { //jika pelanggan bayar
			$status = 'lunas';
		} else if(isset($_POST['btn_hutang'])){ //jika pelanggan hutang
			$status = 'hutang';
		}
		//buat pasokan baru
		$totalBayar = $this->cart->total();
		$dibayar = $this->input->post('inputBayar');
		$kembali = $this->input->post('inputKembali');
		$pemasok = $this->input->post('inputPemasok');
		$oleh = $this->session->userdata('id_pegawai');
		if(empty($oleh)){$oleh=1;}
		$data= array(
			'rp'=>$totalBayar,
			'status'=>$status,
			'oleh'=>$oleh,
			'rp_bayar'=>$dibayar,
			'rp_kembali'=>$kembali,
			'pemasok'=>$pemasok);
		//memasukan ke tabel pasokan
		if($this->db->insert('pasokan',$data)){
			//ambil id pasokan paling atas
			$this->db->select_max('id_pasokan');
			$query = $this->db->get('pasokan');
			$pasokan = $query->row_array();
			$id_pasokan = $pasokan['id_pasokan'];//get lattest id pasokan [WORKED]
			$id_pegawai = $this->session->userdata('id_pegawai');
			if(empty($id_pegawai)){$id_pegawai=null;}
			//mememasukan item pasokan
			foreach($this->cart->contents() as $i):
				$barang = $this->m_barang->showBarangByKode($i['id']);//ambil data barang by kode
				$idbarang = $barang['id_barang'];//mendapatkan id barang
				$item = array(
					'id_pasokan'=>$id_pasokan,
					'id_barang'=>$idbarang,
					'jumlah'=>$i['qty'],
					'harga_beli'=>$i['price'],
					'subtotal_beli'=>$i['subtotal']
					);
				$hargajual = $i['price'] + ($i['price'] * 0.01);
				//update stok barang
				$sql = "UPDATE barang SET stok = stok + ".$i['qty'].", harga_beli = ".$i['price'].",harga_jual = ".$hargajual." WHERE id_barang = ".$idbarang;
				$this->db->query($sql);//
				$this->db->insert('pasokan_item',$item); //query untuk memasukan transaksi item
				endforeach;
				//memasukan pengeluaran data keuangan
				$pengeluaran = array(
					'oleh'=>$this->session->userdata('id_pegawai'),
					'keterangan'=>'tambah pasokan dengan id : '.$id_pasokan.' , atas nama karyawan dengan id :'.$this->session->userdata('id_pegawai'),
					'rp'=>$totalBayar,
					'kategori'=>6,
					'id_pasokan'=>$id_pasokan,
					'status'=>$status);
				if($this->db->insert('pengeluaran',$pengeluaran)) {
					//membuat laporan
					$log = array(
						'id_pegawai'=>$id_pegawai,
						'id_pemasok'=>$pemasok,
						'activity'=>'menambah pasokan dari pemasok : '.$pemasok.' | dengan id pasokan : '.$id_pasokan,
						);
					$this->db->insert('gudang_activity',$log);//memasukan log ke database
					$this->cart->destroy();//membersihkan cart
					//$this->baseView('gudang/pasokan');		
					redirect(site_url('gudang/pasokan'));
				} else {
					echo 'data gagal masuk tabel pengeluaran';
				}				
			} else{
			//gagal masuk ketabel pasokan
			}

		}

	//hapus pasokan
		public function hapus_pasokan() {
			$id = $this->input->get('id');
			$this->db->where('id_pasokan',$id);
			$this->db->delete('pasokan');
			redirect(site_url('gudang/pasokan'));
		}

	}