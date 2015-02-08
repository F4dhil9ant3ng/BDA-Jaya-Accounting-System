-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 08, 2015 at 11:21 
-- Server version: 5.6.12
-- PHP Version: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `2015_BDAJaya`
--
CREATE DATABASE IF NOT EXISTS `2015_BDAJaya` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `2015_BDAJaya`;

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE IF NOT EXISTS `absensi` (
  `id_absensi` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_karyawan` int(11) NOT NULL,
  `bulan` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `tgl_terakhir` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `total` int(11) NOT NULL,
  PRIMARY KEY (`id_absensi`),
  KEY `id_karyawan` (`id_karyawan`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id_absensi`, `id_karyawan`, `bulan`, `tahun`, `tgl_terakhir`, `total`) VALUES
(1, 1, 11, 2014, '2014-11-20 01:41:39', 1),
(2, 2, 11, 2014, '2014-11-20 01:06:57', 1);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(30) NOT NULL,
  `telp` varchar(12) NOT NULL,
  `alamat` text NOT NULL,
  `username` varchar(10) NOT NULL,
  `password` text NOT NULL,
  `log` double NOT NULL,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama`, `telp`, `alamat`, `username`, `password`, `log`) VALUES
(1, 'Admin BDA Jaya', '628123123123', 'Rahasia dunks', 'admin', 'ac43724f16e9241d990427ab7c8f4228', 151305);

-- --------------------------------------------------------

--
-- Table structure for table `angsuran_piutang`
--

CREATE TABLE IF NOT EXISTS `angsuran_piutang` (
  `id_angsuran` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_transaksi` bigint(20) NOT NULL,
  `tgl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `oleh` int(11) NOT NULL,
  `rp` bigint(20) NOT NULL,
  PRIMARY KEY (`id_angsuran`),
  KEY `id_transaksi` (`id_transaksi`,`oleh`),
  KEY `oleh` (`oleh`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE IF NOT EXISTS `barang` (
  `id_barang` bigint(20) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `no_seri` varchar(12) NOT NULL,
  `kategori` int(11) DEFAULT NULL,
  `oleh` int(11) DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `harga_beli` bigint(20) NOT NULL,
  `harga_jual` bigint(20) NOT NULL,
  `stok` int(11) NOT NULL,
  PRIMARY KEY (`id_barang`),
  KEY `kategori` (`kategori`,`oleh`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `nama`, `no_seri`, `kategori`, `oleh`, `tanggal`, `harga_beli`, `harga_jual`, `stok`) VALUES
(2, 'Sarung Hari Raya Edisi Garuda', '112345', 2, 1, '2015-02-07 03:26:39', 45000, 45450, 274),
(3, 'Baju koko ala Malaysia', '112346', 2, 2, '2015-02-07 04:30:42', 45000, 50000, 488),
(4, 'Handuk Bayi', '11247', 4, 1, '2015-01-10 02:48:08', 12000, 12120, 70),
(5, 'Handuk Dewasa', '11223', 4, 0, '2015-01-10 02:17:49', 25000, 25250, 144),
(8, 'asisisisi', '1212', 2, 1, '2015-01-26 13:44:41', 3400, 3740, 45),
(13, 'kaftan', '122234', 3, 0, '2015-02-02 03:29:42', 50000, 50500, 12),
(17, 'baju bayi', '2323', 2, NULL, '2015-02-07 03:31:55', 25000, 25250, 40),
(19, 'gamis', '111', 3, 1, '2015-02-07 04:01:50', 150000, 151500, 6),
(20, 'Mukena', '222', 3, 1, '2015-02-07 04:06:09', 150000, 151500, 20);

-- --------------------------------------------------------

--
-- Table structure for table `gudang_activity`
--

CREATE TABLE IF NOT EXISTS `gudang_activity` (
  `id_activity` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_pegawai` int(11) DEFAULT NULL,
  `id_barang` bigint(20) DEFAULT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `id_pemasok` int(11) DEFAULT NULL,
  `activity` varchar(200) NOT NULL,
  `tgl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_activity`),
  KEY `id_pegawai` (`id_pegawai`,`id_barang`,`id_kategori`,`id_pemasok`),
  KEY `id_barang` (`id_barang`),
  KEY `id_pemasok` (`id_pemasok`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `gudang_activity`
--

INSERT INTO `gudang_activity` (`id_activity`, `id_pegawai`, `id_barang`, `id_kategori`, `id_pemasok`, `activity`, `tgl`) VALUES
(27, NULL, NULL, NULL, 1, 'menambah pasokan dari pemasok : 1 | dengan id pasokan : 19', '2015-02-07 03:01:35'),
(28, NULL, NULL, NULL, 1, 'menambah pasokan dari pemasok : 1 | dengan id pasokan : 20', '2015-02-07 03:26:39'),
(29, NULL, NULL, NULL, 2, 'menambah pasokan dari pemasok : 2 | dengan id pasokan : 21', '2015-02-07 03:31:55'),
(30, 1, 19, 3, NULL, 'Tambah barang baru dengan nomor seri 111 dari ', '2015-02-07 04:00:31'),
(31, NULL, NULL, NULL, 6, 'menambah pasokan dari pemasok : 6 | dengan id pasokan : 22', '2015-02-07 04:01:50'),
(32, 1, 20, 3, NULL, 'Tambah barang baru dengan nomor seri 222 dari ', '2015-02-07 04:04:02'),
(33, NULL, NULL, NULL, 5, 'menambah pasokan dari pemasok : 5 | dengan id pasokan : 23', '2015-02-07 04:06:09');

-- --------------------------------------------------------

--
-- Table structure for table `kasir_activity`
--

CREATE TABLE IF NOT EXISTS `kasir_activity` (
  `id_activity` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_pegawai` int(11) DEFAULT NULL,
  `id_transaksi` bigint(20) DEFAULT NULL,
  `catatan` varchar(500) NOT NULL,
  `tgl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_activity`),
  KEY `id_pegawai` (`id_pegawai`,`id_transaksi`),
  KEY `id_transaksi` (`id_transaksi`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

--
-- Dumping data for table `kasir_activity`
--

INSERT INTO `kasir_activity` (`id_activity`, `id_pegawai`, `id_transaksi`, `catatan`, `tgl`) VALUES
(37, NULL, 25, 'membuat transaksi baru dengan id : 25', '2015-02-07 03:00:41'),
(38, NULL, 26, 'membuat transaksi baru dengan id : 26', '2015-02-07 03:32:46'),
(39, 2, 29, 'membuat transaksi baru dengan id : 29', '2015-02-07 04:28:23'),
(40, 2, 30, 'membuat transaksi baru dengan id : 30', '2015-02-07 04:30:42');

-- --------------------------------------------------------

--
-- Table structure for table `kategori_barang`
--

CREATE TABLE IF NOT EXISTS `kategori_barang` (
  `id_kat_barang` int(11) NOT NULL AUTO_INCREMENT,
  `des_kat_barang` varchar(50) NOT NULL,
  PRIMARY KEY (`id_kat_barang`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `kategori_barang`
--

INSERT INTO `kategori_barang` (`id_kat_barang`, `des_kat_barang`) VALUES
(2, 'Pakaian Muslim Laki-laki'),
(3, 'Pakaian Muslim Perempuan'),
(4, 'Handuk Mandi'),
(5, 'Pakaian Anak'),
(6, 'Celana Dalam Wanita Dewasa');

-- --------------------------------------------------------

--
-- Table structure for table `kategori_pemasukan`
--

CREATE TABLE IF NOT EXISTS `kategori_pemasukan` (
  `id_kat_masuk` int(11) NOT NULL AUTO_INCREMENT,
  `det_kat_masuk` varchar(50) NOT NULL,
  PRIMARY KEY (`id_kat_masuk`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `kategori_pemasukan`
--

INSERT INTO `kategori_pemasukan` (`id_kat_masuk`, `det_kat_masuk`) VALUES
(2, 'Pendapatan'),
(4, 'Modal'),
(6, 'Kredit Bank');

-- --------------------------------------------------------

--
-- Table structure for table `kategori_pengeluaran`
--

CREATE TABLE IF NOT EXISTS `kategori_pengeluaran` (
  `id_kat_pengeluaran` int(11) NOT NULL AUTO_INCREMENT,
  `det_kat_pengeluaran` varchar(100) NOT NULL,
  PRIMARY KEY (`id_kat_pengeluaran`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `kategori_pengeluaran`
--

INSERT INTO `kategori_pengeluaran` (`id_kat_pengeluaran`, `det_kat_pengeluaran`) VALUES
(6, 'Pembelian Barang'),
(7, 'Perlengkapan'),
(9, 'Peralatan'),
(10, 'Beban Listrik'),
(11, 'Beban Pajak'),
(13, 'Prive'),
(14, 'Beban Sewa'),
(15, 'Pembayaran Kredit'),
(16, 'Beban Asuransi'),
(17, 'Beban Lain-lain');

-- --------------------------------------------------------

--
-- Table structure for table `pasokan`
--

CREATE TABLE IF NOT EXISTS `pasokan` (
  `id_pasokan` bigint(20) NOT NULL AUTO_INCREMENT,
  `pemasok` int(11) NOT NULL,
  `tgl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `oleh` int(11) DEFAULT NULL,
  `rp` bigint(20) NOT NULL,
  `rp_bayar` bigint(20) DEFAULT NULL,
  `rp_kembali` bigint(20) NOT NULL,
  `status` enum('lunas','hutang') NOT NULL,
  PRIMARY KEY (`id_pasokan`),
  KEY `pemasok` (`pemasok`,`oleh`),
  KEY `oleh` (`oleh`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `pasokan`
--

INSERT INTO `pasokan` (`id_pasokan`, `pemasok`, `tgl`, `oleh`, `rp`, `rp_bayar`, `rp_kembali`, `status`) VALUES
(19, 1, '2015-02-07 03:24:49', 1, 480000, 480000, 0, 'lunas'),
(20, 1, '2015-02-07 03:27:45', 1, 540000, 540000, 0, 'lunas'),
(21, 2, '2015-02-07 03:31:55', 1, 325000, 325000, 0, 'lunas'),
(22, 6, '2015-01-07 04:01:50', 1, 900000, 900000, 0, 'lunas'),
(23, 5, '2015-01-07 04:06:09', 1, 3000000, 3000000, 0, 'lunas');

-- --------------------------------------------------------

--
-- Table structure for table `pasokan_angsuran`
--

CREATE TABLE IF NOT EXISTS `pasokan_angsuran` (
  `id_angsuran` bigint(20) NOT NULL AUTO_INCREMENT,
  `rp` bigint(20) NOT NULL,
  `oleh` int(11) NOT NULL,
  `tgl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_pasokan` bigint(20) NOT NULL,
  PRIMARY KEY (`id_angsuran`),
  KEY `oleh` (`oleh`,`id_pasokan`),
  KEY `id_pasokan` (`id_pasokan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pasokan_item`
--

CREATE TABLE IF NOT EXISTS `pasokan_item` (
  `id_pasokan` bigint(20) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_beli` int(11) NOT NULL,
  `subtotal_beli` bigint(20) NOT NULL,
  KEY `id_pasokan` (`id_pasokan`,`id_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pasokan_item`
--

INSERT INTO `pasokan_item` (`id_pasokan`, `id_barang`, `jumlah`, `harga_beli`, `subtotal_beli`) VALUES
(19, 2, 12, 40000, 480000),
(20, 2, 12, 45000, 540000),
(21, 17, 13, 25000, 325000),
(22, 19, 6, 150000, 900000),
(23, 20, 20, 150000, 3000000);

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE IF NOT EXISTS `pegawai` (
  `id_pegawai` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `bagian` enum('kasir','gudang') NOT NULL,
  `telp` varchar(12) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `username` varchar(10) NOT NULL,
  `password` text NOT NULL,
  `login_log` datetime NOT NULL,
  PRIMARY KEY (`id_pegawai`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id_pegawai`, `nama`, `bagian`, `telp`, `alamat`, `username`, `password`, `login_log`) VALUES
(1, 'Yusuf Akhsan Hidayat', 'gudang', '085645777298', 'Lele 2th Road, Maguwoharjo, Sleman, DIY', 'yussan', 'ac43724f16e9241d990427ab7c8f4228', '2015-02-03 15:42:08'),
(2, 'Merti Dina Nisa', 'kasir', '08134567890', 'Bethoven St Number 23, Chicago', 'dina', 'ac43724f16e9241d990427ab7c8f4228', '2015-02-07 08:51:51'),
(3, 'Firman Hidayat', 'gudang', '123', 'mama', 'fir', 'ac43724f16e9241d990427ab7c8f4228', '2015-02-02 15:34:41');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE IF NOT EXISTS `pelanggan` (
  `id_pelanggan` bigint(20) NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(30) NOT NULL,
  `alamat` varchar(50) NOT NULL,
  `kontak` varchar(12) NOT NULL,
  `tgl_daftar` datetime NOT NULL,
  PRIMARY KEY (`id_pelanggan`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_lengkap`, `alamat`, `kontak`, `tgl_daftar`) VALUES
(1, 'suyanto', 'Jalan Kaliurang KM 4 No 45', '085645777888', '2014-12-25 10:06:38'),
(3, 'kama jong', 'soying street 21', '2345', '2014-12-25 04:51:06'),
(4, 'Bu Hj Lin', 'Kota Agung', '0857987684', '2015-01-10 03:47:46');

-- --------------------------------------------------------

--
-- Table structure for table `pemasok`
--

CREATE TABLE IF NOT EXISTS `pemasok` (
  `id_pemasok` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `alamat` varchar(300) NOT NULL,
  `kontak` varchar(300) NOT NULL,
  PRIMARY KEY (`id_pemasok`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `pemasok`
--

INSERT INTO `pemasok` (`id_pemasok`, `nama`, `alamat`, `kontak`) VALUES
(1, 'PT. Gadjah Duduks', 'Surabaya, Jawa Timur, Indonesia', '8232568'),
(2, 'Sumber Maju', 'PasarCipulir Jakarta', ''),
(3, 'Yusuf Abadi', 'Jl Lele 1 Maguwojarho DIY', '24324234234'),
(4, 'toko mitha (tn abdullah)', 'cipulir', '783872'),
(5, 'Pak Dji', 'Jakarta tengah', '123'),
(6, 'Adela', 'Tanah Abang', '78347838');

-- --------------------------------------------------------

--
-- Table structure for table `pemasukan`
--

CREATE TABLE IF NOT EXISTS `pemasukan` (
  `id_pemasukan` bigint(20) NOT NULL AUTO_INCREMENT,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `oleh` int(11) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `rp` bigint(20) NOT NULL,
  `kategori` int(11) NOT NULL,
  `id_transaksi` bigint(20) DEFAULT NULL,
  `id_barang` bigint(20) DEFAULT NULL,
  `id_pemasok` int(11) DEFAULT NULL,
  `status` enum('piutang','lunas') NOT NULL,
  `det` varchar(6) NOT NULL DEFAULT 'masuk',
  PRIMARY KEY (`id_pemasukan`),
  KEY `oleh` (`oleh`,`kategori`,`id_barang`,`id_pemasok`),
  KEY `kategori` (`kategori`),
  KEY `id_barang` (`id_barang`),
  KEY `id_transaksi` (`id_transaksi`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `pemasukan`
--

INSERT INTO `pemasukan` (`id_pemasukan`, `tanggal`, `oleh`, `keterangan`, `rp`, `kategori`, `id_transaksi`, `id_barang`, `id_pemasok`, `status`, `det`) VALUES
(23, '2015-02-07 02:42:36', 1, 'Modal', 15000000, 4, NULL, NULL, NULL, 'lunas', 'masuk'),
(24, '2015-02-07 02:43:22', 1, 'Kredit Bank', 10000000, 6, NULL, NULL, NULL, 'lunas', 'masuk'),
(28, '2015-02-07 03:00:41', 0, 'Penjualan dengan id transaksi : 25', 165000, 2, 25, NULL, NULL, 'lunas', 'masuk'),
(29, '2015-01-07 03:32:46', 0, 'Penjualan dengan id transaksi : 26', 750000, 2, 26, NULL, NULL, 'lunas', 'masuk'),
(30, '2015-01-07 03:35:17', 1, 'Modal', 13000000, 4, NULL, NULL, NULL, 'lunas', 'masuk'),
(31, '2015-01-07 04:28:23', 0, 'Penjualan dengan id transaksi : 29', 5000000, 2, 29, NULL, NULL, 'lunas', 'masuk'),
(32, '2015-01-07 04:30:42', 0, 'Penjualan dengan id transaksi : 30', 2500000, 2, 30, NULL, NULL, 'lunas', 'masuk');

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
--

CREATE TABLE IF NOT EXISTS `pengeluaran` (
  `id_pengeluaran` bigint(20) NOT NULL AUTO_INCREMENT,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `oleh` int(11) DEFAULT NULL,
  `keterangan` varchar(100) NOT NULL,
  `rp` bigint(20) NOT NULL,
  `kategori` int(11) NOT NULL,
  `id_barang` bigint(20) DEFAULT NULL,
  `id_pasokan` bigint(11) DEFAULT NULL,
  `status` enum('hutang','lunas') NOT NULL,
  `det` varchar(6) NOT NULL DEFAULT 'keluar',
  PRIMARY KEY (`id_pengeluaran`),
  KEY `oleh` (`oleh`,`kategori`,`id_barang`,`id_pasokan`),
  KEY `kategori` (`kategori`),
  KEY `id_barang` (`id_barang`),
  KEY `id_pasokan` (`id_pasokan`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `pengeluaran`
--

INSERT INTO `pengeluaran` (`id_pengeluaran`, `tanggal`, `oleh`, `keterangan`, `rp`, `kategori`, `id_barang`, `id_pasokan`, `status`, `det`) VALUES
(36, '2015-02-07 02:44:04', 1, 'Beli Lakban', 150000, 7, NULL, NULL, 'lunas', 'keluar'),
(37, '2015-02-07 02:44:57', 1, 'Beban Listrik', 125000, 10, NULL, NULL, 'lunas', 'keluar'),
(38, '2015-01-07 02:45:40', 1, 'Prive', 50000, 13, NULL, NULL, 'lunas', 'keluar'),
(41, '2015-02-07 03:01:35', 0, 'tambah pasokan dengan id : 19 , atas nama karyawan dengan id :', 480000, 6, NULL, 19, 'lunas', 'keluar'),
(42, '2015-02-07 03:26:39', 0, 'tambah pasokan dengan id : 20 , atas nama karyawan dengan id :', 540000, 6, NULL, 20, 'lunas', 'keluar'),
(43, '2015-02-07 03:31:55', 0, 'tambah pasokan dengan id : 21 , atas nama karyawan dengan id :', 325000, 6, NULL, 21, 'lunas', 'keluar'),
(44, '2015-01-07 03:43:27', 1, 'Sewa toko', 7000000, 14, NULL, NULL, 'lunas', 'keluar'),
(45, '2015-01-07 04:00:31', 1, 'Tambah Barang gamis ke Gudang', 0, 6, 19, NULL, 'lunas', 'keluar'),
(46, '2015-01-07 04:01:50', 0, 'tambah pasokan dengan id : 22 , atas nama karyawan dengan id :', 900000, 6, NULL, 22, 'lunas', 'keluar'),
(47, '2015-01-07 04:04:02', 1, 'Tambah Barang Mukena ke Gudang', 0, 6, 20, NULL, 'lunas', 'keluar'),
(48, '2015-01-07 04:06:09', 0, 'tambah pasokan dengan id : 23 , atas nama karyawan dengan id :', 3000000, 6, NULL, 23, 'lunas', 'keluar');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE IF NOT EXISTS `transaksi` (
  `id_transaksi` bigint(20) NOT NULL AUTO_INCREMENT,
  `tgl_transaksi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_pelanggan` bigint(20) DEFAULT NULL,
  `total_bayar` bigint(20) NOT NULL,
  `bayar` bigint(20) NOT NULL,
  `kembali` bigint(20) NOT NULL,
  `status` enum('lunas','piutang') NOT NULL,
  PRIMARY KEY (`id_transaksi`),
  KEY `id_pelanggan` (`id_pelanggan`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `tgl_transaksi`, `id_pelanggan`, `total_bayar`, `bayar`, `kembali`, `status`) VALUES
(25, '2015-02-07 03:00:41', 3, 165000, 170000, 5000, 'lunas'),
(26, '2015-01-07 03:32:46', 4, 750000, 750000, 0, 'lunas'),
(29, '2015-01-07 04:28:23', 4, 5000000, 5000000, 0, 'lunas'),
(30, '2015-01-07 04:30:42', 3, 2500000, 2500000, 0, 'lunas');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_item`
--

CREATE TABLE IF NOT EXISTS `transaksi_item` (
  `id_transaksi` bigint(20) NOT NULL,
  `id_barang` bigint(20) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `subtotal` bigint(20) NOT NULL,
  KEY `id_transaksi` (`id_transaksi`,`id_barang`),
  KEY `id_barang` (`id_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaksi_item`
--

INSERT INTO `transaksi_item` (`id_transaksi`, `id_barang`, `jumlah`, `subtotal`) VALUES
(25, 2, 1, 65000),
(25, 3, 2, 100000),
(26, 3, 15, 750000),
(29, 3, 100, 5000000),
(30, 3, 50, 2500000);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`id_karyawan`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `angsuran_piutang`
--
ALTER TABLE `angsuran_piutang`
  ADD CONSTRAINT `angsuran_piutang_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `angsuran_piutang_ibfk_2` FOREIGN KEY (`oleh`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`kategori`) REFERENCES `kategori_barang` (`id_kat_barang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `gudang_activity`
--
ALTER TABLE `gudang_activity`
  ADD CONSTRAINT `gudang_activity_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gudang_activity_ibfk_2` FOREIGN KEY (`id_pemasok`) REFERENCES `pemasok` (`id_pemasok`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gudang_activity_ibfk_3` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kasir_activity`
--
ALTER TABLE `kasir_activity`
  ADD CONSTRAINT `kasir_activity_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kasir_activity_ibfk_2` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pasokan`
--
ALTER TABLE `pasokan`
  ADD CONSTRAINT `pasokan_ibfk_1` FOREIGN KEY (`pemasok`) REFERENCES `pemasok` (`id_pemasok`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pasokan_ibfk_2` FOREIGN KEY (`oleh`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pasokan_angsuran`
--
ALTER TABLE `pasokan_angsuran`
  ADD CONSTRAINT `pasokan_angsuran_ibfk_1` FOREIGN KEY (`id_pasokan`) REFERENCES `pasokan` (`id_pasokan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pasokan_angsuran_ibfk_2` FOREIGN KEY (`oleh`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pasokan_item`
--
ALTER TABLE `pasokan_item`
  ADD CONSTRAINT `pasokan_item_ibfk_1` FOREIGN KEY (`id_pasokan`) REFERENCES `pasokan` (`id_pasokan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD CONSTRAINT `pemasukan_ibfk_1` FOREIGN KEY (`kategori`) REFERENCES `kategori_pemasukan` (`id_kat_masuk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pemasukan_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pemasukan_ibfk_3` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD CONSTRAINT `pengeluaran_ibfk_1` FOREIGN KEY (`kategori`) REFERENCES `kategori_pengeluaran` (`id_kat_pengeluaran`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pengeluaran_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pengeluaran_ibfk_3` FOREIGN KEY (`id_pasokan`) REFERENCES `pasokan` (`id_pasokan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `transaksi_item`
--
ALTER TABLE `transaksi_item`
  ADD CONSTRAINT `transaksi_item_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_item_ibfk_2` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
