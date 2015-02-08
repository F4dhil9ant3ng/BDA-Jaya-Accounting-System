<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'application/controllers/base/base.php';
class kasir extends base {
	public function __construct(){
		parent::__construct();
		// $this->kasir_logged_in();
		$this->load->library('cart');
	}
	//halaman pertama
	public function index(){
		$data['script'] = "<script> $(document).ready(function(){ document.getElementById('stats').className = 'active';});</script>";
		$data['title'] = 'Kasir';
		$data['activity'] = $this->m_kasir->activityKasir(10,0);
		$data['log'] = $this->m_karyawan->loginTerakhir();
		$data['today'] = $this->m_kasir->totTransaksiHariIni();
		$data['piutang'] = $this->m_kasir->totPiutang();
		$this->baseView('kasir/stats',$data);
	}
	//membuat transaksi baru oleh kasir, ketika ada pelanggan yang mau beli
	public function transaksiBaru(){
		$data['title'] = 'Kasir ';
		if(!empty($this->input->post())){//jika tambah transaksi
			// $data['title'] = 'Memproses...';
			$this->load->library('cart');//cart library
			$kodebarang = $this->input->post('inputKode');
			$jumlah = $this->input->post('inputJumlah');
			$barang = $this->m_barang->showBarangByKode($kodebarang);
			//print_r($barang);
			$insert = array(
				'id'=>$this->input->post('inputKode'), //id barang untuk dimasukan kedalam session
				'qty'=>$this->input->post('inputJumlah'),//jumlah barang yang dibeli
				'price'=>$barang['harga_jual'],//harga jual barang = (HB*10%) + HB
				'name'=>$barang['nama'],//nama barang yang dimasukan
				'kembali'=>$this->input->post('kembali'), 
				);
			$this->cart->insert($insert);//memasukan ke cart
			//redirect(site_url('kasir/transaksiBaru'));//redirect ke halaman transaksi baru 
		}
		$data['script'] = "<script> $(document).ready(function(){ document.getElementById('transaksiBaru').className = 'active';});</script>";
		$this->baseView('kasir/transaksiBaru',$data);
	}
	public function hapusCart(){
		$id= $this->input->get('id');
		$data = array('rowid'=>$id,'qty'=>0);//kembali ke 0
		$this->cart->update($data);
		redirect(site_url('kasir/TransaksiBaru'));
	}
	//cek barang digudang
	public function cekBarang(){
		$kodebarang = $this->input->get('kode');
		// echo $kodebarang;
		$this->db->like('nama',$kodebarang);
		// $this->db->or_where('id_barang',$kodebarang);
		$query = $this->db->get('barang',5,0);
		if($query->num_rows()>0){
			$result= $query->result_array();
			echo '<table class="table">';
			echo '<tr><td>Kode</td><td>Nama</td><td>Stok</td><td></td></tr>';
			foreach($result as $r){
				echo '<tr>';
				echo '<td>'.$r['no_seri'].'</td>';
				echo '<td>'.$r['nama'].'</td>';
				echo '<td>'.$r['stok'].'</td>';
				echo '<td><button class="btn-xs btn btn-primary" onclick="addBarang('.$r['no_seri'].')">+</button></td>';
				echo '</tr>';
			}
			echo '</table>';
		} else {
			echo 'Barang tidak ditemukan';
			
		}
	}
	//memproses transaksi
	public function prosesTransaksi(){
		$data['script'] = "<script>$(document).ready(function(){ document.getElementById('transaksiBaru').className = 'active';});</script>";
		$data['title'] = "Transaksi Tersimpan";
		// set status pembayaran
		if(isset($_POST['btn_bayar'])) { //jika pelanggan bayar
			$status = 'lunas';
		} else if(isset($_POST['btn_hutang'])){ //jika pelanggan hutang
			$status = 'piutang';
		}
		//buat transaksi baru
		$totalBayar = $this->cart->total();
		$dibayar = $this->input->post('inputBayar');
		$kembali = $this->input->post('inputKembali');
		$data= array(
			'id_pelanggan'=>$_POST['inputIdPelanggan'],
			'total_bayar'=>$totalBayar,
			'status'=>$status,
			'bayar'=>$dibayar,
			'kembali'=>$kembali);
		if ($this->db->insert('transaksi',$data)){ //insert to table [WORKED]
			//ambil id_transaksi paling atas
			$this->db->select_max('id_transaksi');
			$query = $this->db->get('transaksi');
			$transaksi = $query->row_array();
			$id_transaksi = $transaksi['id_transaksi'];//get lattest id transaksi [WORKED]
			$id_pegawai = $this->session->userdata('id_pegawai');
			if(empty($id_pegawai)){$id_pegawai=2;}
			//memasukan item transaksi
			foreach($this->cart->contents() as $i):
				$barang = $this->m_barang->showBarangByKode($i['id']);
				$idbarang = $barang['id_barang'];//mendapatkan id barang berdasarkan kode barang
				$item = array(
					'id_transaksi'=>$id_transaksi,
					'id_barang'=>$idbarang,
					'jumlah'=>$i['qty'],
					'subtotal'=>$i['subtotal']
					);
				$sql = "UPDATE barang SET stok = stok - ".$i['qty']." WHERE id_barang = ".$idbarang;//query mengurangi stok di gudang
				$this->db->query($sql);//query untuk menngurangi stok di gudang
				$this->db->insert('transaksi_item',$item); //query untuk memasukan transaksi item
				endforeach;
			//memasukan data keuangan ke pemasukan
				$pemasukan = array(
					'oleh'=>$this->session->userdata('id_pegawai'),
					'keterangan'=>'Penjualan dengan id transaksi : '.$id_transaksi,
					'rp'=>$totalBayar,
					'kategori'=>2,
					'id_transaksi'=>$id_transaksi,
					'status'=>$status
					);
				if($this->db->insert('pemasukan',$pemasukan)) {
				//membuat log untuk kasir
					$log = array(
						'id_pegawai'=>$id_pegawai,
						'id_transaksi'=>$id_transaksi,
						'catatan'=>'membuat transaksi baru dengan id : '.$id_transaksi,
						);
				$this->db->insert('kasir_activity',$log);//memasukan log ke database
				$this->cart->destroy();//membersihkan cart
				$this->baseView('kasir/cetak');
				// echo 'data berhasil disimpan';
			}//else jika gagal masuk tabel pemasukan
		} //ekse jika gagal buat transaksi baru
	}
		//lihat semua transaksi yang penah terjadi
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
		$this->baseView('kasir/transaksi',$data);
	}
	public function transaksiUbahStatus(){
		$status = $this->input->get('status');
		$id = $this->input->get('id');
		$update = array('status'=>$status);
		$this->db->update('pemasukan',$update,array('id_transaksi'=>$id));//update status di tabel pemasukan
		$this->db->update('transaksi',$update,array('id_transaksi'=>$id));//update status di tabel transaksi
		redirect(site_url('kasir/transaksi'));
	}
	public function deleteTransaksi(){
		$id = $this->input->get('id');
		//mengembalikan stok barang didatabase
		//on construct
		$this->db->delete('transaksi', array('id_transaksi' => $id));//delete transaksi
		redirect(site_url('kasir/transaksi'));
	}
	//lihat semua piutang transaksi yang dilakukan oelh pelanggan
	public function piutang(){

	}
	public function tambahkeluar(){
		$data['title'] = 'Tambah Pengluaran';
		$data['script'] = "<script> $(document).ready(function(){ document.getElementById('tambahkeluar').className = 'active';});</script>";
		$this->load->model('m_admin');
		$data['kategorikeluar'] = $this->m_admin->show_all_kategoripengeluaran();
		$this->baseView('kasir/tambahkeluar',$data);
	}
	//show all pelanggan
	public function pelanggan(){
		$data = array(
			'title'=>'Daftar Pelanggan',
			'pelanggan'=>$this->m_pelanggan->show_all_pelanggan(),
			'script'=>"<script> $(document).ready(function(){ document.getElementById('pelanggan').className = 'active';});</script>",
			);
		$this->load->model('m_admin');
		$this->baseView('kasir/daftarpelanggan',$data);
	}
	//tambah pelanggan
	public function tambahpelanggan(){
		$data = array(
			'nama_lengkap'=>$_POST['input_nama'],
			'alamat'=>$_POST['input_alamat'],
			'kontak'=>$_POST['input_kontak'],
			'tgl_daftar'=>date('Y-m-d h:i:s'),
			);
		if($this->db->insert('pelanggan',$data)){
			redirect('kasir/pelanggan');
		}else{
			echo 'gagal memasukan data';
		}
	}
	//hapus pelanggan
	public function hapuspelanggan(){
		$id=$_GET['id'];
		$this->db->delete('pelanggan',array('id_pelanggan'=>$id));
		redirect('kasir/pelanggan');
	}
	//edit pelanggan
	public function editpelanggan(){
		switch ($_GET['act']) {
			case 'edit':
			$id = $_GET['id'];
			$data = array(
				'title'=>'Daftar Pelanggan',
				'pelanggan'=>$this->m_pelanggan->show_all_pelanggan(),
				'editpelanggan'=>$this->m_pelanggan->show_pelanggan_by_id($id),
				'script'=>"<script> $(document).ready(function(){ document.getElementById('pelanggan').className = 'active';});</script>",
				);
			$this->baseView('kasir/daftarpelanggan',$data);
			break;
			case 'procedit':
			$this->db->where('id_pelanggan',$_POST['input_id']);
			$data = array(
				'nama_lengkap'=>$_POST['input_nama'],
				'alamat'=>$_POST['input_alamat'],
				'kontak'=>$_POST['input_kontak'],
				);
			$this->db->update('pelanggan',$data);
			redirect('kasir/pelanggan');
			break;
			default:
			echo 'get out hacker!';
			break;
		}
	}
	// ajax cek transaksi
	public function cek_pelanggan(){
		$nama = $_GET['nama'];
		$pelanggan = $this->m_pelanggan->search_pelanggan_by_name($nama);
		echo '<table class=\'table\'>';
		echo '<tr><td>id pelanggan</td><td>nama</td></tr>';
		foreach($pelanggan as $p):
			echo '<tr>';
		echo '<td>'.$p['id_pelanggan'].'</td>';
		echo '<td><a href="#" onclick="addPelanggan('.$p['id_pelanggan'].',\''.$p['nama_lengkap'].'\')">'.$p['nama_lengkap'].'</a></td>';
		echo '</tr>';
		endforeach;
		echo '</table>';
	}
	//////////// 
	// ALL ABOUT
	////////////
	public function cetaknota(){
		$idtransaksi = $this->input->get('id');//get id transaksi
		$data['detail'] = $this->m_transaksi->detail_transaksi($idtransaksi);
		$data['item'] = $this->m_transaksi->show_transaksi_item($idtransaksi);
		//show detail transaksi;
		$this->load->view('kasir/cetaknota',$data);
		$html = $this->output->get_output();
			//echo $html;
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->render();
		$this->dompdf->stream('nota'.".pdf");//pdf file name
	}
}