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
	<?php //print_r($this->session->userdata('neraca'));?>
	<div class="col-md-10">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Neraca</h3>
				</div>
				<div class="panel-body">
					<center>
					<?php 
					switch ($_GET['bln']) {
						case '1':
							$bln = 'Januari';
							break;
						case '2':
							$bln = 'Februari';
							break;
						case '3':
							$bln = 'Maret';
							break;
						case '4':
							$bln = 'April';
							break;
						case '5':
							$bln = 'Mei';
							break;
						case '6':
							$bln = 'Juni';
							break;
						case '7':
							$bln = 'Juli';
							break;
						case '8':
							$bln = 'Agustus';
							break;
						case '9':
							$bln = 'September';
							break;
						case '10':
							$bln = 'Oktober';
							break;
						case '11':
							$bln = 'Nopember';
							break;
						case '12':
							$bln = 'Desember';
							break;
					}
					?>
					<h4>
					Neraca <br/>
					"BDA-JAYA" <br/>
					Bulan <?php echo $bln;?> Tahun <?php echo $_GET['thn'];?>
					</h4>
					</center>
				<!-- <pre>
				<?php print_r($this->session->userdata('neraca')); ?>
			</pre> -->
			<table class="table table-striped">
				<tr>

					<th>Nama Akun</th>
					<th>Debet</th>
					<th>Kredit</th>
				</tr>
				<?php $totaldebet=0;$totalkredit=0;
				foreach($this->session->userdata('neraca') as $n):?>
				<tr>						
					<td><?php echo $n['tipe'];?></td>
					<td>
						<?php
						if($n['pos'] == 'debit'){
							echo number_format($n['value']);
							$totaldebet = $totaldebet + $n['value'] ;
						}
						?>
					</td>
					<td>
						<?php
						if($n['pos'] == 'kredit'){
							echo number_format($n['value']);
							$totalkredit = $totalkredit + $n['value'];
						}
						?>
					</td>
				</tr>
			<?php endforeach;$totaldebet = $totaldebet+$this->session->userdata('modallalu');?>
			<tr>
				<td></td>
				<?php echo $this->session->userdata('modallalu');?>
				<td><strong><?php echo number_format($totaldebet);?></strong></td>
				<td><strong><?php echo number_format($totalkredit);?></strong></td>
			</tr>
		</table>
	</div>
	<div class="col-md-12">
		<center><a href="<?php echo site_url('dashboard/raba_rugi?bln='.$bulan.'&thn='.$tahun)?>" class="btn btn-primary btn-lg">Laporan Raba Rugi / Perubahan Modal</a></center>
		<br/><br/>
		<hr/>
	</div>
</div>


</div>
</div>
</div>
