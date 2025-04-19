<?php
include 'C:/xampp/htdocs/adpsi/config/config.php';

$id = $_POST['id'];
$nama_barang = $_POST['nama_barang'];
$jenis = $_POST['jenis'];
$stok = $_POST['stok'];
$satuan = $_POST['satuan'];
$tanggal_masuk = $_POST['tanggal_masuk'];

$query = "UPDATE tb_stok_barang SET 
            nama_barang='$nama_barang', 
            jenis='$jenis', 
            stok='$stok', 
            satuan='$satuan', 
            tanggal_masuk='$tanggal_masuk' 
          WHERE id='$id'";

if (mysqli_query($koneksi, $query)) {
  header("Location: stok.php"); // ganti dengan nama file utama stok
} else {
  echo "Gagal update data: " . mysqli_error($koneksi);
}
?>
