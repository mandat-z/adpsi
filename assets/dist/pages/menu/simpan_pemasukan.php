<?php
// koneksi ke database
include 'C:/xampp/htdocs/adpsi/config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // ambil data dari form
  $tanggal    = $_POST['tanggal'];
  $keterangan = $_POST['keterangan'];
  $kategori   = $_POST['kategori'];
  $nominal    = $_POST['nominal'];

  // query insert
  $query = "INSERT INTO tb_pemasukan (tanggal, keterangan, kategori, nominal)
            VALUES ('$tanggal', '$keterangan', '$kategori', '$nominal')";

  if (mysqli_query($koneksi, $query)) {
    // redirect balik ke halaman pemasukan setelah berhasil simpan
    header("Location: pemasukan.php?status=sukses");
    exit;
  } else {
    echo "Gagal menyimpan data: " . mysqli_error($koneksi);
  }
}
?>
