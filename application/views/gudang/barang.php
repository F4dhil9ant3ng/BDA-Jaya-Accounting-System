<script type="text/javascript">
	function tambahBarang(){
		$('#form-tambahbarang').toggle('fast');
		$('#tambahStok').hide('fast');
		$('#kurangiStok').hide('fast');
	}
	function tambahStok(){
		$('#form-tambahbarang').hide('fast');
		$('#tambahStok').toggle('fast');
		$('#kurangiStok').hide('fast');
	}
	function kurangiStok(){
		$('#form-tambahbarang').hide('fast');
		$('#tambahStok').hide('fast');
		$('#kurangiStok').toggle('fast');
	}
	function closeAll(){
		$('#form-tambahbarang').hide('fast');
		$('#tambahStok').hide('fast');
		$('#kurangiStok').hide('fast');
	}
	function cekSeri(){
		$('#cekSeri').html('cek nomor seri...');
		seri= $('#inputSeriTambah').val();
		if(seri==''){$('#cekSeri').html('<p></p>');}
		else {
		//ajax untuk cek barang
		$.ajax({
			type:'POST',
			url:'<?php echo site_url("gudang/cekseri?seri=")?>'+seri,//mengirim get seri
			timeout: 50000,//50000MS
			success:function(data){ //SUCCESS INSERT TO DB
				$('#cekresult').html(data);
			},
			error:function(data){
				$('#cekresult').html(data);
			}
		});
	}

}
//set harga jual 
	function setHargaJual(){
		hargabeli = parseInt($('#inputHB').val());
		hargajual = (0.10*hargabeli)+hargabeli;
		$('#inputHJ').val(hargajual);
	}
</script>
<div class="container">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="#">Gudang</a></li>
			<li class="active">Barang</li>
		</ol>
	</div>
</div>
<div class="container">
	<?php 
	if($this->session->userdata('gudang_logged_in')){
		$this->load->view('gudang/menu');
	}else if($this->session->userdata('admin_logged_in')){
		$this->load->view('admin/menu');
	}
	?>
	<div class="col-md-10">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Barang</h3>
				</div>
				<div class="panel-body">
					<!-- menu -->
					<div class="col-md-6">
						<!-- <a onclick="tambahStok()" href="#addbarang" class="btn btn-default btn-xs" href="">+ Tambah Stok</a>
						<a onclick="kurangiStok()" href="#addbarang" class="btn btn-default btn-xs" href="">+ Kurangi Stok</a>
						<!-- <a onclick="closeAll()" href="#" class="btn btn-default btn-xs" href="">x</a> -->
						<!-- modal tambah barang -->
						<div class="col-md-12">
							<!-- form untuk tambah barang -->
							<?php if(!empty($this->uri->segment(3))){
									//get detail barang by id
								$id = $this->uri->segment(3);
								$editbarang = $this->m_barang->showBarangById($id);
								// print_r($editbarang);
								?>
								<h4>Edit Barang</h4>
								<form class="form-horizontal" role="form" action="<?php echo site_url('gudang/editbarang/'.$this->uri->segment(3));?>" method="POST">
									<div class="form-group">
										<label for="inputSeri" class="col-lg-2 control-label"><small>No Seri</small></label>
										<div class="col-lg-10">
											<input name="inputSeri" type="number" class="input-sm form-control" id="inputSeri" placeholder="No seri barang" value="<?php echo $editbarang['no_seri']?>">
										</div>
									</div>
									<div class="form-group">
										<label for="inputNama" class="col-lg-2 control-label"><small>Nama</small></label>
										<div class="col-lg-10">
											<input name="inputNama" type="text" class="input-sm form-control" id="inputNama" placeholder="Nama barang" value="<?php echo $editbarang['nama']?>">
										</div>
									</div>
									<div class="form-group">
										<label for="inputKategori" class="col-lg-2 control-label"><small>Kategori</small></label>
										<div class="col-lg-4">
											<select name="inputKategori" id="inputKategori" class="input-sm form-control">
												<?php foreach($kategori as $k):?>
													<option value="<?php echo $k['id_kat_barang'];?>"><?php echo $k['des_kat_barang'];?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="inputStok" class="col-lg-2 control-label"><small>Stok</small></label>
										<div class="col-lg-4">
											<input name="inputStok" value="<?php echo $editbarang['stok'];?>" type="number" id="inputStok" class="input-sm form-control" placeholder="Stok">
										</div>
									</div>
									<div class="form-group">
										<label for="inputHJ" class="col-lg-2 control-label"><small>Harga Beli</small></label>
										<div class="col-lg-4">
										<input onkeyup="setHargaJual()" name="inputHargaBeli" type="number" value="<?php echo $editbarang['harga_beli'];?>" id="inputHB" class="input-sm form-control" placeholder="Harga Beli">
										</div>
									</div>
									<div class="form-group">
										<label for="inputHJ" class="col-lg-2 control-label"><small>Harga Jual</small></label>
										<div class="col-lg-4">
										<input name="inputHargaJual" type="number" value="<?php echo $editbarang['harga_jual'];?>" id="inputHJ" class="input-sm form-control" placeholder="Harga Beli">
										</div>
									</div>
									<button class="btn btn-primary" type="submit">Simpan Perubahan</button>
									<hr/>
								</form>
								<?php }?>
								<div style="display:none" id="form-tambahbarang">
									<br/>
									<h4>Tambah Barang Baru</h4>
									<form method="POST" action="<?php echo site_url('gudang/tambahBarang');?>" class="form-horizontal" role="form">
										<div class="form-group">
											<label for="inputSeri" class="col-lg-2 control-label"><small>No Seri</small></label>
											<div class="col-lg-10">
												<input name="inputSeri" type="number" class="input-sm form-control" id="inputSeri" placeholder="No seri barang">
											</div>
										</div>
										<div class="form-group">
											<label for="inputNama" class="col-lg-2 control-label"><small>Nama</small></label>
											<div class="col-lg-10">
												<input name="inputNama" type="text" class="input-sm form-control" id="inputNama" placeholder="Nama barang">
											</div>
										</div>
										<div class="form-group">
											<label for="inputKategori" class="col-lg-2 control-label"><small>Kategori</small></label>
											<div class="col-lg-4">
												<select name="inputKategori" id="inputKategori" class="input-sm form-control">
													<?php foreach($kategori as $k):?>
														<option value="<?php echo $k['id_kat_barang'];?>"><?php echo $k['des_kat_barang'];?></option>
													<?php endforeach;?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label for="inputKategori" class="col-lg-2 control-label"><small>Pemasok</small></label>
											<div class="col-lg-4">
												<select name="inputPemasok" id="inputKategori" class="input-sm form-control">
													<?php foreach($pemasok as $p):?>
														<option value="<?php echo $p['id_pemasok'];?>"><?php echo $p['nama'];?></option>
													<?php endforeach;?>
												</select>
											</div>
										</div>
										<div style="display:none" class="form-group">
											<label for="inputHJ" class="col-lg-2 control-label"><small>Harga Beli</small></label>
											<div class="col-lg-4">
												<input name="inputHargaBeli" type="number" value="0" id="inputHJ" class="input-sm form-control" placeholder="Harga Beli">
											</div>
										</div>
										<div style="display:none" class="form-group">
											<label for="inputStok" class="col-lg-2 control-label"><small>Stok</small></label>
											<div class="col-lg-4">
												<input name="inputStok" value="0" type="number" id="inputStok" class="input-sm form-control" placeholder="Stok">
											</div>
										</div>
										<div style="display:none" class="form-group">
											<label for="inputStatus" class="col-lg-2 control-label"><small>Status</small></label>
											<div class="col-lg-4">
												<select name="statusTransaksi" id="inputStatus" class="input-sm form-control">														
													<option value="lunas">Lunas</option>		
													<option value="hutang">Hutang</option>													
												</select>
											</div>
										</div>
										<div class="form-group">
											<div class="col-lg-offset-2 col-lg-10">
												<button name="btnTambah" type="submit" class="btn btn-primary">+ Tambah</button>
											</div>
										</div>
										<br/>										
									</form>
								</div>
								<a onclick="tambahBarang()" href="#addbarang" class="btn btn-primary btn-xs">+ Barang Baru</a>
								<br/><br/>
								<!-- end of form untuk tambah barang -->
								<!-- form tambah stok -->
								<div style="display:none" id="tambahStok">
									<br/>
									<h4>Tambah Stok</h4>
									<form method="POST" action="<?php echo site_url('gudang/tambahStok');?>" class="form-horizontal" role="form">
										<div class="form-group">
											<label for="inputSeri" class="col-lg-2 control-label"><small>No Seri</small></label>
											<div class="col-lg-10">
												<input onkeyup="cekSeri()" name="inputSeri" type="number" class="input-sm form-control" id="inputSeriTambah" placeholder="No seri barang" value=""><span id="cekresult"></span>
											</div>
										</div>								
										<div class="form-group">
											<label for="inputKategori" class="col-lg-2 control-label"><small>Pemasok</small></label>
											<div class="col-lg-4">
												<select name="inputPemasok" id="inputKategori" class="input-sm form-control">
													<?php foreach($pemasok as $p):?>
														<option value="<?php echo $p['id_pemasok'];?>"><?php echo $p['nama'];?></option>
													<?php endforeach;?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label for="inputStok" class="col-lg-2 control-label"><small>Stok Ditambah</small></label>
											<div class="col-lg-4">
												<input min="0" name="inputStok" type="number" id="inputStok" class="input-sm form-control" placeholder="Stok">
											</div>
										</div>
										<div class="form-group">
											<label for="inputHJ" class="col-lg-2 control-label"><small>Harga Beli</small></label>
											<div class="col-lg-4">
												<input min="0" name="inputHargaBeli" type="number" id="inputHJ" class="input-sm form-control" placeholder="Harga Beli">
											</div>
										</div>
										<div class="form-group">
											<label for="inputStatus" class="col-lg-2 control-label"><small>Status</small></label>
											<div class="col-lg-4">
												<select name="statusTransaksi" id="inputStatus" class="input-sm form-control">														
													<option value="lunas">Lunas</option>		
													<option value="hutang">Hutang</option>													
												</select>
											</div>
										</div>
										<div class="form-group">
											<div class="col-lg-offset-2 col-lg-10">
												<button name="btnTambah" type="submit" class="btn btn-primary">+ Tambah</button>
											</div>
										</div>
										<br/>
									</form>
								</div>
								<!-- end of form tambah stok -->
								<!-- form kurangi stok -->
								<div style="display:none" id="kurangiStok">
									<br/>
									<h4>Kurangi Stok</h4>
									<form method="POST" action="<?php echo site_url('gudang/tambahBarang');?>" class="form-horizontal" role="form">
										<div class="form-group">
											<label for="inputSeri" class="col-lg-2 control-label"><small>No Seri</small></label>
											<div class="col-lg-10">
												<input name="inputSeri" type="number" class="input-sm form-control" id="inputSeriKurang" placeholder="No seri barang">
											</div>
										</div>								
										<div class="form-group">
											<label for="inputKategori" class="col-lg-2 control-label"><small>Pemasok</small></label>
											<div class="col-lg-4">
												<select name="inputPemasok" id="inputKategori" class="input-sm form-control">
													<?php foreach($pemasok as $p):?>
														<option value="<?php echo $p['id_pemasok'];?>"><?php echo $p['nama'];?></option>
													<?php endforeach;?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label for="inputStok" class="col-lg-2 control-label"><small>Stok Ditambah</small></label>
											<div class="col-lg-4">
												<input name="inputStok" type="number" id="inputStok" class="input-sm form-control" placeholder="Stok">
											</div>
										</div>
										<div class="form-group">
											<label for="inputHJ" class="col-lg-2 control-label"><small>Harga Beli</small></label>
											<div class="col-lg-4">
												<input name="inputHargaBeli" type="number" id="inputHJ" class="input-sm form-control" placeholder="Harga Beli">
											</div>
										</div>
										<div class="form-group">
											<label for="inputStatus" class="col-lg-2 control-label"><small>Status</small></label>
											<div class="col-lg-4">
												<select name="statusTransaksi" id="inputStatus" class="input-sm form-control">														
													<option value="lunas">Lunas</option>		
													<option value="hutang">Hutang</option>													
												</select>
											</div>
										</div>
										<div class="form-group">
											<div class="col-lg-offset-2 col-lg-10">
												<button name="btnTambah" type="submit" class="btn btn-primary">+ Tambah</button>
											</div>
										</div>
										<br/>
									</form>
								</div>
								<!-- end of form kurangi stok -->
							</div>
						</div>
						<!-- search -->
						<div class="col-md-6">
							<form method="get">
								<div class="input-group">
									<input name="q" type="text" class="form-control" placeholder="pencarian barang">
									<span class="input-group-btn">
										<button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
									</span>
								</div><!-- /input-group -->
							</form>
							<br/>
						</div>
						<br/>
						<br/>
						<table style="font-size:11px" class="table table-striped">
							<tr>
								<th>#</th>
								<th>Code</th>
								<th>Nama</th>
								<th>Kategori</th>
								<th>Harga Beli</th>
								<th>Harga Jual</th>
								<th>Stok</th>
								<!-- <th>Update terakhir</th> -->
								<th style="width:100px"></th>
							</tr>
							<?php foreach($barang AS $b):?>
								<tr>
									<td><?php echo $b['id_barang']?></td>
									<td><?php echo $b['no_seri']?></td>
									<td><?php echo $b['nama']?></td>
									<td><?php echo $b['kategori']?></td>
									<td>Rp<?php echo $b['harga_beli']?>,-</td>
									<td>Rp<?php echo $b['harga_jual']?>,-</td>
									<td><?php echo $b['stok']?></td>
									<!-- <td>herman 12/08/2014 18:00</td> -->
									<td>
										<div class="btn-group">
											<a href="<?php echo site_url('gudang/barang/'.$b['id_barang'])?>" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-pencil"></span></a>
											<a onclick="return confirm('Anda yakin!')" href="<?php echo site_url('gudang/hapusBarang?id='.$b['id_barang'])?>" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-trash"></span></a>
										</div>
									</td>
								</tr>
							<?php endforeach;?>
						</table>
					</div>
				</div>

			</div>
		</div>
	</div>
