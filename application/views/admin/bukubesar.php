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
					<h3 class="panel-title">Buku Besar</h3>
				</div>
				<div class="panel-body">
					<!-- menu -->
					<div class="col-md-6">
						<!-- <h4><strong>Buku Kas per : <?php echo $bulan.'/'.$tahun;?></strong></h4> -->
						<a title="menampilkan buku kas untuk bulan ini" href="<?php echo site_url('dashboard/buku_besar')?>" class="btn btn-primary btn-sm">Hari Ini</a>
					</div>
					<div class="col-md-6">
						<span><form style="float:right" class="form-inline" action="<?php echo site_url('dashboard/buku_besar?act=sort')?>" method="get">

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
							<?php $jurnalmerge = array_merge($pemasukan_bln_ini,$pengeluaran_bln_ini);
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
							sort_array_by_value('tanggal',$jurnalmerge);
							?>
							<h4><strong>Akun : Kas</strong></h4><br/>
							<table class="table table-striped">
							<tr>
								<th>Tanggal</th>
								<th>Keterangan</th>
								<th>Ref</th>
								<th>Debet (Rp)</th>
								<th>Kredit (Rp)</th>
								<th>D/K</th>
								<th>Saldo (Rp)</th>
							</tr>
							<?php $saldo = 0;$debitkas=0;$kreditkas=0;
							foreach($jurnalmerge as $jurnal):?>
								<tr>
									<td><?php echo date('d',strtotime($jurnal['tanggal']))?></td>
									<td><?php echo $jurnal['keterangan']?></td>
									<td></td>
									<td>
									<?php if($jurnal['det']=='masuk'){
										if($jurnal['status']=='piutang') {
											//cek yang sudah terbayarkan
											$this->db->where('id_transaksi',$jurnal['id_transaksi']);//cek transaksi sesuai dengan id trannsksi
											$transaksi = $this->db->get('transaksi');
											$transaksi = $transaksi->row_array();
											$totalterbayarkan = $transaksi['bayar'];
											echo number_format($totalterbayarkan);//lihat total piutang yang telah terbayarkan
											$saldo = $saldo + $totalterbayarkan;
											$debitkas = $debitkas+$totalterbayarkan;
										} else {
											echo number_format($jurnal['rp']);$saldo=$saldo+$jurnal['rp'];
											$debitkas = $debitkas + $jurnal['rp'];
										}
									}?>
									</td>
									<td>
									<?php if($jurnal['det']=='keluar'){
										if($jurnal['status']=='hutang'){
											$this->db->where('id_pasokan',$jurnal['id_pasokan']);//cek id pasokan
											$pasokan = $this->db->get('pasokan');
											$pasokan = $pasokan->row_array();
											$totalterbayarkan = $pasokan['rp_bayar'];
											echo number_format($totalterbayarkan);
											$saldo = $saldo - $totalterbayarkan;
											$kreditkas = $kreditkas + $totalterbayarkan;
										} else {
											echo number_format($jurnal['rp']);$saldo=$saldo-$jurnal['rp'];
											$kreditkas = $kreditkas + $jurnal['rp'];
										}										
									}?>
									</td>
									<td></td>
									<td><?php $total_kas = $saldo;echo number_format($total_kas);$neraca_kas = $total_kas;
									?></td>
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
								<td><?php echo 'Pemberian gaji';?></td>
								<td></td>
								<td></td>
								<td><?php echo number_format($totalgaji);?></td>
								<td></td>
								<td><?php $kreditkas = $kreditkas + $total_gaji;$total_kas = $total_gaji;$neraca_kas = $saldo-$totalgaji;echo number_format($neraca_kas);?></td>
							</tr>
							<?php }?>
							<?php
							//untuk data neraca 
							//cek lebih besar debit atau kredit
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
							?>
							</table>
							<hr/>
							<!-- perulangan untuk semua kategori keluar dan masuk -->
							<!-- semua kategori masuk -->
							<?php foreach($all_kategori_masuk as $katmasuk):?>
								<?php if($katmasuk['id_kat_masuk']  == '2') {
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
							?>
								<!-- menampilkan pendapatan -->
								<h4><strong>Pendapatan</strong></h4><br/>
								<table class="table table-striped">
								<tr>
									<th>Tanggal</th>
									<th>Keterangan</th>
									<th>Ref</th>
									<th>Debet (Rp)</th>
									<th>Kredit (Rp)</th>
									<th>D/K</th>
									<th>Saldo (Rp)</th>
								</tr>
								<?php $totalpendapatan = 0;foreach($pendapatan as $dapat):?>
									<tr>
										<td><?php echo date('d',strtotime($dapat['tanggal']))?></td>
										<td><?php echo $dapat['keterangan'];?></td>
										<td></td>
										<td></td>
										<td><?php echo number_format($dapat['rp']);$totalpendapatan = $totalpendapatan + $dapat['rp'];?></td>
										<td></td>
										<td><?php echo number_format($totalpendapatan)?></td>
									</tr>
								<?php endforeach;
								$neraca = array('tipe'=>'pendapatan','value'=>$totalpendapatan,'pos'=>'kredit');
								array_push($data['neraca'], $neraca);
								?>
								</table>
								<!-- menampilkan piutang -->
								<h4><strong>Piutang</strong></h4><br/>
								<table class="table table-striped">
								<tr>
									<th>Tanggal</th>
									<th>Keterangan</th>
									<th>Ref</th>
									<th>Debet (Rp)</th>
									<th>Kredit (Rp)</th>
									<th>D/K</th>
									<th>Saldo (Rp)</th>
								</tr>
								<?php 
								$totalpiutang = 0;
								//print_r($piutang);
								foreach($piutang as $piut):
								//cek yang sudah dibayarkan
								$this->db->where('id_transaksi',$piut['id_transaksi']);//cek transaksi sesuai dengan id trannsksi
								$transaksi = $this->db->get('transaksi');
								$transaksi = $transaksi->row_array();
								$piutang = $transaksi['total_bayar'] - $transaksi['bayar'];
								?>
									<tr>
										<td><?php echo date('d',strtotime($piut['tanggal']))?></td>
										<td><?php echo $piut['keterangan'];?></td>
										<td></td>
										<td><?php echo number_format($piutang);?></td>
										<td></td>
										<td></td>
										<td><?php echo number_format($totalpiutang + $piutang);?></td>
									</tr>
								<?php endforeach;
								$neraca = array('tipe'=>'piutang','value'=>$totalpiutang,'pos'=>'debit');
								array_push($data['neraca'], $neraca);
								?>
								</table>
							<?php									
								} /*end of jika kategori = penjualan barang*/ else {
							?>									
								<h4><strong><?php echo $katmasuk['det_kat_masuk']?></strong></h4><br/>
								<table class="table table-striped">
								<tr>
									<th>Tanggal</th>
									<th>Keterangan</th>
									<th>Ref</th>
									<th>Debet (Rp)</th>
									<th>Kredit (Rp)</th>
									<th>D/K</th>
									<th>Saldo (Rp)</th>
								</tr>
								<?php 
								$params = array($katmasuk['id_kat_masuk'],$bulan,$tahun);
								$akun = $this->m_pemasukan->show_masuk_bukubesar($params);								
								$total = 0;
								foreach($akun as $a):
								?>
								<tr>
									<td><?php echo date('d',strtotime($a['tanggal']))?></td>
									<td><?php echo $a['keterangan']?></td>
									<td></td>
									<td></td>
									<td><?php echo number_format($a['rp']);$total = $total + $a['rp'];?></td>
									<td></td>
									<td><?php echo number_format($total);?></td>
								</tr>
								<?php
								endforeach;
								$this->db->where('id_kat_masuk',$a['kategori']);
								$query = $this->db->get('kategori_pemasukan');//select ke table pemasukan
								$query = $query->row_array();
								$neraca=array('tipe'=>$query['det_kat_masuk'],'value'=>$total,'pos'=>'kredit');
								array_push($data['neraca'], $neraca);
								?>
								</table>
								<hr/>	
							<?php } ?>
							<?php endforeach;

							?>

							<!-- semua kategori keluar -->
							<?php foreach($all_kategori_keluar as $katkeluar):
							//jika kategori keluar adalah pembelian barang
							if($katkeluar['id_kat_pengeluaran'] == 6){
								$params = array($katkeluar['id_kat_pengeluaran'],$bulan,$tahun);
								$akun = $this->m_pengeluaran->show_keluar_bukubesar($params);
								$pembelian = array();
								$hutang = array();
								foreach($akun as $a):
									if($a['status']=='lunas') { //menyimpan semua yang lunas
										$pembelian[] = array_merge($pembelian,$a);
									} else if ($a['status']=='hutang') { //menyimpan semua yang masih hutang
										$pembelian[] = array_merge($pembelian,$a);
										$hutang[] = array_merge($hutang,$a);
									}
								endforeach;
							?>
								<!-- menampilkan pembelian -->
								<h4><strong>Pembelian</strong></h4><br/>
								<table class="table table-striped">
								<tr>
									<th>Tanggal</th>
									<th>Keterangan</th>
									<th>Ref</th>
									<th>Debet (Rp)</th>
									<th>Kredit (Rp)</th>
									<th>D/K</th>
									<th>Saldo (Rp)</th>
								</tr>
								<?php $totalpembelian = 0;foreach($pembelian as $beli):?>
								<tr>
									<td><?php echo $beli['tanggal'];?></td>
									<td><?php echo $beli['keterangan'];?></td>
									<td></td>
									<td><?php echo number_format($beli['rp']);$totalpembelian = $totalpembelian+$beli['rp'];?></td>
									<td></td>
									<td></td>
									<td><?php echo number_format($totalpembelian);
									?></td>
								</tr>
								<?php endforeach;
								$neraca=array('tipe'=>'pembelian','value'=>$totalpembelian,'pos'=>'debit');
								array_push($data['neraca'], $neraca);
								?>
								</table>
								<hr/>
								<!-- menampilkan hutang -->
								<h4><strong>Hutang Usaha</strong></h4><br/>
								<table class="table table-striped">
								<tr>
									<th>Tanggal</th>
									<th>Keterangan</th>
									<th>Ref</th>
									<th>Debet (Rp)</th>
									<th>Kredit (Rp)</th>
									<th>D/K</th>
									<th>Saldo (Rp)</th>
								</tr>
								<?php $totalhutang=0; foreach($hutang as $hut):?>
								<tr>
									<td><?php echo $hut['tanggal'];?></td>
									<td><?php echo 'Hutang : '.$hut['keterangan']?></td>
									<td></td>
									<td></td>
									<td>
										<?php //cek detail pasokan
										$this->db->where('id_pasokan',$hut['id_pasokan']);
										$pasokan = $this->db->get('pasokan');
										$pasokan = $pasokan->row_array();
										$hutang = $pasokan['rp'] - $pasokan['rp_bayar'];
										echo number_format($hutang);
										$totalhutang = $totalhutang+$hutang;
										?>
									</td>
									<td></td>
									<td><?php echo number_format($totalhutang);
									?></td>
								</tr>
								<?php endforeach;
								$neraca=array('tipe'=>'hutang','value'=>$totalhutang,'pos'=>'kredit');
								array_push($data['neraca'], $neraca);
								?>
								</table>
								<hr/>
							<?php
							} else {  //end of if kategori pengeluaran = pembelian barang?>
								<h4><strong><?php echo $katkeluar['det_kat_pengeluaran']?></strong></h4><br/>
								<table class="table table-striped">
								<tr>
									<th>Tanggal</th>
									<th>Keterangan</th>
									<th>Ref</th>
									<th>Debet (Rp)</th>
									<th>Kredit (Rp)</th>
									<th>D/K</th>
									<th>Saldo (Rp)</th>
								</tr>
								<?php
								$params = array($katkeluar['id_kat_pengeluaran'],$bulan,$tahun);
								$akun = $this->m_pengeluaran->show_keluar_bukubesar($params);
								foreach($akun as $a):	
								$totalkeluar[$a['kategori']] = 0;								
								?>
								<tr>
									<td><?php echo date('d',strtotime($a['tanggal']))?></td>
									<td><?php echo $a['keterangan'];?></td>
									<td></td>
									<td><?php echo number_format($a['rp']);$totalkeluar[$a['kategori']] = $totalkeluar[$a['kategori']] + $a['rp'];?></td>
									<td></td>
									<td></td>
									<td><?php echo number_format($totalkeluar[$a['kategori']])?></td>
								</tr>
								<?php	
								$this->db->where('id_kat_pengeluaran',$a['kategori']);
								$query = $this->db->get('kategori_pengeluaran');
								$query = $query->row_array();
								$neraca=array('tipe'=>$query['det_kat_pengeluaran'],'value'=>$totalkeluar[$a['kategori']],'pos'=>'debit');
								array_push($data['neraca'], $neraca);
								endforeach;								
								?>
								</table>
								<hr/>	
							<?php
							} //end of else kategori pengeluaran
							?>								
							<?php endforeach; ?>

							<!-- gaji -->
							<?php if($total_gaji != 0){
								$totalgaji = $total_gaji * 30000;?>
							<h4><strong>Beban Gaji</strong></h4><br/>
								<table class="table table-striped">
								<tr>
									<th>Tanggal</th>
									<th>Keterangan</th>
									<th>Ref</th>
									<th>Debet (Rp)</th>
									<th>Kredit (Rp)</th>
									<th>D/K</th>
									<th>Saldo (Rp)</th>
								</tr>
								<tr>
									<td>
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
										echo $tgl;
										?>
									</td>
									<td>beban gaji</td>
									<td></td>
									<td><?php echo number_format($total_gaji * 30000);?></td>
									<td></td>
									<td></td>
									<td><?php echo number_format($total_gaji * 30000);?></td>
								</tr>
								</table>
								<?php 
								$neraca=array('tipe'=>'Beban Gaji','value'=>$total_gaji*30000,'pos'=>'debit');
								array_push($data['neraca'], $neraca);
								} //end of total gaji?>

							</div>
						</div>
						<!-- <pre>
						<?php print_r($data);?>
						</pre> -->
						<?php $this->session->set_userdata($data);?>
						<div class="col-md-12">
							<center><a href="<?php echo site_url('dashboard/neraca?bln='.$bulan.'&thn='.$tahun)?>" class="btn btn-primary btn-lg">Neraca Saldo</a></center>
							<br/><br/>
							<hr/>
						</div>				
					</div>
				</div>

			</div>
		</div>
	</div>
