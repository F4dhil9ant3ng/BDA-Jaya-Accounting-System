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
</script>
<div class="container">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="#">Gudang</a></li>
			<li class="active">Kategori</li>
		</ol>
	</div>
</div>
<div class="container">
	<?php $this->load->view('admin/menu')?>
	<div class="col-md-10">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Barang</h3>
				</div>
				<div class="panel-body">
					<!-- menu -->
					<div class="col-md-6">
						<a onclick="tambahBarang()" href="#addbarang" class="btn btn-primary btn-xs">+ Pemasok Baru</a>
						
						<!-- modal tambah barang -->
						<div class="col-md-12">
							<!-- form untuk tambah barang -->
							<div style="display:none" id="form-tambahbarang">
								<br/>
								<h4>Tambah Pemasok Baru</h4>
								<form method="POST" action="<?php echo site_url('gudang/tambahPemasok');?>" class="form-horizontal" role="form">
									<div class="form-group">
										<label for="inputKategori" class="col-lg-2 control-label"><small>Pemasok</small></label>
										<div class="col-lg-10">
											<input name="inputPemasok" type="text" class="input-sm form-control" id="inputKategori" placeholder="Nama Kategori Barang">
										</div>
									</div>
									<div class="form-group">
										<label for="inputKategori" class="col-lg-2 control-label"><small>Alamat</small></label>
										<div class="col-lg-10">
											<textarea name="inputAlamat" type="text" class="input-sm form-control" id="inputKategori" placeholder="Alamat dan kontak pemasok"></textarea>
										</div>
									</div>
									<div class="form-group">
										<label for="inputKategori" class="col-lg-2 control-label"><small>Kontak</small></label>
										<div class="col-lg-10">
											<textarea name="inputKontak" type="text" class="input-sm form-control" id="inputKategori" placeholder="Alamat dan kontak pemasok"></textarea>
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
							<!-- end of form untuk tambah barang -->							
					</div>
				</div>
				<!-- search -->
				<div class="col-md-6">
					
				</div>
				<br/>
				<br/>
				<table style="font-size:11px" class="table table-striped">
					<tr>
						<th>#</th>
						<th>Nama</th>
						<th>Alamat dan Kontak</th>
						<!-- <th>Update terakhir</th> -->
						<th style="width:100px"></th>
					</tr>
					<?php foreach($pemasok AS $p):?>
					<tr>
						<td><?php echo $p['id_pemasok']?></td>
						<td><?php echo $p['nama']?></td>					
						<td><?php echo $p['alamat']?></td>
						<td>
							<div class="btn-group">
								<a href="<?php echo site_url('gudang/editdata?act=pemasok&id='.$p['id_pemasok'])?>" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-pencil"></span></a>
								<a onclick="return confirm('Anda yakin!')" href="<?php echo site_url('gudang/hapusPemasok?id='.$p['id_pemasok'])?>" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-trash"></span></a>
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
