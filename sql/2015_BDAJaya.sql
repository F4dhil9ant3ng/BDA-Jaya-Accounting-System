-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 27, 2015 at 03:21 
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
(1, 'Admin BDA Jaya', '628123123123', 'Rahasia dunks', 'admin', 'ac43724f16e9241d990427ab7c8f4228', 211323);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `angsuran_piutang`
--

INSERT INTO `angsuran_piutang` (`id_angsuran`, `id_transaksi`, `tgl`, `oleh`, `rp`) VALUES
(1, 14, '2015-01-10 02:56:01', 2, 71200);

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE IF NOT EXISTS `barang` (
  `id_barang` bigint(20) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `no_seri` varchar(12) NOT NULL,
  `kategori` int(11) DEFAULT NULL,
  `oleh` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `harga_beli` bigint(20) NOT NULL,
  `harga_jual` bigint(20) NOT NULL,
  `stok` int(11) NOT NULL,
  PRIMARY KEY (`id_barang`),
  KEY `kategori` (`kategori`,`oleh`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `nama`, `no_seri`, `kategori`, `oleh`, `tanggal`, `harga_beli`, `harga_jual`, `stok`) VALUES
(2, 'Sarung Hari Raya Edisi Garuda', '112345', 2, 1, '2015-01-26 02:36:49', 60000, 60600, 251),
(3, 'Baju koko ala Malaysia', '112346', 2, 2, '2015-01-26 13:43:14', 0, 0, 661),
(4, 'Handuk Bayi', '11247', 4, 1, '2015-01-10 02:48:08', 12000, 12120, 70),
(5, 'Handuk Dewasa', '11223', 4, 0, '2015-01-10 02:17:49', 25000, 25250, 144),
(8, 'asisisisi', '1212', 2, 1, '2015-01-26 13:44:41', 3400, 3740, 45),
(10, 'sdsd', '121212', 2, 1, '2015-01-26 14:00:13', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gudang_activity`
--

CREATE TABLE IF NOT EXISTS `gudang_activity` (
  `id_activity` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_pegawai` int(11) NOT NULL,
  `id_barang` bigint(20) DEFAULT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `id_pemasok` int(11) DEFAULT NULL,
  `activity` varchar(200) NOT NULL,
  `tgl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_activity`),
  KEY `id_pegawai` (`id_pegawai`,`id_barang`,`id_kategori`,`id_pemasok`),
  KEY `id_barang` (`id_barang`),
  KEY `id_pemasok` (`id_pemasok`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `gudang_activity`
--

INSERT INTO `gudang_activity` (`id_activity`, `id_pegawai`, `id_barang`, `id_kategori`, `id_pemasok`, `activity`, `tgl`) VALUES
(1, 1, 2, 2, 1, 'Tambah barang baru dengan nomor seri 112345 dari PT. Gadjah Duduk', '2014-11-04 13:16:56'),
(2, 1, 2, NULL, 1, 'Menambah stok barang dengan nomor seri : 112345', '2014-11-06 13:19:06'),
(3, 1, NULL, NULL, 1, 'menambah pasokan dari pemasok : 1 | dengan id pasokan : 2', '2014-11-15 10:15:53'),
(4, 1, NULL, NULL, 1, 'menambah pasokan dari pemasok : 1 | dengan id pasokan : 3', '2014-11-15 10:42:17'),
(5, 1, NULL, NULL, 1, 'menambah pasokan dari pemasok : 1 | dengan id pasokan : 4', '2014-11-15 12:50:24'),
(6, 1, NULL, NULL, 1, 'menambah pasokan dari pemasok : 1 | dengan id pasokan : 5', '2014-11-15 12:54:04'),
(7, 1, NULL, NULL, 1, 'menambah pasokan dari pemasok : 1 | dengan id pasokan : 6', '2014-11-15 12:56:52'),
(8, 1, NULL, NULL, 1, 'menambah pasokan dari pemasok : 1 | dengan id pasokan : 7', '2014-11-15 14:11:27'),
(9, 1, NULL, NULL, 1, 'menambah pasokan dari pemasok : 1 | dengan id pasokan : 8', '2014-11-19 03:22:50'),
(10, 1, NULL, NULL, 1, 'menambah pasokan dari pemasok : 1 | dengan id pasokan : 9', '2014-11-21 13:02:26'),
(11, 1, NULL, NULL, 1, 'menambah pasokan dari pemasok : 1 | dengan id pasokan : 10', '2014-11-23 02:15:07'),
(12, 1, NULL, NULL, 2, 'menambah pasokan dari pemasok : 2 | dengan id pasokan : 11', '2014-11-27 07:24:47'),
(14, 1, 8, 2, NULL, 'Tambah barang baru dengan nomor seri 1212 dari ', '2015-01-26 03:34:00'),
(16, 1, 10, 2, NULL, 'Tambah barang baru dengan nomor seri 121212 dari ', '2015-01-26 14:00:14');

-- --------------------------------------------------------

--
-- Table structure for table `kasir_activity`
--

CREATE TABLE IF NOT EXISTS `kasir_activity` (
  `id_activity` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_pegawai` int(11) NOT NULL,
  `id_transaksi` bigint(20) DEFAULT NULL,
  `catatan` varchar(500) NOT NULL,
  `tgl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_activity`),
  KEY `id_pegawai` (`id_pegawai`,`id_transaksi`),
  KEY `id_transaksi` (`id_transaksi`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `kasir_activity`
--

INSERT INTO `kasir_activity` (`id_activity`, `id_pegawai`, `id_transaksi`, `catatan`, `tgl`) VALUES
(11, 2, NULL, 'Pembelian 7 : beli pena merek pilot 1 pack', '2014-11-13 13:16:12'),
(15, 2, NULL, 'Pembelian 7 : ', '2014-11-20 13:07:44'),
(24, 2, 13, 'membuat transaksi baru dengan id : 13', '2015-01-03 13:36:21'),
(25, 2, 14, 'membuat transaksi baru dengan id : 14', '2015-01-09 06:30:00'),
(26, 2, 15, 'membuat transaksi baru dengan id : 15', '2015-01-10 02:46:17'),
(27, 2, 16, 'membuat transaksi baru dengan id : 16', '2015-01-10 02:48:08'),
(28, 2, 14, 'melayani pembayaran angsuran piutang dengan id : 14', '2015-01-10 02:56:01'),
(29, 2, 17, 'membuat transaksi baru dengan id : 17', '2015-01-16 13:26:45'),
(30, 2, 18, 'membuat transaksi baru dengan id : 18', '2015-01-17 01:28:57'),
(31, 2, 19, 'membuat transaksi baru dengan id : 19', '2015-01-17 01:35:29'),
(32, 2, 20, 'membuat transaksi baru dengan id : 20', '2015-01-25 23:55:50'),
(33, 2, 21, 'membuat transaksi baru dengan id : 21', '2015-01-26 02:36:49');

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
  `oleh` int(11) NOT NULL,
  `rp` bigint(20) NOT NULL,
  `rp_bayar` bigint(20) DEFAULT NULL,
  `rp_kembali` bigint(20) NOT NULL,
  `status` enum('lunas','hutang') NOT NULL,
  PRIMARY KEY (`id_pasokan`),
  KEY `pemasok` (`pemasok`,`oleh`),
  KEY `oleh` (`oleh`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `pasokan`
--

INSERT INTO `pasokan` (`id_pasokan`, `pemasok`, `tgl`, `oleh`, `rp`, `rp_bayar`, `rp_kembali`, `status`) VALUES
(9, 1, '2014-11-21 13:02:26', 1, 1125000, 1130000, 5000, 'lunas'),
(10, 1, '2014-11-23 02:25:05', 1, 720000, 200000, -720000, 'hutang'),
(11, 2, '2014-11-27 07:24:47', 1, 1200000, 1200000, 0, 'lunas');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `pasokan_angsuran`
--

INSERT INTO `pasokan_angsuran` (`id_angsuran`, `rp`, `oleh`, `tgl`, `id_pasokan`) VALUES
(1, 200000, 1, '2014-11-23 02:25:05', 10);

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
(9, 2, 10, 25000, 250000),
(9, 3, 25, 35000, 875000),
(10, 2, 12, 60000, 720000),
(11, 4, 100, 12000, 1200000);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id_pegawai`, `nama`, `bagian`, `telp`, `alamat`, `username`, `password`, `login_log`) VALUES
(1, 'Yusuf Akhsan Hidayat', 'gudang', '085645777298', 'Lele 2th Road, Maguwoharjo, Sleman, DIY', 'yussan', 'ac43724f16e9241d990427ab7c8f4228', '2015-01-27 21:20:38'),
(2, 'Merti Dina Nisab', 'kasir', '08134567890', 'Bethoven St Number 23, Chicago', 'dina', 'ac43724f16e9241d990427ab7c8f4228', '2015-01-27 21:13:38');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `pemasok`
--

INSERT INTO `pemasok` (`id_pemasok`, `nama`, `alamat`, `kontak`) VALUES
(1, 'PT. Gadjah Duduks', 'Surabaya, Jawa Timur, Indonesia', ''),
(2, 'Sumber Maju', 'PasarCipulir Jakarta', ''),
(3, 'Yusuf Abadi', 'Jl Lele 1 Maguwojarho DIY', '');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `pemasukan`
--

INSERT INTO `pemasukan` (`id_pemasukan`, `tanggal`, `oleh`, `keterangan`, `rp`, `kategori`, `id_transaksi`, `id_barang`, `id_pemasok`, `status`, `det`) VALUES
(3, '2014-11-21 13:10:11', 1, 'Modal dari Tuan Dina', 23000000, 4, NULL, NULL, NULL, 'lunas', 'masuk'),
(7, '2014-11-24 05:23:18', 1, 'modal', 5000000, 4, NULL, NULL, NULL, 'lunas', 'masuk'),
(8, '2014-11-27 04:30:58', 1, 'Peminjaman Kredit ', 1000000, 6, NULL, NULL, NULL, 'lunas', 'masuk'),
(12, '2015-01-03 13:36:21', 2, 'Penjualan dengan id transaksi : 13', 797900, 2, 13, NULL, NULL, 'lunas', 'masuk'),
(13, '2015-01-09 06:30:00', 2, 'Penjualan dengan id transaksi : 14', 121200, 2, 14, NULL, NULL, 'piutang', 'masuk'),
(14, '2015-01-10 02:09:27', 1, 'Modal', 20000000, 4, NULL, NULL, NULL, 'lunas', 'masuk'),
(15, '2015-01-10 02:09:59', 1, 'Peminjaman Kredit', 25000000, 6, NULL, NULL, NULL, 'lunas', 'masuk'),
(16, '2015-01-10 02:46:17', 2, 'Penjualan dengan id transaksi : 15', 959500, 2, 15, NULL, NULL, 'lunas', 'masuk'),
(17, '2015-01-10 02:48:08', 2, 'Penjualan dengan id transaksi : 16', 121200, 2, 16, NULL, NULL, 'lunas', 'masuk'),
(18, '2015-01-16 13:26:44', 2, 'Penjualan dengan id transaksi : 17', 868600, 2, 17, NULL, NULL, 'piutang', 'masuk'),
(19, '2015-01-17 01:28:56', 2, 'Penjualan dengan id transaksi : 18', 60600, 2, 18, NULL, NULL, 'lunas', 'masuk'),
(20, '2015-01-17 01:35:29', 2, 'Penjualan dengan id transaksi : 19', 0, 2, 19, NULL, NULL, 'lunas', 'masuk'),
(21, '2015-01-25 23:55:50', 2, 'Penjualan dengan id transaksi : 20', 60600, 2, 20, NULL, NULL, 'lunas', 'masuk'),
(22, '2015-01-26 02:36:49', 2, 'Penjualan dengan id transaksi : 21', 121200, 2, 21, NULL, NULL, 'piutang', 'masuk');

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
--

CREATE TABLE IF NOT EXISTS `pengeluaran` (
  `id_pengeluaran` bigint(20) NOT NULL AUTO_INCREMENT,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `oleh` int(11) NOT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `pengeluaran`
--

INSERT INTO `pengeluaran` (`id_pengeluaran`, `tanggal`, `oleh`, `keterangan`, `rp`, `kategori`, `id_barang`, `id_pasokan`, `status`, `det`) VALUES
(7, '2014-11-21 13:02:26', 1, 'tambah pasokan dengan id : 9 , atas nama karyawan dengan id :1', 1125000, 6, NULL, 9, 'lunas', 'keluar'),
(8, '2014-11-23 02:21:19', 1, 'tambah pasokan dengan id : 10 , atas nama karyawan dengan id :1', 720000, 6, NULL, 10, 'hutang', 'keluar'),
(9, '2014-11-23 02:27:48', 1, 'pembayaran beban listrik', 100000, 10, NULL, NULL, 'lunas', 'keluar'),
(11, '2014-11-24 02:35:07', 1, 'beli komputer', 2000000, 9, NULL, NULL, 'lunas', 'keluar'),
(12, '2014-11-24 03:32:26', 1, 'beli nota', 50000, 7, NULL, NULL, 'lunas', 'keluar'),
(13, '2014-11-24 03:33:04', 1, 'bayar pajak', 100000, 11, NULL, NULL, 'lunas', 'keluar'),
(14, '2014-11-24 03:33:38', 1, 'beli mainan', 52000, 13, NULL, NULL, 'lunas', 'keluar'),
(15, '2014-11-24 03:34:49', 1, 'bayar sewa', 1000000, 14, NULL, NULL, 'lunas', 'keluar'),
(16, '2014-11-27 04:31:41', 1, 'Pembayaran Kredit', 500000, 15, NULL, NULL, 'lunas', 'keluar'),
(17, '2014-11-27 07:24:47', 1, 'tambah pasokan dengan id : 11 , atas nama karyawan dengan id :1', 1200000, 6, NULL, 11, 'lunas', 'keluar'),
(18, '2014-11-27 08:45:19', 1, 'Beban Lain-lain', 400000, 17, NULL, NULL, 'lunas', 'keluar'),
(19, '2015-01-10 02:10:49', 1, 'Lakban 6', 120000, 6, NULL, NULL, 'lunas', 'keluar'),
(20, '2015-01-10 02:11:38', 1, 'Printer', 500000, 9, NULL, NULL, 'lunas', 'keluar'),
(21, '2015-01-10 02:12:08', 1, 'Beban Listrik', 150000, 10, NULL, NULL, 'lunas', 'keluar'),
(22, '2015-01-10 02:13:44', 1, 'Prive', 100000, 13, NULL, NULL, 'lunas', 'keluar'),
(23, '2015-01-10 02:14:08', 1, 'Beban Pajak', 200000, 11, NULL, NULL, 'lunas', 'keluar'),
(25, '2015-01-26 03:34:00', 1, 'Tambah Barang asasa ke Gudang', 0, 6, 8, NULL, 'lunas', 'keluar'),
(27, '2015-01-26 14:00:13', 1, 'Tambah Barang sdsd ke Gudang', 0, 6, 10, NULL, 'lunas', 'keluar');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `tgl_transaksi`, `id_pelanggan`, `total_bayar`, `bayar`, `kembali`, `status`) VALUES
(13, '2015-01-03 13:36:21', 1, 797900, 8000000, 7202100, 'lunas'),
(14, '2015-01-16 13:22:56', 1, 121200, 121200, -71200, 'lunas'),
(15, '2015-01-10 02:46:17', 1, 959500, 960000, 500, 'lunas'),
(16, '2015-01-10 02:48:08', 4, 121200, 150000, 28800, 'lunas'),
(17, '2015-01-16 13:26:44', 1, 868600, 100000, -768600, 'piutang'),
(18, '2015-01-17 01:28:56', 1, 60600, 70000, 9400, 'lunas'),
(19, '2015-01-17 01:35:29', 1, 0, 70000, 9400, 'lunas'),
(20, '2015-01-25 23:55:50', 4, 60600, 70000, 9400, 'lunas'),
(21, '2015-01-26 02:36:49', 1, 121200, 50, -121150, 'piutang');

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
(13, 2, 12, 727200),
(13, 3, 2, 70700),
(14, 2, 2, 121200),
(15, 3, 10, 353500),
(15, 2, 10, 606000),
(16, 4, 10, 121200),
(17, 2, 12, 727200),
(17, 3, 4, 141400),
(18, 2, 1, 60600),
(20, 2, 1, 60600),
(21, 2, 2, 121200);

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
