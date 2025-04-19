<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  include 'C:/xampp/htdocs/adpsi/config/config.php';

  $nama_barang = $_POST['nama_barang'];
  $jenis = $_POST['jenis'];
  $stok = $_POST['stok'];
  $satuan = $_POST['satuan'];
  $tanggal_masuk = $_POST['tanggal_masuk'];

  $query = "INSERT INTO tb_stok_barang (nama_barang, jenis, stok, satuan, tanggal_masuk) 
            VALUES ('$nama_barang', '$jenis', '$stok', '$satuan', '$tanggal_masuk')";

  if (mysqli_query($koneksi, $query)) {
    header('Location: stok.php');
  } else {
    echo "Error: " . mysqli_error($koneksi);
  }
}
?>
