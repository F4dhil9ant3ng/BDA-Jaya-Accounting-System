<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class m_barang extends CI_Model{
	//kategori barang
	public function semua_kat_barang(){
		$query = $this->db->get('kategori_barang');
		if($query->num_rows()>0){
			return $query->result_array();
		} else {
			return array();
		}
	}
	//show barang
	public function semuaBarang($limit,$offset){
		$sql = "SELECT barang.id_barang AS 'id_barang',barang.no_seri AS 'no_seri',barang.nama AS 'nama',
		barang.tanggal AS 'tanggal',barang.harga_jual AS 'harga_jual',barang.harga_beli AS 'harga_beli',
		barang.stok AS 'stok',kategori_barang.des_kat_barang AS 'kategori'
		FROM barang INNER JOIN kategori_barang 
		ON barang.kategori = kategori_barang.id_kat_barang LIMIT ".$offset.",".$limit;
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result_array();
		} else { return array();}
	}
	//pencarian barang
	public function searchBarang($limit,$offset,$keyword){
		$sql = "SELECT barang.id_barang AS 'id_barang',barang.no_seri AS 'no_seri',barang.nama AS 'nama',
		barang.tanggal AS 'tanggal',barang.harga_jual AS 'harga_jual',barang.harga_beli AS 'harga_beli',
		barang.stok AS 'stok',kategori_barang.des_kat_barang AS 'kategori'
		FROM barang INNER JOIN kategori_barang 
		ON barang.kategori = kategori_barang.id_kat_barang WHERE barang.no_seri = '".$keyword."' OR 
		barang.nama LIKE '%".$keyword."%' LIMIT ".$offset.",".$limit;
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result_array();
		} else { return array();}
	}
	//show barang by kode barang
	public function showBarangByKode($kodebarang){
		$sql = "SELECT barang.id_barang AS 'id_barang',barang.no_seri AS 'no_seri',barang.nama AS 'nama',
		barang.tanggal AS 'tanggal',barang.harga_jual AS 'harga_jual',barang.harga_beli AS 'harga_beli',
		barang.stok AS 'stok',kategori_barang.des_kat_barang AS 'kategori'
		FROM barang INNER JOIN kategori_barang 
		ON barang.kategori = kategori_barang.id_kat_barang
		WHERE barang.no_seri = ?";
		$query = $this->db->query($sql,$kodebarang);
		if($query->num_rows()>0){
			return $query->row_array();
		} else { return array();}
	}
	//show barang show barang by id barang
	public function showBarangById($idbarang){
		$sql = "SELECT barang.id_barang AS 'id_barang',barang.no_seri AS 'no_seri',barang.nama AS 'nama',
		barang.tanggal AS 'tanggal',barang.harga_jual AS 'harga_jual',barang.harga_beli AS 'harga_beli',
		barang.stok AS 'stok',kategori_barang.des_kat_barang AS 'kategori'
		FROM barang INNER JOIN kategori_barang 
		ON barang.kategori = kategori_barang.id_kat_barang
		WHERE barang.id_barang = ?";
		$query = $this->db->query($sql,$idbarang);
		if($query->num_rows()>0){
			return $query->row_array();
		} else { return array();}
	}
	////////////////////// ALL ABOUT PROCESS
	//tambah barang
	public function tambahBarang($params){
		if($this->db->insert('barang',$params)){
			return true;
		} else {
			return false;
		}
	}

}