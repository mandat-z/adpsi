<?php
if (isset($_GET['id'])) {
  include 'C:/xampp/htdocs/adpsi/config/config.php';

  $id = $_GET['id'];

  $query = "DELETE FROM tb_stok_barang WHERE id = $id";

  if (mysqli_query($koneksi, $query)) {
    header('Location: stok.php');
  } else {
    echo "Error: " . mysqli_error($koneksi);
  }
}
?>
