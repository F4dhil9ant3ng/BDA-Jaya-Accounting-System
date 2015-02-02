<!DOCTYPE html>
<html>
<head>
	<title>Cetak Detail Pasokan</title>
	<style type="text/css">
	.mytable {
		width: 100%;border:1px solid #000;padding:5px;
	}
	.mytable2 {
		width: 100%;border:1px solid #000;padding:5px;
	}
	.mytable td,.mytable th{
		width: 50%;padding:5px;
	}
	.mytable2 td{
		padding: 5px;
	}
	</style>
</head>
<body>
<?php
echo "
<h1>Nota</h1>
<p><strong>Id:</strong> ".$detail['id_transaksi']."</p>
<p><strong>Oleh:</strong> ".$this->session->userdata['nama']."</p>
<p><strong>Tanggal:</strong> ".$detail['tgl_transaksi']."</p>
<p><strong>Status:</strong> ".$detail['status']."</p>
<hr/>
<h5>Barang Yang Dibeli</h5>
<table class='mytable2' style='aligment:center' class=''>
	<tr>
		<th>#</th>
		<th>Id Barang</th>
		<th>Q</th>
		<th>Rp/unit</th>
		<th>Total Rp</th>
	</tr>
	";
		//show transaksi item;
	$x = 1;
	foreach($item as $i):
		echo "
	<tr>
		<td><center>".$x."</center></td>
		<td><center>".$i['nama']."</center></td>
		<td><center>".$i['jumlah']."</center></td>
		<td><center>Rp.".number_format($i['subtotal'] / $i['jumlah']).",-</center></td>
		<td><center>Rp.".number_format($i['subtotal']).",-</center></td>
	</tr>
	";
	$x++;
	endforeach;
	echo "</table>
	<br/><h3><strong>Total:</strong> Rp".number_format($detail['total_bayar']).",-</h3>";
	?>
</body>
</html>