<div class="container">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="#">Admin</a></li>
			<li class="active">Karyawan</li>
		</ol>
	</div>
</div>
<div class="container">
	<?php $this->load->view('admin/menu')?>
	<div class="col-md-10">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Karyawan</h3>
				</div>
				<div class="panel-body">
					<?php if(!empty($_GET['act']) && $_GET['act']=='editkaryawan'){?>
					<h4>Edit Data Karyawan</h4>
					<form role="form" action="<?php echo site_url('dashboard/karyawan?act=proceditkaryawan')?>" method="POST">
						<input type="hidden" name="addid" value="<?php echo $karyawan['id_pegawai']?>">
						<div class="form-group">
							<label for="inputNama" class="col-lg-2 control-label">Nama</label>
							<div class="col-lg-10">
								<input name="addnama" value="<?php echo $karyawan['nama']?>" type="text" class="form-control" id="inputNama">
								<br/>
							</div>
						</div>
						<div class="form-group">
							<label for="inputBagian" class="col-lg-2 control-label">Bagian</label>
							<div class="col-lg-10">
								<select name="addbagian" class="form-control" id="inputBagian">
									<option value="<?php echo $karyawan['bagian']?>"><?php echo $karyawan['bagian']?></option>
									<option value="<?php if($karyawan['bagian']=='gudang'){$bagian = 'kasir';} else {$bagian = 'gudang';} echo $bagian;?>"><?php echo $bagian;?></option>
								</select>
								<br/>
							</div>
						</div>
						<div class="form-group">
							<label for="inputTelp" class="col-lg-2 control-label">Telp</label>
							<div class="col-lg-10">
								<input name="addtelepon" value="<?php echo $karyawan['telp']?>" type="phone" class="form-control" id="inputTelp">
								<br/>
							</div>
						</div>
						<div class="form-group">
							<label for="inputAlamat" class="col-lg-2 control-label">Alamat</label>
							<div class="col-lg-10">
								<textarea name="addalamat" class="form-control" id="inputAlamat"><?php echo $karyawan['alamat']?></textarea>
								<br/>
							</div>
						</div>
						<div class="form-group">
							<label for="inputUsername" class="col-lg-2 control-label">Username</label>
							<div class="col-lg-10">
								<input name="addusername" value="<?php echo $karyawan['username']?>" type="text" class="form-control" id="inputUsername">
								<br/>
							</div>							
						</div>
						<br/>
						<div class="form-group">
							<label for="inputUsername" class="col-lg-2 control-label">Ubah Password</label>
							<div class="col-lg-10">
								<p>Kosongkan jika tidak ingin ubah password</p>
								<input name="addpassword" min="5" type="password" class="form-control" id="inputPassword">
								<input name="recentpassword" min="5" type="hidden" value="<?php echo $karyawan['password']?>">
								<br/>
								<button class="btn btn-primary" type="submit">Update Data</button>
							</div>							
						</div>

					</form>
					<hr/><br/><br/>
					<?php } ?>				
					<a href="#tambahkaryawan" data-toggle="modal" class="btn btn-primary">+ Tambah Karyawan</a>
					<!-- modal tambah karyawan -->
					<!-- Modal -->
					<div class="modal fade" id="tambahkaryawan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title">Tambah Karyawan</h4>
								</div>
								<div class="modal-body">
									<form role="form" action="<?php echo site_url('dashboard/karyawan?act=addkaryawan')?>" method="POST">
										<div class="form-group">
											<label for="inputNama" class="col-lg-2 control-label">Nama</label>
											<div class="col-lg-10">
												<input name="addnama" type="text" class="form-control" id="inputNama">
											</div>
										</div>
										<div class="form-group">
											<label for="inputBagian" class="col-lg-2 control-label">Bagian</label>
											<div class="col-lg-10">
												<select name="addbagian" class="form-control" id="inputBagian">
													<option value="gudang">Gudang</option>
													<option value="kasir">Kasir</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label for="inputTelp" class="col-lg-2 control-label">Telp</label>
											<div class="col-lg-10">
												<input name="addtelepon" type="phone" class="form-control" id="inputTelp">
											</div>
										</div>
										<div class="form-group">
											<label for="inputAlamat" class="col-lg-2 control-label">Alamat</label>
											<div class="col-lg-10">
												<textarea name="addalamat" class="form-control" id="inputAlamat"></textarea>
											</div>
										</div>
										<div class="form-group">
											<label for="inputUsername" class="col-lg-2 control-label">Username</label>
											<div class="col-lg-10">
												<input name="addusername" type="text" class="form-control" id="inputUsername">
											</div>
										</div><div class="form-group">
										<label for="inputPassword" class="col-lg-2 control-label">Password</label>
										<div class="col-lg-10">
											<input name="addpassword" type="password" class="form-control" id="inputPassword">
										</div>
									</div>
									<br/>
									<br/>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									<button type="submit" class="btn btn-primary">+ Tambah</button>
								</div>

							</form>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
				<!-- end of modal tambah karyawan -->
				<br/><br/>
				<table class="table table-striped">
					<tr>
						<th>#</th>
						<th>Nama</th>
						<th>Bagian</th>
						<th>Telp</th>
						<th>Alamat</th>
						<th>Username</th>
						<th>Last Login</th>
						<th></th>
					</tr>
					<?php $n=1;foreach($pegawai as $v):?>
					<tr>
						<td><?php echo $n;?></td>
						<td><?php echo $v['nama'];?></td>
						<td><?php echo $v['bagian'];?></td>
						<td><?php echo $v['telp'];?></td>
						<td><?php echo $v['alamat'];?></td>
						<td><?php echo $v['username'];?></td>
						<td><?php echo $v['login_log'];?></td>
						<td><a href="<?php echo site_url('dashboard/karyawan?act=editkaryawan&id='.$v['id_pegawai'])?>">Edit</a> | <a onclick="return confirm('Anda Yakin!')" href="<?php echo site_url('dashboard/karyawan?act=delkaryawan&id='.$v['id_pegawai'])?>">Hapus</a></td>
					</tr>
					<?php $n++;endforeach;?>
				</table>
			</div>
		</div>

	</div>
</div>
</div>
