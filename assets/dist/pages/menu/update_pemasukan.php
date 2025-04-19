<?php
include 'C:/xampp/htdocs/adpsi/config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = intval($_POST['id']);
  $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
  $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
  $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
  $nominal = floatval($_POST['nominal']);

  // Query untuk update data
  $query = "UPDATE tb_pemasukan 
            SET tanggal = '$tanggal', 
                keterangan = '$keterangan', 
                kategori = '$kategori', 
                nominal = $nominal 
            WHERE id = $id";

  if (mysqli_query($koneksi, $query)) {
    header("Location: pemasukan.php?status=update-sukses");
    exit;
  } else {
    echo "Gagal memperbarui data: " . mysqli_error($koneksi);
  }
} else {
  echo "Metode request tidak diizinkan.";
}
?>
