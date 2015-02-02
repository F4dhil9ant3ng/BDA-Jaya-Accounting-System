<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_pemasukan extends CI_Model{
	//show pengeluaran
	public function showPemasukan($params){//tgl bln , tahun
		// print_r($params);
		if($params[0]=='00' && $params[1] != '00'){//berdasarkan bulan
			// echo 'perbulan';
			$sql = "SELECT pemasukan.id_pemasukan AS  'id_pemasukan', pemasukan.tanggal AS  'tanggal', pemasukan.rp AS  'rp', kategori_pemasukan.det_kat_masuk AS 'kategori', 
			pemasukan.status AS  'status',pemasukan.keterangan AS 'keterangan'
			FROM pemasukan
			INNER JOIN kategori_pemasukan ON kategori_pemasukan.id_kat_masuk = pemasukan.kategori
			WHERE MONTH(pemasukan.tanggal) = '".$params[1]."'
			AND YEAR(pemasukan.tanggal) =  '".$params[2]."'" ;
			$query = $this->db->query($sql);
		} else if($params[0] =='00' && $params[1]=='00'){//berdasarkan tahun
			// echo 'pertahun';
			$sql = "SELECT pemasukan.id_pemasukan AS  'id_pemasukan', pemasukan.tanggal AS  'tanggal', pemasukan.rp AS  'rp', kategori_pemasukan.det_kat_masuk AS 'kategori', 
			pemasukan.status AS  'status',pemasukan.keterangan AS 'keterangan'
			FROM pemasukan
			INNER JOIN kategori_pemasukan ON kategori_pemasukan.id_kat_masuk = pemasukan.kategori
			WHERE YEAR(pemasukan.tanggal) =  '".$params[2]."'";
			$query = $this->db->query($sql);
		}else{
			// echo 'custom';
			$sql = "SELECT pemasukan.id_pemasukan AS  'id_pemasukan', pemasukan.tanggal AS  'tanggal', pemasukan.rp AS  'rp', kategori_pemasukan.det_kat_masuk AS 'kategori', 
			pemasukan.status AS  'status',pemasukan.keterangan AS 'keterangan'
			FROM pemasukan
			INNER JOIN kategori_pemasukan ON kategori_pemasukan.id_kat_masuk = pemasukan.kategori
			WHERE DAY(pemasukan.tanggal) = ? 
			AND MONTH( pemasukan.tanggal ) =  ?
			AND YEAR( pemasukan.tanggal ) =  ?" ;
			$query = $this->db->query($sql,$params);
		}
		if($query->num_rows()>0){return $query->result_array();}else{return array();}
	}
	//menampilkan pemasukan bulan ini
	public function showPemasukan_blnini($params){//bln , tahun
		if($params[0]=='00'){//pertahun
			$sql = "SELECT pemasukan.id_pemasukan AS  'id_pemasukan', pemasukan.tanggal AS  'tanggal', pemasukan.rp AS  'rp', kategori_pemasukan.det_kat_masuk AS 'kategori', 
			pemasukan.status AS  'status',pemasukan.keterangan AS 'keterangan',det,id_transaksi
			FROM pemasukan
			INNER JOIN kategori_pemasukan ON kategori_pemasukan.id_kat_masuk = pemasukan.kategori
			WHERE YEAR( pemasukan.tanggal ) =  ?" ;
			$query = $this->db->query($sql,$params[1]);
		}else{
			$sql = "SELECT pemasukan.id_pemasukan AS  'id_pemasukan', pemasukan.tanggal AS  'tanggal', pemasukan.rp AS  'rp', kategori_pemasukan.det_kat_masuk AS 'kategori', 
			pemasukan.status AS  'status',pemasukan.keterangan AS 'keterangan',det,id_transaksi
			FROM pemasukan
			INNER JOIN kategori_pemasukan ON kategori_pemasukan.id_kat_masuk = pemasukan.kategori
			WHERE MONTH( pemasukan.tanggal ) =  ?
			AND YEAR( pemasukan.tanggal ) =  ?" ;
			$query = $this->db->query($sql,$params);
		}		
		if($query->num_rows()>0){return $query->result_array();}else{return array();}
	}
	//menampilkan semua kategori keluar
	public function show_all_kategori_masuk(){
		$query = $this->db->get('kategori_pemasukan');
		return $query->result_array();
	}
	//menampilkan pemasukan berdasarkan kategori, bulan dan tahun : untuk buku besar
	public function show_masuk_bukubesar($params){ //kat | bln |tahun
		if($params[1]=='00'){//pertahun
			$sql = "SELECT * FROM pemasukan WHERE kategori = '".$params[0]."' AND YEAR(tanggal) = '".$params[2]."'";
			$query = $this->db->query($sql);
		}else{
			$sql = 'SELECT * FROM pemasukan WHERE kategori = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?';
			$query = $this->db->query($sql,$params);
		}		
		if($query->num_rows()<=0){
			return array();
		} else {
			return $query->result_array();
		}
	}
}