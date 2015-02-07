<script type="text/javascript">
	$(function(){
		$('#selectbln').val('<?php echo $bulan;?>');
		$('#selectthn').val('<?php echo $tahun;?>');
	});	
	function addPengeluaran(){
		$('#addpengeluaran').toggle('fast');
	}
	function addPemasukan(){
		$('#addpemasukan').toggle('fast');
	}
</script>
<?php
//sortir berdasarkan tanggal
function sort_array_by_value($key, &$array) {
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
?>
<div class="container">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="#">Admin</a></li>
			<li class="active">Penjurnalan</li>
		</ol>
	</div>
</div>
<div class="container">
	<?php $this->load->view('admin/menu')?>
	<div class="col-md-10">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Jurnal</h3>
				</div>
				<div class="panel-body">
					<!-- menu -->
					<div class="col-md-6">
						<!-- <strong>Buku Kas per : <?php echo $bulan.'/'.$tahun;?></strong> -->
						<a title="menampilkan buku kas untuk bulan ini" href="<?php echo site_url('dashboard/jurnal')?>" class="btn btn-primary btn-sm">Hari Ini</a>
					</div>
					<div class="col-md-6">
						<span><form style="float:right" class="form-inline" action="<?php echo site_url('dashboard/jurnal?act=sort')?>" method="get">

							<div class="form-group">
								<label class="sr-only" for="exampleInputEmail2">Email address</label>
								<select id="selectbln" name="bln" class="input-sm form-control">
									<option value="00">Semua Bulan</option>
									<option value="01">Januari</option>
									<option value="02">Februari</option>
									<option value="03">Maret</option>
									<option value="04">April</option>
									<option value="05">Mei</option>
									<option value="06">Juni</option>
									<option value="07">Juli</option>
									<option value="08">Agustus</option>
									<option value="09">September</option>
									<option value="10">Oktober</option>
									<option value="11">November</option>
									<option value="12">Desember</option>
								</select>
							</div>
							<div class="form-group">
								<label class="sr-only" for="exampleInputPassword2">Password</label>
								<select id="selectthn" name="thn" class="input-sm form-control">
									<option value="2014">2014</option>
									<option value="2015">2015</option>
									<option value="2016">2016</option>
									<option value="2017">2017</option>
									<option value="2018">2018</option>
								</select>
							</div>
							<button type="submit" class="btn btn-sm btn-default">show</button>
						</form></span>
					</div>
					<br/>
					<br/>
					<div class="row">
						<div class="col-md-12">
							<table class="table table-striped">
								<tr>
									<th>Tgl</th>
									<th>Keterangan</th>
									<th>Ref</th>
									<th>Debit</th>
									<th>Kredit</th>
								</tr>
								<?php
								//cek masuk dan keluar bulan lalu;
								$totaldebitlalu=0;$totalkreditlalu=0;
								$jurnalmergelalu = array_merge($pemasukan_bln_lalu,$pengeluaran_bln_lalu);
								sort_array_by_value('tanggal',$jurnalmergelalu);
								//end of cek masuk dan keluar bulan lalu;
								//loping untuk perhitungan modal
								$saldo = 0;$debitkas=0;$kreditkas=0;
								foreach($jurnalmergelalu as $jurnal):
									//jurnal masuk, piutang
									if($jurnal['det']=='masuk'):
										if($jurnal['status']=='piutang') {
											//cek yang sudah terbayarkan
											$this->db->where('id_transaksi',$jurnal['id_transaksi']);//cek transaksi sesuai dengan id trannsksi
											$transaksi = $this->db->get('transaksi');
											$transaksi = $transaksi->row_array();
											$totalterbayarkan = $transaksi['bayar'];
											// echo number_format($totalterbayarkan);//lihat total piutang yang telah terbayarkan
											$saldo = $saldo + $totalterbayarkan;
											$debitkas = $debitkas+$totalterbayarkan;
										} else {
											// echo number_format($jurnal['rp']);$saldo=$saldo+$jurnal['rp'];
											$debitkas = $debitkas + $jurnal['rp'];
										}
										endif;
									//jurnal keluar : hutang
										if($jurnal['det']=='keluar'):
											if($jurnal['status']=='hutang'){
											$this->db->where('id_pasokan',$jurnal['id_pasokan']);//cek id pasokan
											$pasokan = $this->db->get('pasokan');
											$pasokan = $pasokan->row_array();
											$totalterbayarkan = $pasokan['rp_bayar'];
											// echo number_format($totalterbayarkan);
											$saldo = $saldo - $totalterbayarkan;
											$kreditkas = $kreditkas + $totalterbayarkan;
										} else {
											// echo number_format($jurnal['rp']);$saldo=$saldo-$jurnal['rp'];
											$kreditkas = $kreditkas + $jurnal['rp'];
										}										
										endif;
										$totalkas = $total_kas = $saldo;
									// echo number_format($total_kas);
										$neraca_kas = $total_kas;
										endforeach;
									//perhitungan gaji pegawai
										$akhir31 = array(1,3,5,7,8,10,12);
									//perhitungan gaji pegawai
										if($bulan == 2 && $tahun%4 == 0) {
											$tgl = 29;
										} else if($bulan == 2 && $tahun%4 != 0) {
											$tgl = 28;
										} else if(in_array($bulan, $akhir31)) {
											$tgl = 31;
										} else {
											$tgl = 30;
										} 
										//count total gaji
										if($total_gaji != 0){//sudah gajian
										$totalgaji = $total_gaji * 30000;
										}else{
											$totalgaji = 0;
										}
										$kreditkas = $kreditkas + $total_gaji;
										$total_kas = $total_gaji;
										$neraca_kas = $saldo-$totalgaji;
										//cek lebih besar kredit atau debit
										if($kreditkas > $debitkas){
											$kas = $kreditkas - $debitkas;
											$neraca=array('tipe'=>'kas','value'=>$neraca_kas,'pos'=>'kredit');
											$data['neraca'][] = $neraca;
								//echo 'kredit'.$kreditkas.' | ';
										} else {
											$kas = $debitkas - $kreditkas;
											$neraca=array('tipe'=>'kas','value'=>$neraca_kas,'pos'=>'debit');
											$data['neraca'][] = $neraca;
								//echo 'debit'.$debitkas;
										}
										//perulangan semua kategori masuk
										foreach($all_kategori_masuk as $katmasuk):
											if($katmasuk['id_kat_masuk']  == '2') {
												$params = array($katmasuk['id_kat_masuk'],$bulan,$tahun);
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
												//menghitung pendapatan
												$totalpendapatan = 0;
												foreach($pendapatan as $dapat):
													$totalpendapatan = $totalpendapatan+$dapat['rp'];
												endforeach;
												$neraca = array('tipe'=>'pendapatan','value'=>$totalpendapatan,'pos'=>'kredit');
								array_push($data['neraca'], $neraca);
												//mendapatkan piutang
												$totalpiutang = 0;
												foreach($piutang as $piut):
												//cek yang sudah dibayarkan
												$this->db->where('id_transaksi',$piut['id_transaksi']);//cek transaksi sesuai dengan id trannsksi
												$transaksi = $this->db->get('transaksi');
												$transaksi = $transaksi->row_array();
												$piutang = $transaksi['total_bayar'] - $transaksi['bayar'];
												$totalpiutang = $totalpiutang+$piutang;
												endforeach;
												$neraca = array('tipe'=>'piutang','value'=>$totalpiutang,'pos'=>'debit');
												array_push($data['neraca'], $neraca);
											}else{
												//kategori penjualan barang
												$params = array($katmasuk['id_kat_masuk'],$bulanlalu,$tahunlalu);
												$akun = $this->m_pemasukan->show_masuk_bukubesar($params);								
												$total = 0;
												foreach($akun as $a):
													$total = $total + $a['rp'];
												endforeach;
												$this->db->where('id_kat_masuk',$a['kategori']);
												$query = $this->db->get('kategori_pemasukan');//select ke table pemasukan
												$query = $query->row_array();
												$neraca=array('tipe'=>$query['det_kat_masuk'],'value'=>$total,'pos'=>'kredit');
												array_push($data['neraca'], $neraca);
											}
											endforeach;
											?>
											<?php
											//semua kategori keluar
											foreach($all_kategori_keluar as $katkeluar):
												
											endforeach;
											?>
											<?php 
								/////////////////////////////////////////////
								//cek masuk dan keluar bulan sekarang
											$totaldebit=0;$totalkredit=0;?>
											<?php $jurnalmerge = array_merge($pemasukan_bln_ini,$pengeluaran_bln_ini);
											sort_array_by_value('tanggal',$jurnalmerge);
											?>
											<?php foreach($jurnalmerge as $jurnal):?>								
												<tr>
													<td><?php echo date('d',strtotime($jurnal['tanggal']));?></td>
													<td>
														<?php if($jurnal['det']=='masuk') {
											if($jurnal['status']=='piutang') { //jika masih piutang
												echo 'Kas<br/>';
												echo '<span style="padding-left:2em">Piutang '.$jurnal['keterangan'].'</span><br/>';
												echo '<span style="padding-left:2em">Pendapatan</span><br/>';
											} else {
												//jika sudah lunas
												echo 'Kas<br/><span style="padding-left:2em"> '.$jurnal['keterangan'].'</span>';
											}											
										} else if($jurnal['det']=='keluar'){ //keluar
											if($jurnal['status']=='hutang'){//jika masih hutang
												echo 'Pasokan : '.$jurnal['keterangan'].'<br/>';
												echo '<span style="padding-left:2em">Kas<span/><br/>';
												echo '<span style="padding-left:2em">Hutang</span>';												
											} else { //hutang sudah lunas
												echo $jurnal['keterangan'].'<br/><span style="padding-left:2em">Kas</span>';
											}
										}?>
									</td>
									<td>
										
									</td>
									<td>
										<?php
											if($jurnal['det']=='masuk') { //masuk
												if($jurnal['status']=='piutang'){ //jika masih piutang
													//cek detail transaksi
													$this->db->where('id_transaksi',$jurnal['id_transaksi']);
													$transaksi = $this->db->get('transaksi');
													$transaksi = $transaksi->row_array();
													echo ''.number_format($transaksi['bayar']).'<br/>';
													echo ''.number_format($jurnal['rp']-$transaksi['bayar']).'<br/>';
												} else { //sudah lunas
													echo ''.number_format($jurnal['rp']).'';
												}
											} else if($jurnal['det']=='keluar'){ //keluar
												if($jurnal['status']=='hutang'){//jika masih hutang													
													echo ''.number_format($jurnal['rp']).'';
												} else { //hutang sudah lunas
													echo ''.number_format($jurnal['rp']).'';
												}
											} else {
												echo ''.number_format($jurnal['rp']).'';
											}																					
											$totaldebit = $totaldebit + $jurnal['rp']; ?>
										</td>
										<td>
											<?php
											if($jurnal['det']=='masuk') {
												if($jurnal['status']=='piutang'){ //jika masih piutang
													echo '<br/><br/>';
													echo ''.number_format($jurnal['rp']).'';	
												} else { //sudah lunas
													echo '<br/>'.number_format($jurnal['rp']).'';
												}
											} else if($jurnal['det']=='keluar'){ //keluar
												if($jurnal['status']=='hutang'){//jika masih hutang
													$this->db->where('id_pasokan',$jurnal['id_pasokan']);
													$pasokan = $this->db->get('pasokan');
													$pasokan = $pasokan->row_array();
													echo '<br/>'.number_format($pasokan['rp_bayar']).'';
													echo '<br/>'.number_format($jurnal['rp'] - $pasokan['rp_bayar']).'';
												} else { //hutang sudah lunas
													echo '<br/>'.number_format($jurnal['rp']).'';		
												}
											} else {
												echo '<br/>'.number_format($jurnal['rp']).'';		
											}															
											$totalkredit = $totalkredit + $jurnal['rp'];?>
										</td>
									</tr>																							
								<?php endforeach;?>
								<?php
								$akhir31 = array(1,3,5,7,8,10,12);
							//perhitungan gaji pegawai
								if($bulan == 2 && $tahun%4 == 0) {
									$tgl = 29;
								} else if($bulan == 2 && $tahun%4 != 0) {
									$tgl = 28;
								} else if(in_array($bulan, $akhir31)) {
									$tgl = 31;
								} else {
									$tgl = 30;
								} 
								?>
								<?php if($total_gaji != 0){
									$totalgaji = $total_gaji * 30000;?>
									<tr>
										<td><?php echo $tgl?></td>
										<td>Pemberian gaji karyawan<br/><span style="padding-left:2em">Kas</span></td>
										<td></td>
										<td><?php echo ''.number_format($total_gaji * 30000).'';?></td>
										<td><?php echo '<br/>'.number_format($total_gaji * 30000).'';?></td>
									</tr>
									<?php }else{$totalgaji = 0;} ?>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td><strong><?php echo number_format($totaldebit + $totalgaji).'';?> </strong></td>
										<td><strong><?php echo number_format($totalkredit + $totalgaji).'';?> </strong></td>
									</tr>
								</table>
							</div>
						</div>
						<div class="col-md-12">
							<br/><br/>
							<hr/>
						</div>				
					</div>
				</div>

			</div>
		</div>
	</div>
