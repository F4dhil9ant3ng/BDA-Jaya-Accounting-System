<div class="col-md-2">
	<ul class="nav nav-pills nav-stacked">
		<li id="stats" class="">
			<a href="<?php echo site_url('dashboard')?>">
				Stats
			</a>
		</li>
		<hr/>
		<li id="bukukas" class="">
			<a href="<?php echo site_url('dashboard/bukukas')?>">
				<!-- <span class="badge pull-right"><?php echo $this->db->count_all('transaksi');?></span> -->
				Masuk/Keluar
			</a>
		</li>
		<li id="kategorikas" class="">
			<a href="<?php echo site_url('dashboard/kategori_kas')?>">
				<!-- <span class="badge pull-right"><?php echo $this->db->count_all('transaksi');?></span> -->
				Kategori Masuk/Keluar
			</a>
		</li>
		<li id="jurnal" class="">
			<a href="<?php echo site_url('dashboard/jurnal')?>">
				<!-- <span class="badge pull-right"><?php echo $this->db->count_all('transaksi');?></span> -->
				Jurnal
			</a>
		</li>
		<li id="bukubesar">
			<a href="<?php echo site_url('dashboard/buku_besar')?>">
				<!-- <span class="badge pull-right"><?php echo $this->db->count_all('transaksi');?></span> -->
				Buku Besar
			</a>
		</li>
		<!-- <li id="rugilaba">
			<a href="<?php echo site_url('dashboard/jurnal')?>">
				<span class="badge pull-right"><?php echo $this->db->count_all('transaksi');?></span>
				Laporan Rugi Laba
			</a>
		</li> -->
		<hr/>
		<li id="barang" class="">
			<a href="<?php echo site_url('gudang/barang')?>">
				<span class="badge pull-right"><?php echo $this->db->count_all('barang');?></span>
				Barang
			</a>
		</li>
		<li id="kategori" class="">
			<a href="<?php echo site_url('dashboard/kategori_barang')?>">
				<span class="badge pull-right"><?php echo $this->db->count_all('kategori_barang');?></span>
				Kategori Barang
			</a>
		</li>
		<li id="pemasok" class="">
			<a href="<?php echo site_url('dashboard/pemasok')?>">
				<span class="badge pull-right"><?php echo $this->db->count_all('pemasok');?></span>
				Pemasok
			</a>
		</li>
		<li id="pasokan" class="">
			<a href="<?php echo site_url('gudang/pasokan')?>">
				<span class="badge pull-right"><?php echo $this->db->count_all('pasokan');?></span>
				Pasokan
			</a>
		</li>
		<hr/>
		<li id="transaksi" class="">
			<a href="<?php echo site_url('dashboard/transaksi')?>">
				<span class="badge pull-right"><?php echo $this->db->count_all('transaksi');?></span>
				Transaksi
			</a>
		</li>
		<hr/>
		<li id="pelanggan" class="">
			<a href="<?php echo site_url('kasir/pelanggan')?>">
				<span class="badge pull-right"><?php echo $this->db->count_all('pelanggan');?></span>
				Pelanggan
			</a>
		</li>
		<hr/>
		<li id="karyawan" class="">
			<a href="<?php echo site_url('dashboard/karyawan')?>">
				<span class="badge pull-right"><?php echo $this->db->count_all('pegawai');?></span>
				Karyawan
			</a>
		</li>
	</ul>
</div>