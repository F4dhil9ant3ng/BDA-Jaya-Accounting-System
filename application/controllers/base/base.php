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
		error_reporting(1);
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
	public function get_modal($bln,$thn){
		$bln_params = array($bln,$thn);
		//mendapatkan total gaji;
		$total_gaji= $this->m_karyawan->total_gaji_bln_ini($bln_params);
		//mendapatkan total pengeluaran
		$pengeluaran_bln_ini = $this->m_pengeluaran->showPengeluaran_blnini($bln_params);//show pengeluaran bulan ini
		$pemasukan_bln_ini = $this->m_pemasukan->showPemasukan_blnini($bln_params);//show pemasukan bulan ini;
		$all_kategori_keluar = $this->m_pengeluaran->show_all_kategori_keluar();
		$all_kategori_masuk = $this->m_pemasukan->show_all_kategori_masuk();
		//jurnal
		$jurnalmerge = array_merge($pemasukan_bln_ini,$pengeluaran_bln_ini);
		//sortir berdasarkan tanggal
		function gsort_array_by_value($key, &$array) {
			$sorter = array();
			$ret = array();
			reset($array);
			foreach($array as $ii => $value) {
				$sorter[$ii] = $value[$key];
			}
			asort($sorter);
			foreach($sorter as $ii => $value) {
				$ret[$ii] = $array[$ii];
			}
			$array = $ret;
		}
		//sortir jurnal berdasarkan tanggal
		gsort_array_by_value('tanggal',$jurnalmerge);
		//akun kas
		$saldo = 0;$debitkas=0;$kreditkas=0;
		foreach($jurnalmerge as $jurnal):
			//untuk pemasukan
			if($jurnal['det']=='masuk'){
				if($jurnal['status']=='piutang'){
					$this->db->where('id_transaksi',$jurnal['id_transaksi']);//cek transaksi sesuai dengan id trannsksi
					$transaksi = $this->db->get('transaksi');
					$transaksi = $transaksi->row_array();
					$totalterbayarkan = $transaksi['bayar'];
					$saldo = $saldo + $totalterbayarkan;
					$debitkas = $debitkas + $jurnal['rp'];
				}else{
					$saldo = $saldo+$jurnal['rp'];
					$debitkas = $debitkas + $jurnal['rp'];
				}
			}
			//untuk pengeluaran
			if($jurnal['det']=='keluar'){
				if($jurnal['status']=='hutang'){
					$this->db->where('id_pasokan',$jurnal['id_pasokan']);//cek id pasokan
					$pasokan = $this->db->get('pasokan');
					$pasokan = $pasokan->row_array();
					$totalterbayarkan = $pasokan['rp_bayar'];
					$saldo = $saldo - $totalterbayarkan;
					$kreditkas = $kreditkas + $totalterbayarkan;
				}else{
					$saldo = $saldo-$jurnal['rp'];
					$kreditkas = $kreditkas + $jurnal['rp'];
				}
			}
			$total_kas = $saldo;
			$neraca_kas = $total_kas;
			endforeach;
		//end of akun kas
			//tanggal yang berakhir dengan 31
			$akhir31 = array(1,3,5,7,8,10,12);
							//perhitungan gaji pegawai
			if($bln == 2 && $thn%4 == 0) {
				$tgl = 29;
			} else if($bln == 2 && $thn%4 != 0) {
				$tgl = 28;
			} else if(in_array($bln, $akhir31)) {
				$tgl = 31;
			} else {
				$tgl = 30;
			}
			if($total_gaji != 0 ){
				$totalgaji = $total_gaji * 30000;
				$kreditkas = $kreditkas + $total_gaji;
				$total_kas = $total_gaji;
				$neraca_kas = $saldo-$totalgaji;
			}
			//lebih besar debit atau kredit
			if($kreditkas > $debitkas){
				$kas = $kreditkas - $debitkas;
				$neraca=array('tipe'=>'kas','value'=>$neraca_kas,'pos'=>'kredit');
				$data['neraca'][] = $neraca;
			} else {
				$kas = $debitkas - $kreditkas;
				$neraca=array('tipe'=>'kas','value'=>$neraca_kas,'pos'=>'debit');
				$data['neraca'][] = $neraca;
			}

			//perulangan untuk kategori pemasukan
			foreach($all_kategori_masuk as $katmasuk):
				if($katmasuk['id_kat_masuk'] == '2'){
					$params = array($katmasuk['id_kat_masuk'],$bln,$thn);
					$akun = $this->m_pemasukan->show_masuk_bukubesar($params);
					$pendapatan = array();
					$piutang = array();
					foreach($akun as $a):
						if($a['status']=='lunas') { //menyimpan semua yang lunas
							//memasukan data ke pendapatan
							$pendapatan[] = array_merge($pendapatan,$a);
						} else if ($a['status']=='piutang') { //menyimpan semua yang masih piuntag
							//memasukan data ke pendapatan
							$pendapatan[] = array_merge($pendapatan,$a);
							//memasukan data ke piutang
							$piutang[] = array_merge($piutang,$a);
						}
						endforeach;
					//mencari total pendapatan
						$totalpendapatan = 0;
						foreach($pendapatan as $dapat):
							$totalpendapatan = $totalpendapatan = $dapat['rp'];
						endforeach;
						$neraca = array('tipe'=>'pendapatan','value'=>$totalpendapatan,'pos'=>'kredit');
						array_push($data['neraca'], $neraca);
					//mencari total piutang
						$totalpiutang = 0;
						foreach($piutang as $piut):
						$this->db->where('id_transaksi',$piut['id_transaksi']);//cek transaksi sesuai dengan id trannsksi
					$transaksi = $this->db->get('transaksi');
					$transaksi = $transaksi->row_array();
					$piutang = $transaksi['total_bayar'] - $transaksi['bayar'];
					$totalpiutang = $totalpiutang + $piutang;
					endforeach;
					$neraca = array('tipe'=>'piutang','value'=>$totalpiutang,'pos'=>'debit');
					array_push($data['neraca'], $neraca);
				}else{ //selesai kategori penjualan barang
					$params = array($katmasuk['id_kat_masuk'],$bln,$thn);
					$akun = $this->m_pemasukan->show_masuk_bukubesar($params);								
					$total = 0;
					foreach($akun as $a):
						$total = $total = $a['rp'];
					endforeach;
					$this->db->where('id_kat_masuk',$a['kategori']);
					$query = $this->db->get('kategori_pemasukan');//select ke table pemasukan
					$query = $query->row_array();
					$neraca=array('tipe'=>$query['det_kat_masuk'],'value'=>$total,'pos'=>'kredit');
					array_push($data['neraca'], $neraca);
				}
				endforeach;
			//end of perulangan untuk kategori pemasukan

			//perulangan untuk kategori pengeluaran
				foreach($all_kategori_keluar as $katkeluar):
					if($katkeluar['id_kat_pengeluaran'] == 6){
						$params = array($katkeluar['id_kat_pengeluaran'],$bln,$thn);
						$akun = $this->m_pengeluaran->show_keluar_bukubesar($params);
						$pembelian = array();
						$hutang = array();
						foreach($akun as $a):
							if($a['status']=='lunas'){
								$pembelian[] = array_merge($pembelian,$a);
							}else if($a['status']=='hutang'){
								$pembelian[] = array_merge($pembelian,$a);
								$hutang[] = array_merge($hutang,$a);
							}
							endforeach;

							$totalpembelian = 0;
							foreach($pembelian as $beli):
								$totalpembelian = $totalpembelian+$beli['rp'];
							endforeach;
							$neraca=array('tipe'=>'pembelian','value'=>$totalpembelian,'pos'=>'debit');
							array_push($data['neraca'], $neraca);
						//hutang usaha
							$totalhutang = 0;
							foreach($hutang as $hut):
								$this->db->where('id_pasokan',$hut['id_pasokan']);
							$pasokan = $this->db->get('pasokan');
							$pasokan = $pasokan->row_array();
							$hutang = $pasokan['rp'] - $pasokan['rp_bayar'];
							$totalhutang = $totalhutang+$hutang;
							endforeach;
							$neraca=array('tipe'=>'hutang','value'=>$totalhutang,'pos'=>'kredit');
							array_push($data['neraca'], $neraca);
					}else{//jika merupakan hasil pembelian barang
						$params = array($katkeluar['id_kat_pengeluaran'],$bln,$thn);
						$akun = $this->m_pengeluaran->show_keluar_bukubesar($params);
						foreach($akun as $a):	
							$totalkeluar[$a['kategori']] = 0;
						$totalkeluar[$a['kategori']] = $totalkeluar[$a['kategori']] + $a['rp'];
						$this->db->where('id_kat_pengeluaran',$a['kategori']);
						$query = $this->db->get('kategori_pengeluaran');
						$query = $query->row_array();
						$neraca=array('tipe'=>$query['det_kat_pengeluaran'],'value'=>$totalkeluar[$a['kategori']],'pos'=>'debit');
						array_push($data['neraca'], $neraca);
						endforeach;
					}
					endforeach;
			//end of perulangan untuk kategori pengeluaran

				//gaji
					if($total_gaji !=0){
						$totalgaji = $total_gaji * 30000;
						$akhir31 = array(1,3,5,7,8,10,12);
					//perhitungan gaji pegawai
						if($bln == 2 && $thn%4 == 0) {
							$tgl = 29;
						} else if($bln == 2 && $thn%4 != 0) {
							$tgl = 28;
						} else if(in_array($bln, $akhir31)) {
							$tgl = 31;
						} else {
							$tgl = 30;
							$neraca=array('tipe'=>'Beban Gaji','value'=>$total_gaji*30000,'pos'=>'debit');
							array_push($data['neraca'], $neraca);
						} 
					} 
				//end of gaji

				//set session
					$this->session->set_userdata($data);
					//mengatur neraca
					$totaldebet = 0;$totalkredit=0;
					foreach($this->session->userdata('neraca') as $n):
						if($n['pos'] == 'debit'){
							$totaldebet = $totaldebet + $n['value'];
						}
						if($n['pos'] == 'kredit'){
							$totalkredit = $totalkredit + $n['value'];
						}
					endforeach;
					//end of mengatur neraca

					//mengatur raba rugi
					$beban = array('beban','Beban');
					$tot_beban = 0;
					$tot_pendapatan = 0;
					//total pendapatan
					foreach ($this->session->userdata('neraca') as $data) {
						//jika pendapatan
						if($data['tipe']=='pendapatan'){
							$tot_pendapatan = $tot_pendapatan + $data['value'];
						} else if(strpos($data['value'],$beban == 1)){ //jika beban
							$tot_beban = $tot_beban + $data['value'];
						}
					}
					//total beban
					foreach ($this->session->userdata('neraca') as $data) {
						$tipe = $data['tipe'];
						$pencarian = strpos($tipe, 'Beban');
						if($pencarian !== false){
							$tot_beban = $tot_beban + $data['value'];
						}
					}
					//cek laba atau rugi
					$labrug = $tot_pendapatan - $tot_beban;
					//cek rugi ataukah laba
					if($labrug > 0){
						$status = 'laba';
					} else {
						$status = 'Rugi';
					}
					$modal = 0;
					foreach($this->session->userdata('neraca') as $n):
						if($n['tipe'] == 'Modal' && $n['value']!=0){
							$modal = $modal+$n['value'];
						}else if($n['tipe'] == 'Prive' && $n['value']!=0){
							$modal = $modal-$n['value'];
						}
					endforeach;
					if($status == 'laba'){
						$modal = $modal+$labrug;
					}else{
						$modal = $modal-$labrug;
					}
					$this->session->set_userdata('modallalu',$modal);//memasukan ke session
					return $modal;
					//end of mengatur raba rugi
				}	
			}